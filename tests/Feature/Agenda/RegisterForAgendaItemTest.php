<?php

namespace Tests\Feature\Agenda;

use App\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class RegisterForAgendaItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = '/forms/';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function show_event_registration_form_for_not_existing_agenda_item()
    {
        $response = $this->get($this->url . 3424);

        $response->assertStatus(404);
    }

    /** @test */
    public function show_event_registration_form_for_agenda_item_without_registration_form()
    {
        $agendaItem = factory(AgendaItem::class)->create();
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();
        $response = $this->get($this->url . $agendaItem->id);

        $response->assertStatus(200);
        $response->assertViewHas('error', 'ERROR: No registration form available for this activity.');
    }

    /** @test */
    public function show_event_registration_form_for_agenda_item_with_past_subscription_date()
    {
        $agendaItem = factory(AgendaItem::class)->create();
        $agendaItem->subscription_endDate = Carbon::now()->subWeek();
        $agendaItem->save();
        $response = $this->get($this->url . $agendaItem->id);

        $response->assertStatus(200);
        $response->assertViewHas('error', 'The registration date has expired, so you can no longer subscribe.');
    }

    /** @test */
    public function show_event_registration_form_for_agenda_item_where_user_is_already_registered()
    {
        $agendaItem = factory(AgendaItem::class)->create();
        $applicationForm = factory(ApplicationForm::class)->create();

        $agendaItem->application_form_id = $applicationForm->id;
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();

        ApplicationResponse::create([
            'user_id' => $this->user->id,
            'agenda_id' => $agendaItem->id,
            'inschrijf_form_id' => $applicationForm->id,
        ]);

        $response = $this->get($this->url . $agendaItem->id);

        $response->assertStatus(200);
        $response->assertViewHas('error', 'You have already signed up for this event.');
    }

    /** @test */
    public function register_for_agenda_item(): void
    {
        $agendaItem = factory(AgendaItem::class)->create();
        $applicationForm = factory(ApplicationForm::class)->create();

        $agendaItem->application_form_id = $applicationForm->id;
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();

        $applicationFormRowNumber = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
        ]);
        $applicationFormRowText = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_TEXT,
        ]);
        $applicationFormRowTextBox = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_TEXT_BOX,
        ]);

        $body = [
            $applicationFormRowNumber->id => 2342,
            $applicationFormRowText->id => "hello",
            $applicationFormRowTextBox->id => "hello",
        ];

        $response = $this->post($this->url . $agendaItem->id, $body);

        $response->assertStatus(302);

        $applicationResponse = ApplicationResponse::all()->last();

        $this->assertEquals($this->user->id, $applicationResponse->user_id);
        $this->assertEquals($agendaItem->id, $applicationResponse->agenda_id);
        $this->assertEquals($applicationForm->id, $applicationResponse->inschrijf_form_id);

        $this->assertCount(3, $applicationResponse->getApplicationFormResponseRows);

        $appliationForRowIds = [];
        foreach ($applicationResponse->getApplicationFormResponseRows as $responseRow) {
            $appliationForRowIds[] = $responseRow->application_form_row_id;
            $this->assertEquals($body[$responseRow->application_form_row_id], $responseRow->value);
        }
    }

    /** @test */
    public function register_user_for_agenda_item_as_normale_member_should_not_be_allowed(): void
    {
        $userToRegister = factory(User::class)->create();
        $agendaItem = factory(AgendaItem::class)->create();
        $applicationForm = factory(ApplicationForm::class)->create();

        $agendaItem->application_form_id = $applicationForm->id;
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();

        $applicationFormRowNumber = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
        ]);
        $applicationFormRowText = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_TEXT,
        ]);
        $applicationFormRowTextBox = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_TEXT_BOX,
        ]);

        $body = [
            'user' => $userToRegister->id,
            $applicationFormRowNumber->id => 2342,
            $applicationFormRowText->id => "hello",
            $applicationFormRowTextBox->id => "hello",
        ];

        $response = $this->post($this->url . 'admin/' . $agendaItem->id, $body);

        $response->assertStatus(403);
    }

    /** @test */
    public function register_user_for_agenda_item_as_administrator(): void
    {
        $this->user->roles()->attach(Config::get('constants.Content_administrator'));
        $userToRegister = factory(User::class)->create();
        $agendaItem = factory(AgendaItem::class)->create();
        $applicationForm = factory(ApplicationForm::class)->create();

        $agendaItem->application_form_id = $applicationForm->id;
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();

        $applicationFormRowNumber = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
        ]);
        $applicationFormRowText = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_TEXT,
        ]);
        $applicationFormRowTextBox = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $applicationForm->id,
            'type' => ApplicationFormRow::FORM_TYPE_TEXT_BOX,
        ]);

        $body = [
            'user' => $userToRegister->id,
            $applicationFormRowNumber->id => 2342,
            $applicationFormRowText->id => "hello",
            $applicationFormRowTextBox->id => "hello",
        ];

        $response = $this->post($this->url . 'admin/' . $agendaItem->id, $body);

        $response->assertStatus(302);

        $applicationResponse = ApplicationResponse::all()->last();

        $this->assertEquals($userToRegister->id, $applicationResponse->user_id);
        $this->assertEquals($agendaItem->id, $applicationResponse->agenda_id);
        $this->assertEquals($applicationForm->id, $applicationResponse->inschrijf_form_id);

        $this->assertCount(3, $applicationResponse->getApplicationFormResponseRows);

        $appliationForRowIds = [];
        foreach ($applicationResponse->getApplicationFormResponseRows as $responseRow) {
            $appliationForRowIds[] = $responseRow->application_form_row_id;
            $this->assertEquals($body[$responseRow->application_form_row_id], $responseRow->value);
        }
    }
}
