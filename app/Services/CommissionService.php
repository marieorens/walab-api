<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Laboratorie;
use App\Models\Paiement;
use App\Models\Wallet;

class CommissionService
{
    public function calculate(float $montantTotal, float $pourcentageCommission): array
    {
        $montantLaboratoire = round(($montantTotal * $pourcentageCommission) / 100, 2);
        $montantPlateforme = round($montantTotal - $montantLaboratoire, 2);

        return [
            'montant_total' => $montantTotal,
            'montant_laboratoire' => $montantLaboratoire,
            'montant_plateforme' => $montantPlateforme,
            'pourcentage_applique' => $pourcentageCommission,
        ];
    }

    public function getLaboratoireFromPaiement(Paiement $paiement): ?Laboratorie
    {
        $commande = Commande::where('code', $paiement->code_commande)->first();
        
        if (!$commande) {
            return null;
        }

        if ($commande->examen_id) {
            return $commande->examen?->laboratorie;
        }

        if ($commande->type_bilan_id) {
            return $commande->type_bilan?->laboratorie;
        }

        return null;
    }

    public function processCommission(Paiement $paiement): bool
    {
        if ($paiement->commission_processed) {
            return false;
        }

        $laboratoire = $this->getLaboratoireFromPaiement($paiement);
        
        if (!$laboratoire) {
            return false;
        }

        // Calcul basé sur les frais de service si disponibles, sinon sur le montant total (legacy)
        $commande = Commande::where('code', $paiement->code_commande)->first();
        $montantService = ($commande && $commande->frais_service > 0) ? $commande->frais_service : floatval($paiement->montant);
        
        $pourcentage = $laboratoire->pourcentage_commission ?? 0;
        $commission = $this->calculate($montantService, $pourcentage);

        $paiement->update([
            'laboratoire_id' => $laboratoire->id,
            'montant_laboratoire' => $commission['montant_laboratoire'],
            'montant_plateforme' => $commission['montant_plateforme'],
            'pourcentage_applique' => $commission['pourcentage_applique'],
            'commission_processed' => true,
        ]);

        $this->creditWallets($paiement, $laboratoire, $commission);

        return true;
    }

    protected function creditWallets(Paiement $paiement, Laboratorie $laboratoire, array $commission): void
    {
        $walletPlateforme = Wallet::getPlateforme();

        if ($paiement->status === \App\Enum\StatutPaiementEnum::PHYSICAL->value) {
            if ($laboratoire->user_id) {
                $walletLabo = Wallet::getOrCreateForUser($laboratoire->user_id, 'laboratoire');
                if ($walletLabo->isActive() && $commission['montant_plateforme'] > 0) {
                    $walletLabo->debit(
                        $commission['montant_plateforme'],
                        "Commission due (Paiement physique) #{$paiement->id} - Commande {$paiement->code_commande}",
                        null,
                        true 
                    );
                }
            }

            if ($walletPlateforme && $walletPlateforme->isActive() && $commission['montant_plateforme'] > 0) {
                $walletPlateforme->credit(
                    $commission['montant_plateforme'],
                    "Commission perçue (Paiement physique) #{$paiement->id} - Commande {$paiement->code_commande}",
                    $paiement->id
                );
            }
        } else {
            if ($laboratoire->user_id) {
                $walletLabo = Wallet::getOrCreateForUser($laboratoire->user_id, 'laboratoire');
                
                if ($walletLabo->isActive() && $commission['montant_laboratoire'] > 0) {
                    $walletLabo->credit(
                        $commission['montant_laboratoire'],
                        "Commission paiement #{$paiement->id} - Commande {$paiement->code_commande}",
                        $paiement->id
                    );
                }
            }

            if ($walletPlateforme && $walletPlateforme->isActive() && $commission['montant_plateforme'] > 0) {
                $walletPlateforme->credit(
                    $commission['montant_plateforme'],
                    "Part plateforme paiement #{$paiement->id} - Commande {$paiement->code_commande}",
                    $paiement->id
                );
            }

            // Crédit de l'agent si déjà assigné lors du paiement online
            $commande = Commande::where('code', $paiement->code_commande)->first();
            if ($commande && $commande->agent_id && $commande->frais_deplacement > 0) {
                $walletAgent = Wallet::getOrCreateForUser($commande->agent_id, 'agent');
                if ($walletAgent && $walletAgent->isActive()) {
                    $walletAgent->credit(
                        $commande->frais_deplacement,
                        "Frais de déplacement paiement #{$paiement->id} - Commande {$commande->code}",
                        $paiement->id,
                        $commande->id
                    );
                }
            }
        }
    }

