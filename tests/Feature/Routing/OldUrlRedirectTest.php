<?php

namespace Tests\Feature\Routing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OldUrlRedirectTest extends TestCase
{
    public static function oldUrls(): array
    {
        return [
            ['/agenda', '/events'],
            ['/agenda/12', '/events/12'],
            ['/memberlist', '/members'],
            ['/signup', '/sign-up'],
            ['/forms/12', '/events/12/registration'],
        ];
    }

    #[Test]
    #[DataProvider('oldUrls')]
    public function an_old_url_redirects_permanently(string $old, string $new)
    {
        $this->get($old)->assertStatus(301)->assertRedirect($new);
    }
}
