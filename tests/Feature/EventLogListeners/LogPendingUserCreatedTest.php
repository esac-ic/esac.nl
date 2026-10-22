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

        $this->assertDatabaseEmpty(UserEventLogEntry::class);

        $listener = new LogUserEvent;
        $listener->handle($event);

        $logEntry = UserEventLogEntry::sole();

        // assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(class_basename(PendingUserCreated::class), $logEntry->event_type);
        $this->assertEquals('New pending user: '.$user->getName(), $logEntry->event_details);
    }

    public function test_dispatching_the_event_logs_it()
    {
        $user = User::factory()->create();

        PendingUserCreated::dispatch($user);

        $this->assertEquals('New pending user: '.$user->getName(), UserEventLogEntry::sole()->event_details);
    }
}
