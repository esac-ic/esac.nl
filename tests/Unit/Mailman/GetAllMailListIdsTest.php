<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\FakesMailman;
use Tests\TestCase;

class GetAllMailListIdsTest extends TestCase
{
    use FakesMailman;

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

        $this->assertSame(
            array_map(fn ($name) => "$name.esac.nl", $names),
            $this->app->make(Mailman::class)->getAllMailListIds(),
        );
    }
}
