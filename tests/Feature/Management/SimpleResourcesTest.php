<?php

namespace Tests\Feature\Management;

use App\Enums\Role;
use App\Models\AgendaItemCategory;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\User;
use Database\Seeders\MenuItemTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SimpleResourcesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach([Role::Administrator->value, Role::CertificateAdministrator->value]);
    }

    #[Test]
    public function books_can_be_managed()
    {
        $book = ['title' => 'Alpine climbing', 'year' => 1990, 'type' => 'Guide', 'country' => 'CH', 'code' => 'A1'];

        $this->actingAs($this->admin)->post('books', $book)->assertRedirect('books');
        $created = Book::sole();
        $this->assertSame('Alpine climbing', $created->title);

        $this->get('books')->assertOk()->assertSee('Alpine climbing');
        $this->patch("books/{$created->id}", ['title' => 'Ice climbing'] + $book)->assertRedirect('books');
        $this->assertSame('Ice climbing', $created->fresh()->title);

        $this->delete("books/{$created->id}")->assertRedirect('books');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function certificates_can_be_managed()
    {
        $this->actingAs($this->admin)->post('certificates', ['name' => 'Toprope', 'abbreviation' => 'TR'])->assertRedirect('certificates');
        $created = Certificate::sole();

        $this->get('certificates')->assertOk()->assertSee('Toprope');
        $this->patch("certificates/{$created->id}", ['name' => 'Lead', 'abbreviation' => 'L'])->assertRedirect('certificates');
        $this->assertSame('Lead', $created->fresh()->name);

        $this->delete("certificates/{$created->id}")->assertRedirect('certificates');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function agenda_item_categories_can_be_managed()
    {
        $this->actingAs($this->admin)->post('agendaItemCategories', ['name' => 'Weekend'])->assertRedirect('agendaItemCategories');
        $created = AgendaItemCategory::sole();

        $this->get('agendaItemCategories')->assertOk()->assertSee('Weekend');
        $this->patch("agendaItemCategories/{$created->id}", ['name' => 'Trip'])->assertRedirect('agendaItemCategories');
        $this->assertSame('Trip', $created->fresh()->name);

        $this->delete("agendaItemCategories/{$created->id}")->assertRedirect('agendaItemCategories');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function the_library_and_front_pages_list_their_content()
    {
        $this->seed(MenuItemTableSeeder::class);
        Book::create(['title' => 'Alpine climbing', 'year' => 1990, 'type' => 'Guide', 'country' => 'CH', 'code' => 'A1']);

        $this->actingAs($this->admin)->get('library')->assertOk()->assertSee('Alpine climbing');
        $this->get('home')->assertOk();
        $this->get('agenda')->assertOk();
    }
}