    public function reprocessCommission(Paiement $paiement): bool
    {
        $paiement->commission_processed = false;
        $paiement->save();

        return $this->processCommission($paiement);
    }

    /**
     * Créditer les portefeuilles pour une commande terminée (appelé depuis les contrôleurs)
     */
    public function creditForCommande(Commande $commande): bool
    {
        $paiement = Paiement::where('code_commande', $commande->code)
            ->whereIn('status', [
                \App\Enum\StatutPaiementEnum::PAYER->value,
                \App\Enum\StatutPaiementEnum::PHYSICAL->value
            ])
            ->first();

        if (!$paiement) {
            \Illuminate\Support\Facades\Log::warning("Impossible de créditer : Aucun paiement approuvé pour la commande {$commande->code}");
            return false;
        }

        if (!$paiement->commission_processed) {
            return $this->processCommission($paiement);
        }

       
        $alreadyCredited = \App\Models\WalletTransaction::where('commande_id', $commande->id)
            ->where('type', 'credit')
            ->exists();

        if ($alreadyCredited) {
            return true; 
        }

        $laboratoire = $commande->examen ? $commande->examen->laboratorie : $commande->type_bilan?->laboratorie;
        if (!$laboratoire) return false;

        $pourcentage = $laboratoire->pourcentage_commission ?? 0;
        $montantService = $commande->frais_service > 0 ? $commande->frais_service : floatval($commande->montant);
        $commission = $this->calculate($montantService, $pourcentage);

        $walletPlateforme = Wallet::getPlateforme();
        if ($paiement->status === \App\Enum\StatutPaiementEnum::PHYSICAL->value) {
            if ($laboratoire->user_id && $commission['montant_plateforme'] > 0) {
                $walletLabo = Wallet::getOrCreateForUser($laboratoire->user_id, 'laboratoire');
                $walletLabo->debit(
                    $commission['montant_plateforme'],
                    "Délivrance résultats (Paiement physique) #{$commande->code}",
                    null,
                    true 
                );
            }

            if ($walletPlateforme && $commission['montant_plateforme'] > 0) {
                $walletPlateforme->credit(
                    $commission['montant_plateforme'],
                    "Commission physique collectée commande #{$commande->code}",
                    $paiement->id,
                    $commande->id
                );
            }
        } else {
            if ($laboratoire->user_id && $commission['montant_laboratoire'] > 0) {
                $walletLabo = Wallet::getOrCreateForUser($laboratoire->user_id, 'laboratoire');
                $walletLabo->credit(
                    $commission['montant_laboratoire'],
                    "Accréditation commande terminée #{$commande->code}",
                    $paiement->id,
                    $commande->id
                );
            }

            if ($walletPlateforme && $commission['montant_plateforme'] > 0) {
                $walletPlateforme->credit(
                    $commission['montant_plateforme'],
                    "Commission plateforme commande #{$commande->code}",
                    $paiement->id,
                    $commande->id
                );
            }

            // Crédit de l'agent (Frais de déplacement)
            if ($commande->agent_id && $commande->frais_deplacement > 0) {
                $walletAgent = Wallet::getOrCreateForUser($commande->agent_id, 'agent');
                if ($walletAgent && $walletAgent->isActive()) {
                    // Éviter double crédit
                    $alreadyCreditedAgent = \App\Models\WalletTransaction::where('commande_id', $commande->id)
                        ->where('wallet_id', $walletAgent->id)
                        ->where('type', 'credit')
                        ->exists();

                    if (!$alreadyCreditedAgent) {
                        $walletAgent->credit(
                            $commande->frais_deplacement,
                            "Frais de déplacement commande #{$commande->code}",
                            $paiement->id,
                            $commande->id
                        );
                    }
                }
            }
        }

        return true;
    }
}
