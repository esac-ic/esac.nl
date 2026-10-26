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
            'body' => fake()->text(),
            'short_description' => fake()->word(),
            'created_by' => User::factory(),
            'event_category_id' => EventCategory::factory(),
            'start_date' => $start = fake()->dateTimeBetween('-1 month', '+3 months'),
            // A valid event ends after it starts and closes its registration before.
            'end_date' => (clone $start)->modify('+'.fake()->numberBetween(2, 48).' hours'),
            'registration_deadline' => (clone $start)->modify('-1 day'),
            'shows_certificates' => fake()->boolean(),
        ];
    }
}
