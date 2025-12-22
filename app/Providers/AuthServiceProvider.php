<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Wallet;
use App\Policies\UserPolicy;
use App\Policies\WalletPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Wallet::class => WalletPolicy::class,
    ];

  
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistrer les policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
