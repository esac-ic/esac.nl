<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\PendingUserCreated;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogPendingUserCreatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_pending_member()
    {
        $user = User::factory()->create();

        $event = new PendingUserCreated($user);

        $this->assertEquals(0, UserEventLogEntry::all()->count());

        $listener = new LogUserEvent;
        $listener->handle($event);

        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();

        // assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals((new \ReflectionClass(PendingUserCreated::class))->getShortName(), $logEntry->event_type);
        $this->assertEquals('New pending user: '.$user->getName(), $logEntry->event_details);
    }

    public function test_dispatching_the_event_logs_it()
    {
        $user = User::factory()->create();

        PendingUserCreated::dispatch($user);

        $this->assertEquals('New pending user: '.$user->getName(), UserEventLogEntry::sole()->event_details);
    }
}
