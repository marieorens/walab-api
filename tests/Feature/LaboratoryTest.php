<?php

namespace Tests\Feature;

use App\Models\Laboratorie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les rôles nécessaires
        \App\Models\Role::create(['label' => 'Admin', 'value' => 'admin']);
        \App\Models\Role::create(['label' => 'Client', 'value' => 'client']);
        \App\Models\Role::create(['label' => 'Laboratoire', 'value' => 'laboratory']);
    }

    public function test_laboratory_list_works(): void
    {
        $user = User::factory()->create();
        $laboratory = Laboratorie::factory()->create(['user_id' => $user->id]);

        // Faire la requête
        $response = $this->get('/api/laboratorie/list');

        // Vérifier que ça fonctionne
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'code' => 200
                ]);
    }

    public function test_laboratory_model_validation(): void
    {
        $user = User::factory()->create();

        // Créer un laboratoire avec des données valides
        $laboratory = Laboratorie::create([
            'name' => 'Laboratoire Test',
            'description' => 'Description du laboratoire',
            'address' => '123 Rue de Test',
            'user_id' => $user->id,
            'pourcentage_commission' => 10.5
        ]);

        // Vérifier que le laboratoire a été créé
        $this->assertEquals('Laboratoire Test', $laboratory->name);
        $this->assertEquals('Description du laboratoire', $laboratory->description);
        $this->assertEquals('123 Rue de Test', $laboratory->address);
    }
}
