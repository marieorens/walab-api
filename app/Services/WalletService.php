<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WalletService
{
    public function getOrCreateWallet(int $userId, string $type = 'laboratoire'): Wallet
    {
        return Wallet::getOrCreateForUser($userId, $type);
    }

    public function getWalletStats(Wallet $wallet, ?string $periode = null): array
    {
        $periode = $periode ?? now()->format('Y-m');
        
        $transactions = $wallet->transactions()
            ->completed()
            ->forPeriod($periode);

        $credits = (clone $transactions)->credits()->sum('montant');
        $debits = (clone $transactions)->debits()->sum('montant');
        $count = $transactions->count();

        return [
            'solde_actuel' => $wallet->balance,
            'total_entrees' => $wallet->total_entrees,
            'total_sorties' => $wallet->total_sorties,
            'credits_periode' => $credits,
            'debits_periode' => $debits,
            'transactions_periode' => $count,
            'periode' => $periode,
            'status' => $wallet->status,
        ];
    }

    public function getTransactions(Wallet $wallet, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $wallet->transactions()->orderBy('created_at', 'desc');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['periode'])) {
            $query->forPeriod($filters['periode']);
        }

        if (!empty($filters['date_debut'])) {
            $query->whereDate('created_at', '>=', $filters['date_debut']);
        }

        if (!empty($filters['date_fin'])) {
            $query->whereDate('created_at', '<=', $filters['date_fin']);
        }

        return $query->paginate($perPage);
    }

    public function getAllWallets(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Wallet::with('user')->orderBy('created_at', 'desc');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function adjustBalance(Wallet $wallet, float $montant, string $description, int $performedBy, string $type = 'credit'): ?WalletTransaction
    {
        if ($type === 'credit') {
            return $wallet->credit($montant, "[Ajustement Admin] {$description}", null, null, $performedBy);
        }

        if ($type === 'debit' && $wallet->canDebit($montant)) {
            return $wallet->debit($montant, "[Ajustement Admin] {$description}", $performedBy);
        }

        return null;
    }

    public function blockWallet(Wallet $wallet): void
    {
        $wallet->block();
    }

    public function suspendWallet(Wallet $wallet): void
    {
        $wallet->suspend();
    }

    public function activateWallet(Wallet $wallet): void
    {
        $wallet->activate();
    }

    public function getPlatformStats(?string $periode = null): array
    {
        $periode = $periode ?? now()->format('Y-m');
        
        $walletPlateforme = Wallet::getPlateforme();
        
        $totalLabosBalance = Wallet::where('type', 'laboratoire')
            ->where('status', 'active')
            ->sum('balance');

        $labosCount = Wallet::where('type', 'laboratoire')->count();
        $labosActifs = Wallet::where('type', 'laboratoire')
            ->where('status', 'active')
            ->count();

        $transactionsPeriodeCount = WalletTransaction::completed()
            ->forPeriod($periode)
            ->count();

        // Calcul des dettes (soldes négatifs des laboratoires)
        $totalDebts = Wallet::where('type', 'laboratoire')
            ->where('balance', '<', 0)
            ->sum('balance');
        $debtCount = Wallet::where('type', 'laboratoire')
            ->where('balance', '<', 0)
            ->count();

        return [
            'total_commissions' => $walletPlateforme?->balance ?? 0,
            'total_lab_earnings' => $totalLabosBalance,
            'total_transactions' => $transactionsPeriodeCount,
            'active_wallets' => $labosActifs,
            'total_wallets' => $labosCount,
            'total_debts' => abs($totalDebts),
            'debt_count' => $debtCount,
            'periode' => $periode,
        ];
    }

    public function getTopLaboratoires(int $limit = 10, ?string $periode = null): Collection
    {
        $periode = $periode ?? now()->format('Y-m');

        return Wallet::where('type', 'laboratoire')
            ->with('user.laboratorie')
            ->withSum(['transactions as revenus_periode' => function ($query) use ($periode) {
                $query->credits()->completed()->forPeriod($periode);
            }], 'montant')
            ->withCount(['transactions as count_periode' => function ($query) use ($periode) {
                $query->credits()->completed()->forPeriod($periode);
            }])
            ->orderByDesc('revenus_periode')
            ->limit($limit)
            ->get()
            ->map(function($wallet) {
                return [
                    'nom' => $wallet->user->laboratorie->nom ?? ($wallet->user->firstname . ' ' . $wallet->user->lastname),
                    'total' => floatval($wallet->revenus_periode ?? 0),
                    'count' => $wallet->count_periode ?? 0,
                ];
            });
    }

    public function generateMonthlyWithdrawals(?string $periode = null): array
    {
        $periode = $periode ?? now()->subMonth()->format('Y-m');
        return $this->generateWithdrawalsForType('laboratoire', $periode, '%Y-%m');
    }

    public function generateWeeklyWithdrawals(?string $periode = null): array
    {
        // Format ISO de la semaine : 2025-W51
        $periode = $periode ?? now()->subWeek()->format('o-\WW'); 
        return $this->generateWithdrawalsForType('agent', $periode, '%X-W%V');
    }

    public function generateWithdrawalsForType(string $type, string $periode, string $format): array
    {
        $results = ['created' => 0, 'skipped' => 0, 'errors' => 0];

        $wallets = Wallet::where('type', $type)
            ->where('status', 'active')
            ->get();

        foreach ($wallets as $wallet) {
            try {
                $withdrawal = Withdrawal::createForWallet($wallet, $periode, $format);
                
                if ($withdrawal) {
                    $results['created']++;
                } else {
                    $results['skipped']++;
                }
            } catch (\Exception $e) {
                $results['errors']++;
            }
        }

        return $results;
    }

    public function getPendingWithdrawals(): Collection
    {
        return Withdrawal::with('wallet.user.laboratorie')
            ->pending()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function processWithdrawal(Withdrawal $withdrawal, int $adminId, string $action, ?string $notes = null): bool
    {
        $success = false;
        
        switch ($action) {
            case 'approve':
                $withdrawal->markAsCompleted($adminId, $notes);
                $success = true;
                break;
            case 'reject':
                $withdrawal->markAsFailed($adminId, $notes);
                $success = true;
                break;
            case 'cancel':
                $withdrawal->cancel($adminId, $notes);
                $success = true;
                break;
        }

        // Envoyer notification au propriétaire du wallet
        if ($success && $withdrawal->wallet && $withdrawal->wallet->user) {
            try {
                $actionMap = [
                    'approve' => 'approved',
                    'reject' => 'rejected',
                    'cancel' => 'cancelled',
                ];
                
                $withdrawal->wallet->user->notify(
                    new \App\Notifications\WithdrawalProcessedNotification(
                        $withdrawal, 
                        $actionMap[$action] ?? $action
                    )
                );
            } catch (\Exception $e) {
                \Log::warning("Impossible d'envoyer la notification de retrait: " . $e->getMessage());
            }
        }

        return $success;
    }
}
