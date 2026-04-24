<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\PendingUserRemoved;
use App\Listeners\LogPendingUserRemoved;
use App\Models\UserEventLogEntry;
use App\Repositories\UserEventLogEntryRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogPendingUserRemovedTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_pending_user_removed()
    {
        $user = User::factory()->create();
        
        $event = new PendingUserRemoved($user, $user->getName());
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $listener = new LogPendingUserRemoved($this->app->make(UserEventLogEntryRepository::class));
        $listener->handle($event);
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();
        
        //assert correct event format
        $this->assertNull($logEntry->user);
        $this->assertEquals((new \ReflectionClass(PendingUserRemoved::class))->getShortName(),  $logEntry->event_type);
        $this->assertEquals($user->getName() . " was removed as a pending member", $logEntry->event_details);
    }
}
