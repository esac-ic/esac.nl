<?php

namespace Tests\Feature\EventLogListeners;

use App\Enums\UserEventTypes;
use App\Events\MemberBecameOldMember;
use App\Listeners\LogMemberBecameOldMember;
use App\Models\UserEventLogEntry;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogMemberBecameOldMemberTest extends \TestCase
{
    use RefreshDatabase;
    
    public function test_member_becomes_old_member()
    {
        $user = User::factory()->create();
        
        $event = new MemberBecameOldMember($user);
        
        $this->assertEquals(0, UserEventLogEntry::all()->count());
        
        $listener = new LogMemberBecameOldMember();
        $listener->handle($event);
        
        $this->assertEquals(1, UserEventLogEntry::all()->count());
        $logEntry = UserEventLogEntry::all()->first();
        
        //assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(UserEventTypes::MemberBecameOldMember->value,  $logEntry->eventType);
        $this->assertEquals($user->getName() . " became an old member", $logEntry->eventDetails);
    }
}
