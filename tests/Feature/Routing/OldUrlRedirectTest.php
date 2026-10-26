<?php

namespace Tests\Feature\Routing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OldUrlRedirectTest extends TestCase
{
    use RefreshDatabase;

    public static function oldUrls(): array
    {
        return [
            ['/agenda', '/events'],
            ['/agenda/12', '/events/12'],
            ['/memberlist', '/members'],
            ['/signup', '/sign-up'],
            ['/forms/12', '/events/12/registration'],
            ['/password/reset', '/forgot-password'],
            ['/password/reset/abc', '/reset-password/abc'],
        ];
    }

    #[Test]
    #[DataProvider('oldUrls')]
    public function an_old_url_redirects_permanently(string $old, string $new)
    {
        $this->get($old)->assertStatus(301)->assertRedirect($new);
    }

    #[Test]
    public function the_old_home_url_shows_the_home_page()
    {
        $this->get('/home')->assertOk();
    }
}
