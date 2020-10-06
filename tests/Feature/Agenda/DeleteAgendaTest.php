<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Rol;
use App\User;
use App\Text;
use App\AgendaItem;
use App\AgendaItemCategorie;
use App\ApplicationForm;

class DeleteAgendaItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'agendaItems';
    private $agendaItem;

    /**
     * @var
     */
    private $user;
    
    protected function setUp() : void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $user->roles()->attach(Config::get('constants.Activity_administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown() : void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    /** @test */
    public function DeleteAgendaItem(){
        $agendaItem = factory(AgendaItem::class)->create();

        $response = $this->delete($this->url . '/' . $agendaItem->id);

        $response->assertStatus(302);

        $this->assertNull(AgendaItem::find($agendaItem->id));
    }
}