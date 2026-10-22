<?php

namespace Tests\Feature\Role;

use App\Enums\Role;
use App\Models\Rol;
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
        $this->actingAs(User::factory()->withRole(Role::Administrator)->create());
    }

    #[Test]
    public function an_admin_can_create_a_role()
    {
        $this->post('beheer/roles', ['name' => 'test role'])->assertRedirect();

        $this->assertDatabaseHas(Rol::class, ['name' => 'test role']);
    }

    #[Test]
    public function a_role_can_not_create_a_role()
    {
        $this->actingAs(User::factory()->create());

        $this->post('beheer/roles', ['name' => 'test role'])->assertForbidden();
    }

    #[Test]
    public function a_role_can_not_be_created_without_required_fields()
    {
        $this->post('beheer/roles')
            ->assertRedirect()
            ->assertInvalid(['name' => 'Field name is required'])
            ->assertOnlyInvalid(['name']);
    }
}
