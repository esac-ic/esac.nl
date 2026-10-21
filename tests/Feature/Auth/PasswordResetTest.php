<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_reset_request_page_is_shown()
    {
        $this->get('/password/reset')->assertOk()->assertSee('Send password reset link');
    }

    #[Test]
    public function a_member_resets_their_password()
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/password/email', ['email' => $user->email])->assertSessionHas('status');

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token, $user) {
            $token = $notification->token;

            return str_contains($notification->toMail($user)->actionUrl, '/password/reset/'.$token);
        });

        $this->get('/password/reset/'.$token.'?email='.urlencode($user->email))
            ->assertOk()
            ->assertSee($token);

        $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'a-new-password',
            'password_confirmation' => 'a-new-password',
        ])->assertRedirect('/login')->assertSessionHas('status');

        $this->assertTrue(Hash::check('a-new-password', $user->fresh()->password));
    }
}
