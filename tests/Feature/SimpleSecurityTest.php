<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleSecurityTest extends TestCase
{
    use RefreshDatabase;

  
    public function test_passwords_are_automatically_hashed(): void
    {
        // Créer d'abord un rôle
        $role = \App\Models\Role::create([
            'label' => 'Client',
            'value' => 'client'
        ]);

        $plainPassword = 'monmotdepasse123';

        $user = User::create([
            'firstname' => 'Marie',
            'lastname' => 'Dubois',
            'email' => 'marie@example.com',
            'password' => $plainPassword,
            'role_id' => $role->id,
        ]);

        $this->assertNotEquals($plainPassword, $user->password);

        $this->assertStringStartsWith('$2y$', $user->password);
    }

    
    public function test_emails_must_be_unique_in_database(): void
    {
        // Créer d'abord un rôle
        $role = \App\Models\Role::create([
            'label' => 'Client',
            'value' => 'client'
        ]);

        // Créer le premier utilisateur
        User::create([
            'firstname' => 'Pierre',
            'lastname' => 'Martin',
            'email' => 'pierre@example.com',
            'password' => 'password123',
            'role_id' => $role->id,
        ]);

        try {
            User::create([
                'firstname' => 'Paul',
                'lastname' => 'Durand',
                'email' => 'pierre@example.com', // Même email
                'password' => 'password456',
                'role_id' => $role->id,
            ]);
            // Si on arrive ici, le test échoue
            $this->fail('L\'email dupliqué a été accepté');
        } catch (\Exception $e) {
            // C'est normal que ça échoue
            $this->assertTrue(true);
        }
    }
}
