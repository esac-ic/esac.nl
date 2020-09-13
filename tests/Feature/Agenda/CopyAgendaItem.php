<?php

namespace Tests\Feature\Agenda;


use App\AgendaItem;
use App\Rol;
use App\User;
use Artisan;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TestCase;

class CopyAgendaItem extends TestCase
{
    use DatabaseMigrations;

    private $url = 'agendaItems/';
    private $user;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();

        $this->role = factory(Rol::class)->create([
            'id' => 3
        ]);

        $this->user->roles()->attach(Config::get('constants.Activity_administrator'));

        $this->be($this->user);

        session()->start();
    }

    protected function tearDown()  : void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function agenda_item_can_be_copied(){
        $agendaItem = factory(AgendaItem::class)->create(['image_url' => ""]);

        $response = $this->get($this->url . $agendaItem->id . '/copy');

        $response->assertStatus(302);

        $agendaItems = AgendaItem::all();
        $this->assertCount(2, $agendaItems);
        $newAgendaItem = $agendaItems[1];

        $this->assertEquals($agendaItem->subscription_endDate, $newAgendaItem->subscription_endDate);
        $this->assertEquals($agendaItem->startDate, $newAgendaItem->startDate);
        $this->assertEquals($agendaItem->endDate, $newAgendaItem->endDate);
        $this->assertEquals($agendaItem->category, $newAgendaItem->category);
        $this->assertEquals($agendaItem->climbing_activity, $newAgendaItem->climbing_activity);

        $this->assertNotEquals($agendaItem->title, $newAgendaItem->title);
        $this->assertNotEquals($agendaItem->text, $newAgendaItem->text);
        $this->assertNotEquals($agendaItem->shortDescription, $newAgendaItem->shortDescription);

        $this->assertEquals($agendaItem->agendaItemText->NL_text, $newAgendaItem->agendaItemText->NL_text);
        $this->assertEquals($agendaItem->agendaItemText->EN_text, $newAgendaItem->agendaItemText->EN_text);
        $this->assertEquals($agendaItem->agendaItemTitle->NL_text, $newAgendaItem->agendaItemTitle->NL_text);
        $this->assertEquals($agendaItem->agendaItemTitle->EN_text, $newAgendaItem->agendaItemTitle->EN_text);
        $this->assertEquals($agendaItem->agendaItemShortDescription->NL_text, $newAgendaItem->agendaItemShortDescription->NL_text);
        $this->assertEquals($agendaItem->agendaItemShortDescription->EN_text, $newAgendaItem->agendaItemShortDescription->EN_text);
    }
}
