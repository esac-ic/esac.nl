<?php

namespace Tests\Feature\Role;

use App\Enums\AdminRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_role_can_be_deleted_by_an_admin()
    {
        $role = Role::factory()->create();
        $this->actingAs(User::factory()->withRole(AdminRole::Administrator)->create());

        $this->delete('admin/roles/'.$role->id)->assertRedirect();

        $this->assertSoftDeleted($role);
    }

    #[Test]
    public function a_user_can_not_delete_a_role()
    {
        $role = Role::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->delete('admin/roles/'.$role->id)->assertForbidden();
    }
}
