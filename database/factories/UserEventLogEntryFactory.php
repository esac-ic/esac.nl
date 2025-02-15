<?php

namespace Database\Factories;

use App\Models\UserEventLogEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserEventLogEntry>
 */
class UserEventLogEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'eventType' => fake()->randomElement(\App\Enums\UserEventTypes::values()),
            'eventDetails' => fake()->sentence(5),
            'created_at' => fake()->dateTimeThisYear(),
        ];
    }
    
    public function randomUser(int $totalNrOfUsers): Factory
    {
        return $this->state(function (array $attributes) use($totalNrOfUsers) {
            return [
                'user_id' => random_int(1, $totalNrOfUsers),
            ];
        });
    }
}
