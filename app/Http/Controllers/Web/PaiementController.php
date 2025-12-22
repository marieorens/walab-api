<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    protected $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Liste tous les paiements
     */
    public function index(Request $request)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();

        $query = Paiement::with(['laboratoire'])
            ->where('isdelete', false)
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('code_commande', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($request->filled('laboratoire_id')) {
            $query->where('laboratoire_id', $request->laboratoire_id);
        }

        $paiements = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Paiement::where('isdelete', false)->count(),
            'total_montant' => Paiement::where('isdelete', false)->where('status', 'approved')->sum('montant'),
            'en_attente' => Paiement::where('isdelete', false)->where('status', 'pending')->count(),
            'payes' => Paiement::where('isdelete', false)->where('status', 'approved')->count(),
            'echoues' => Paiement::where('isdelete', false)->whereIn('status', ['declined', 'cancelled'])->count(),
        ];

        // Liste des laboratoires pour le filtre
        $laboratoires = \App\Models\Laboratorie::where('isdelete', false)->get();

        return view('user.admin.paiements.index', compact('user_auth', 'paiements', 'stats', 'laboratoires'));
    }

    /**
     * Affiche les détails d'un paiement
     */
    public function show(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();

        $paiement = Paiement::with([
            'laboratoire',
            'walletTransactions'
        ])->findOrFail($id);

        // Récupérer la commande associée via code_commande
        $commande = \App\Models\Commande::with(['client', 'agent', 'examen.laboratorie', 'type_bilan.laboratorie'])
            ->where('code', $paiement->code_commande)
            ->first();

        return view('user.admin.paiements.show', compact('user_auth', 'paiement', 'commande'));
    }
}
