<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterForAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    private function openAgendaItem(array $attributes = []): AgendaItem
    {
        return AgendaItem::factory()->create(['subscription_end_date' => now()->addWeek(), ...$attributes]);
    }

    private function agendaItemWithForm(): AgendaItem
    {
        return $this->openAgendaItem(['application_form_id' => ApplicationForm::factory()->create()->id]);
    }

    private function answers(AgendaItem $agendaItem): array
    {
        $values = [
            ApplicationFormRow::FORM_TYPE_NUMBER => 2342,
            ApplicationFormRow::FORM_TYPE_TEXT => 'hello',
            ApplicationFormRow::FORM_TYPE_TEXT_BOX => 'hello',
        ];

        $body = [];
        foreach ($values as $type => $value) {
            $row = ApplicationFormRow::factory()->create(['application_form_id' => $agendaItem->application_form_id, 'type' => $type]);
            $body[$row->id] = $value;
        }

        return $body;
    }

    private function assertRegistered(User $user, AgendaItem $agendaItem, array $answers): void
    {
        $response = ApplicationResponse::sole();

        $this->assertEquals($user->id, $response->user_id);
        $this->assertEquals($agendaItem->id, $response->agenda_id);
        $this->assertEquals($agendaItem->application_form_id, $response->inschrijf_form_id);
        $this->assertEquals($answers, $response->rows->pluck('value', 'application_form_row_id')->all());
    }

    #[Test]
    public function show_event_registration_form_for_not_existing_agenda_item()
    {
        $this->get('/forms/3424')->assertNotFound();
    }

    #[Test]
    public function show_event_registration_form_for_agenda_item_without_registration_form()
    {
        $this->get('/forms/'.$this->openAgendaItem()->id)
            ->assertRedirect()
            ->assertSessionHas('error', 'ERROR: No registration form available for this activity.');
    }

    #[Test]
    public function show_event_registration_form_for_agenda_item_with_past_subscription_date()
    {
        $this->get('/forms/'.$this->openAgendaItem(['subscription_end_date' => now()->subWeek()])->id)
            ->assertRedirect()
            ->assertSessionHas('error', 'The registration date has expired, so you can no longer subscribe.');
    }

    #[Test]
    public function show_event_registration_form_for_agenda_item_where_user_is_already_registered()
    {
        $agendaItem = $this->agendaItemWithForm();
        ApplicationResponse::factory()->create([
            'user_id' => $this->user->id,
            'agenda_id' => $agendaItem->id,
            'inschrijf_form_id' => $agendaItem->application_form_id,
        ]);

        $this->get('/forms/'.$agendaItem->id)
            ->assertRedirect()
            ->assertSessionHas('error', 'You have already signed up for this event.');
    }

    #[Test]
    public function register_for_agenda_item(): void
    {
        $agendaItem = $this->agendaItemWithForm();
        $answers = $this->answers($agendaItem);

        $this->post('/forms/'.$agendaItem->id, $answers)->assertRedirect();

        $this->assertRegistered($this->user, $agendaItem, $answers);
    }

    #[Test]
    public function register_user_for_agenda_item_as_normale_member_should_not_be_allowed(): void
    {
        $agendaItem = $this->agendaItemWithForm();

        $this->post(route('beheer.agenda-items.registrations.store', $agendaItem), ['user' => User::factory()->create()->id] + $this->answers($agendaItem))
            ->assertForbidden();
    }

    #[Test]
    public function register_user_for_agenda_item_as_administrator(): void
    {
        $this->actingAs(User::factory()->withRole(Role::ContentAdministrator)->create());
        $userToRegister = User::factory()->create();
        $agendaItem = $this->agendaItemWithForm();
        $answers = $this->answers($agendaItem);

        $this->post(route('beheer.agenda-items.registrations.store', $agendaItem), ['user' => $userToRegister->id] + $answers)->assertRedirect();

        $this->assertRegistered($userToRegister, $agendaItem, $answers);
    }

    #[Test]
    public function answers_are_validated_against_the_events_form(): void
    {
        $agendaItem = $this->agendaItemWithForm();
        $required = ApplicationFormRow::factory()->create([
            'application_form_id' => $agendaItem->application_form_id,
            'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
            'required' => true,
        ]);
        $otherFormsRow = ApplicationFormRow::factory()->create();

        $this->post('/forms/'.$agendaItem->id, [$required->id => 'not a number'])->assertInvalid([$required->id]);
        $this->post('/forms/'.$agendaItem->id, $this->answers($agendaItem) + [$required->id => 3, $otherFormsRow->id => 'x'])->assertValid();

        $this->assertNotContains($otherFormsRow->id, ApplicationResponse::sole()->rows->pluck('application_form_row_id'));
    }
}
