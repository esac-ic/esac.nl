<?php

namespace Tests\Feature\Role;

use App\Rol;
use App\User;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

class CreateRoleTest extends TestCase
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
    public function an_admin_can_create_a_role(){
        $body = [
            'NL_text' => 'test role',
            'EN_text' => 'test role',
            '_token' => csrf_token()
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);

        $role = Rol::all()->last();

        $this->assertEquals($body['NL_text'], $role->text->text());
    }

    /** @test */
    public function a_role_can_not_create_a_role(){
        $body = [
            'NL_text' => 'test role',
            'EN_text' => 'test role',
            '_token' => csrf_token()
        ];

        $this->user->roles()->detach();

        $response = $this->post($this->url, $body);

        $response->assertStatus(403);
    }

    /** @test */
    public function a_role_can_not_be_created_without_required_fields(){
        $body = [
            '_token' => csrf_token()
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);

        $errors = session('errors');
        $this->count(2,$errors);

        $this->assertEquals("Veld n l text moet ingevuld zijn", $errors->get('NL_text')[0]);
        $this->assertEquals("Veld e n text moet ingevuld zijn", $errors->get('EN_text')[0]);
    }
}
