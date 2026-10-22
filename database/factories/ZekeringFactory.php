<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Zekering;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Zekering>
 */
class ZekeringFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => fake()->sentence(rand(4, 20)),
            'created_by' => User::factory(),
        ];
    }
}
