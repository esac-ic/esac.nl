<?php

namespace Tests\Unit\Eboekhouden;

use App\CustomClasses\Eboekhouden\Eboekhouden;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use TestCase;
use function PHPUnit\Framework\assertEquals;

class SessionTokenTest extends TestCase {
    use RefreshDatabase;
    private $_baseurl = 'https://api.e-boekhouden.nl';

    protected function setUp(): void
    {
        parent::setup();

        $this->_baseurl = Config::get('eboekhouden.url') ?? '';
    }

    public function test_basic()
    {
        Http::preventStrayRequests();
        Http::fake([
            ($this->_baseurl . '/v1/session') => ['token' => "specialToken", 'expiresIn' => 10000],
            ($this->_baseurl . '/test-request') => [ 'some-header' => "ok" ],
        ]);

        $eboekhouden = $this->app->make(Eboekhouden::class);
        $response = $eboekhouden->get('test-request');

        assertEquals("ok", $response["some-header"]);

        // Session request
        Http::assertSent(function (Request $request) {
            return $request->url() == $this->_baseurl . '/v1/session' &&
                $request['accessToken'] == Config::get('eboekhouden.api_token') &&
                $request['source'] == Config::get('eboekhouden.source');
        });
        // Test request
        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Authorization', 'specialToken') &&
                $request->url() == $this->_baseurl . '/test-request';
        });

        Http::assertSentCount(2);
    }

    public function test_token_storage()
    {
        Http::preventStrayRequests();
        Http::fake([
            ($this->_baseurl . '/v1/session') => ['token' => "specialToken", 'expiresIn' => 10000],
            ($this->_baseurl . '/test-request') => [ 'some-header' => "ok" ],
        ]);

        $eboekhouden = $this->app->make(Eboekhouden::class);

        // Send first request (should retrieve a session token)
        $response = $eboekhouden->get('test-request');
        assertEquals("ok", $response["some-header"]);
        // Send second request (shouldn't retrieve a session token)
        $response = $eboekhouden->get('test-request');
        assertEquals("ok", $response["some-header"]);

        // Session request
        Http::assertSent(function (Request $request) {
            return $request->url() == $this->_baseurl . '/v1/session' &&
                $request['accessToken'] == Config::get('eboekhouden.api_token') &&
                $request['source'] == Config::get('eboekhouden.source');
        });
        // Test request
        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Authorization', 'specialToken') &&
                $request->url() == $this->_baseurl . '/test-request';
        });

        Http::assertSentCount(3);
    }

    public function test_token_expiration()
    {
        Http::preventStrayRequests();
        Http::fake([
            ($this->_baseurl . '/v1/session') => ['token' => "specialToken", 'expiresIn' => 0],
            ($this->_baseurl . '/test-request') => [ 'some-header' => "ok" ],
        ]);

        $eboekhouden = $this->app->make(Eboekhouden::class);

        // Send first request (should retrieve a session token)
        $response = $eboekhouden->get('test-request');
        assertEquals("ok", $response["some-header"]);
        // Send second request (should also retrieve a session token)
        $response = $eboekhouden->get('test-request');
        assertEquals("ok", $response["some-header"]);

        // Session request
        Http::assertSent(function (Request $request) {
            return $request->url() == $this->_baseurl . '/v1/session' &&
                $request['accessToken'] == Config::get('eboekhouden.api_token') &&
                $request['source'] == Config::get('eboekhouden.source');
        });
        // Test request
        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Authorization', 'specialToken') &&
                $request->url() == $this->_baseurl . '/test-request';
        });

        Http::assertSentCount(4);
    }

}