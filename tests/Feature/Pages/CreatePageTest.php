<?php

namespace Tests\Feature\Pages;

use App\Enums\AdminRole;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreatePageTest extends TestCase
{
    use RefreshDatabase;

    private Page $parent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->withRole(AdminRole::ContentAdministrator)->create());
        $this->parent = Page::query()->forceCreate([
            'name' => 'Parent', 'slug' => 'parent', 'body' => 'Parent', 'requires_login' => false,
            'show_in_menu' => true, 'deletable' => true, 'editable' => true,
        ]);
    }

    private function body(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'test',
            'parent_id' => $this->parent->id,
            'name' => 'Test title',
            'body' => 'Very cool test content.',
        ], $overrides);
    }

    public static function validPages(): array
    {
        return [
            'sub item' => [[], true],
            'sub item with emoji' => [['name' => 'Test title 😀', 'body' => 'Hele leuke test inhoud. 😀'], true],
            'top-level page' => [['parent_id' => null], false],
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

        $this->assertDatabaseHas(Page::class, [
            'parent_id' => $isSubItem ? $this->parent->id : null,
            'slug' => $body['slug'],
            'name' => $body['name'],
            'body' => $body['body'],
        ]);
    }

    #[Test]
    public function an_edited_page_is_validated_when_its_slug_is_unchanged()
    {
        $body = $this->body(['parent_id' => null]);
        $this->post('admin/pages', $body)->assertValid();
        $page = Page::query()->where('slug', 'test')->sole();

        $this->patch(route('admin.pages.update', $page), [...$body, 'name' => ''])->assertInvalid('name');
        $this->patch(route('admin.pages.update', $page), [...$body, 'name' => 'New title'])->assertValid();
        $this->assertSame('New title', $page->fresh()->name);
    }

    #[Test]
    public function a_page_with_an_older_slug_can_be_saved_without_renaming_it()
    {
        $this->post('admin/pages', $this->body(['parent_id' => null]))->assertValid();
        $page = Page::query()->where('slug', 'test')->sole();
        $page->forceFill(['slug' => 'Huisregels'])->save();

        $this->patch(route('admin.pages.update', $page), $this->body(['slug' => 'Huisregels', 'parent_id' => null]))->assertValid();
        $this->patch(route('admin.pages.update', $page), $this->body(['slug' => 'Huis.regels', 'parent_id' => null]))->assertInvalid('slug');
    }

    #[Test]
    public function other_roles_can_not_create_a_page()
    {
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator, AdminRole::CertificateAdministrator)->create());

        $this->post('admin/pages', $this->body())->assertForbidden();

        $this->assertDatabaseCount(Page::class, 1);
    }

    #[Test]
    public function a_page_can_require_a_login()
    {
        $this->post('admin/pages', $this->body())->assertSessionHasNoErrors();
        $this->post('admin/pages', $this->body(['slug' => 'test2', 'requires_login' => true]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas(Page::class, ['slug' => 'test', 'requires_login' => false]);
        $this->assertDatabaseHas(Page::class, ['slug' => 'test2', 'requires_login' => true]);
    }

    #[Test]
    public function a_new_page_goes_last_in_the_menu_and_can_be_moved_up()
    {
        $this->post('admin/pages', $this->body(['slug' => 'first', 'name' => 'First', 'parent_id' => null, 'show_in_menu' => true]));
        $this->post('admin/pages', $this->body(['slug' => 'second', 'name' => 'Second', 'parent_id' => null, 'show_in_menu' => true]));
        $this->get('second')->assertSeeInOrder(['navbar-nav', 'Parent', 'First', 'Second']);

        $this->patch(route('admin.pages.move', Page::query()->where('slug', 'second')->sole()), ['direction' => 'up']);

        $this->get('second')->assertSeeInOrder(['navbar-nav', 'Parent', 'Second', 'First']);
    }

    #[Test]
    public function a_page_needs_the_required_fields()
    {
        $this->post('admin/pages')
            ->assertRedirect()
            ->assertInvalid(['slug', 'name', 'body']);

        $this->assertDatabaseCount(Page::class, 1);
    }

    #[Test]
    public function a_page_can_not_take_a_reserved_or_malformed_slug()
    {
        foreach (['events', 'agenda', 'admin', 'Test', 'a/b'] as $slug) {
            $this->post('admin/pages', $this->body(['slug' => $slug]))->assertInvalid('slug');
        }

        $this->assertDatabaseCount(Page::class, 1);
    }
}
