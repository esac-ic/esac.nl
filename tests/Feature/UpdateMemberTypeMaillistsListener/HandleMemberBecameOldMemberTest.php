<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Events\MemberBecameOldMember;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HandleMemberBecameOldMemberTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_old_member_is_removed_from_all_mail_lists()
    {
        $user = User::factory()->create();
        $mailman = Mockery::mock(Mailman::class);
        $mailman->shouldReceive('deleteUserForAllMailList')
            ->with(Mockery::on(fn ($argument) => $user->is($argument)))
            ->once();

        new UpdateMemberTypeMaillists($mailman)->handleMemberBecameOldMember(new MemberBecameOldMember($user));
    }

    #[Test]
    public function the_listener_is_queued_for_the_event()
    {
        Event::fake();

        Event::assertListening(MemberBecameOldMember::class, [UpdateMemberTypeMaillists::class, 'handleMemberBecameOldMember']);
    }
}
