<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\OldMemberBecameMember;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogOldMemberBecameMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_becomes_old_member()
    {
        $user = User::factory()->create();

        $event = new OldMemberBecameMember($user);

        $this->assertDatabaseEmpty(UserEventLogEntry::class);

        $listener = new LogUserEvent;
        $listener->handle($event);

        $logEntry = UserEventLogEntry::sole();

        // assert correct event format
        $this->assertTrue($user->is($logEntry->user));
        $this->assertEquals(class_basename(OldMemberBecameMember::class), $logEntry->event_type);
        $this->assertEquals($user->getName().' became a member again', $logEntry->event_details);
    }
}
