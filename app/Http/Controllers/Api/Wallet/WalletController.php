<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
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

    public function getBalance(): JsonResponse
    {
        $user = $this->auth->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Portefeuille non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Solde du portefeuille',
            'data' => [
                'balance' => $wallet->balance,
                'pending_balance' => $wallet->pending_balance,
                'status' => $wallet->status,
            ]
        ]);
    }

    public function getStats(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Portefeuille non trouvé',
            ], 404);
        }

        $periode = $request->get('periode', now()->format('Y-m'));
        $stats = $this->walletService->getWalletStats($wallet, $periode);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Statistiques du portefeuille',
            'data' => $stats
        ]);
    }

    public function getTransactions(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Portefeuille non trouvé',
            ], 404);
        }

        $filters = [
            'type' => $request->get('type'),
            'status' => $request->get('status'),
            'periode' => $request->get('periode'),
            'date_debut' => $request->get('date_debut'),
            'date_fin' => $request->get('date_fin'),
        ];

        $perPage = $request->get('per_page', 15);
        $transactions = $this->walletService->getTransactions($wallet, $filters, $perPage);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Liste des transactions',
            'data' => $transactions
        ]);
    }

    public function getWithdrawals(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Portefeuille non trouvé',
            ], 404);
        }

        $withdrawals = $wallet->withdrawals()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Liste des retraits',
            'data' => $withdrawals
        ]);
    }

    public function getDashboard(Request $request): JsonResponse
    {
        $user = $this->auth->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Portefeuille non trouvé',
            ], 404);
        }

        $periode = $request->get('periode', now()->format('Y-m'));
        $stats = $this->walletService->getWalletStats($wallet, $periode);
        $lastTransactions = $wallet->transactions()->limit(5)->get();
        $lastWithdrawals = $wallet->withdrawals()->limit(3)->get();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Dashboard portefeuille',
            'data' => [
                'stats' => $stats,
                'last_transactions' => $lastTransactions,
                'last_withdrawals' => $lastWithdrawals,
            ]
        ]);
    }
}
