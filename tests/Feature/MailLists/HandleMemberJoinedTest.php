<?php

namespace Tests\Feature\MailLists;

use App\Enums\MemberType;
use App\Events\MembershipRestored;
use App\Events\SignUpApproved;
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
     * An approved pending member and a former member who comes back, for every member type.
     */
    public static function joinedMembers(): array
    {
        return collect(['approved' => SignUpApproved::class, 'back from old' => MembershipRestored::class])
            ->crossJoin(MemberType::cases())
            ->mapWithKeys(fn (array $case) => [class_basename($case[0]).", {$case[1]->value}" => $case])
            ->all();
    }

    #[Test]
    #[DataProvider('joinedMembers')]
    public function a_joined_member_is_added_to_the_mail_lists_of_their_member_type(string $event, MemberType $type)
    {
        $this->fakeMailListSettings();
        $user = User::factory()->create(['member_type' => $type]);

        $this->mock(Mailman::class, function (MockInterface $mock) use ($user, $type) {
            $mock->shouldNotReceive('removeFromMailLists');
            $mock->shouldReceive('addToMailLists')->with($user->email, $user->name, [$this->mailListOf($type)])->once();
        });

        event(new $event($user));
    }
}
