<?php

namespace Tests\Feature\User;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
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

        $admin->roles()->attach(Config::get('constants.Administrator'));
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
    public function make_user_active_test()
    {
        $response = $this->patch('users/' . $this->user->id . '/makeActiveMember');

        $response->assertStatus(302);

        $this->assertNotNull($this->user->lid_af);
    }
}
