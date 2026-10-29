<?php

namespace Tests\Feature\MailLists;

use App\Enums\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MailListRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function mailman_failures_are_shown_instead_of_a_server_error()
    {
        Http::fake(['*' => Http::response(['title' => 'List exists'], 400)]);
        $admin = User::factory()->withRole(AdminRole::Administrator)->create();

        $this->actingAs($admin)->from(route('admin.mail-lists.create'))
            ->post(route('admin.mail-lists.store'), ['address' => 'test'])
            ->assertRedirect(route('admin.mail-lists.create'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->deleteJson(route('admin.mail-lists.members.destroy', ['test.esac.nl', 'a@example.com']))
            ->assertStatus(502)
            ->assertJsonPath('message', fn (string $message) => str_starts_with($message, 'Mailman failed: '));

        $this->actingAs($admin)
            ->get(route('admin.mail-lists.index'))
            ->assertStatus(502)
            ->assertSee('Mail lists unavailable');
    }
}
