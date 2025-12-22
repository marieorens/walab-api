<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use App\Models\User; // Pour récupérer l'auth user si besoin dans le layout
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VilleController extends Controller
{
    /**
     * Affiche la liste des villes
     */
    public function index()
    {
        // On récupère l'utilisateur connecté pour le passer à la vue (comme dans tes autres controlleurs)
        $user_auth = User::where('id', Auth::user()->id)->first();

        // On récupère les villes, les plus récentes en premier
        $villes = Ville::orderBy('created_at', 'DESC')->paginate(10);

        return view('villes.index', compact('villes', 'user_auth'));
    }

    /**
     * Enregistrer une nouvelle ville
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:villes,nom'
        ]);

        Ville::create([
            'nom' => $request->nom,
            'is_active' => true
        ]);

        return back()->with('success', 'Ville ajoutée avec succès.');
    }

    /**
     * Mettre à jour une ville
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:villes,nom,' . $id
        ]);

        $ville = Ville::findOrFail($id);
        $ville->update([
            'nom' => $request->nom
        ]);

        return back()->with('success', 'Ville modifiée avec succès.');
    }

    /**
     * Activer / Désactiver une ville
     */
    public function toggle($id)
    {
        $ville = Ville::findOrFail($id);
        $ville->is_active = !$ville->is_active;
        $ville->save();

        $status = $ville->is_active ? 'activée' : 'désactivée';
        return back()->with('success', "Ville $status avec succès.");
    }

    /**
     * Supprimer une ville
     */
    public function destroy($id)
    {
        $ville = Ville::findOrFail($id);
        $ville->delete();

        return back()->with('success', 'Ville supprimée avec succès.');
    }
}
