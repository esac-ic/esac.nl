<?php

namespace Tests\Feature;

use App\User;
use App\Rol;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

class MakeUserActiveTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var
     */
    private $admin, $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $admin = factory(User::class)->create();
        $role = factory(Rol::class)->create([
            'id' => 1
        ]);

        $admin->roles()->attach($role->id);
        $this->be($admin);

        $this->user = $user = factory(User::class)->create();
        $this->user->lid_af = Carbon::Now();
        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }

    /** @test */
    public function make_user_active_test(){
        $response = $this->patch('users/' . $this->user->id . '/makeActiveMember');

        $response->assertStatus(302);
        
        $this->assertNotNull($this->user->lid_af);
    }
}
