<?php

namespace Tests\Feature\EventLogListeners;

use App\Enums\UserEventTypes;
use App\Events\PendingUserCreated;
use App\Listeners\LogPendingUserCreated;
use App\Models\UserEventLogEntry;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogNewPendingUserTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_new_pending_member()
    {
        $user = User::factory()->create();
        
        $event = new PendingUserCreated($user);
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $listener = new LogPendingUserCreated();
        $listener->handle($event);
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();
        
        //assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(UserEventTypes::NewPendingUser->value,  $logEntry->eventType);
        $this->assertEquals("New pending user: " . $user->getName(), $logEntry->eventDetails);
    }
}
