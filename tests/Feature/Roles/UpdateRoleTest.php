<?php

namespace Tests\Feature\Roles;

use App\Enums\AdminRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = Role::factory()->create();
        $this->actingAs(User::factory()->withRole(AdminRole::Administrator)->create());
    }

    #[Test]
    public function an_admin_can_update_a_role()
    {
        $this->patch('admin/roles/'.$this->role->id, ['name' => 'test role'])->assertRedirect();

        $this->assertSame('test role', $this->role->refresh()->name);
    }

    #[Test]
    public function a_member_without_the_administrator_role_can_not_update_a_role()
    {
        $this->actingAs(User::factory()->create());

        $this->patch('admin/roles/'.$this->role->id, ['name' => 'test role'])->assertForbidden();
    }

    #[Test]
    public function a_role_can_not_be_updated_without_required_fields()
    {
        $this->patch('admin/roles/'.$this->role->id)
            ->assertRedirect()
            ->assertInvalid(['name' => 'Field name is required'])
            ->assertOnlyInvalid(['name']);
    }
}
