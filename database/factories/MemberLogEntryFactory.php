<?php

namespace Database\Factories;

use App\Enums\MemberLogType;
use App\Models\MemberLogEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberLogEntry>
 */
class MemberLogEntryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(MemberLogType::cases());

        return [
            'type' => $type,
            'details' => $type->label().': '.fake()->name(),
            'created_at' => fake()->dateTimeThisYear(),
        ];
    }
}
