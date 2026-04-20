<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\OldMemberBecameMember;
use App\Listeners\LogOldMemberBecameMember;
use App\Models\UserEventLogEntry;
use App\Repositories\UserEventLogEntryRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogOldMemberBecameMemberTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_member_becomes_old_member()
    {
        $user = User::factory()->create();
        
        $event = new OldMemberBecameMember($user);
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $listener = new LogOldMemberBecameMember($this->app->make(UserEventLogEntryRepository::class));
        $listener->handle($event);
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();
        
        //assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals((new \ReflectionClass(OldMemberBecameMember::class))->getShortName(),  $logEntry->event_type);
        $this->assertEquals($user->getName() . " became a member again", $logEntry->event_details);
    }
}
