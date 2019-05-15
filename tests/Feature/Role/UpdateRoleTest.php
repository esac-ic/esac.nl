<?php

namespace Tests\Feature;

use App\Rol;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
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

    protected function setUp()
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $role = factory(Rol::class)->create([
            'id' => 1
        ]);

        $user->roles()->attach($role->id);
        $this->be($user);

        session()->start();
    }

    protected function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function an_admin_can_update_a_role(){
        $role = factory(Rol::class)->create();
        $body = [
            'NL_text' => 'test role',
            'EN_text' => 'test role',
            '_token' => csrf_token()
        ];

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(302);

        $role = $role->refresh();

        $this->assertEquals($body['NL_text'], $role->text->text());
    }

    /** @test */
    public function a_role_can_not_update_a_role(){
        $role = factory(Rol::class)->create();
        $body = [
            'NL_text' => 'test role',
            'EN_text' => 'test role',
            '_token' => csrf_token()
        ];

        $this->user->roles()->detach();

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(403);
    }

    /** @test */
    public function a_role_can_not_be_updated_without_required_fields(){
        $role = factory(Rol::class)->create();
        $body = [
            '_token' => csrf_token()
        ];

        $response = $this->patch($this->url . "/" . $role->id, $body);

        $response->assertStatus(302);

        $errors = session('errors');
        $this->count(2,$errors);

        $this->assertEquals("Veld n l text moet ingevuld zijn", $errors->get('NL_text')[0]);
        $this->assertEquals("Veld e n text moet ingevuld zijn", $errors->get('EN_text')[0]);
    }
}
