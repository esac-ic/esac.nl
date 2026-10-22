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

        $this->admin = User::factory()->withRole(Role::Administrator, Role::CertificateAdministrator)->create();
    }

    #[Test]
    public function books_can_be_managed()
    {
        $book = ['title' => 'Alpine climbing', 'year' => 1990, 'type' => 'Guide', 'country' => 'CH', 'code' => 'A1'];

        $this->actingAs($this->admin)->post('beheer/books', $book)->assertRedirect('beheer/books');
        $created = Book::sole();
        $this->assertSame('Alpine climbing', $created->title);

        $this->get('beheer/books')->assertOk()->assertSee('Alpine climbing');
        $this->patch("beheer/books/{$created->id}", ['title' => 'Ice climbing'] + $book)->assertRedirect('beheer/books');
        $this->assertSame('Ice climbing', $created->fresh()->title);

        $this->delete("beheer/books/{$created->id}")->assertRedirect('beheer/books');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function certificates_can_be_managed()
    {
        $this->actingAs($this->admin)->post('beheer/certificates', ['name' => 'Toprope', 'abbreviation' => 'TR'])->assertRedirect('beheer/certificates');
        $created = Certificate::sole();

        $this->get('beheer/certificates')->assertOk()->assertSee('Toprope');
        $this->patch("beheer/certificates/{$created->id}", ['name' => 'Lead', 'abbreviation' => 'L'])->assertRedirect('beheer/certificates');
        $this->assertSame('Lead', $created->fresh()->name);

        $this->delete("beheer/certificates/{$created->id}")->assertRedirect('beheer/certificates');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function agenda_item_categories_can_be_managed()
    {
        $this->actingAs($this->admin)->post('beheer/agenda-item-categories', ['name' => 'Weekend'])->assertRedirect('beheer/agenda-item-categories');
        $created = AgendaItemCategory::sole();

        $this->get('beheer/agenda-item-categories')->assertOk()->assertSee('Weekend');
        $this->patch("beheer/agenda-item-categories/{$created->id}", ['name' => 'Trip'])->assertRedirect('beheer/agenda-item-categories');
        $this->assertSame('Trip', $created->fresh()->name);

        $this->delete("beheer/agenda-item-categories/{$created->id}")->assertRedirect('beheer/agenda-item-categories');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function a_members_certificates_are_scoped_to_that_member()
    {
        $member = User::factory()->create();
        [$held, $other] = [Certificate::create(['name' => 'Toprope', 'abbreviation' => 'TR']), Certificate::create(['name' => 'Lead', 'abbreviation' => 'L'])];
        $member->certificates()->attach($held);

        $this->actingAs($this->admin)->get(route('beheer.users.certificates.edit', [$member, $held]))->assertOk();
        $this->get(route('beheer.users.certificates.edit', [$member, $other]))->assertNotFound();
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
