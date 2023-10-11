<?php

namespace Tests\Feature\Role;

use App\Rol;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

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
        $this->user = $user = factory(User::class)->create();

        $user->roles()->attach(Config::get('constants.Administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function an_admin_can_update_a_role()
    {
        $role = factory(Rol::class)->create();
        $body = [
            'name' => 'test role',
            '_token' => csrf_token(),
        ];

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(302);

        $role = $role->refresh();

        $this->assertEquals($body['name'], $role->name);
    }

    /** @test */
    public function a_role_can_not_update_a_role()
    {
        $role = factory(Rol::class)->create();
        $body = [
            'name' => 'test role',
            '_token' => csrf_token(),
        ];

        $this->user->roles()->detach();

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(403);
    }

    /** @test */
    public function a_role_can_not_be_updated_without_required_fields()
    {
        $role = factory(Rol::class)->create();
        $body = [
            '_token' => csrf_token(),
        ];

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertCount(1, $errors);
        $this->assertEquals("Field name is required", $errors->get('name')[0]);
    }
}
