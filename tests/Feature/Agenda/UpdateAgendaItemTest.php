<?php

namespace Tests\Feature\Agenda;

use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\AgendaItem;
use App\AgendaItemCategorie;
use App\Rol;
use App\Text;
use App\User;
use Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAgendaItemTest extends TestCase
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
    public function UpdateAgendaItemTest(){
        $agendaItem = factory(AgendaItem::class)->create();
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

        $response = $this->patch($this->url . "/" . $agendaItem->id, $body);

        $response->assertStatus(302);

        $agendaItem = $agendaItem->refresh();

        $this->assertEquals($body['NL_text'],$agendaItem->agendaItemText->text());
        $this->assertEquals($body['NL_title'],$agendaItem->agendaItemTitle->text());
        $this->assertEquals($body['category'],$agendaItem->agendaItemCategory->id);
    }
}
