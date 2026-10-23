<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\RegistrationForm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'admin/events';

    private $event;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(AdminRole::EventAdministrator)->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function an_event_administrator_can_update_an_event()
    {
        $event = Event::factory()->create();
        $eventCategory = EventCategory::factory()->create();

        $body = [
            'title' => 'test agenda',
            'text' => 'test agenda',
            'short_description' => 'test agenda',
            'event_category_id' => $eventCategory->id,
            'registration_form_id' => RegistrationForm::factory()->create()->id,
            'registration_deadline' => Carbon::now()->addDays(2),
            'end_date' => Carbon::now()->addDays(3),
            'start_date' => Carbon::now()->addDays(1),
        ];

        $response = $this->patch($this->url.'/'.$event->id, $body);

        $response->assertStatus(302);

        $event = $event->refresh();

        $this->assertEquals($body['text'], $event->text);
        $this->assertEquals($body['title'], $event->title);
        $this->assertEquals($body['event_category_id'], $event->category->id);
    }
}
