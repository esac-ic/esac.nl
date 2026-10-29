<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteEventTest extends TestCase
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
    public function an_event_administrator_can_delete_an_event()
    {
        $event = Event::factory()->create();

        $response = $this->delete($this->url.'/'.$event->id);

        $response->assertStatus(302);

        $this->assertModelMissing($event);
    }
}
