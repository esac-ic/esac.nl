<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AgendaFilterTest extends TestCase
{
    use RefreshDatabase;

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
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::Administrator->value);
        $this->be($user);

        session()->start();
    }

    #[Test]
    public function api_returns_filtered_agenda_items()
    {
        $agendaItemCategory = AgendaItemCategory::factory()->create();
        $agendaItem = AgendaItem::factory()->create();
        $agendaItem->category = $agendaItemCategory->id;
        $agendaItem->save();

        AgendaItem::factory()->create();
        AgendaItem::factory()->create();

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
