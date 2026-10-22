<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\PendingUserRemoved;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogPendingUserRemovedTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_user_removed()
    {
        $user = User::factory()->create();

        $event = new PendingUserRemoved($user, $user->getName());

        $this->assertDatabaseEmpty(UserEventLogEntry::class);

        $listener = new LogUserEvent;
        $listener->handle($event);

        $logEntry = UserEventLogEntry::sole();

        // assert correct event format
        $this->assertNull($logEntry->user);
        $this->assertEquals(class_basename(PendingUserRemoved::class), $logEntry->event_type);
        $this->assertEquals($user->getName().' was removed as a pending member', $logEntry->event_details);
    }
}
