<?php

namespace App\Http\Controllers\Web\Laboratoire;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion laboratoire
     */
    public function create_login()
    {
        return view('laboratoire.auth.login');
    }

    /**
     * Traiter la connexion laboratoire
     */
    public function store_login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user){
            // Debug
            \Log::info('Login attempt for user: ' . $user->email);
            \Log::info('User role_id: ' . $user->role_id);
            \Log::info('Expected role_id: ' . RoleEnum::LABORATOIRE->id());

            // Vérifier que l'utilisateur est bien un laboratoire
            if ($user->role_id != RoleEnum::LABORATOIRE->id()) {
                \Log::info('User is not a laboratoire');
                return back()->withErrors([
                    'email' => 'Vous n\'avez pas accès à cet espace. Veuillez utiliser votre espace dédié.',
                ]);
            }

            // Vérifier que l'email est vérifié
            if (!$user->email_verified_at) {
                \Log::info('Email not verified');
                return redirect()->route('laboratoire.login')->with('requires_verification', true)->with('email', $request->email);
            }

            // Vérifier que le compte est actif (pas en attente de validation ou suspendu)
            if ($user->status === 'pending') {
                \Log::info('Account is pending');
                return back()->withErrors([
                    'email' => 'Votre compte est en attente de validation par un administrateur. Vous recevrez un email dès l\'activation.',
                ]);
            }

            if ($user->status === 'suspended') {
                \Log::info('Account is suspended');
                return back()->withErrors([
                    'email' => 'Votre compte a été suspendu. Veuillez contacter l\'administration pour plus d\'informations.',
                ]);
            }

            if ($user->status !== 'active') {
                \Log::info('Account is not active: ' . $user->status);
                return back()->withErrors([
                    'email' => 'Votre compte n\'est pas actif. Veuillez contacter l\'administration.',
                ]);
            }

            // Tentative de connexion
            $credentials = $request->only('email', 'password');
            \Log::info('Attempting login with credentials: ' . json_encode($credentials));

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                \Log::info('Login successful');
                $request->session()->regenerate();
                return redirect()->route('laboratoire.dashboard');
            }

            \Log::info('Login failed - invalid credentials');
            return back()->withErrors([
                'email' => 'Les informations d\'identification fournies ne sont pas valides.',
            ]);

        }
        else {
            \Log::info('User not found: ' . $request->email);
            return back()->withErrors([
                'email' => 'Aucun compte trouvé avec cette adresse email.',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('laboratoire.login');
    }
}
