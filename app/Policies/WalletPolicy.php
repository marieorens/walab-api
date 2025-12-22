<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any wallets.
     */
    public function viewAny(User $user): bool
    {
        // Seuls les admins peuvent voir tous les wallets
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can view the wallet.
     */
    public function view(User $user, Wallet $wallet): bool
    {
        // Admin peut tout voir, ou le propriétaire peut voir son wallet
        return $this->isAdmin($user) || $wallet->user_id === $user->id;
    }

    /**
     * Determine whether the user can view wallet transactions.
     */
    public function viewTransactions(User $user, Wallet $wallet): bool
    {
        return $this->isAdmin($user) || $wallet->user_id === $user->id;
    }

    /**
     * Determine whether the user can adjust the wallet balance.
     */
    public function adjust(User $user, Wallet $wallet): bool
    {
        // Seuls les admins peuvent ajuster les soldes
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can block the wallet.
     */
    public function block(User $user, Wallet $wallet): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can suspend the wallet.
     */
    public function suspend(User $user, Wallet $wallet): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can activate the wallet.
     */
    public function activate(User $user, Wallet $wallet): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can process withdrawals.
     */
    public function processWithdrawal(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can generate monthly withdrawals.
     */
    public function generateWithdrawals(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if user is an admin
     */
    protected function isAdmin(User $user): bool
    {
        // role_id 4 = admin Sup
        if ($user->role_id == 4) {
            return true;
        }

        // Vérifier via la relation role
        if ($user->role && in_array($user->role->label, ['admin Sup', 'admin', 'Admin'])) {
            return true;
        }

        return false;
    }
}
