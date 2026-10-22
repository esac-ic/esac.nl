<?php

namespace Tests\Feature\Role;

use App\Enums\Role;
use App\Models\Rol;
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
        $role = Rol::factory()->create();
        $this->actingAs(User::factory()->withRole(Role::Administrator)->create());

        $this->delete('rols/'.$role->id)->assertRedirect();

        $this->assertSoftDeleted($role);
    }

    #[Test]
    public function a_user_can_not_delete_a_role()
    {
        $role = Rol::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->delete('rols/'.$role->id)->assertForbidden();
    }
}
