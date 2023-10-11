<?php

namespace Tests\Feature\Agenda;

use App\Models\ApplicationForm\ApplicationResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

class DeleteAgendaRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = '/forms/';

    /**
     * @var User
     */
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
    public function remove_agenda_registration(): void
    {
        $applicationResponse = factory(ApplicationResponse::class)->create([
            'user_id' => $this->user->id,
        ]);
        $agendaItem = $applicationResponse->agendaItem;
        $agendaItem->subscription_endDate = Carbon::now()->addWeek();
        $agendaItem->save();

        $response = $this->get('forms/' . $agendaItem->id . '/unregister');

        $response->assertStatus(302);

        $this->assertNull(ApplicationResponse::find($applicationResponse->id));
    }

    /** @test */
    public function a_agenda_registration_can_not_be_removed_when_the_subscription_date_is_pasted(): void
    {
        $applicationResponse = factory(ApplicationResponse::class)->create([
            'user_id' => $this->user->id,
        ]);
        $agendaItem = $applicationResponse->agendaItem;
        $agendaItem->subscription_endDate = Carbon::now()->subWeek();
        $agendaItem->save();

        $response = $this->get('forms/' . $agendaItem->id . '/unregister');

        $response->assertStatus(302);

        $response->assertSessionHas('message', 'The registration date has expired, so you can no longer unsubscribe.');
        $this->assertNotNull(ApplicationResponse::find($applicationResponse->id));
    }
}
