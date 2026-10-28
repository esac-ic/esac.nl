<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StoreMailListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.mailman.url', 'http://mailman.test/3.1');
    }

    public function testBasic()
    {
        Http::fake(['http://mailman.test/3.1/lists' => Http::response()]);

        $this->app->make(Mailman::class)->storeMailList(['id' => "", 'address' => "test"]);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === 'http://mailman.test/3.1/lists'
            && $request->data() === ['fqdn_listname' => "test@esac.nl"]);
    }

    public function test_already_existing_mail_list()
    {
        Http::fake(['http://mailman.test/3.1/lists' => Http::response('{"title": "400 Bad Request", "description": "Mailing list exists"}', 400)]);

        //the service shouldn't handle exception
        $this->expectException(RequestException::class);
        $this->expectExceptionCode(400);

        $this->app->make(Mailman::class)->storeMailList(['id' => "", 'address' => "duplicate"]);
    }
}
