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

    private $url = 'agendaItems';
    private $agendaItem;

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();
        $user->roles()->attach(Role::ActivityAdministrator->value);
        $this->be($user);

        session()->start();
    }

    #[Test]
    public function DeleteAgendaItem()
    {
        $agendaItem = AgendaItem::factory()->create();

        $response = $this->delete($this->url . '/' . $agendaItem->id);

        $response->assertStatus(302);

        $this->assertNull(AgendaItem::find($agendaItem->id));
    }
}
