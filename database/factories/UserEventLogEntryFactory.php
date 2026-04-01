<?php

namespace Database\Factories;

use App\Enums\UserEventTypes;
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
        $eventType = fake()->randomElement(UserEventTypes::cases());
        switch ($eventType) {
            case UserEventTypes::MemberTypeChanged:
                $eventDetails = "User changed from ... to ...";
                break;
            case UserEventTypes::MemberBecameOldMember:
                $eventDetails = "User became old member";
                break;
            case UserEventTypes::NewPendingUser:
                $eventDetails = "New pending user: ...";
                break;
            case UserEventTypes::OldMemberBecameMember:
                $eventDetails = "User became a member again";
                break;
            case UserEventTypes::PendingUserApproved:
                $eventDetails = "User was approved as a member";
                break;
            case UserEventTypes::PendingUserRemoved:
                $eventDetails = "User was removed as a pending member";
                break;
            default:
                $eventDetails = "Unknown event type";
                break;
        }
                    
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
