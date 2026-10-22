<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesMailman;
use Tests\TestCase;

class StoreMailListTest extends TestCase
{
    use FakesMailman;

    public function test_basic()
    {
        Http::fake(["$this->mailmanUrl/lists" => Http::response()]);

        $this->app->make(Mailman::class)->storeMailList(['id' => '', 'address' => 'test']);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === "$this->mailmanUrl/lists"
            && $request->data() === ['fqdn_listname' => 'test@esac.nl']);
    }

    public function test_already_existing_mail_list()
    {
        Http::fake(["$this->mailmanUrl/lists" => Http::response('{"title": "400 Bad Request", "description": "Mailing list exists"}', 400)]);

        $this->expectException(RequestException::class);
        $this->expectExceptionCode(400);

        $this->app->make(Mailman::class)->storeMailList(['id' => '', 'address' => 'duplicate']);
    }
}
