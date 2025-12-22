<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laboratorie>
 */
class LaboratorieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'address' => $this->faker->address(),
            'image' => 'laboratoire/default.png',
            'pourcentage_commission' => $this->faker->numberBetween(5, 20),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
