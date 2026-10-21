<?php

namespace Database\Factories;

use App\Models\AgendaItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgendaItemCategory>
 */
class AgendaItemCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
