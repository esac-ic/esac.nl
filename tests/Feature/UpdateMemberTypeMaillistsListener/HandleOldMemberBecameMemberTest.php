<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Enums\KindOfMember;
use App\Events\OldMemberBecameMember;
use App\Listeners\UpdateMemberTypeMaillists;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class HandleOldMemberBecameMemberTest extends TestCase
{
    use FakesMailListSettings, RefreshDatabase;

    #[Test]
    #[DataProvider('kindsOfMember')]
    public function an_old_member_is_added_to_the_mail_lists_of_their_kind(KindOfMember $kind)
    {
        $this->fakeMailListSettings();
        $user = User::factory()->create(['kind_of_member' => $kind]);

        $mailman = Mockery::mock(Mailman::class);
        $mailman->shouldNotReceive('removeUserFromSpecifiedMailLists');
        $mailman->shouldReceive('addUserToSpecifiedMailLists')
            ->with($user->email, $user->getName(), [$this->mailListOf($kind)])
            ->once();

        new UpdateMemberTypeMaillists($mailman)->handleOldMemberBecameMember(new OldMemberBecameMember($user));
    }
}
