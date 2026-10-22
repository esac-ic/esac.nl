<?php

namespace Tests\Feature\Admin;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LegacyAdminRedirectTest extends TestCase
{
    public static function oldUrls(): array
    {
        return [
            'dashboard' => ['/beheer/home', '/admin/home'],
            'prefix only' => ['/beheer', '/admin/home'],
            'query' => ['/beheer/pages?page=2', '/admin/pages?page=2'],
            'renamed page' => ['/beheer/agendaItems/5/edit', '/admin/events/5/edit'],
            'renamed sub page' => ['/beheer/users/old_members', '/admin/former-members'],
            'member' => ['/beheer/users/7', '/admin/members/7'],
            'similar name' => ['/beheer/usersx', '/admin/usersx'],
        ];
    }

    #[Test]
    #[DataProvider('oldUrls')]
    public function an_old_admin_url_redirects_permanently_to_its_new_url(string $old, string $new): void
    {
        $this->get($old)->assertStatus(301)->assertRedirect($new);
    }
}
