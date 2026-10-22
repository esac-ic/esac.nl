<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'beheer/agenda-items';

    private $agendaItem;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(Role::ActivityAdministrator)->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function delete_agenda_item()
    {
        $agendaItem = AgendaItem::factory()->create();

        $response = $this->delete($this->url.'/'.$agendaItem->id);

        $response->assertStatus(302);

        $this->assertModelMissing($agendaItem);
    }
}
