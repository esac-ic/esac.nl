<?php

namespace Tests\Feature\MemberLog;

use App\Enums\MemberLogType;
use App\Enums\MemberType;
use App\Events\MemberTypeChanged;
use App\Listeners\WriteMemberLogEntry;
use App\Models\MemberLogEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailListSettings;
use Tests\TestCase;

class LogMemberTypeChangedTest extends TestCase
{
    use FakesMailListSettings, RefreshDatabase;

    #[Test]
    #[DataProvider('memberTypeChanges')]
    public function a_member_type_change_is_logged(MemberType $from, MemberType $to)
    {
        $user = User::factory()->create(['member_type' => $from]);

        new WriteMemberLogEntry()->handle(new MemberTypeChanged($user, $from, $to));

        $logEntry = MemberLogEntry::sole();
        $this->assertTrue($user->is($logEntry->user));
        $this->assertSame(MemberLogType::MemberTypeChanged, $logEntry->type);
        $this->assertSame($user->name." changed from {$from->value} to {$to->value}", $logEntry->details);
    }
}
