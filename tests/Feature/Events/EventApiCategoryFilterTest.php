<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventApiCategoryFilterTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/agenda';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(AdminRole::Administrator)->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function the_events_api_filters_by_category()
    {
        $eventCategory = EventCategory::factory()->create();
        $event = Event::factory()->create();
        $event->event_category_id = $eventCategory->id;
        $event->save();

        Event::factory()->create();
        Event::factory()->create();

        $response = $this->get($this->url.'?category='.$eventCategory->id);
        $json = json_decode($response->getContent());

        $this->assertEquals($json->{'agendaItemCount'}, 1);
        $this->assertEquals($json->{'agendaItems'}[0]->{'title'}, $event->title);

        $response = $this->get($this->url.'?category='.($eventCategory->id + 1));
        $json = json_decode($response->getContent());
        $this->assertEquals($json->{'agendaItemCount'}, 0);

        $response = $this->get($this->url);
        $json = json_decode($response->getContent());
        $this->assertEquals($json->{'agendaItemCount'}, 3);
    }
}
