<?php

namespace Tests\Unit;

use App\Services\Mailman;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailman;
use Tests\TestCase;

class MailmanTest extends TestCase
{
    use FakesMailman;

    private function mailman(): Mailman
    {
        return $this->app->make(Mailman::class);
    }

    #[Test]
    public function it_returns_all_mail_lists()
    {
        $memberCounts = ['alle-leden' => 1, 'lid' => 5, 'nieuwsbrief' => 48, 'reunist' => 0];
        Http::fake([
            "$this->mailmanUrl/lists" => Http::response($this->collectionPayload(
                array_map($this->mailListPayload(...), array_keys($memberCounts), $memberCounts),
            )),
        ]);

        $this->assertEquals([
            ['id' => 'alle-leden.esac.nl', 'address' => 'alle-leden@esac.nl', 'members_count' => 1, 'name' => 'Alle-leden', 'members' => []],
            ['id' => 'lid.esac.nl', 'address' => 'lid@esac.nl', 'members_count' => 5, 'name' => 'Lid', 'members' => []],
            ['id' => 'nieuwsbrief.esac.nl', 'address' => 'nieuwsbrief@esac.nl', 'members_count' => 48, 'name' => 'Nieuwsbrief', 'members' => []],
            ['id' => 'reunist.esac.nl', 'address' => 'reunist@esac.nl', 'members_count' => 0, 'name' => 'Reunist', 'members' => []],
        ], $this->mailman()->mailLists()->map->toArray()->all());
    }

    public static function mailLists(): array
    {
        return [
            'no mail lists' => [[]],
            'single mail list' => [['alle-leden']],
            'multiple mail lists' => [['alle-leden', 'lid', 'reunist']],
        ];
    }

    #[Test]
    #[DataProvider('mailLists')]
    public function it_returns_the_ids_of_all_mail_lists(array $names)
    {
        Http::fake([
            "$this->mailmanUrl/lists" => Http::response($this->collectionPayload(array_map(fn ($name) => $this->mailListPayload($name), $names))),
        ]);

        $this->assertSame(array_map(fn ($name) => "$name.esac.nl", $names), $this->mailman()->mailListIds());
    }

    public static function members(): array
    {
        return [
            'multiple members' => [[
                'agustinavandekeebler@yahoo.com' => 'Agustina van de Keebler',
                'aidenvandebergstrom@hotmail.com' => 'Aiden van de Bergstrom',
                'antonettahaley@hotmail.com' => 'Antonetta Haley',
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

        $mailList = $this->mailman()->mailList('lid.esac.nl');

        $this->assertSame(['lid.esac.nl', 'lid@esac.nl', count($members), 'Lid'], [$mailList->id, $mailList->address, $mailList->members_count, $mailList->name]);
        $this->assertSame(
            array_map(fn ($address, $name) => ['address' => $address, 'name' => $name], array_keys($members), $members),
            array_map(fn ($member) => $member->toArray(), $mailList->members),
        );
    }

    #[Test]
    public function it_stores_and_deletes_a_mail_list()
    {
        Http::fake(["$this->mailmanUrl/lists*" => Http::response()]);

        $this->mailman()->createMailList('test');
        $this->mailman()->deleteMailList('test.esac.nl');

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === "$this->mailmanUrl/lists"
            && $request->data() === ['fqdn_listname' => 'test@esac.nl']);
        Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
            && $request->url() === "$this->mailmanUrl/lists/test.esac.nl");
    }

    #[Test]
    public function errors_reach_the_caller()
    {
        Http::fake(["$this->mailmanUrl/lists" => Http::response('{"title": "400 Bad Request", "description": "Mailing list exists"}', 400)]);

        $this->expectException(RequestException::class);
        $this->expectExceptionCode(400);

        $this->mailman()->createMailList('duplicate');
    }

    #[Test]
    public function adding_an_existing_member_and_removing_a_missing_one_are_fine()
    {
        Http::fake([
            "$this->mailmanUrl/members" => Http::response('{"title": "409 Conflict", "description": "Member already subscribed"}', 409),
            "$this->mailmanUrl/lists/lid.esac.nl/member/*" => Http::response('Not Found', 404),
        ]);

        $this->mailman()->addMember('lid.esac.nl', 'member@esac.nl', 'Member');
        $this->mailman()->removeMember('lid.esac.nl', 'member@esac.nl');

        Http::assertSentCount(2);
    }

    #[Test]
    public function adding_and_removing_a_member_and_finding_their_mail_lists_succeed()
    {
        Http::fake([
            "$this->mailmanUrl/members" => Http::response(status: 201),
            "$this->mailmanUrl/lists/lid.esac.nl/member/*" => Http::response(status: 204),
            "$this->mailmanUrl/lists/find" => Http::response($this->collectionPayload([$this->mailListPayload('lid')])),
        ]);

        $this->mailman()->addMember('lid.esac.nl', 'member@esac.nl', 'Member');
        $this->mailman()->removeMember('lid.esac.nl', 'member@esac.nl');

        $this->assertSame(['lid.esac.nl'], $this->mailman()->mailListsOf('member@esac.nl')->pluck('id')->all());
    }
}
