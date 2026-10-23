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
    public function copying_opens_the_new_event_form_filled_in_without_saving()
    {
        $event = Event::factory()->create(['title' => 'Climbing weekend', 'short_description' => 'Two days in Belgium']);

        $this->get(route('admin.events.create', ['copy' => $event]))
            ->assertOk()
            ->assertSee('Based on Climbing weekend')
            ->assertSee('value="Climbing weekend"', false)
            ->assertSee('value="Two days in Belgium"', false)
            ->assertSee('name="copy_of" value="'.$event->id.'"', false);

        $this->assertCount(1, Event::all());
    }

    #[Test]
    public function a_copy_gets_its_own_image_file_when_no_new_image_is_uploaded()
    {
        Storage::fake('public')->put('agendaItem/1-thumbnail.jpg', 'image');
        $event = Event::factory()->create(['image_path' => 'agendaItem/1-thumbnail.jpg']);

        $this->post(route('admin.events.store'), [
            'title' => $event->title,
            'body' => $event->body,
            'short_description' => $event->short_description,
            'event_category_id' => $event->event_category_id,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'copy_of' => $event->id,
        ])->assertSessionHasNoErrors()->assertRedirect();

        $copy = Event::query()->latest('id')->first();
        $this->assertNotSame($event->id, $copy->id);
        $this->assertSame("agendaItem/{$copy->id}-thumbnail.jpg", $copy->image_path);
        Storage::disk('public')->assertExists([$event->image_path, $copy->image_path]);
    }
}
