<?php

namespace App\Enum;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

enum RoleEnum: string
{
    // admin
    case ADMIN = 'Admin';

    // agent
    case AGENT = 'Agent';

    // client
    case CLIENT = 'client';

    // admin Sup
    case ADMIN_SUP = 'Admin Sup';

    // laboratoire
    case LABORATOIRE = 'Laboratoire';

    /**
     * Récupérer l'ID du rôle depuis la base de données (avec cache)
     */
    public function id(): int
    {
        return Cache::remember("role_id_{$this->value}", 3600, function () {
            $role = Role::where('value', $this->value)->first();
            return $role ? $role->id : 0;
        });
    }

    /**
     * Récupérer plusieurs IDs de rôles
     */
    public static function ids(array $roles): array
    {
        return array_map(fn($role) => $role->id(), $roles);
    }
}
