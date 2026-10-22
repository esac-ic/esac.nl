<?php

namespace Tests\Feature\Agenda;

use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteAgendaRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private $url = '/forms/';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $this->be($user);
    }

    #[Test]
    public function remove_agenda_registration(): void
    {
        $applicationResponse = ApplicationResponse::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $agendaItem = $applicationResponse->agendaItem;
        $agendaItem->subscription_end_date = Carbon::now()->addWeek();
        $agendaItem->save();

        $response = $this->delete('forms/'.$agendaItem->id);

        $response->assertStatus(302);

        $this->assertSoftDeleted($applicationResponse);
    }

    #[Test]
    public function a_agenda_registration_can_not_be_removed_when_the_subscription_date_is_pasted(): void
    {
        $applicationResponse = ApplicationResponse::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $agendaItem = $applicationResponse->agendaItem;
        $agendaItem->subscription_end_date = Carbon::now()->subWeek();
        $agendaItem->save();

        $response = $this->delete('forms/'.$agendaItem->id);

        $response->assertStatus(302);

        $response->assertSessionHas('message', 'The registration date has expired, so you can no longer unsubscribe.');
        $this->assertNotSoftDeleted($applicationResponse);
    }
}
