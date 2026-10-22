<?php

namespace Tests\Feature\Agenda;

use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteAgendaRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function registration(string $subscriptionEndDate): ApplicationResponse
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $registration = ApplicationResponse::factory()->create(['user_id' => $user->id]);
        $registration->agendaItem->update(['subscription_end_date' => now()->modify($subscriptionEndDate)]);

        return $registration;
    }

    #[Test]
    public function remove_agenda_registration(): void
    {
        $registration = $this->registration('+1 week');

        $this->delete('forms/'.$registration->agenda_id)->assertRedirect();

        $this->assertSoftDeleted($registration);
    }

    #[Test]
    public function a_agenda_registration_can_not_be_removed_when_the_subscription_date_is_pasted(): void
    {
        $registration = $this->registration('-1 week');

        $this->delete('forms/'.$registration->agenda_id)
            ->assertRedirect()
            ->assertSessionHas('message', 'The registration date has expired, so you can no longer unsubscribe.');

        $this->assertNotSoftDeleted($registration);
    }
}
