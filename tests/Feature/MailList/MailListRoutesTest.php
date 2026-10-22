<?php

namespace Tests\Feature\MailList;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MailListRoutesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function mailing_lists_can_not_be_edited()
    {
        $admin = User::factory()->withRole(Role::Administrator)->create();

        $this->actingAs($admin)->get('beheer/mail-lists/leden.esac.nl/edit')->assertNotFound();
        $this->actingAs($admin)->patch('beheer/mail-lists/leden.esac.nl')->assertMethodNotAllowed();
    }

    #[Test]
    public function mailman_failures_are_shown_instead_of_a_server_error()
    {
        Http::fake(['*' => Http::response(['title' => 'List exists'], 400)]);
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::Administrator->value);

        $this->actingAs($admin)->from(route('beheer.mail-lists.create'))
            ->post(route('beheer.mail-lists.store'), ['address' => 'test'])
            ->assertRedirect(route('beheer.mail-lists.create'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->deleteJson(route('beheer.mail-lists.members.destroy', ['test.esac.nl', 'a@example.com']))
            ->assertStatus(502)
            ->assertJsonPath('message', fn (string $message) => str_starts_with($message, 'Mailman failed: '));
    }
}
