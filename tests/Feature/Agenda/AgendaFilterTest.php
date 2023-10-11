<?php

namespace Tests\Feature\Agenda;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class AgendaFilterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = 'api/agenda';

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

        $user->roles()->attach(Config::get('constants.Administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }

    /** @test */
    public function api_returns_filtered_agenda_items()
    {
        $agendaItemCategory = factory(AgendaItemCategory::class)->create();
        $agendaItem = factory(AgendaItem::class)->create();
        $agendaItem->category = $agendaItemCategory->id;
        $agendaItem->save();

        factory(AgendaItem::class)->create();
        factory(AgendaItem::class)->create();

        $response = $this->get($this->url . "?category=" . $agendaItemCategory->id);
        $json = json_decode($response->getContent());

        $this->assertEquals($json->{"agendaItemCount"}, 1);
        $this->assertEquals($json->{"agendaItems"}[0]->{"category"}, $agendaItemCategory->name);
        $this->assertEquals($json->{"agendaItems"}[0]->{"title"}, $agendaItem->title);

        $response = $this->get($this->url . "?category=" . ($agendaItemCategory->id + 1));
        $json = json_decode($response->getContent());
        $this->assertEquals($json->{"agendaItemCount"}, 0);

        $response = $this->get($this->url);
        $json = json_decode($response->getContent());
        $this->assertEquals($json->{"agendaItemCount"}, 3);
    }
}
