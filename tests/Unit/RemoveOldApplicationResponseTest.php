<?php

namespace Tests\Unit;

use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

class RemoveOldApplicationResponseTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');

    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function testRemoveOldApplicationResponseFrom3YearsAgo()
    {
        $id = 1;
        $applicationResponse = ApplicationResponse::find($id);
        $applicationResponse->created_at = Carbon::now()->subYears(3);
        $applicationResponse->save();
        $responseRows = $applicationResponse->getApplicationFormResponseRows;

        Artisan::call('remove:oldApplications');

        $this->assertEquals(null, ApplicationResponse::find($id));

        foreach ($responseRows as $row) {
            $this->assertEquals(null, ApplicationResponseRow::find($row->id));
        }
    }

    public function testDontRemoveOldApplicationResponseFrom1YearsAgo()
    {
        $id = 1;
        $applicationResponse = ApplicationResponse::find($id);
        $applicationResponse->created_at = Carbon::now()->subYears(1);
        $applicationResponse->save();
        $responseRows = $applicationResponse->getApplicationFormResponseRows;

        Artisan::call('remove:oldApplications');

        $this->assertNotEquals(null, ApplicationResponse::find($id));

        foreach ($responseRows as $row) {
            $this->assertNotEquals(null, ApplicationResponseRow::find($row->id));
        }
    }
}
