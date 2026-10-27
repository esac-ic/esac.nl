<?php

namespace Tests\Feature\Events;

use App\Events\MemberBecameOldMember;
use App\Events\MemberKindChanged;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Events\PendingUserCreated;
use App\Events\PendingUserRemoved;
use App\Listeners\LogMemberBecameOldMember;
use App\Listeners\LogMemberKindChanged;
use App\Listeners\LogOldMemberBecameMember;
use App\Listeners\LogPendingUserApproved;
use App\Listeners\LogPendingUserCreated;
use App\Listeners\LogPendingUserRemoved;
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
            'PendingUserCreated' => [PendingUserCreated::class, [LogPendingUserCreated::class]],
            'PendingUserRemoved' => [PendingUserRemoved::class, [LogPendingUserRemoved::class]],
            'PendingUserApproved' => [PendingUserApproved::class, [LogPendingUserApproved::class, UpdateMemberTypeMaillists::class.'@handlePendingUserApproved']],
            'MemberBecameOldMember' => [MemberBecameOldMember::class, [LogMemberBecameOldMember::class, UpdateMemberTypeMaillists::class.'@handleMemberBecameOldMember']],
            'OldMemberBecameMember' => [OldMemberBecameMember::class, [LogOldMemberBecameMember::class, UpdateMemberTypeMaillists::class.'@handleOldMemberBecameMember']],
            'MemberKindChanged' => [MemberKindChanged::class, [LogMemberKindChanged::class, UpdateMemberTypeMaillists::class.'@handleMemberKindChanged']],
        ];
    }

    #[Test]
    #[DataProvider('listeners')]
    public function the_event_has_each_listener_once(string $event, array $listeners)
    {
        Event::fake();

        foreach ($listeners as $listener) {
            Event::assertListening($event, $listener);
        }
        $this->assertCount(count($listeners), Event::getRawListeners()[$event]);
    }
}
