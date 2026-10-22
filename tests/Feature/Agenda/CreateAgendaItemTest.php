<?php

namespace Tests\Feature\Agenda;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateAgendaItemTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsActivityAdministrator(): void
    {
        $this->actingAs(User::factory()->withRole(Role::ActivityAdministrator)->create());
    }

    private function body(array $overrides = []): array
    {
        return [
            'title' => 'test agenda',
            'text' => 'test agenda',
            'short_description' => 'test agenda',
            'category' => AgendaItemCategory::factory()->create()->id,
            'applicationForm' => ApplicationForm::factory()->create()->id,
            'subscription_end_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
            'start_date' => now()->addDays(1),
            ...$overrides,
        ];
    }

    public static function texts(): array
    {
        return [
            'plain' => ['test agenda'],
            'emoji' => ['test agenda 😀'],
        ];
    }

    #[Test]
    #[DataProvider('texts')]
    public function create_agenda_item(string $text)
    {
        $this->actingAsActivityAdministrator();
        $body = $this->body(['title' => $text, 'text' => $text, 'short_description' => $text]);

        $this->post('agendaItems', $body)->assertRedirect();

        $this->assertDatabaseHas(AgendaItem::class, [
            'title' => $text,
            'text' => $text,
            'category' => $body['category'],
        ]);
    }

    #[Test]
    public function creating_an_agenda_item_stores_a_resized_thumbnail()
    {
        Storage::fake('public');
        $this->actingAsActivityAdministrator();

        $this->post('agendaItems', $this->body([
            'applicationForm' => null,
            'thumbnail' => UploadedFile::fake()->image('photo.png', 1000, 1000),
        ]))->assertRedirect();

        $this->assertSame([400, 300], array_slice(getimagesize(Storage::disk('public')->path(AgendaItem::sole()->image_url)), 0, 2));
    }

    #[Test]
    public function create_agenda_with_incorrect_role()
    {
        $this->actingAs(User::factory()->create());

        $this->post('agendaItems', $this->body())->assertForbidden();
    }

    #[Test]
    public function create_agenda_with_empty_fields()
    {
        $this->actingAsActivityAdministrator();

        $this->post('agendaItems', $this->body(['title' => '']))->assertInvalid('title');

        $this->assertDatabaseEmpty(AgendaItem::class);
    }
}
