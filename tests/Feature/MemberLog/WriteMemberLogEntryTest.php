<?php

namespace Tests\Feature\MemberLog;

use App\Enums\MemberLogType;
use App\Events\MembershipEnded;
use App\Events\MembershipRestored;
use App\Events\MemberSignedUp;
use App\Events\SignUpApproved;
use App\Events\SignUpRejected;
use App\Listeners\WriteMemberLogEntry;
use App\Models\MemberLogEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WriteMemberLogEntryTest extends TestCase
{
    use RefreshDatabase;

    public static function loggedEvents(): array
    {
        return [
            'membership ended' => [MembershipEnded::class, '%s became a former member'],
            'membership restored' => [MembershipRestored::class, '%s became a member again'],
            'sign-up approved' => [SignUpApproved::class, '%s was approved as a member'],
            'member signed up' => [MemberSignedUp::class, 'New pending member: %s'],
        ];
    }

    #[Test]
    #[DataProvider('loggedEvents')]
    public function the_event_is_logged_for_the_member(string $eventClass, string $details)
    {
        $user = User::factory()->create();

        new WriteMemberLogEntry()->handle(new $eventClass($user));

        $logEntry = MemberLogEntry::sole();
        $this->assertTrue($user->is($logEntry->user));
        $this->assertSame(new $eventClass($user)->logType(), $logEntry->type);
        $this->assertSame(sprintf($details, $user->name), $logEntry->details);
    }

    #[Test]
    public function a_rejected_sign_up_is_logged_without_a_member()
    {
        $user = User::factory()->create();

        new WriteMemberLogEntry()->handle(new SignUpRejected($user, $user->name));

        $logEntry = MemberLogEntry::sole();
        $this->assertNull($logEntry->user);
        $this->assertSame(MemberLogType::SignUpRejected, $logEntry->type);
        $this->assertSame($user->name.' was removed as a pending member', $logEntry->details);
    }

    #[Test]
    public function dispatching_the_event_logs_it()
    {
        $user = User::factory()->create();

        MemberSignedUp::dispatch($user);

        $this->assertSame('New pending member: '.$user->name, MemberLogEntry::sole()->details);
    }
}
