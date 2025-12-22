<?php

namespace App\Http\Controllers\Web\Laboratoire;

use App\Http\Controllers\Controller;
use App\Models\Laboratorie;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Affiche le portefeuille du laboratoire connecté
     */
    public function index(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if (!$laboratoire) {
            return redirect()->route('laboratoire.login')
                ->with('error', 'Aucun laboratoire n\'est associé à votre compte.');
        }

        // Récupérer ou créer le wallet du laboratoire
        $wallet = $user_auth->getOrCreateWallet();
        
        $periode = $request->get('periode', now()->format('Y-m'));
        $stats = $this->walletService->getWalletStats($wallet, $periode);

        // Statistiques des 6 derniers mois pour le graphique
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $monthlyData[$month] = $wallet->getMonthlyBalance($month);
        }

        // Transactions récentes
        $recentTransactions = $this->walletService->getTransactions($wallet, [], 10);

        return view('laboratoire.wallet.index', compact(
            'user_auth',
            'laboratoire',
            'wallet',
            'stats',
            'periode',
            'monthlyData',
            'recentTransactions'
        ));
    }

    /**
     * Affiche l'historique complet des transactions
     */
    public function transactions(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if (!$laboratoire) {
            return redirect()->route('laboratoire.login')
                ->with('error', 'Aucun laboratoire n\'est associé à votre compte.');
        }

        $wallet = $user_auth->getOrCreateWallet();

        $filters = [
            'type' => $request->get('type'),
            'periode' => $request->get('periode'),
            'date_debut' => $request->get('date_debut'),
            'date_fin' => $request->get('date_fin'),
        ];

        $transactions = $this->walletService->getTransactions($wallet, $filters, 20);

        return view('laboratoire.wallet.transactions', compact(
            'user_auth',
            'laboratoire',
            'wallet',
            'transactions',
            'filters'
        ));
    }

    /**
     * Affiche les retraits du laboratoire
     */
    public function withdrawals(Request $request)
    {
        $user_auth = User::where('id', Auth::user()->id)->first();
        $laboratoire = Laboratorie::where('user_id', $user_auth->id)->first();

        if (!$laboratoire) {
            return redirect()->route('laboratoire.login')
                ->with('error', 'Aucun laboratoire n\'est associé à votre compte.');
        }

        $wallet = $user_auth->getOrCreateWallet();

        $withdrawals = $wallet->withdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('laboratoire.wallet.withdrawals', compact(
            'user_auth',
            'laboratoire',
            'wallet',
            'withdrawals'
        ));
    }
}
