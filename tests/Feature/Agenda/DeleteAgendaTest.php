<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Artisan;
use Carbon\Carbon;
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

    protected function setUp()
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $role = factory(Rol::class)->create([
            'id' => 3
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
    public function DeleteAgendaItem(){
        $agendaItem = factory(AgendaItem::class)->create();

        $response = $this->delete($this->url . '/' . $agendaItem->id);

        $response->assertStatus(302);

        $this->assertNull(AgendaItem::find($agendaItem->id));
    }
}