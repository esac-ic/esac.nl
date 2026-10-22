<?php

namespace Tests\Feature\Events;

use App\Events\LoggableUserEventInterface;
use App\Events\MemberBecameOldMember;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Events\PendingUserCreated;
use App\Events\PendingUserRemoved;
use App\Listeners\LogUserEvent;
use App\Listeners\UpdateMemberTypeMaillists;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'PendingUserCreated' => [PendingUserCreated::class, []],
            'PendingUserRemoved' => [PendingUserRemoved::class, []],
            'PendingUserApproved' => [PendingUserApproved::class, [UpdateMemberTypeMaillists::class.'@handlePendingUserApproved']],
            'MemberBecameOldMember' => [MemberBecameOldMember::class, [UpdateMemberTypeMaillists::class.'@handleMemberBecameOldMember']],
            'OldMemberBecameMember' => [OldMemberBecameMember::class, [UpdateMemberTypeMaillists::class.'@handleOldMemberBecameMember']],
            'MemberKindChanged' => [MemberKindChanged::class, [UpdateMemberTypeMaillists::class.'@handleMemberKindChanged']],
        ];
    }

    #[Test]
    #[DataProvider('listeners')]
    public function the_event_is_logged_and_has_each_listener_once(string $event, array $listeners)
    {
        Event::fake();

        $this->assertContains(LoggableUserEventInterface::class, class_implements($event));
        foreach ($listeners as $listener) {
            Event::assertListening($event, $listener);
        }
        $this->assertCount(count($listeners), Event::getRawListeners()[$event] ?? []);
    }

    #[Test]
    public function the_user_event_log_listens_once_to_every_loggable_event()
    {
        Event::fake();

        Event::assertListening(LoggableUserEventInterface::class, LogUserEvent::class);
        $this->assertCount(1, Event::getRawListeners()[LoggableUserEventInterface::class]);
    }
}
