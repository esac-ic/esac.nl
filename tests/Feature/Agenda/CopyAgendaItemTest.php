<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CopyAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'beheer/agenda-items/';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(Role::ActivityAdministrator)->create();

        $this->be($this->user);
    }

    #[Test]
    public function agenda_item_can_be_copied()
    {
        $agendaItem = AgendaItem::factory()->create(['image_url' => '']);

        $response = $this->post($this->url.$agendaItem->id.'/copy');

        $response->assertStatus(302);

        $agendaItems = AgendaItem::all();
        $this->assertCount(2, $agendaItems);
        $newAgendaItem = $agendaItems[1];

        $this->assertEquals($agendaItem->subscription_end_date, $newAgendaItem->subscription_end_date);
        $this->assertEquals($agendaItem->start_date, $newAgendaItem->start_date);
        $this->assertEquals($agendaItem->end_date, $newAgendaItem->end_date);
        $this->assertEquals($agendaItem->category, $newAgendaItem->category);
        $this->assertEquals($agendaItem->climbing_activity, $newAgendaItem->climbing_activity);

        $this->assertEquals($agendaItem->title, $newAgendaItem->title);
        $this->assertEquals($agendaItem->text, $newAgendaItem->text);
        $this->assertEquals($agendaItem->short_description, $newAgendaItem->short_description);
    }

    #[Test]
    public function a_copy_gets_its_own_image_file()
    {
        Storage::fake('public')->put('agendaItem/1-thumbnail.jpg', 'image');
        // A copy of a copy still has the image name of the original.
        $agendaItem = AgendaItem::factory()->create(['image_url' => 'agendaItem/1-thumbnail.jpg']);

        $this->post($this->url.$agendaItem->id.'/copy');

        $copy = AgendaItem::query()->latest('id')->first();
        $this->assertSame("agendaItem/{$copy->id}-thumbnail.jpg", $copy->image_url);
        Storage::disk('public')->assertExists([$agendaItem->image_url, $copy->image_url]);
    }
}
