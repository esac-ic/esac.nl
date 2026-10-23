<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Enums\FormFieldType;
use App\Models\Event;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterForEventTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    private function openEvent(array $attributes = []): Event
    {
        return Event::factory()->create(['registration_deadline' => now()->addWeek(), ...$attributes]);
    }

    private function eventWithRegistrationForm(): Event
    {
        return $this->openEvent(['registration_form_id' => RegistrationForm::factory()->create()->id]);
    }

    private function answers(Event $event): array
    {
        $values = [
            [FormFieldType::Number, 2342],
            [FormFieldType::Text, 'hello'],
            [FormFieldType::Textarea, 'hello'],
        ];

        $body = [];
        foreach ($values as [$type, $value]) {
            $row = RegistrationFormField::factory()->create(['registration_form_id' => $event->registration_form_id, 'type' => $type]);
            $body[$row->id] = $value;
        }

        return $body;
    }

    private function assertRegistered(User $user, Event $event, array $answers): void
    {
        $response = Registration::sole();

        $this->assertEquals($user->id, $response->user_id);
        $this->assertEquals($event->id, $response->event_id);
        $this->assertEquals($event->registration_form_id, $response->registration_form_id);
        $this->assertEquals($answers, $response->answers->pluck('value', 'registration_form_field_id')->all());
    }

    #[Test]
    public function the_registration_form_of_a_missing_event_is_not_found()
    {
        $this->get('/events/3424/registration')->assertNotFound();
    }

    #[Test]
    public function an_event_without_a_registration_form_can_not_be_registered_for()
    {
        $this->get('/events/'.$this->openEvent()->id.'/registration')
            ->assertRedirect()
            ->assertSessionHas('error', 'This event has no registration form.');
    }

    #[Test]
    public function registration_is_closed_after_the_registration_deadline()
    {
        $this->get('/events/'.$this->openEvent(['registration_deadline' => now()->subWeek()])->id.'/registration')
            ->assertRedirect()
            ->assertSessionHas('error', 'The registration deadline has passed, so you can no longer register.');
    }

    #[Test]
    public function a_registered_member_can_not_register_again()
    {
        $event = $this->eventWithRegistrationForm();
        Registration::factory()->create([
            'user_id' => $this->user->id,
            'event_id' => $event->id,
            'registration_form_id' => $event->registration_form_id,
        ]);

        $this->get('/events/'.$event->id.'/registration')
            ->assertRedirect(route('events.show', $event))
            ->assertSessionMissing('error');

        $this->post('/events/'.$event->id.'/registration', $this->answers($event))
            ->assertRedirect()
            ->assertSessionHas('error', 'You are already registered for this event.');
    }

    #[Test]
    public function a_member_can_register_for_an_event(): void
    {
        $event = $this->eventWithRegistrationForm();
        $answers = $this->answers($event);

        $this->post('/events/'.$event->id.'/registration', $answers)
            ->assertRedirect(route('events.show', $event))
            ->assertSessionHas('message', 'You are registered for '.$event->title.'.');

        $this->assertRegistered($this->user, $event, $answers);
    }

    #[Test]
    public function a_member_can_not_register_someone_else(): void
    {
        $event = $this->eventWithRegistrationForm();

        $this->post(route('admin.events.registrations.store', $event), ['user_id' => User::factory()->create()->id] + $this->answers($event))
            ->assertForbidden();
    }

    #[Test]
    public function an_event_administrator_can_register_a_member(): void
    {
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());
        $userToRegister = User::factory()->create();
        $event = $this->eventWithRegistrationForm();
        $answers = $this->answers($event);

        $this->post(route('admin.events.registrations.store', $event), ['user_id' => $userToRegister->id] + $answers)->assertRedirect();

        $this->assertRegistered($userToRegister, $event, $answers);
    }

    #[Test]
    public function answers_are_validated_against_the_registration_form_of_the_event(): void
    {
        $event = $this->eventWithRegistrationForm();
        $required = RegistrationFormField::factory()->create([
            'registration_form_id' => $event->registration_form_id,
            'type' => FormFieldType::Number,
            'required' => true,
        ]);
        $otherFormsRow = RegistrationFormField::factory()->create();

        $this->post('/events/'.$event->id.'/registration', [$required->id => 'not a number'])->assertInvalid([$required->id]);
        $this->post('/events/'.$event->id.'/registration', $this->answers($event) + [$required->id => 3, $otherFormsRow->id => 'x'])->assertValid();

        $this->assertNotContains($otherFormsRow->id, Registration::sole()->answers->pluck('registration_form_field_id'));
    }
}
