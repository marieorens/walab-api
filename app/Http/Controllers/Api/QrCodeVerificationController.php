<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrCodeVerificationController extends Controller
{
    /**
     * Récupérer le QR Code d'une commande (côté agent)
     */
    public function getQrCode(Request $request, $commandeId)
    {
        try {
            $user = $request->user();

            // Vérifier que l'utilisateur est bien l'agent assigné
            $commande = Commande::where('id', $commandeId)
                ->where('agent_id', $user->id)  
                ->with('client:id,firstname,lastname,phone')  
                ->first();

            if (!$commande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non trouvée ou vous n\'êtes pas l\'agent assigné.',
                ], 404);
            }

            // Vérifier si le QR Code doit être régénéré
            if ($commande->needsQrCodeRegeneration()) {
                $commande->generateAndStoreQrCode();  
                $commande->refresh();
            }

            return response()->json([
                'success' => true,
                'message' => 'QR Code récupéré avec succès.',
                'data' => [
                    'qr_code' => $commande->qr_code_base64,
                    'verification_url' => $commande->getQrCodeUrl(),
                    'expires_at' => $commande->token_expires_at?->toISOString(),
                    'is_verified' => $commande->is_verified,
                    'verified_at' => $commande->verified_at?->toISOString(),
                    'commande' => [
                        'id' => $commande->id,
                        'code' => $commande->code,
                        'status' => $commande->statut,
                    ],
                    'client' => [
                        'id' => $commande->client->id ?? null,
                        'name' => ($commande->client->firstname ?? '') . ' ' . ($commande->client->lastname ?? ''),
                        'phone' => $commande->client->phone ?? null,
                    ],
                    'agent' => [
                        'id' => $user->id,
                        'name' => $user->firstname . ' ' . $user->lastname,
                        'photo' => $user->url_profil ?? null,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération QR Code: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du QR Code: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Régénérer manuellement le QR Code
     */
    public function regenerateQrCode(Request $request, $commandeId)
    {
        try {
            $user = $request->user();

            $commande = Commande::where('id', $commandeId)
                ->where('agent_id', $user->id) 
                ->first();

            if (!$commande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non trouvée ou vous n\'êtes pas l\'agent assigné.',
                ], 404);
            }

            // Régénérer le QR Code
            $commande->generateAndStoreQrCode();  
            $commande->refresh();

            return response()->json([
                'success' => true,
                'message' => 'QR Code régénéré avec succès.',
                'data' => [
                    'qr_code' => $commande->qr_code_base64,
                    'verification_url' => $commande->getQrCodeUrl(),
                    'expires_at' => $commande->token_expires_at?->toISOString(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur régénération QR Code: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la régénération du QR Code.',
            ], 500);
        }
    }

    /**
     * Vérifier le QR Code scanné côté client
     */
    public function verifyQrCode(Request $request, $token)
    {
        try {
            $user = $request->user();

            // Trouver la commande par le token et charger toutes les relations nécessaires
            $commande = Commande::where('verification_token', $token)
                ->with([
                    'agent:id,firstname,lastname,email,phone,url_profil', 
                    'client:id,firstname,lastname',
                    'examen:id,label,price',
                    'type_bilan:id,label,price'
                ])
                ->first();

            if (!$commande) {
                return response()->json([
                    'success' => false,
                    'verified' => false,
                    'message' => 'QR Code invalide. Ce code n\'existe pas dans notre système.',
                    'error_type' => 'invalid_token',
                ], 404);
            }

            // Vérifier que le token n'est pas expiré
            if (!$commande->isTokenValid()) {
                return response()->json([
                    'success' => false,
                    'verified' => false,
                    'message' => 'Ce QR Code a expiré. Demandez à l\'agent de vous montrer un code valide.',
                    'error_type' => 'token_expired',
                    'expired_at' => $commande->token_expires_at?->toISOString(),
                ], 400);
            }

            // Vérifier que l'utilisateur est bien le client de cette commande
            if ($commande->client_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'verified' => false,
                    'message' => 'Cette commande ne vous appartient pas. Vérifiez que vous êtes connecté avec le bon compte.',
                    'error_type' => 'wrong_client',
                ], 403);
            }

            // Récupérer toutes les sous-commandes (analyses) du même code
            $sousCommandes = Commande::where('code', $commande->code)
                ->with(['examen:id,label,price', 'type_bilan:id,label,price'])
                ->get();

            // Calculer le prix total des analyses
            $prixAnalyses = $sousCommandes->sum(function($sc) {
                if ($sc->examen) {
                    return $sc->examen->price ?? 0;
                }
                if ($sc->type_bilan) {
                    return $sc->type_bilan->price ?? 0;
                }
                return 0;
            });

            // Prix du prélèvement (frais fixe)
            $prixPrelevement = $commande->montant_preleveur ?? 0;

            // Préparer la liste des analyses
            $analyses = $sousCommandes->map(function($sc) {
                if ($sc->examen) {
                    return [
                        'type' => 'examen',
                        'label' => $sc->examen->label,
                        'price' => $sc->examen->price ?? 0,
                    ];
                }
                if ($sc->type_bilan) {
                    return [
                        'type' => 'bilan',
                        'label' => $sc->type_bilan->label,
                        'price' => $sc->type_bilan->price ?? 0,
                    ];
                }
                return null;
            })->filter()->values();

            // Vérifier si déjà vérifié
            if ($commande->is_verified) {
                return response()->json([
                    'success' => true,
                    'verified' => true,
                    'already_verified' => true,
                    'message' => 'Cette commande a déjà été vérifiée.',
                    'data' => [
                        'commande' => [
                            'id' => $commande->id,
                            'code' => $commande->code,
                            'status' => $commande->statut,
                            'verified_at' => $commande->verified_at?->toISOString(),
                            'date_prelevement' => $commande->date_prelevement,
                            'adresse' => $commande->adress,
                        ],
                        'agent' => [
                            'id' => $commande->agent->id,
                            'name' => $commande->agent->firstname . ' ' . $commande->agent->lastname,
                            'email' => $commande->agent->email,
                            'phone' => $commande->agent->phone,
                            'photo' => $commande->agent->url_profil ?? null,
                        ],
                        'analyses' => $analyses,
                        'prix' => [
                            'analyses' => $prixAnalyses,
                            'prelevement' => $prixPrelevement,
                            'total' => $prixAnalyses + $prixPrelevement,
                        ],
                    ],
                ]);
            }

            // Marquer comme vérifié
            $commande->markAsVerified();
            $commande->refresh();

            return response()->json([
                'success' => true,
                'verified' => true,
                'already_verified' => false,
                'message' => 'Identité de l\'agent vérifiée avec succès !',
                'data' => [
                    'commande' => [
                        'id' => $commande->id,
                        'code' => $commande->code,
                        'status' => $commande->statut,
                        'verified_at' => $commande->verified_at->toISOString(),
                        'date_prelevement' => $commande->date_prelevement,
                        'adresse' => $commande->adress,
                    ],
                    'agent' => [
                        'id' => $commande->agent->id,
                        'name' => $commande->agent->firstname . ' ' . $commande->agent->lastname,
                        'email' => $commande->agent->email,
                        'phone' => $commande->agent->phone,
                        'photo' => $commande->agent->url_profil ?? null,
                    ],
                    'analyses' => $analyses,
                    'prix' => [
                        'analyses' => $prixAnalyses,
                        'prelevement' => $prixPrelevement,
                        'total' => $prixAnalyses + $prixPrelevement,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur vérification QR Code: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'verified' => false,
                'message' => 'Erreur lors de la vérification. Veuillez réessayer.',
            ], 500);
        }
    }

    /**
     * Obtenir le statut de vérification d'une commande
     */
    public function getVerificationStatus(Request $request, $commandeId)
    {
        try {
            $user = $request->user();

            $commande = Commande::where('id', $commandeId)
                ->where(function ($query) use ($user) {
                    $query->where('client_id', $user->id)
                          ->orWhere('agent_id', $user->id);  //agent_id
                })
                ->first();

            if (!$commande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Commande non trouvée.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'is_verified' => $commande->is_verified,
                    'verified_at' => $commande->verified_at?->toISOString(),
                    'token_valid' => $commande->isTokenValid(),
                    'expires_at' => $commande->token_expires_at?->toISOString(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur statut vérification: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du statut.',
            ], 500);
        }
    }
}