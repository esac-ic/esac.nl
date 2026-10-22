<?php

namespace Tests\Feature\Agenda;

use App\Enums\AdminRole;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AgendaFilterTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/agenda';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(AdminRole::Administrator)->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function api_returns_filtered_agenda_items()
    {
        $agendaItemCategory = AgendaItemCategory::factory()->create();
        $agendaItem = AgendaItem::factory()->create();
        $agendaItem->agenda_item_category_id = $agendaItemCategory->id;
        $agendaItem->save();

        AgendaItem::factory()->create();
        AgendaItem::factory()->create();

        $response = $this->get($this->url.'?category='.$agendaItemCategory->id);
        $json = json_decode($response->getContent());

        $this->assertEquals($json->{'agendaItemCount'}, 1);
        $this->assertEquals($json->{'agendaItems'}[0]->{'category'}, $agendaItemCategory->name);
        $this->assertEquals($json->{'agendaItems'}[0]->{'title'}, $agendaItem->title);

        $response = $this->get($this->url.'?category='.($agendaItemCategory->id + 1));
        $json = json_decode($response->getContent());
        $this->assertEquals($json->{'agendaItemCount'}, 0);

        $response = $this->get($this->url);
        $json = json_decode($response->getContent());
        $this->assertEquals($json->{'agendaItemCount'}, 3);
    }
}
