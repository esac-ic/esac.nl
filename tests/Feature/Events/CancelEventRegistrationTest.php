<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CancelEventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function registration(string $registrationDeadline): Registration
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $registration = Registration::factory()->create(['user_id' => $user->id]);
        $registration->event->update(['registration_deadline' => now()->modify($registrationDeadline)]);

        return $registration;
    }

    #[Test]
    public function a_member_can_cancel_their_registration(): void
    {
        $registration = $this->registration('+1 week');

        $this->delete('events/'.$registration->event_id.'/registration')->assertRedirect();

        $this->assertModelMissing($registration);
    }

    #[Test]
    public function a_registration_can_not_be_cancelled_after_the_registration_deadline(): void
    {
        $registration = $this->registration('-1 week');

        $this->delete('events/'.$registration->event_id.'/registration')
            ->assertRedirect()
            ->assertSessionHas('error', 'The registration deadline has passed, so you can no longer unregister.');

        $this->assertModelExists($registration);
    }

    #[Test]
    public function a_registration_can_only_be_cancelled_through_its_own_event(): void
    {
        $registration = $this->registration('+1 week');
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());

        $this->delete(route('admin.events.registrations.destroy', [Event::factory()->create(), $registration]))->assertNotFound();
        $this->delete(route('admin.events.registrations.destroy', [$registration->event_id, $registration]))->assertRedirect();

        $this->assertModelMissing($registration);
    }
}
