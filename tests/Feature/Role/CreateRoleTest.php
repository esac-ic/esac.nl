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

    private $url = 'rols';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::Administrator->value);
        $this->be($user);
    }

    #[Test]
    public function an_admin_can_create_a_role()
    {
        $body = [
            'name' => 'test role',
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);

        $role = Rol::all()->last();

        $this->assertEquals($body['name'], $role->name);
    }

    #[Test]
    public function a_role_can_not_create_a_role()
    {
        $body = [
            'name' => 'test role',
        ];

        $this->user->roles()->detach();

        $response = $this->post($this->url, $body);

        $response->assertStatus(403);
    }

    #[Test]
    public function a_role_can_not_be_created_without_required_fields()
    {
        $body = [
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);

        $response->assertSessionHasErrors();
        $errors = session('errors');

        $this->assertCount(1, $errors);
        $this->assertEquals('Field name is required', $errors->get('name')[0]);
    }
}
