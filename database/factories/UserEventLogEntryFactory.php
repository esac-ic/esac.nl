<?php

namespace Database\Factories;

use App\Events\MemberBecameOldMember;
use App\Events\MemberKindChanged;
use App\Events\MemberTypeChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Events\PendingUserCreated;
use App\Events\PendingUserRemoved;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserEventLogEntry>
 */
class UserEventLogEntryFactory extends Factory
{
    /**
     * List of all classes implementing LoggableUserEventInterface.
     * You can't really get this list with reflections here since the classes need to be loaded by php and they aren't at seeding time.
     */
    private const LOGGABLE_USER_EVENTS = [
        MemberKindChanged::class,
        MemberBecameOldMember::class,
        OldMemberBecameMember::class,
        PendingUserCreated::class,
        PendingUserApproved::class,
        PendingUserRemoved::class,
    ];
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventType = fake()->randomElement(UserEventLogEntryFactory::LOGGABLE_USER_EVENTS);
        
        $eventDetails = match ($eventType) {
            MemberKindChanged::class => "User changed from ... to ...",
            MemberBecameOldMember::class => "User became old member",
            PendingUserCreated::class => "New pending user: ...",
            OldMemberBecameMember::class => "User became a member again",
            PendingUserApproved::class => "User was approved as a member",
            PendingUserRemoved::class => "User was removed as a pending member",
            default => "Unknown event type",
        };
        
        $eventType = (new ReflectionClass($eventType))->getShortName(); //turn it to the display name
        
        return [
            'event_type' => $eventType,
            'event_details' => $eventDetails,
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
