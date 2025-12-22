<?php

namespace App\Http\Controllers\Web;

use App\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create_login()
    {
        return view('login');
    }

    /**
     * Logout the form for creating a new resource.
     */
    public function logout()
    {
        Auth::logout();
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // Gate::allows('login', $user)
        if($user){
            // Autoriser uniquement Admin et Admin Sup au dashboard admin
            $allowedRoles = [
                RoleEnum::ADMIN->id(),
                RoleEnum::ADMIN_SUP->id()
            ];
            
            if (in_array($user->role_id, $allowedRoles) && $user->status == "active") {

                $credentials = $request->only('email', 'password');

                if (Auth::attempt($credentials, $request->filled('remember'))) {
                    $request->session()->regenerate();
                    return redirect()->route('home');
                }

                return back()->withErrors([
                    'email' => 'Les informations d\'identification fournies ne sont pas valide.',
                ]);

            } else {
                return back()->withErrors([
                    'email' => 'Compte utilisateur invalide',
                ]);
            }
        }
        else {
            return back()->withErrors([
                'email' => 'Compte utilisateur invalide',
            ]);
        }
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Envoyer l'email de vérification
        try {
            $user->notify(new \App\Notifications\VerifyEmailNotification());
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email vérification: ' . $e->getMessage());
        }

        return $user;
    }
}
