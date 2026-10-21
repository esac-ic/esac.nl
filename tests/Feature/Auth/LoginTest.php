<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_login_page_is_shown()
    {
        $this->get('/login')->assertOk()->assertSee('Welcome back');
    }

    #[Test]
    public function a_member_logs_in_and_is_remembered()
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret-password',
            'remember' => '1',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
        $response->assertCookie(Auth::guard()->getRecallerName());
        $this->assertNotNull($user->fresh()->remember_token);
    }

    #[Test]
    public function a_member_is_not_remembered_without_the_checkbox()
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);

        $response = $this->post('/login', ['email' => $user->email, 'password' => 'secret-password']);

        $response->assertRedirect('/home');
        $response->assertCookieMissing(Auth::guard()->getRecallerName());
    }

    #[Test]
    public function a_wrong_password_is_rejected()
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[Test]
    public function an_old_member_can_not_log_in()
    {
        $user = User::factory()->lidAf()->create(['password' => Hash::make('secret-password')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertSessionHasErrors(['email' => 'Old members can not login']);
        $this->assertGuest();
    }

    #[Test]
    public function a_pending_member_can_not_log_in()
    {
        $user = User::factory()->pending()->create(['password' => Hash::make('secret-password')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-password'])
            ->assertSessionHasErrors(['email' => 'Pending members can not login']);
        $this->assertGuest();
    }

    #[Test]
    public function an_old_member_with_a_wrong_password_gets_the_generic_error()
    {
        $user = User::factory()->lidAf()->create(['password' => Hash::make('secret-password')]);

        $response = $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);

        $response->assertSessionHasErrors('email');
        $this->assertNotSame('Old members can not login', session('errors')->first('email'));
    }

    #[Test]
    public function logins_are_throttled()
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password')]);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-password'])->assertTooManyRequests();
        $this->assertGuest();
    }

    #[Test]
    public function a_member_logs_out()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    #[Test]
    public function an_outdated_password_hash_is_rehashed_on_login()
    {
        $user = User::factory()->create(['password' => Hash::make('secret-password', ['rounds' => 4])]);
        Hash::driver('bcrypt')->setRounds(5);

        $this->post('/login', ['email' => $user->email, 'password' => 'secret-password'])->assertRedirect('/home');

        $this->assertSame(5, password_get_info($user->fresh()->password)['options']['cost']);
    }
}
