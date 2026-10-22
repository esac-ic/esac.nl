<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\PendingUserApproved;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogPendingUserApprovedTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_user_approved()
    {
        $user = User::factory()->create();

        $event = new PendingUserApproved($user);

        $this->assertDatabaseEmpty(UserEventLogEntry::class);

        $listener = new LogUserEvent;
        $listener->handle($event);

        $logEntry = UserEventLogEntry::sole();

        // assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(class_basename(PendingUserApproved::class), $logEntry->event_type);
        $this->assertEquals($user->getName().' was approved as a member', $logEntry->event_details);
    }
}
