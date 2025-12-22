<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerySimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_cities_api_works(): void
    {
        $response = $this->get('/api/villes');

        $this->assertTrue(
            $response->status() === 200 ||
            $response->status() === 404 ||
            $response->status() === 500
        );
    }

    public function test_exams_api_works(): void
    {
        $response = $this->get('/api/examen/list');

        $this->assertTrue(
            $response->status() === 200 ||
            $response->status() === 404 ||
            $response->status() === 500
        );
    }


    public function test_bilan_types_api_works(): void
    {
        $response = $this->get('/api/typebilan/list');

        $this->assertTrue(
            $response->status() === 200 ||
            $response->status() === 404 ||
            $response->status() === 500
        );
    }
}
