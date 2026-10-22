<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'text' => fake()->text(),
            'short_description' => fake()->word(),
            'created_by' => User::factory(),
            'event_category_id' => EventCategory::factory(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'registration_deadline' => fake()->date(),
            'image_path' => '',
            'shows_certificates' => fake()->boolean(),
        ];
    }
}
