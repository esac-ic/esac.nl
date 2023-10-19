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

class UpdateAgendaItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'agendaItems';
    private $agendaItem;

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $user->roles()->attach(Config::get('constants.Activity_administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    /** @test */
    public function UpdateAgendaItemTest()
    {
        $agendaItem = factory(AgendaItem::class)->create();
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

        $response = $this->patch($this->url . "/" . $agendaItem->id, $body);

        $response->assertStatus(302);

        $agendaItem = $agendaItem->refresh();

        $this->assertEquals($body['text'], $agendaItem->text);
        $this->assertEquals($body['title'], $agendaItem->title);
        $this->assertEquals($body['category'], $agendaItem->agendaItemCategory->id);
    }
}
