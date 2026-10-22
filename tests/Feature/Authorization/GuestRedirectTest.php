<?php

namespace Tests\Feature\Authorization;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GuestRedirectTest extends TestCase
{
    public static function protectedRoutes(): array
    {
        return [
            'management home' => ['beheer/home'],
            'application forms' => ['beheer/applicationForms'],
            'settings' => ['beheer/settings'],
            'roles' => ['rols'],
            'pages' => ['pages'],
            'certificates' => ['certificates'],
            'agenda items' => ['agendaItems'],
            'agenda item categories' => ['agendaItemCategories'],
            'news items' => ['newsItems'],
            'mail lists' => ['mailList'],
            'books' => ['books'],
            'users' => ['users'],
            'user event log' => ['userEventLog'],
        ];
    }

    #[Test]
    #[DataProvider('protectedRoutes')]
    public function a_guest_is_redirected_to_login(string $uri)
    {
        $this->get($uri)->assertRedirect('/login');
    }
}
