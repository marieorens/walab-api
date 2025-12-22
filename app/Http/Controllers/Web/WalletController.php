<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected WalletService $walletService;
    protected $auth;

    public function __construct(WalletService $walletService, AuthManager $auth)
    {
        $this->walletService = $walletService;
        $this->auth = $auth;
    }

    public function index(Request $request)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $periode = $request->get('periode', now()->format('Y-m'));
        
        $wallets = $this->walletService->getAllWallets([
            'type' => $request->get('type'),
            'status' => $request->get('status'),
        ], 10);

        $platformStats = $this->walletService->getPlatformStats($periode);
        $topLabos = $this->walletService->getTopLaboratoires(5, $periode);

        return view('user.admin.wallets.index', compact(
            'user_auth', 
            'wallets', 
            'platformStats', 
            'topLabos', 
            'periode'
        ));
    }

    public function show(string $id)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $wallet = Wallet::with('user.laboratorie')->findOrFail($id);
        
        $periode = request('periode', now()->format('Y-m'));
        $stats = $this->walletService->getWalletStats($wallet, $periode);
        $transactions = $this->walletService->getTransactions($wallet, [
            'periode' => request('transaction_periode'),
        ], 10);

        return view('user.admin.wallets.show', compact(
            'user_auth', 
            'wallet', 
            'stats', 
            'transactions',
            'periode'
        ));
    }

    public function transactions(string $id, Request $request)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $wallet = Wallet::with('user.laboratorie')->findOrFail($id);
        
        $filters = [
            'type' => $request->get('type'),
            'status' => $request->get('status'),
            'periode' => $request->get('periode'),
            'date_debut' => $request->get('date_debut'),
            'date_fin' => $request->get('date_fin'),
        ];

        $transactions = $this->walletService->getTransactions($wallet, $filters, 20);

        return view('user.admin.wallets.transactions', compact(
            'user_auth', 
            'wallet', 
            'transactions',
            'filters'
        ));
    }

    public function adjust(Request $request, string $id)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        $wallet = Wallet::findOrFail($id);
        $adminId = $this->auth->user()->id;

        $transaction = $this->walletService->adjustBalance(
            $wallet,
            floatval($request->montant),
            $request->description,
            $adminId,
            $request->type
        );

        if ($transaction) {
            return back()->with('success', 'Ajustement effectué avec succès.');
        }

        return back()->with('error', 'Impossible d\'effectuer l\'ajustement. Vérifiez le solde disponible.');
    }

    public function block(string $id)
    {
        $wallet = Wallet::findOrFail($id);
        $this->walletService->blockWallet($wallet);

        return back()->with('success', 'Portefeuille bloqué avec succès.');
    }

    public function suspend(string $id)
    {
        $wallet = Wallet::findOrFail($id);
        $this->walletService->suspendWallet($wallet);

        return back()->with('success', 'Portefeuille suspendu avec succès.');
    }

    public function activate(string $id)
    {
        $wallet = Wallet::findOrFail($id);
        $this->walletService->activateWallet($wallet);

        return back()->with('success', 'Portefeuille activé avec succès.');
    }

    public function withdrawals(Request $request)
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        
        $status = $request->get('status', 'pending');
        $periode = $request->get('periode');

        $query = Withdrawal::with('wallet.user.laboratorie');

        if ($status) {
            $query->where('status', $status);
        }

        if ($periode) {
            $query->where('periode', $periode);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(15);
        $pendingCount = Withdrawal::pending()->count();

        return view('user.admin.wallets.withdrawals', compact(
            'user_auth',
            'withdrawals',
            'pendingCount',
            'status',
            'periode'
        ));
    }

    public function processWithdrawal(Request $request, string $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,cancel',
            'notes' => 'nullable|string|max:500',
        ]);

        $withdrawal = Withdrawal::findOrFail($id);
        $adminId = $this->auth->user()->id;

        $success = $this->walletService->processWithdrawal(
            $withdrawal,
            $adminId,
            $request->action,
            $request->notes
        );

        if ($success) {
            $messages = [
                'approve' => 'Retrait approuvé et traité avec succès.',
                'reject' => 'Retrait rejeté.',
                'cancel' => 'Retrait annulé.',
            ];
            return back()->with('success', $messages[$request->action]);
        }

        return back()->with('error', 'Erreur lors du traitement du retrait.');
    }

    public function generateWithdrawals(Request $request)
    {
        $periode = $request->get('periode', now()->subMonth()->format('Y-m'));
        $results = $this->walletService->generateMonthlyWithdrawals($periode);

        return back()->with('success', 
            "Retraits générés : {$results['created']} créés, {$results['skipped']} ignorés, {$results['errors']} erreurs."
        );
    }

    public function platformWallet()
    {
        $user_auth = User::where("id", $this->auth->user()->id)->first();
        $wallet = Wallet::getPlateforme();
        
        if (!$wallet) {
            $adminUser = User::whereHas('role', function($q) {
                $q->where('label', 'admin Sup');
            })->first();
            
            if ($adminUser) {
                $wallet = Wallet::getOrCreateForUser($adminUser->id, 'plateforme');
            }
        }

        $periode = request('periode', now()->format('Y-m'));
        $stats = $wallet ? $this->walletService->getWalletStats($wallet, $periode) : null;
        $transactions = $wallet ? $this->walletService->getTransactions($wallet, [], 15) : collect();

        return view('user.admin.wallets.platform', compact(
            'user_auth',
            'wallet',
            'stats',
            'transactions',
            'periode'
        ));
    }
}
