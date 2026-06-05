<?php

namespace Database\Factories;

use App\Models\Musique;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Musique>
 */
class MusiqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_musique'=>$this->faker->numberBetween(1,12),
            'prix' => $this->faker->randomFloat(2, 0, 3),
            'duree' => $this->faker->numberBetween(120, 360),
        ];
    }
}
