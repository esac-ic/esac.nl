<?php

namespace Tests\Feature;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RemoveOldApplicationResponseTest extends TestCase
{
    use RefreshDatabase;

    private ApplicationResponse $applicationResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $agendaItem = AgendaItem::factory()->create(['application_form_id' => ApplicationForm::factory()]);
        $row = ApplicationFormRow::factory()->create(['application_form_id' => $agendaItem->application_form_id]);
        $agendaItem->register(User::factory()->create()->id, [$row->id => 'answer']);
        $this->applicationResponse = ApplicationResponse::firstOrFail();
    }

    #[Test]
    public function registrations_from_three_years_ago_are_removed()
    {
        $this->applicationResponse->forceFill(['created_at' => now()->subYears(3)])->save();

        $this->artisan('remove:oldApplications');

        $this->assertSame(0, ApplicationResponse::withTrashed()->count());
        $this->assertSame(0, ApplicationResponseRow::withTrashed()->count());
    }

    #[Test]
    public function registrations_from_a_year_ago_are_kept()
    {
        $this->applicationResponse->forceFill(['created_at' => now()->subYear()])->save();

        $this->artisan('remove:oldApplications');

        $this->assertSame(1, ApplicationResponse::count());
        $this->assertSame(1, ApplicationResponseRow::count());
    }
}
