<?php

namespace Tests\Feature\EventLogListeners;

use App\Events\MemberKindChanged;
use App\Listeners\LogUserEvent;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class LogMemberKindChangedTest extends TestCase
{
    use FakesMailListSettings, RefreshDatabase;

    #[Test]
    #[DataProvider('kindChanges')]
    public function a_kind_of_member_change_is_logged(string $from, string $to)
    {
        $user = User::factory()->create(['kind_of_member' => $from]);

        new LogUserEvent()->handle(new MemberKindChanged($user, $from, $to));

        $logEntry = UserEventLogEntry::sole();
        $this->assertTrue($user->is($logEntry->user));
        $this->assertSame('MemberKindChanged', $logEntry->event_type);
        $this->assertSame($user->getName()." changed from $from to $to", $logEntry->event_details);
    }
}
