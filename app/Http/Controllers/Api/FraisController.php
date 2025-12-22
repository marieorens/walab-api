<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frais;
use App\Models\Examen;
use App\Models\TypeBilan;
use Illuminate\Http\Request;

class FraisController extends Controller
{
    /**
     * Configuration des frais et du pourcentage (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'frais' => 'required|numeric',
            'pourcentage' => 'nullable|numeric|min:0|max:100' // Validation du pourcentage
        ]);

        $frais = Frais::find(1);

        $pourcentage = $request->input('pourcentage',40); // 40 par défaut si vide

        if(!$frais){
            $frais = Frais::create([
                'nom' => 'Frais de deplacement',
                'frais' => $request->frais,
                'pourcentage_majoration' => $pourcentage
            ]);
        } else {
            $frais->frais = $request->frais;
            $frais->pourcentage_majoration = $pourcentage;
            $frais->save();
        }

        return back()->with('success', 'Frais et pourcentage mis à jour');
    }

    /**
     * Récupérer le frais de base (Simple)
     */
    public function getFrais()
    {
        $frais = Frais::find(1);
        return response()->json([
            'success' => true,
            'code' => 200,
            'frais' => $frais ? $frais->frais : 1500,
            'pourcentage' => $frais ? $frais->pourcentage_majoration : 40
        ]);
    }

    /**
     * Calculer les frais CUMULATIFS
     */
    public function calculateFrais(Request $request)
    {
        // 1. Récupérer config
        $fraisConfig = Frais::find(1);
        $baseFrais = $fraisConfig ? (float)$fraisConfig->frais : 1500;
        $pourcentage = $fraisConfig ? (float)$fraisConfig->pourcentage_majoration : 40;

        // 2. Récupérer IDs
        $examenIds = $request->input('examen_ids', []);
        $bilanIds = $request->input('type_bilan_ids', []);

        if (empty($examenIds) && empty($bilanIds)) {
            return response()->json(['success' => true, 'frais' => $baseFrais]);
        }

        // 3. Compter Labos
        $laboratoires = collect();

        if (!empty($examenIds)) {
            $labsExamens = Examen::whereIn('id', $examenIds)->pluck('laboratorie_id');
            $laboratoires = $laboratoires->concat($labsExamens);
        }

        if (!empty($bilanIds)) {
            $labsBilans = TypeBilan::whereIn('id', $bilanIds)->pluck('laboratorie_id');
            $laboratoires = $laboratoires->concat($labsBilans);
        }

        $uniqueLabsCount = $laboratoires->unique()->count();

        // 4. CALCUL DYNAMIQUE
        $montantFinal = $baseFrais;
        $nbLabosSupplementaires = 0;

        if ($uniqueLabsCount > 1) {
            $nbLabosSupplementaires = $uniqueLabsCount - 1;

            // On utilise le pourcentage de la BDD (ex: 40/100 = 0.40)
            $facteur = $pourcentage / 100;
            $surchargeParLabo = $baseFrais * $facteur;

            $montantFinal = $baseFrais + ($nbLabosSupplementaires * $surchargeParLabo);
        }

        return response()->json([
            'success' => true,
            'frais' => round($montantFinal), // Arrondi
            'frais_base' => $baseFrais,
            'pourcentage_applique' => $pourcentage,
            'nb_laboratoires' => $uniqueLabsCount,
            'majoration' => $uniqueLabsCount > 1,
            'message' => ($uniqueLabsCount > 1)
                ? "Majoration de {$pourcentage}% par laboratoire supplémentaire appliquée."
                : "Tarif standard."
        ]);
    }
}
