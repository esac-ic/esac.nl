<?php

namespace Tests\Feature\Agenda;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class CreateAgendaItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'agendaItems';
    private $agendaItem;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    /** @test */
    public function CreateAgendaItem()
    {
        //Attach
        $this->user->roles()->attach(Config::get('constants.Activity_administrator'));

        $agendaItemCategory = factory(AgendaItemCategory::class)->create();

        $body = [
            '_token' => csrf_token(),
            'title' => 'test agenda',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => factory(ApplicationForm::class)->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $agendaItem = AgendaItem::all()->last();

        $this->assertEquals($body['text'], $agendaItem->text);
        $this->assertEquals($body['title'], $agendaItem->title);
        $this->assertEquals($body['category'], $agendaItem->agendaItemCategory->id);
    }

    public function CreateAgendaWithIncorrectRole()
    {
        //Login as User with incorrect role
        $this->user->roles()->detach();

        $agendaItemCategory = factory(AgendaItemCategorie::class)->create();
        $body = [
            '_token' => csrf_token(),
            'title' => 'test agenda',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => factory(ApplicationForm::class)->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(403);

    }

    public function CreateAgendaWithEmptyFields()
    {
        $this->user->roles()->attach(Config::get('constants.Activity_administrator'));

        $agendaItemCategory = factory(AgendaItemCategory::class)->create();
        $body = [
            '_token' => csrf_token(),
            'title' => '',
            'text' => 'test agenda',
            'shortDescription' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => factory(ApplicationForm::class)->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(500);
    }

    public function CreateAgendaItemWithEmoji()
    {
        //Attach
        $this->user->roles()->attach(Config::get('constants.Activity_administrator'));

        $agendaItemCategory = factory(AgendaItemCategory::class)->create();

        $body = [
            '_token' => csrf_token(),
            'title' => 'test agenda 😀',
            'text' => 'test agenda 😀',
            'shortDescription' => 'test agenda 😀',
            'category' => $agendaItemCategory->id,
            'applicationForm' => factory(ApplicationForm::class)->create()->id,
            'subscription_endDate' => Carbon::now()->addDays(2),
            'endDate' => Carbon::now()->addDays(3),
            'startDate' => Carbon::now()->addDays(1),
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $agendaItem = AgendaItem::all()->last();

        $this->assertEquals($body['text'], $agendaItem->text);
        $this->assertEquals($body['title'], $agendaItem->title);
        $this->assertEquals($body['category'], $agendaItem->agendaItemCategory->id);
    }
}
