<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesMailman;
use Tests\TestCase;

class GetAllMailListsTest extends TestCase
{
    use FakesMailman;

    public function test_get_multiple_mail_lists()
    {
        $memberCounts = ['alle-leden' => 1, 'lid' => 5, 'nieuwsbrief' => 48, 'reunist' => 0];
        Http::fake([
            "$this->mailmanUrl/lists" => Http::response($this->collectionPayload(
                array_map($this->mailListPayload(...), array_keys($memberCounts), $memberCounts),
            )),
        ]);

        $mailLists = $this->app->make(Mailman::class)->getAllMailLists();

        $this->assertEquals([
            ['id' => 'alle-leden.esac.nl', 'address' => 'alle-leden@esac.nl', 'members_count' => 1, 'name' => 'Alle-leden', 'members' => []],
            ['id' => 'lid.esac.nl', 'address' => 'lid@esac.nl', 'members_count' => 5, 'name' => 'Lid', 'members' => []],
            ['id' => 'nieuwsbrief.esac.nl', 'address' => 'nieuwsbrief@esac.nl', 'members_count' => 48, 'name' => 'Nieuwsbrief', 'members' => []],
            ['id' => 'reunist.esac.nl', 'address' => 'reunist@esac.nl', 'members_count' => 0, 'name' => 'Reunist', 'members' => []],
        ], $mailLists->map->toArray()->all());
    }

    public function test_no_mail_lists()
    {
        Http::fake(["$this->mailmanUrl/lists" => Http::response($this->collectionPayload([]))]);

        $this->assertEquals(collect(), $this->app->make(Mailman::class)->getAllMailLists());
    }
}
