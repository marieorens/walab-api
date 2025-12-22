<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function login_Admin(User $user)
    {
        return $user->role_id === 1;
    }

    public function client(User $user)
    {
        return $user->role_id === 3;
    }

    public function agent(User $user)
    {
        return $user->role_id === 2;
    }
}
