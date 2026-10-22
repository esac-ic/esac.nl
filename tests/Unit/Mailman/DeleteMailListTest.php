<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeleteMailListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.mailman.url', 'http://mailman.test/3.1');
    }

    public function test_delete_mail_list()
    {
        Http::fake(['http://mailman.test/3.1/lists/test.esac.nl' => Http::response()]);

        $this->app->make(Mailman::class)->deleteMailList("test.esac.nl");

        Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
            && $request->url() === 'http://mailman.test/3.1/lists/test.esac.nl');
    }

    public function test_delete_non_existent_mail_list()
    {
        Http::fake(['http://mailman.test/3.1/lists/test.esac.nl' => Http::response('{"title": "404 Not Found", "description": "404 Not Found"}', 404)]);

        //the service shouldn't handle exception
        $this->expectException(RequestException::class);
        $this->expectExceptionCode(404);

        $this->app->make(Mailman::class)->deleteMailList("test.esac.nl");
    }
}
