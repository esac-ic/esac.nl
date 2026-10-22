<?php

namespace Tests\Feature\Pages;

use App\Enums\Role;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreatePageTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'pages';

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::ContentAdministrator->value);
        $this->be($user);
    }

    #[Test]
    public function admin_can_create_page()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test titel',
            'content' => 'Hele leuke test inhoud.',
        ];

        $response = $this->post($this->url, $body);

        // After creation the user should be redirect to /pages.
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pages');

        // Check whether a new page has been added to DB, other test will make sure content is actually correct.
        $menuItem = MenuItem::all()->last();

        $this->assertEquals($body['content'], $menuItem->content);
    }

    #[Test]
    public function admin_can_create_page_with_emoji()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test title 😀',
            'content' => 'Hele leuke test inhoud. 😀',
        ];

        $response = $this->post($this->url, $body);

        // After creation the user should be redirect to /pages.
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pages');

        // Check whether a new page has been added to DB, other test will make sure content is actually correct.
        $menuItem = MenuItem::all()->last();

        $this->assertEquals($body['content'], $menuItem->content);
    }

    #[Test]
    public function an_edited_page_is_validated_when_its_url_name_is_unchanged()
    {
        $body = ['url_name' => 'test', 'itemType' => 'standAlone', 'afterItem' => -1, 'name' => 'Test title', 'content' => 'Content'];
        $this->post($this->url, $body)->assertSessionHasNoErrors();
        $page = MenuItem::sole();

        $this->patch(route('pages.update', $page), [...$body, 'name' => ''])->assertSessionHasErrors('name');
        $this->patch(route('pages.update', $page), [...$body, 'name' => 'New title'])->assertSessionHasNoErrors();
        $this->assertSame('New title', $page->fresh()->name);
    }

    #[Test]
    public function no_role_no_create_page()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test titel',
            'content' => 'Very cool test content.',
        ];

        // Give the user all roles except for content admin.
        $this->user->roles()->sync([
            Role::Administrator->value,
            Role::ActivityAdministrator->value,
            Role::CertificateAdministrator->value,
        ]);

        $response = $this->post($this->url, $body);

        // The user should not be able to create the page.
        $response->assertStatus(403);
        $this->assertTrue(MenuItem::count() == 0);
    }

    #[Test]
    public function create_page_subitem_content_correct()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
        ];

        $response = $this->post($this->url, $body);
        $response->assertSessionHasNoErrors();

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $this->assertEquals($menuItem->parent_id, $body['parentItem']);
        $this->assertEquals($menuItem->url_name, $body['url_name']);
        $this->assertEquals($menuItem->after, $body['afterItem']);
        $this->assertEquals($menuItem->name, $body['name']);
        $this->assertEquals($menuItem->content, $body['content']);
    }

    #[Test]
    public function create_page_standalone_content_correct()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'standAlone',
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
        ];

        $response = $this->post($this->url, $body);
        $response->assertSessionHasNoErrors();

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $this->assertNull($menuItem->parent_id);
        $this->assertEquals($menuItem->url_name, $body['url_name']);
        $this->assertEquals($menuItem->after, $body['afterItem']);
        $this->assertEquals($menuItem->name, $body['name']);
        $this->assertEquals($menuItem->content, $body['content']);
    }

    #[Test]
    public function create_page_login_correct()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
        ];

        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertFalse((bool) $menuItem->login);

        $body['url_name'] = 'test2';
        $body['login'] = true;
        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $menuItem->login);
    }

    #[Test]
    public function create_page_no_after_item_correct()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => -1,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
        ];

        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertNull($menuItem->after);

        $body['url_name'] = 'test2';
        $body['login'] = true;
        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $menuItem->login);
    }

    #[Test]
    public function empty_body_meaningful_error()
    {
        $body = [
        ];

        $response = $this->post($this->url, $body);

        // Must be redirected.
        $response->assertStatus(302);

        // Session must contain an error for each of the missing fields.
        $response->assertSessionHasErrors([
            'url_name',
            'itemType',
            'afterItem',
            'name',
            'content',
        ]);

        // No pages should be created.
        $this->assertTrue(MenuItem::count() == 0);
    }

    #[Test]
    public function empty_body_meaningful_error_subitem()
    {
        $body = [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
        ];

        $response = $this->post($this->url, $body);

        // Must be redirected.
        $response->assertStatus(302);

        // Session must contain an error for each of the missing fields.
        $response->assertSessionHasErrors([
            'parentItem',
        ]);

        // No pages should be created.
        $this->assertTrue(MenuItem::count() == 0);
    }
}
