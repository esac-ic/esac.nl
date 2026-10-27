<?php

namespace Tests\Unit;

use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RemoveOldApplicationResponseTest extends TestCase
{
    use RefreshDatabase;

    private int $id;

    protected function setUp(): void
    {
        parent::setUp();
        $applicationResponse = ApplicationResponse::factory()->create();
        foreach (ApplicationFormRow::factory()->count(2)->create(['application_form_id' => $applicationResponse->inschrijf_form_id]) as $row) {
            $applicationResponse->getApplicationFormResponseRows()->create(['application_form_row_id' => $row->id, 'value' => 'answer']);
        }
        $this->id = $applicationResponse->id;
    }

    public function testRemoveOldApplicationResponseFrom3YearsAgo()
    {
        $id = $this->id;
        $applicationResponse = ApplicationResponse::find($id);
        $applicationResponse->created_at = Carbon::now()->subYears(3);
        $applicationResponse->save();
        $responseRows = $applicationResponse->getApplicationFormResponseRows;
        $this->assertCount(2, $responseRows);

        Artisan::call('remove:oldApplications');

        $this->assertEquals(null, ApplicationResponse::find($id));

        foreach ($responseRows as $row) {
            $this->assertEquals(null, ApplicationResponseRow::find($row->id));
        }
    }

    public function testDontRemoveOldApplicationResponseFrom1YearsAgo()
    {
        $id = $this->id;
        $applicationResponse = ApplicationResponse::find($id);
        $applicationResponse->created_at = Carbon::now()->subYears(1);
        $applicationResponse->save();
        $responseRows = $applicationResponse->getApplicationFormResponseRows;
        $this->assertCount(2, $responseRows);

        Artisan::call('remove:oldApplications');

        $this->assertNotEquals(null, ApplicationResponse::find($id));

        foreach ($responseRows as $row) {
            $this->assertNotEquals(null, ApplicationResponseRow::find($row->id));
        }
    }
}
