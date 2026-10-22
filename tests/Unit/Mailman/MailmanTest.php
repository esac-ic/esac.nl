<?php

namespace Tests\Unit\Mailman;

use App\Services\Mailman;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MailmanTest extends TestCase
{
    #[Test]
    public function requests_work_without_configured_credentials()
    {
        Config::set('services.mailman.url', 'http://mailman.test/3.1');
        Config::set('services.mailman.username', null);
        Config::set('services.mailman.password', null);
        Http::fake(['mailman.test/*' => Http::response(['entries' => []])]);

        $this->assertSame([], $this->app->make(Mailman::class)->getAllMailListIds());
    }
}
