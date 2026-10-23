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

    private const array ABILITIES = ['access-admin', 'view-members', 'view-member-details', 'manage-registrations', 'manage-pages'];

    /**
     * A user, and whether they have each of the abilities above.
     */
    public static function membersByRole(): array
    {
        return [
            'administrator' => [AdminRole::Administrator, true, [true, true, true, true, true]],
            'certificate administrator' => [AdminRole::CertificateAdministrator, true, [true, true, false, false, false]],
            'content administrator' => [AdminRole::ContentAdministrator, true, [true, false, false, false, true]],
            'event administrator' => [AdminRole::EventAdministrator, true, [true, false, false, true, false]],
            'member' => [null, true, [false, false, false, false, false]],
            'guest' => [null, false, [false, false, false, false, false]],
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

        $this->assertSame(
            array_combine(self::ABILITIES, $expected),
            array_combine(self::ABILITIES, array_map(fn ($ability) => Gate::forUser($user)->allows($ability), self::ABILITIES)),
        );
    }

    #[Test]
    public function a_certificate_administrator_sees_a_members_certificates_but_not_their_personal_details()
    {
        $member = User::factory()->create(['iban' => 'NL91ABNA0417164300']);

        $this->actingAs(User::factory()->withRole(AdminRole::CertificateAdministrator)->create())
            ->get(route('admin.members.show', $member))
            ->assertOk()
            ->assertSee('Certificates')
            ->assertDontSee('NL91ABNA0417164300')
            ->assertDontSee('Registrations');
    }

    #[Test]
    public function a_member_without_an_admin_role_can_not_open_the_admin_pages()
    {
        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
    }
}
