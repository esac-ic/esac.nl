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

class CreateAgendaItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'agendaItems';
    private $agendaItem;

    private $user;

    protected function setUp() : void
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
    public function CreateAgendaItem(){
        $agendaItemCategory = factory(AgendaItemCategorie::class)->create();

        $body = [
            '_token' => csrf_token(),
            'NL_title' => 'test agenda',
            'EN_title' => 'test agenda',
            'NL_text' => 'test agenda',
            'EN_text' => 'test agenda',
            'NL_shortDescription' => 'test agenda',
            'EN_shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => factory(ApplicationForm::class)->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' =>  Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $agendaItem = AgendaItem::all()->last();

        $this->assertEquals($body['NL_text'],$agendaItem->agendaItemText->text());
        $this->assertEquals($body['NL_title'],$agendaItem->agendaItemTitle->text());
        $this->assertEquals($body['category'],$agendaItem->agendaItemCategory->id);
    }
}