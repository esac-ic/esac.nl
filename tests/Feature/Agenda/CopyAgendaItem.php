<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CopyAgendaItem extends TestCase
{
    use RefreshDatabase;

    private $url = 'agendaItems/';
    private $user;
    private $role;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->role = Rol::factory()->create([
            'id' => 3
        ]);

        $this->user->roles()->attach(Role::ActivityAdministrator->value);

        $this->be($this->user);

        session()->start();
    }

    #[Test]
    public function agenda_item_can_be_copied(){
        $agendaItem = AgendaItem::factory()->create(['image_url' => ""]);

        $response = $this->get($this->url . $agendaItem->id . '/copy');

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
