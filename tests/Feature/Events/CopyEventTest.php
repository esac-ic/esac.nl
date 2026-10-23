<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CopyEventTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'admin/events/';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(AdminRole::EventAdministrator)->create();

        $this->be($this->user);
    }

    #[Test]
    public function an_event_can_be_copied()
    {
        $event = Event::factory()->create(['image_path' => '']);

        $response = $this->post($this->url.$event->id.'/copy');

        $response->assertStatus(302);

        $events = Event::all();
        $this->assertCount(2, $events);
        $copy = $events[1];

        $this->assertEquals($event->registration_deadline, $copy->registration_deadline);
        $this->assertEquals($event->start_date, $copy->start_date);
        $this->assertEquals($event->end_date, $copy->end_date);
        $this->assertEquals($event->event_category_id, $copy->event_category_id);
        $this->assertEquals($event->shows_certificates, $copy->shows_certificates);

        $this->assertEquals($event->title, $copy->title);
        $this->assertEquals($event->body, $copy->body);
        $this->assertEquals($event->short_description, $copy->short_description);
    }

    #[Test]
    public function a_copy_gets_its_own_image_file()
    {
        Storage::fake('public')->put('agendaItem/1-thumbnail.jpg', 'image');
        // A copy of a copy still has the image name of the original.
        $event = Event::factory()->create(['image_path' => 'agendaItem/1-thumbnail.jpg']);

        $this->post($this->url.$event->id.'/copy');

        $copy = Event::query()->latest('id')->first();
        $this->assertSame("agendaItem/{$copy->id}-thumbnail.jpg", $copy->image_path);
        Storage::disk('public')->assertExists([$event->image_path, $copy->image_path]);
    }
}
