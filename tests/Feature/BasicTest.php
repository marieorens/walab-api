<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Test que l'application répond correctement
     */
    public function test_application_responds(): void
    {
        $response = $this->get('/api/laboratorie/list');

        $response->assertStatus(200);
    }

    public function test_laboratories_list_endpoint(): void
    {
        $response = $this->get('/api/laboratorie/list');

        // Vérifie que la réponse est au format JSON
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'code',
                    'message'
                ]);
    }
}
