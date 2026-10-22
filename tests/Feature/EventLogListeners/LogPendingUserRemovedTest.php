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

        $this->assertEquals(0, UserEventLogEntry::all()->count());

        $listener = new LogUserEvent;
        $listener->handle($event);

        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();

        // assert correct event format
        $this->assertNull($logEntry->user);
        $this->assertEquals((new \ReflectionClass(PendingUserRemoved::class))->getShortName(), $logEntry->event_type);
        $this->assertEquals($user->getName().' was removed as a pending member', $logEntry->event_details);
    }
}
