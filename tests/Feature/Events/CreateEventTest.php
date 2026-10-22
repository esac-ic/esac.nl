<?php

namespace Tests\Feature\Events;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\RegistrationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsEventAdministrator(): void
    {
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());
    }

    private function body(array $overrides = []): array
    {
        return [
            'title' => 'test agenda',
            'text' => 'test agenda',
            'short_description' => 'test agenda',
            'event_category_id' => EventCategory::factory()->create()->id,
            'registration_form_id' => RegistrationForm::factory()->create()->id,
            'registration_deadline' => now()->addDays(2),
            'end_date' => now()->addDays(3),
            'start_date' => now()->addDays(1),
            ...$overrides,
        ];
    }

    public static function eventDescriptions(): array
    {
        return [
            'plain' => ['test agenda'],
            'emoji' => ['test agenda 😀'],
        ];
    }

    #[Test]
    #[DataProvider('eventDescriptions')]
    public function an_event_administrator_can_create_an_event(string $text)
    {
        $this->actingAsEventAdministrator();
        $body = $this->body(['title' => $text, 'text' => $text, 'short_description' => $text]);

        $this->post('admin/events', $body)->assertRedirect();

        $this->assertDatabaseHas(Event::class, [
            'title' => $text,
            'text' => $text,
            'event_category_id' => $body['event_category_id'],
        ]);
    }

    #[Test]
    public function creating_an_event_stores_a_resized_thumbnail()
    {
        Storage::fake('public');
        $this->actingAsEventAdministrator();

        $this->post('admin/events', $this->body([
            'registration_form_id' => null,
            'thumbnail' => UploadedFile::fake()->image('photo.png', 1000, 1000),
        ]))->assertRedirect();

        $this->assertSame([400, 300], array_slice(getimagesize(Storage::disk('public')->path(Event::sole()->image_path)), 0, 2));
    }

    #[Test]
    public function other_roles_can_not_create_an_event()
    {
        $this->actingAs(User::factory()->create());

        $this->post('admin/events', $this->body())->assertForbidden();
    }

    #[Test]
    public function an_event_needs_the_required_fields()
    {
        $this->actingAsEventAdministrator();

        $this->post('admin/events', $this->body(['title' => '']))->assertInvalid('title');

        $this->assertDatabaseEmpty(Event::class);
    }
}
