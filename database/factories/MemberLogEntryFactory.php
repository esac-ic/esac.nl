<?php

namespace Database\Factories;

use App\Events\MembershipEnded;
use App\Events\MembershipRestored;
use App\Events\MemberSignedUp;
use App\Events\MemberTypeChanged;
use App\Events\SignUpApproved;
use App\Events\SignUpRejected;
use App\Models\MemberLogEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberLogEntry>
 */
class MemberLogEntryFactory extends Factory
{
    /**
     * The events that implement LogsToMemberLog, which the seeder can't find by reflection.
     */
    private const array LOGGED_EVENTS = [
        MemberTypeChanged::class,
        MembershipEnded::class,
        MembershipRestored::class,
        MemberSignedUp::class,
        SignUpApproved::class,
        SignUpRejected::class,
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = fake()->randomElement(self::LOGGED_EVENTS);

        $details = match ($event) {
            MemberTypeChanged::class => 'Member changed from ... to ...',
            MembershipEnded::class => 'Member became a former member',
            MemberSignedUp::class => 'New pending member: ...',
            MembershipRestored::class => 'Member became a member again',
            SignUpApproved::class => 'Member was approved as a member',
            SignUpRejected::class => 'Member was removed as a pending member',
            default => 'Unknown change',
        };

        return [
            'type' => class_basename($event),
            'details' => $details,
            'created_at' => fake()->dateTimeThisYear(),
        ];
    }
}
