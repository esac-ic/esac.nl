<?php

namespace Tests\Feature\MailLists;

use App\Enums\MemberType;
use App\Events\MemberTypeChanged;
use App\Listeners\SyncMemberMailLists;
use App\Models\User;
use App\Services\Mailman;
use App\Settings\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class HandleMemberTypeChangedTest extends TestCase
{
    use FakesMailListSettings, RefreshDatabase;

    private MockInterface $mockedMailman;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedMailman = $this->mock(Mailman::class);
        $this->user = User::factory()->member()->create();
        $this->fakeMailListSettings();
    }

    #[Test]
    public function a_member_moves_between_member_types_with_several_mail_lists()
    {
        SiteSettings::fake(['mail_lists' => [
            MemberType::Member->value => ['first-member', 'second-member', 'third-member', 'fourth-member'],
            MemberType::Reunist->value => ['first-reunist', 'second-reunist', 'third-reunist', 'fourth-reunist'],
        ]]);

        // Removing before adding keeps the member on lists that both kinds share.
        $this->mockedMailman->shouldReceive('removeFromMailLists')
            ->with($this->user->email, ['first-member', 'second-member', 'third-member', 'fourth-member'])
            ->once()
            ->ordered();
        $this->mockedMailman->shouldReceive('addToMailLists')
            ->with($this->user->email, $this->user->name, ['first-reunist', 'second-reunist', 'third-reunist', 'fourth-reunist'])
            ->once()
            ->ordered();

        new SyncMemberMailLists($this->mockedMailman)
            ->handleMemberTypeChanged(new MemberTypeChanged($this->user, MemberType::Member, MemberType::Reunist));
    }

    #[Test]
    #[DataProvider('memberTypeChanges')]
    public function mail_lists_are_updated_when_the_member_type_changes(MemberType $from, MemberType $to)
    {
        $this->mockedMailman->shouldReceive('removeFromMailLists')
            ->with($this->user->email, [$this->mailListOf($from)])
            ->once()
            ->ordered();
        $this->mockedMailman->shouldReceive('addToMailLists')
            ->with($this->user->email, $this->user->name, [$this->mailListOf($to)])
            ->once()
            ->ordered();

        $this->user->update(['member_type' => $from]);

        new SyncMemberMailLists($this->mockedMailman)
            ->handleMemberTypeChanged(new MemberTypeChanged($this->user, $from, $to));
    }
}
