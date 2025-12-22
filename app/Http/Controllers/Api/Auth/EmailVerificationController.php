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

class EmailVerificationController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    /**
     * Vérifier le code OTP pour la vérification d'email
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
     * Renvoyer le code de vérification
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
     * Vérifier le statut de vérification
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
     * Valider un OTP
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
