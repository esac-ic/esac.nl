<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'agendaItems';

    private $agendaItem;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();
        $user->roles()->attach(Role::ActivityAdministrator->value);
        $this->be($user);
    }

    #[Test]
    public function update_agenda_item_test()
    {
        $agendaItem = AgendaItem::factory()->create();
        $agendaItemCategory = AgendaItemCategory::factory()->create();

        $body = [
            'title' => 'test agenda',
            'text' => 'test agenda',
            'short_description' => 'test agenda',
            'category' => $agendaItemCategory->id,
            'applicationForm' => ApplicationForm::factory()->create()->id,
            'subscription_end_date' => Carbon::now()->addDays(2),
            'end_date' => Carbon::now()->addDays(3),
            'start_date' => Carbon::now()->addDays(1),
        ];

        $response = $this->patch($this->url.'/'.$agendaItem->id, $body);

        $response->assertStatus(302);

        $agendaItem = $agendaItem->refresh();

        $this->assertEquals($body['text'], $agendaItem->text);
        $this->assertEquals($body['title'], $agendaItem->title);
        $this->assertEquals($body['category'], $agendaItem->agendaItemCategory->id);
    }
}
