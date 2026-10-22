<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Enums\KindOfMember;
use App\Enums\SettingKey;
use App\Events\MemberKindChanged;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\Setting;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
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

        $this->mockedMailman = Mockery::mock(Mailman::class);
        $this->user = User::factory()->member()->create();
        $this->fakeMailListSettings();
    }

    #[Test]
    public function handles_multiple_mail_lists_correctly()
    {
        Setting::where('name', SettingKey::NormalMemberMailLists->value)->first()
            ->update(['value' => 'first-member;second-member;third-member;fourth-member']);
        Setting::where('name', SettingKey::ReunistMemberMailLists->value)->first()
            ->update(['value' => 'first-reunist;second-reunist;third-reunist;fourth-reunist']);

        // Removing before adding keeps the member on lists that both kinds share.
        $this->mockedMailman->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, ['first-member', 'second-member', 'third-member', 'fourth-member'])
            ->once()
            ->ordered();
        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), ['first-reunist', 'second-reunist', 'third-reunist', 'fourth-reunist'])
            ->once()
            ->ordered();

        new UpdateMemberTypeMaillists($this->mockedMailman)
            ->handleMemberKindChanged(new MemberKindChanged($this->user, KindOfMember::Member, KindOfMember::Reunist));
    }

    #[Test]
    #[DataProvider('kindChanges')]
    public function mail_lists_are_updated_when_the_kind_of_member_changes(KindOfMember $from, KindOfMember $to)
    {
        $this->mockedMailman->shouldReceive('removeUserFromSpecifiedMailLists')
            ->with($this->user->email, [$this->mailListOf($from)])
            ->once()
            ->ordered();
        $this->mockedMailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($this->user->email, $this->user->getName(), [$this->mailListOf($to)])
            ->once()
            ->ordered();

        $this->user->update(['kind_of_member' => $from]);

        new UpdateMemberTypeMaillists($this->mockedMailman)
            ->handleMemberKindChanged(new MemberKindChanged($this->user, $from, $to));
    }
}
