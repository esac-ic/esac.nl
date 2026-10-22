<?php

namespace Tests\Unit;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use App\Services\AgendaApplicationFormService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RemoveOldApplicationResponseTest extends TestCase
{
    use RefreshDatabase;

    private function registrationForEventThatEnded(Carbon $endDate): ApplicationResponse
    {
        $agendaItem = AgendaItem::factory()->create(['end_date' => $endDate]);
        $applicationResponse = ApplicationResponse::factory()->create(['agenda_id' => $agendaItem->id]);
        foreach (ApplicationFormRow::factory()->count(2)->create(['application_form_id' => $applicationResponse->inschrijf_form_id]) as $row) {
            $applicationResponse->getApplicationFormResponseRows()->create(['application_form_row_id' => $row->id, 'value' => 'answer']);
        }

        return $applicationResponse;
    }

    public function testAnonymiseTheRegistrationsOfAnEventThatEnded3YearsAgo()
    {
        $applicationResponse = $this->registrationForEventThatEnded(Carbon::now()->subYears(3));

        Artisan::call('remove:oldApplications');

        $applicationResponse->refresh();
        $this->assertNull($applicationResponse->user_id);
        $this->assertSame(0, ApplicationResponseRow::withTrashed()->count());
        $this->assertSame(1, $applicationResponse->agendaItem->getApplicationFormResponses()->count());
        $this->assertSame([], app(AgendaApplicationFormService::class)->getRegisteredUsers($applicationResponse->agendaItem)['userdata']);
    }

    public function testKeepTheRegistrationsOfAnEventThatEnded1YearAgo()
    {
        $applicationResponse = $this->registrationForEventThatEnded(Carbon::now()->subYear());
        $applicationResponse->created_at = Carbon::now()->subYears(3);
        $applicationResponse->save();

        Artisan::call('remove:oldApplications');

        $this->assertNotNull($applicationResponse->fresh()->user_id);
        $this->assertSame(2, ApplicationResponseRow::count());
    }

    public function testDeleteACancelledRegistrationOfAnEventThatEnded3YearsAgo()
    {
        $applicationResponse = $this->registrationForEventThatEnded(Carbon::now()->subYears(3));
        $applicationResponse->delete();

        Artisan::call('remove:oldApplications');

        $this->assertSame(0, ApplicationResponse::withTrashed()->count());
        $this->assertSame(0, ApplicationResponseRow::withTrashed()->count());
    }
}
