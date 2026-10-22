<?php

namespace Tests\Feature\Pages;

use App\Enums\Role;
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
        $this->actingAs(User::factory()->withRole(Role::ContentAdministrator)->create());
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
            'afterItem' => $this->parent->id,
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

        $this->post('pages', $body)
            ->assertSessionHasNoErrors()
            ->assertRedirect('/pages');

        $this->assertDatabaseHas(MenuItem::class, [
            'parent_id' => $isSubItem ? $this->parent->id : null,
            'url_name' => $body['url_name'],
            'after' => $body['afterItem'],
            'name' => $body['name'],
            'content' => $body['content'],
        ]);
    }

    #[Test]
    public function an_edited_page_is_validated_when_its_url_name_is_unchanged()
    {
        $body = $this->body(['itemType' => 'standAlone', 'afterItem' => -1]);
        $this->post('pages', $body)->assertValid();
        $page = MenuItem::query()->where('url_name', 'test')->sole();

        $this->patch(route('pages.update', $page), [...$body, 'name' => ''])->assertInvalid('name');
        $this->patch(route('pages.update', $page), [...$body, 'name' => 'New title'])->assertValid();
        $this->assertSame('New title', $page->fresh()->name);
    }

    #[Test]
    public function no_role_no_create_page()
    {
        $this->actingAs(User::factory()->withRole(Role::Administrator, Role::ActivityAdministrator, Role::CertificateAdministrator)->create());

        $this->post('pages', $this->body())->assertForbidden();

        $this->assertDatabaseCount(MenuItem::class, 1);
    }

    #[Test]
    public function create_page_login_correct()
    {
        $this->post('pages', $this->body())->assertSessionHasNoErrors();
        $this->post('pages', $this->body(['url_name' => 'test2', 'login' => true]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas(MenuItem::class, ['url_name' => 'test', 'login' => false]);
        $this->assertDatabaseHas(MenuItem::class, ['url_name' => 'test2', 'login' => true]);
    }

    #[Test]
    public function create_page_no_after_item_correct()
    {
        $this->post('pages', $this->body(['afterItem' => -1]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas(MenuItem::class, ['url_name' => 'test', 'after' => null]);
    }

    #[Test]
    public function empty_body_meaningful_error()
    {
        $this->post('pages')
            ->assertRedirect()
            ->assertInvalid(['url_name', 'itemType', 'afterItem', 'name', 'content']);

        $this->assertDatabaseCount(MenuItem::class, 1);
    }

    #[Test]
    public function empty_body_meaningful_error_subitem()
    {
        $this->post('pages', $this->body(['parentItem' => null]))
            ->assertRedirect()
            ->assertInvalid(['parentItem']);

        $this->assertDatabaseCount(MenuItem::class, 1);
    }
}
