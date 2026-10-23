<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminEventListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_admin_event_list_shows_the_events_ending_on_or_after_the_start_date()
    {
        Event::factory()->create(['title' => 'May climbing weekend', 'start_date' => '2026-05-01 10:00:00', 'end_date' => '2026-05-03 12:00:00']);
        Event::factory()->create(['title' => 'July climbing weekend', 'start_date' => '2026-07-01 10:00:00', 'end_date' => '2026-07-03 12:00:00']);

        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create())
            ->get(route('admin.events.index', ['start_date' => '2026-05-03']))
            ->assertOk()
            ->assertSee('May climbing weekend')
            ->assertSee('July climbing weekend');

        $this->get(route('admin.events.index', ['start_date' => '2026-06-01']))
            ->assertOk()
            ->assertDontSee('May climbing weekend')
            ->assertSee('July climbing weekend');
    }
}
