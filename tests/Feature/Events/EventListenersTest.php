<?php

namespace Tests\Feature\Events;

use App\Events\LogsToMemberLog;
use App\Events\MembershipEnded;
use App\Events\MembershipRestored;
use App\Events\MemberSignedUp;
use App\Events\MemberTypeChanged;
use App\Events\SignUpApproved;
use App\Events\SignUpRejected;
use App\Listeners\SyncMemberMailLists;
use App\Listeners\WriteMemberLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The listeners are found by event discovery, so a renamed handler silently stops listening.
 */
class EventListenersTest extends TestCase
{
    use RefreshDatabase;

    public static function listeners(): array
    {
        return [
            'MemberSignedUp' => [MemberSignedUp::class, []],
            'SignUpRejected' => [SignUpRejected::class, []],
            'SignUpApproved' => [SignUpApproved::class, [SyncMemberMailLists::class.'@handleSignUpApproved']],
            'MembershipEnded' => [MembershipEnded::class, [SyncMemberMailLists::class.'@handleMembershipEnded']],
            'MembershipRestored' => [MembershipRestored::class, [SyncMemberMailLists::class.'@handleMembershipRestored']],
            'MemberTypeChanged' => [MemberTypeChanged::class, [SyncMemberMailLists::class.'@handleMemberTypeChanged']],
        ];
    }

    #[Test]
    #[DataProvider('listeners')]
    public function the_event_is_logged_and_has_each_listener_once(string $event, array $listeners)
    {
        Event::fake();

        $this->assertContains(LogsToMemberLog::class, class_implements($event));
        foreach ($listeners as $listener) {
            Event::assertListening($event, $listener);
        }
        $this->assertCount(count($listeners), Event::getRawListeners()[$event] ?? []);
    }

    #[Test]
    public function the_member_log_listens_once_to_every_logged_event()
    {
        Event::fake();

        Event::assertListening(LogsToMemberLog::class, WriteMemberLogEntry::class);
        $this->assertCount(1, Event::getRawListeners()[LogsToMemberLog::class]);
    }
}
