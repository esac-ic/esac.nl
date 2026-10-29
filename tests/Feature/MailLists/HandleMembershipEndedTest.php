<?php

namespace Tests\Feature\MailLists;

use App\Events\MembershipEnded;
use App\Listeners\SyncMemberMailLists;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HandleMembershipEndedTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_former_member_is_removed_from_all_mail_lists()
    {
        $user = User::factory()->create();
        $mailman = $this->mock(Mailman::class);
        $mailman->shouldReceive('removeFromAllMailLists')->with($user->email)->once();

        new SyncMemberMailLists($mailman)->handleMembershipEnded(new MembershipEnded($user));
    }

    #[Test]
    public function the_listener_is_queued_for_the_event()
    {
        Event::fake();

        Event::assertListening(MembershipEnded::class, [SyncMemberMailLists::class, 'handleMembershipEnded']);
    }
}
