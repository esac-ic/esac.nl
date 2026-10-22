<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private function login(User $user, string $password = 'password', array $extra = []): TestResponse
    {
        return $this->post('/login', ['email' => $user->email, 'password' => $password, ...$extra]);
    }

    public static function blockedMembers(): array
    {
        return [
            'old member' => ['oldMember', 'Old members can not login'],
            'pending member' => ['pending', 'Pending members can not login'],
        ];
    }

    #[Test]
    public function the_login_page_is_shown()
    {
        $this->get('/login')->assertOk()->assertSee('Welcome back');
    }

    #[Test]
    public function a_member_logs_in_and_is_remembered()
    {
        $user = User::factory()->create();

        $this->login($user, extra: ['remember' => '1'])
            ->assertRedirect('/home')
            ->assertCookie(Auth::guard()->getRecallerName());

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);
    }

    #[Test]
    public function a_member_is_not_remembered_without_the_checkbox()
    {
        $this->login(User::factory()->create())
            ->assertRedirect('/home')
            ->assertCookieMissing(Auth::guard()->getRecallerName());
    }

    #[Test]
    public function a_wrong_password_is_rejected()
    {
        $this->from('/login');

        $this->login(User::factory()->create(), 'wrong')
            ->assertRedirect('/login')
            ->assertInvalid('email');

        $this->assertGuest();
    }

    #[Test]
    #[DataProvider('blockedMembers')]
    public function a_blocked_member_can_not_log_in(string $state, string $message)
    {
        $this->login(User::factory()->{$state}()->create())->assertInvalid(['email' => $message]);

        $this->assertGuest();
    }

    #[Test]
    public function an_old_member_with_a_wrong_password_gets_the_generic_error()
    {
        $this->login(User::factory()->oldMember()->create(), 'wrong')->assertInvalid('email');

        $this->assertNotSame('Old members can not login', session('errors')->first('email'));
    }

    #[Test]
    public function logins_are_throttled()
    {
        $user = User::factory()->create();

        foreach (range(1, 5) as $attempt) {
            $this->login($user, 'wrong');
        }

        $this->login($user)->assertTooManyRequests();
        $this->assertGuest();
    }

    #[Test]
    public function a_member_logs_out()
    {
        $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/');

        $this->assertGuest();
    }

    #[Test]
    public function an_outdated_password_hash_is_rehashed_on_login()
    {
        $user = User::factory()->create(['password' => Hash::make('password', ['rounds' => 4])]);
        Hash::driver('bcrypt')->setRounds(5);

        $this->login($user)->assertRedirect('/home');

        $this->assertSame(5, password_get_info($user->fresh()->password)['options']['cost']);
    }
}
