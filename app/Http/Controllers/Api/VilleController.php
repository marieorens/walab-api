<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use Illuminate\Http\JsonResponse;

class VilleController extends Controller
{
    /**
     * Récupérer la liste des villes actives pour l'inscription
     */
    public function index(): JsonResponse
    {
        // On récupère uniquement les villes marquées comme 'actives'
        $villes = Ville::where('is_active', true)
            ->orderBy('nom', 'asc')
            ->get(['id', 'nom']); // On ne prend que l'ID et le NOM

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }
}
