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
    public function definition(): array
    {
        return [
            'text' => fake()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
