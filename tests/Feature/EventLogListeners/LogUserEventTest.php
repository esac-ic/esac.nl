<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\MemberBecameOldMember;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Events\PendingUserCreated;
use App\Events\PendingUserRemoved;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogUserEventTest extends TestCase
{
    use RefreshDatabase;

    public static function userEvents(): array
    {
        return [
            'member became old member' => [MemberBecameOldMember::class, '%s became an old member'],
            'old member became member' => [OldMemberBecameMember::class, '%s became a member again'],
            'pending user approved' => [PendingUserApproved::class, '%s was approved as a member'],
            'pending user created' => [PendingUserCreated::class, 'New pending user: %s'],
        ];
    }

    #[Test]
    #[DataProvider('userEvents')]
    public function a_user_event_is_logged_for_the_user(string $eventClass, string $details)
    {
        $user = User::factory()->create();

        new LogUserEvent()->handle(new $eventClass($user));

        $logEntry = UserEventLogEntry::sole();
        $this->assertTrue($user->is($logEntry->user));
        $this->assertSame(class_basename($eventClass), $logEntry->event_type);
        $this->assertSame(sprintf($details, $user->getName()), $logEntry->event_details);
    }

    #[Test]
    public function a_removed_pending_user_is_logged_without_a_user()
    {
        $user = User::factory()->create();

        new LogUserEvent()->handle(new PendingUserRemoved($user, $user->getName()));

        $logEntry = UserEventLogEntry::sole();
        $this->assertNull($logEntry->user);
        $this->assertSame('PendingUserRemoved', $logEntry->event_type);
        $this->assertSame($user->getName().' was removed as a pending member', $logEntry->event_details);
    }

    #[Test]
    public function dispatching_the_event_logs_it()
    {
        $user = User::factory()->create();

        PendingUserCreated::dispatch($user);

        $this->assertSame('New pending user: '.$user->getName(), UserEventLogEntry::sole()->event_details);
    }
}
