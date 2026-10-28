<?php

namespace Tests\Feature\Authorization;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GuestRedirectTest extends TestCase
{
    use RefreshDatabase;

    public static function protectedRoutes(): array
    {
        return [
            'admin dashboard' => ['admin'],
            'registration forms' => ['admin/registration-forms'],
            'settings' => ['admin/settings/edit'],
            'roles' => ['admin/roles'],
            'pages' => ['admin/pages'],
            'certificates' => ['admin/certificates'],
            'events' => ['admin/events'],
            'event categories' => ['admin/event-categories'],
            'news items' => ['admin/news-items'],
            'mail lists' => ['admin/mail-lists'],
            'books' => ['admin/books'],
            'members' => ['admin/members'],
            'member log' => ['admin/member-log'],
        ];
    }

    #[Test]
    #[DataProvider('protectedRoutes')]
    public function a_guest_is_redirected_to_login(string $uri)
    {
        $this->get($uri)->assertRedirect('/login');
    }
}
