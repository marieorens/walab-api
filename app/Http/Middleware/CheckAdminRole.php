<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur est un admin (role_id 4) ou admin sup
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Veuillez vous connecter.');
        }

        // role_id 4 = admin Sup (super admin)
        // On peut aussi autoriser d'autres admins si besoin
        $user = Auth::user();
        
        if ($user->role_id == 4) {
            return $next($request);
        }

        // Vérifier aussi via la relation role si elle existe
        if ($user->role && in_array($user->role->label, ['admin Sup', 'admin', 'Admin'])) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', "Vous n'avez pas les permissions pour accéder à cette page.");
    }
}
