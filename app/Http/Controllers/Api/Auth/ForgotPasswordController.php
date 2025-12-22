<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
     /**
     * forgot Password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            $input = $request->only('email');
            $user = User::where('email', $input['email'])->first();
            $user->notify(new ResetPasswordNotification());
            $success['success'] = true;

        } catch (\Exception $e) {
            Log::error('L\'envoi de l\'e-mail de réinitialisation du mot de passe a échoué : ' . $e->getMessage() . ' user:' . $user->email);

            return response()->json(['error' => 'Erreur lors de l\'envoi de l\'email.', 'success' => false], 500);
        }
        return response()->json($success, 200);
    }

    /**
     * reSend Otp
     */
    public function reSendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $input = $request->only('email');
        $user = User::where('email', $input['email'])->first();
        $user->notify(new ResetPasswordNotification());
        $success['succees'] = true;
        return response()->json($success, 200);
    }
}
