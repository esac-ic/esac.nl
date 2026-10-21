<?php

namespace Tests\Feature\Role;

use App\Enums\Role;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = 'rols';

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::Administrator->value);
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    #[Test]
    public function an_admin_can_update_a_role()
    {
        $role = Rol::factory()->create();
        $body = [
            'name' => 'test role',
            '_token' => csrf_token(),
        ];

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(302);

        $role = $role->refresh();

        $this->assertEquals($body['name'], $role->name);
    }

    #[Test]
    public function a_role_can_not_update_a_role()
    {
        $role = Rol::factory()->create();
        $body = [
            'name' => 'test role',
            '_token' => csrf_token(),
        ];

        $this->user->roles()->detach();

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(403);
    }

    #[Test]
    public function a_role_can_not_be_updated_without_required_fields()
    {
        $role = Rol::factory()->create();
        $body = [
            '_token' => csrf_token(),
        ];

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(302);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertCount(1, $errors);
        $this->assertEquals("Field name is required", $errors->get('name')[0]);
    }
}
