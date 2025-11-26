<?php

namespace Tests\Feature\EventLogListeners;

use App\Enums\UserEventTypes;
use App\Events\PendingUserApproved;
use App\Listeners\LogPendingUserApproved;
use App\Models\UserEventLogEntry;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogPendingUserApprovedTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_pending_user_approved()
    {
        $user = User::factory()->create();
        
        $event = new PendingUserApproved($user);
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $listener = new LogPendingUserApproved();
        $listener->handle($event);
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();
        
        //assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(UserEventTypes::PendingUserApproved->value,  $logEntry->eventType);
        $this->assertEquals($user->getName() . " was approved as a member", $logEntry->eventDetails);
    }
}
