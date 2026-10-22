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
            'name' => 'Parent', 'url_name' => 'parent', 'content' => 'Parent', 'login' => false,
            'menu_item' => true, 'deletable' => true, 'editable' => true,
        ]);
    }

    private function body(array $overrides = []): array
    {
        return array_merge([
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => $this->parent->id,
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
            'standalone' => [['itemType' => 'standAlone', 'parentItem' => null], false],
        ];
    }

    #[Test]
    #[DataProvider('validPages')]
    public function admin_can_create_page_with_correct_content(array $overrides, bool $isSubItem)
    {
        $body = $this->body($overrides);

        $this->post('admin/pages', $body)
            ->assertSessionHasNoErrors()
            ->assertRedirect('admin/pages');

        $this->assertDatabaseHas(MenuItem::class, [
            'parent_id' => $isSubItem ? $this->parent->id : null,
            'url_name' => $body['url_name'],
            'position' => 20,
            'name' => $body['name'],
            'content' => $body['content'],
        ]);
    }

    #[Test]
    public function an_edited_page_is_validated_when_its_url_name_is_unchanged()
    {
        $body = $this->body(['itemType' => 'standAlone']);
        $this->post('admin/pages', $body)->assertValid();
        $page = MenuItem::query()->where('url_name', 'test')->sole();

        $this->patch(route('admin.pages.update', $page), [...$body, 'name' => ''])->assertInvalid('name');
        $this->patch(route('admin.pages.update', $page), [...$body, 'name' => 'New title'])->assertValid();
        $this->assertSame('New title', $page->fresh()->name);
    }

    #[Test]
    public function no_role_no_create_page()
    {
        $this->actingAs(User::factory()->withRole(AdminRole::Administrator, AdminRole::ActivityAdministrator, AdminRole::CertificateAdministrator)->create());

        $this->post('admin/pages', $this->body())->assertForbidden();

        $this->assertDatabaseCount(MenuItem::class, 1);
    }

    #[Test]
    public function create_page_login_correct()
    {
        $this->post('admin/pages', $this->body())->assertSessionHasNoErrors();
        $this->post('admin/pages', $this->body(['url_name' => 'test2', 'login' => true]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas(MenuItem::class, ['url_name' => 'test', 'login' => false]);
        $this->assertDatabaseHas(MenuItem::class, ['url_name' => 'test2', 'login' => true]);
    }

    #[Test]
    public function the_menu_shows_the_pages_by_position()
    {
        $this->post('admin/pages', $this->body(['url_name' => 'second', 'name' => 'Second', 'itemType' => 'standAlone', 'position' => 20, 'menu_item' => true]));
        $this->post('admin/pages', $this->body(['url_name' => 'first', 'name' => 'First', 'itemType' => 'standAlone', 'position' => 5, 'menu_item' => true]));

        $this->get('second')->assertSeeInOrder(['navbar-nav', 'Parent', 'First', 'Second']);
    }

    #[Test]
    public function empty_body_meaningful_error()
    {
        $this->post('admin/pages')
            ->assertRedirect()
            ->assertInvalid(['url_name', 'itemType', 'position', 'name', 'content']);

        $this->assertDatabaseCount(MenuItem::class, 1);
    }

    #[Test]
    public function empty_body_meaningful_error_subitem()
    {
        $this->post('admin/pages', $this->body(['parentItem' => null]))
            ->assertRedirect()
            ->assertInvalid(['parentItem']);

        $this->assertDatabaseCount(MenuItem::class, 1);
    }
}
