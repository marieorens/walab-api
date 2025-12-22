<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\VerifyEmailNotification;
use Ichtrojan\Otp\Models\Otp as OtpModel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Vérification Email",
 *     description="Gestion de la validation des emails via OTP (One Time Password)"
 * )
 */
class EmailVerificationController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    /**
     * @OA\Post(
     *     path="/api/email/verify",
     *     summary="Vérifier le code OTP",
     *     tags={"Vérification Email"},
     *     description="Valide le compte utilisateur en vérifiant le code à 6 chiffres envoyé par email.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "otp"},
     *             @OA\Property(property="email", type="string", format="email", example="jean@walab.bj"),
     *             @OA\Property(property="otp", type="string", example="123456", description="Code à 6 chiffres")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email vérifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Email vérifié avec succès !"),
     *             @OA\Property(property="requires_admin_validation", type="boolean", example=false, description="True si le compte doit être approuvé par un admin (Pros)"),
     *             @OA\Property(property="token", type="string", example="3|AbCdEf...", description="Token d'accès (null si validation admin requise)"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Code OTP invalide ou expiré",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Code OTP invalide.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur introuvable"
     *     )
     * )
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé.'
            ], 404);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'success' => true,
                'message' => 'Email déjà vérifié.'
            ], 200);
        }

        $otpValidation = $this->validateOtp($request->email, $request->otp);

        if (!$otpValidation->status) {
            return response()->json([
                'success' => false,
                'message' => $otpValidation->message
            ], 401);
        }

        // Invalider l'OTP seulement après validation réussie
        $this->invalidateOtp($request->email, $request->otp);

        Log::info('About to update user', ['user_id' => $user->id, 'email' => $user->email]);

        try {
            $user->update([
                'email_verified_at' => Carbon::now()
            ]);
            Log::info('User updated successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to update user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }

        // Si c'est un praticien ou lab, notifier les admins pour validation
        if (in_array($user->role_id, [5, 6])) { // 5: lab, 6: practitioner
            $admins = User::whereIn('role_id', [1, 4])->get(); // 1: admin, 4: admin sup
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new \App\Notifications\AdminValidationNotification($user));
                } catch (\Exception $e) {
                    Log::error('Erreur notification admin: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Email vérifié avec succès !',
            'requires_admin_validation' => in_array($user->role_id, [5, 6]),
            'user' => $user,
            'token' => in_array($user->role_id, [5, 6]) ? null : $user->createToken('API Token')->plainTextToken
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/email/resend",
     *     summary="Renvoyer le code OTP",
     *     tags={"Vérification Email"},
     *     description="Génère un nouveau code de vérification et l'envoie par email.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="jean@walab.bj")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Code envoyé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Code de vérification renvoyé avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur d'envoi de mail"
     *     )
     * )
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'success' => true,
                'message' => 'Email déjà vérifié.'
            ], 200);
        }

        try {
            Log::info('Tentative renvoi OTP', [
                'email' => $request->email,
                'user_id' => $user->id,
                'mail_driver' => config('mail.default')
            ]);
            
            $user->notify(new VerifyEmailNotification());
            
            Log::info('OTP renvoyé avec succès', [
                'email' => $request->email,
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Code de vérification renvoyé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur renvoi code vérification', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du code. Vérifiez la configuration email.'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/email/status",
     *     summary="Vérifier le statut",
     *     tags={"Vérification Email"},
     *     description="Vérifie si l'email a déjà été validé ou non.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="jean@walab.bj")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut retourné",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="verified", type="boolean", example=true),
     *             @OA\Property(property="verified_at", type="string", format="date-time", nullable=true)
     *         )
     *     )
     * )
     */
    public function status(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'success' => true,
            'verified' => $user->email_verified_at !== null,
            'verified_at' => $user->email_verified_at
        ], 200);
    }

    /**
     * Valider un OTP (Interne)
     */
    private function validateOtp(string $identifier, string $token): object
    {
        $otp = OtpModel::where('identifier', $identifier)
            ->where('token', $token)
            ->first();

        if (!$otp) {
            return (object)[
                'status' => false,
                'message' => 'Code OTP invalide.'
            ];
        }

        if (!$otp->valid) {
            return (object)[
                'status' => false,
                'message' => 'Code OTP déjà utilisé.'
            ];
        }

        $now = now();
        $validity = $otp->created_at->addMinutes($otp->validity);

        if ($now->gt($validity)) {
            return (object)[
                'status' => false,
                'message' => 'Code OTP expiré.'
            ];
        }

        return (object)[
            'status' => true,
            'message' => 'Code OTP valide.'
        ];
    }

    /**
     * Invalider un OTP après utilisation réussie
     */
    private function invalidateOtp(string $identifier, string $token)
    {
        OtpModel::where('identifier', $identifier)
            ->where('token', $token)
            ->update(['valid' => false]);
    }
}
