<?php

namespace Database\Factories;

use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgendaItem>
 */
class AgendaItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'text' => fake()->text(),
            'shortDescription' => fake()->word(),
            'createdBy' => User::factory(),
            'category' => AgendaItemCategory::factory(),
            'startDate' => fake()->date(),
            'endDate' => fake()->date(),
            'subscription_endDate' => fake()->date(),
            'image_url' => fake()->imageUrl(),
            'climbing_activity' => fake()->boolean(),
        ];
    }
}
