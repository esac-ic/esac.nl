<?php

namespace Tests\Feature;

use App\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use App\User;
use Artisan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TestCase;

class RegisterForAgendaItem extends TestCase
{
    use DatabaseMigrations;

    private $url = '/forms/';

    private $user;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

        $this->be($user);

        session()->start();
    }

    protected function tearDown()  : void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function show_event_registration_form_for_not_existing_agenda_item() {
        $response = $this->get($this->url . 3424);

        $response->assertStatus(404);
    }

    /** @test */
    public function show_event_registration_form_for_agenda_item_without_registration_form() {
        $agendaItem = factory(AgendaItem::class)->create();
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();
        $response = $this->get($this->url . $agendaItem->id);

        $response->assertStatus(200);
        $response->assertViewHas('error', trans("forms.form_not_available"));
    }

    /** @test */
    public function show_event_registration_form_for_agenda_item_with_past_subscription_date() {
        $agendaItem = factory(AgendaItem::class)->create();
        $agendaItem->subscription_endDate = Carbon::now()->subWeek();
        $agendaItem->save();
        $response = $this->get($this->url . $agendaItem->id);

        $response->assertStatus(200);
        $response->assertViewHas('error', trans("forms.signupexpired"));
    }

    /** @test */
    public function show_event_registration_form_for_agenda_item_where_user_is_already_registered() {
        $agendaItem = factory(AgendaItem::class)->create();
        $applicationForm = factory(ApplicationForm::class)->create();

        $agendaItem->application_form_id = $applicationForm->id;
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();

        ApplicationResponse::create([
            'user_id' => $this->user->id,
            'agenda_id' => $agendaItem->id,
            'inschrijf_form_id' => $applicationForm->id
        ]);

        $response = $this->get($this->url . $agendaItem->id);

        $response->assertStatus(200);
        $response->assertViewHas('error', trans("forms.duplicatesignup"));
    }
}
