<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\MemberBecameOldMember;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogMemberBecameOldMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_becomes_old_member()
    {
        $user = User::factory()->create();

        $event = new MemberBecameOldMember($user);

        $this->assertDatabaseEmpty(UserEventLogEntry::class);

        $listener = new LogUserEvent;
        $listener->handle($event);

        $logEntry = UserEventLogEntry::sole();

        // assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(class_basename(MemberBecameOldMember::class), $logEntry->event_type);
        $this->assertEquals($user->getName().' became an old member', $logEntry->event_details);
    }
}
