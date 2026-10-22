<?php

namespace Tests\Feature\Roles;

use App\Enums\AdminRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->withRole(AdminRole::Administrator)->create());
    }

    #[Test]
    public function an_admin_can_create_a_role()
    {
        $this->post('admin/roles', ['name' => 'test role'])->assertRedirect();

        $this->assertDatabaseHas(Role::class, ['name' => 'test role']);
    }

    #[Test]
    public function a_member_without_the_administrator_role_can_not_create_a_role()
    {
        $this->actingAs(User::factory()->create());

        $this->post('admin/roles', ['name' => 'test role'])->assertForbidden();
    }

    #[Test]
    public function a_role_can_not_be_created_without_required_fields()
    {
        $this->post('admin/roles')
            ->assertRedirect()
            ->assertInvalid(['name' => 'Field name is required'])
            ->assertOnlyInvalid(['name']);
    }
}
