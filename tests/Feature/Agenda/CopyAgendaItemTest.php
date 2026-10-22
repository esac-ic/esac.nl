<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CopyAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'agendaItems/';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->user->roles()->attach(Role::ActivityAdministrator->value);

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
}
