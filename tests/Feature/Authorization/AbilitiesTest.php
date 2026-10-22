<?php

namespace Tests\Feature\Authorization;

use App\Enums\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AbilitiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user, and whether they may access-admin, manage-members, manage-certificates and manage-pages.
     */
    public static function membersByRole(): array
    {
        return [
            'administrator' => [AdminRole::Administrator, true, [true, true, true, false]],
            'certificate administrator' => [AdminRole::CertificateAdministrator, true, [true, false, true, false]],
            'member' => [null, true, [false, false, false, false]],
            'guest' => [null, false, [false, false, false, false]],
        ];
    }

    #[Test]
    #[DataProvider('membersByRole')]
    public function a_member_has_the_abilities_of_their_role(?AdminRole $role, bool $loggedIn, array $expected)
    {
        $user = $loggedIn ? User::factory()->create() : null;
        if ($role !== null) {
            $user->roles()->attach($role->value);
        }

        $abilities = ['access-admin', 'manage-members', 'manage-certificates', 'manage-pages'];
        $this->assertSame(
            array_combine($abilities, $expected),
            array_combine($abilities, array_map(fn ($ability) => Gate::forUser($user)->allows($ability), $abilities)),
        );
    }
}
