<?php

namespace Database\Factories;

use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sponsor>
 */
class SponsorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'url' => fake()->url(),
        ];
    }
}
