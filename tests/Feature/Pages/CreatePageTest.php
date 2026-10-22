<?php

namespace Tests\Feature\Pages;

use App\Enums\AdminRole;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreatePageTest extends TestCase
{
    use RefreshDatabase;

    private MenuItem $parent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->withRole(AdminRole::ContentAdministrator)->create());
        $this->parent = MenuItem::query()->forceCreate([
            'name' => 'Parent', 'slug' => 'parent', 'content' => 'Parent', 'requires_login' => false,
            'show_in_menu' => true, 'deletable' => true, 'editable' => true,
        ]);
    }

    private function body(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'test',
            'menu_level' => 'child',
            'parent_id' => $this->parent->id,
            'position' => 20,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
        ], $overrides);
    }

    public static function validPages(): array
    {
        return [
            'sub item' => [[], true],
            'sub item with emoji' => [['name' => 'Test title 😀', 'content' => 'Hele leuke test inhoud. 😀'], true],
            'standalone' => [['menu_level' => 'top', 'parent_id' => null], false],
        ];
    }

    #[Test]
    #[DataProvider('validPages')]
    public function a_content_administrator_can_create_a_page(array $overrides, bool $isSubItem)
    {
        $body = $this->body($overrides);

        $this->post('admin/pages', $body)
            ->assertSessionHasNoErrors()
            ->assertRedirect('admin/pages');

        $this->assertDatabaseHas(MenuItem::class, [
            'parent_id' => $isSubItem ? $this->parent->id : null,
            'slug' => $body['slug'],
            'position' => 20,
            'name' => $body['name'],
            'content' => $body['content'],
        ]);
    }

    #[Test]
    public function an_edited_page_is_validated_when_its_slug_is_unchanged()
    {
        $body = $this->body(['menu_level' => 'top']);
        $this->post('admin/pages', $body)->assertValid();
        $page = MenuItem::query()->where('slug', 'test')->sole();

        $this->patch(route('admin.pages.update', $page), [...$body, 'name' => ''])->assertInvalid('name');
        $this->patch(route('admin.pages.update', $page), [...$body, 'name' => 'New title'])->assertValid();
        $this->assertSame('New title', $page->fresh()->name);
    }

    #[Test]
    public function other_roles_can_not_create_a_page()
    {
        $this->actingAs(User::factory()->withRole(AdminRole::Administrator, AdminRole::EventAdministrator, AdminRole::CertificateAdministrator)->create());

        $this->post('admin/pages', $this->body())->assertForbidden();

        $this->assertDatabaseCount(MenuItem::class, 1);
    }

    #[Test]
    public function a_page_can_require_a_login()
    {
        $this->post('admin/pages', $this->body())->assertSessionHasNoErrors();
        $this->post('admin/pages', $this->body(['slug' => 'test2', 'requires_login' => true]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas(MenuItem::class, ['slug' => 'test', 'requires_login' => false]);
        $this->assertDatabaseHas(MenuItem::class, ['slug' => 'test2', 'requires_login' => true]);
    }

    #[Test]
    public function the_menu_shows_the_pages_by_position()
    {
        $this->post('admin/pages', $this->body(['slug' => 'second', 'name' => 'Second', 'menu_level' => 'top', 'position' => 20, 'show_in_menu' => true]));
        $this->post('admin/pages', $this->body(['slug' => 'first', 'name' => 'First', 'menu_level' => 'top', 'position' => 5, 'show_in_menu' => true]));

        $this->get('second')->assertSeeInOrder(['navbar-nav', 'Parent', 'First', 'Second']);
    }

    #[Test]
    public function a_page_needs_the_required_fields()
    {
        $this->post('admin/pages')
            ->assertRedirect()
            ->assertInvalid(['slug', 'menu_level', 'position', 'name', 'content']);

        $this->assertDatabaseCount(MenuItem::class, 1);
    }

    #[Test]
    public function a_child_page_needs_a_parent()
    {
        $this->post('admin/pages', $this->body(['parent_id' => null]))
            ->assertRedirect()
            ->assertInvalid(['parent_id']);

        $this->assertDatabaseCount(MenuItem::class, 1);
    }
}
