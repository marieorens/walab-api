<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class TestRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles de base pour les tests
        Role::create([
            'label' => 'Admin',
            'value' => 'admin'
        ]);

        Role::create([
            'label' => 'Laboratoire',
            'value' => 'laboratory'
        ]);

        Role::create([
            'label' => 'Client',
            'value' => 'client'
        ]);

        Role::create([
            'label' => 'Praticien',
            'value' => 'practitioner'
        ]);
    }
}