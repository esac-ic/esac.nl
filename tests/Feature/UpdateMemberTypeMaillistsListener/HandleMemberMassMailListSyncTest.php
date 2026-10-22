<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Enums\KindOfMember;
use App\Jobs\MemberMassMailListSyncJob;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class HandleMemberMassMailListSyncTest extends TestCase
{
    use FakesMailListSettings, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeMailListSettings();
    }

    #[Test]
    public function a_member_is_added_to_the_member_mail_lists()
    {
        $user = User::factory()->member()->create();

        $mailman = $this->mock(Mailman::class);
        $mailman->shouldNotReceive('removeUserFromSpecifiedMailLists');
        $mailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($user->email, $user->getName(), ['member.esac.nl'])
            ->once();

        new MemberMassMailListSyncJob()->handle($mailman);
    }

    #[Test]
    public function every_kind_of_member_is_added_to_their_own_mail_lists()
    {
        foreach (KindOfMember::cases() as $kind) {
            User::factory()->create(['kind_of_member' => $kind]);
        }

        $mailman = $this->mock(Mailman::class);
        $mailman->shouldNotReceive('removeUserFromSpecifiedMailLists');
        foreach (User::all() as $user) {
            $mailman->shouldReceive('addUserToSpecifiedMailLists')
                ->with($user->email, $user->getName(), [$this->mailListOf($user->kind_of_member)])
                ->once();
        }

        new MemberMassMailListSyncJob()->handle($mailman);
    }
}
