<?php

namespace Tests\Feature\Role;

use App\Enums\Role;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use RefreshDatabase;

    private Rol $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = Rol::factory()->create();
        $this->actingAs(User::factory()->withRole(Role::Administrator)->create());
    }

    #[Test]
    public function an_admin_can_update_a_role()
    {
        $this->patch('rols/'.$this->role->id, ['name' => 'test role'])->assertRedirect();

        $this->assertSame('test role', $this->role->refresh()->name);
    }

    #[Test]
    public function a_role_can_not_update_a_role()
    {
        $this->actingAs(User::factory()->create());

        $this->patch('rols/'.$this->role->id, ['name' => 'test role'])->assertForbidden();
    }

    #[Test]
    public function a_role_can_not_be_updated_without_required_fields()
    {
        $this->patch('rols/'.$this->role->id)
            ->assertRedirect()
            ->assertInvalid(['name' => 'Field name is required'])
            ->assertOnlyInvalid(['name']);
    }
}
