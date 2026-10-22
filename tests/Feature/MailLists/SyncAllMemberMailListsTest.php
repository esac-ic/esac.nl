<?php

namespace Tests\Feature\MailLists;

use App\Enums\MemberType;
use App\Jobs\SyncAllMemberMailLists;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class SyncAllMemberMailListsTest extends TestCase
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
        $mailman->shouldNotReceive('removeFromMailLists');
        $mailman->shouldReceive('addToMailLists')
            ->with($user->email, $user->name, ['member.esac.nl'])
            ->once();

        new SyncAllMemberMailLists()->handle($mailman);
    }

    #[Test]
    public function every_member_type_is_added_to_their_own_mail_lists()
    {
        foreach (MemberType::cases() as $type) {
            User::factory()->create(['member_type' => $type]);
        }

        $mailman = $this->mock(Mailman::class);
        $mailman->shouldNotReceive('removeFromMailLists');
        foreach (User::all() as $user) {
            $mailman->shouldReceive('addToMailLists')
                ->with($user->email, $user->name, [$this->mailListOf($user->member_type)])
                ->once();
        }

        new SyncAllMemberMailLists()->handle($mailman);
    }
}
