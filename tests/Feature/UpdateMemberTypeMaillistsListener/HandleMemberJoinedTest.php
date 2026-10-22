<?php

namespace Tests\Feature\UpdateMemberTypeMaillistsListener;

use App\Enums\KindOfMember;
use App\Events\OldMemberBecameMember;
use App\Events\PendingUserApproved;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class HandleMemberJoinedTest extends TestCase
{
    use FakesMailListSettings, RefreshDatabase;

    /**
     * An approved pending member and an old member who comes back, for every kind of member.
     */
    public static function joinedMembers(): array
    {
        return collect(['approved' => PendingUserApproved::class, 'back from old' => OldMemberBecameMember::class])
            ->crossJoin(KindOfMember::cases())
            ->mapWithKeys(fn (array $case) => [class_basename($case[0]).", {$case[1]->value}" => $case])
            ->all();
    }

    #[Test]
    #[DataProvider('joinedMembers')]
    public function a_joined_member_is_added_to_the_mail_lists_of_their_kind(string $event, KindOfMember $kind)
    {
        $this->fakeMailListSettings();
        $user = User::factory()->create(['kind_of_member' => $kind]);

        $this->mock(Mailman::class, function (MockInterface $mock) use ($user, $kind) {
            $mock->shouldNotReceive('removeUserFromSpecifiedMailLists');
            $mock->shouldReceive('addUserToSpecifiedMailLists')->with($user->email, $user->getName(), [$this->mailListOf($kind)])->once();
        });

        event(new $event($user));
    }
}
