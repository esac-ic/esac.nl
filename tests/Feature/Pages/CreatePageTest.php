<?php

namespace Tests\Feature\Pages;

use App\MenuItem;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class CreatePageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = 'pages';

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

        $user->roles()->attach(Config::get('constants.Content_administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /**
     * @test whether an admin user can create a new page.
     */
    public function admin_can_create_page()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test titel',
            'content' => 'Hele leuke test inhoud.',
            '_token' => csrf_token(),
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

    public function admin_can_create_page_with_emoji()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test title 😀',
            'content' => 'Hele leuke test inhoud. 😀',
            '_token' => csrf_token(),
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

    /**
     * @test whether users without content admin role cannot create a new page.
     */
    public function no_role_no_create_page()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test titel',
            'content' => 'Very cool test content.',
            '_token' => csrf_token(),
        ];

        // Give the user all roles except for content admin.
        $this->user->roles()->sync([
            Config::get('constants.Administrator'),
            Config::get('constants.Activity_administrator'),
            Config::get('constants.Certificate_administrator'),
        ]);

        $response = $this->post($this->url, $body);

        // The user should not be able to create the page.
        $response->assertStatus(403);
        $this->assertTrue(MenuItem::count() == 0);
    }

    /**
     * @test whether the page content is set correctly when type SubItem.
     */
    public function create_page_subitem_content_correct()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);
        $response->assertSessionHasNoErrors();

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $this->assertEquals($menuItem->parent_id, $body['parentItem']);
        $this->assertEquals($menuItem->urlName, $body['urlName']);
        $this->assertEquals($menuItem->after, $body['afterItem']);
        $this->assertEquals($menuItem->name, $body['name']);
        $this->assertEquals($menuItem->content, $body['content']);
    }

    /**
     * @test whether the page content is set correctly when type Standalone.
     */
    public function create_page_standalone_content_correct()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'standAlone',
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);
        $response->assertSessionHasNoErrors();

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $this->assertNull($menuItem->parent_id);
        $this->assertEquals($menuItem->urlName, $body['urlName']);
        $this->assertEquals($menuItem->after, $body['afterItem']);
        $this->assertEquals($menuItem->name, $body['name']);
        $this->assertEquals($menuItem->content, $body['content']);
    }

    /**
     * @test whether the login required field gets set correctly.
     */
    public function create_page_login_correct()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertFalse((bool) $menuItem->login);

        $body['urlName'] = 'test2';
        $body['login'] = true;
        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $menuItem->login);
    }

    /**
     * @test whether the login required field gets set correctly.
     */
    public function create_page_no_afterItem_correct()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => -1,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertNull($menuItem->after);

        $body['urlName'] = 'test2';
        $body['login'] = true;
        $response = $this->post($this->url, $body);

        // Check whether the newly added page has the correct content.
        $menuItem = MenuItem::all()->last();

        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $menuItem->login);
    }

    /**
     * @test whether a redirect is returned and the session contains errors for each of the required fields when
     * fields are missing in the body.
     */
    public function empty_body_meaningful_error()
    {
        $body = [
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        // Must be redirected.
        $response->assertStatus(302);

        // Session must contain an error for each of the missing fields.
        $response->assertSessionHasErrors([
            'urlName',
            'itemType',
            'afterItem',
            'name',
            'content',
        ]);

        // No pages should be created.
        $this->assertTrue(MenuItem::count() == 0);
    }

    /**
     * @test whether a redirect is returned and the session contains error for parentItem when itemType is subItem.
     */
    public function empty_body_meaningful_error_subitem()
    {
        $body = [
            'urlName' => 'test',
            'itemType' => 'subItem',
            'afterItem' => 0,
            'name' => 'Test title',
            'content' => 'Very cool test content.',
            '_token' => csrf_token(),
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
