<?php

namespace Tests\Feature;

use App\Rol;
use App\User;
use App\AgendaItem;
use App\AgendaItemCategorie;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

class AgendaFilterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = 'api/agenda?category=';

    /**
     * @var
     */
    private $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $role = factory(Rol::class)->create([
            'id' => 1
        ]);

        $user->roles()->attach($role->id);
        $this->be($user);

        session()->start();
    }

    protected function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function api_returns_filtered_agenda_items(){
        $agendaItemCategory = factory(AgendaItemCategorie::class)->create();
        $agendaItem = factory(AgendaItem::class)->create();
        $agendaItem->category = $agendaItemCategory->id;
        $agendaItem->save();

        $response = $this->get($this->url . $agendaItemCategory->id);

        $this->assertEquals(json_decode($response->getContent())->{"agendaItemCount"}, 1);
    }
}
