<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailman;
use Tests\TestCase;

class GetMailListTest extends TestCase
{
    use FakesMailman;

    public static function members(): array
    {
        return [
            'multiple members' => [[
                'agustinavandekeebler@yahoo.com' => 'Agustina van de Keebler',
                'aidenvandebergstrom@hotmail.com' => 'Aiden van de Bergstrom',
                'antonettahaley@hotmail.com' => 'Antonetta Haley',
                'camdendekovacek@gmail.com' => 'Camden de Kovacek',
                'darianavandeckow@gmail.com' => 'Dariana van Deckow',
            ]],
            'single member' => [['agustinavandekeebler@yahoo.com' => 'Agustina van de Keebler']],
            'no members' => [[]],
        ];
    }

    #[Test]
    #[DataProvider('members')]
    public function it_returns_the_mail_list_with_its_members(array $members)
    {
        Http::fake([
            "$this->mailmanUrl/lists/lid.esac.nl" => Http::response($this->mailListPayload('lid', count($members))),
            "$this->mailmanUrl/lists/lid.esac.nl/roster/member" => Http::response($this->collectionPayload(
                array_map($this->memberPayload(...), array_keys($members), $members),
            )),
        ]);

        $mailList = $this->app->make(Mailman::class)->getMailList('lid.esac.nl');

        $this->assertSame('lid.esac.nl', $mailList->id);
        $this->assertSame('lid@esac.nl', $mailList->address);
        $this->assertSame(count($members), $mailList->members_count);
        $this->assertSame('Lid', $mailList->name);
        $this->assertSame(
            array_map(fn ($address, $name) => ['address' => $address, 'name' => $name], array_keys($members), $members),
            array_map(fn ($member) => $member->toArray(), $mailList->members),
        );
    }

    public function test_non_existent_mail_list()
    {
        Http::fake(["$this->mailmanUrl/lists/nonexistent.esac.nl" => Http::response('Not Found', 404)]);

        $this->assertNull($this->app->make(Mailman::class)->getMailList('nonexistent.esac.nl'));
    }
}
