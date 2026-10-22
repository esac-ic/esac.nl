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
            'short_description' => fake()->word(),
            'created_by' => User::factory(),
            'agenda_item_category_id' => AgendaItemCategory::factory(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'subscription_end_date' => fake()->date(),
            'image_url' => '',
            'climbing_activity' => fake()->boolean(),
        ];
    }
}
