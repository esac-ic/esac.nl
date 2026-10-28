<?php

namespace Tests\Feature\Views;

use App\Enums\AdminRole;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\RegistrationForm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Admin Form pages
 */
class AdminFormPagesTest extends TestCase
{
    use InspectsForms, RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withRole(AdminRole::Administrator, AdminRole::ContentAdministrator)->create();

        $this->be($this->user);
    }

    #[Test]
    public function the_book_forms_render()
    {
        $this->assertForm($this->get('admin/books/create')->assertOk(), 'POST', 'admin/books');

        $book = Book::create(['title' => 'Klimgids', 'year' => 1999, 'country' => 'NL', 'type' => 'Gids', 'code' => 'K1']);
        $response = $this->get("admin/books/$book->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/books/$book->id");
        $this->assertFieldValue($response, 'title', 'Klimgids');
        $this->assertFieldValue($response, 'year', '1999');
        $this->assertFieldValue($response, 'code', 'K1');
    }

    #[Test]
    public function the_certificate_forms_render()
    {
        $this->assertForm($this->get('admin/certificates/create')->assertOk(), 'POST', 'admin/certificates');

        $certificate = Certificate::create(['name' => 'Toprope', 'abbreviation' => 'TR']);
        $response = $this->get("admin/certificates/$certificate->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/certificates/$certificate->id");
        $this->assertFieldValue($response, 'name', 'Toprope');
        $this->assertFieldValue($response, 'abbreviation', 'TR');
    }

    #[Test]
    public function the_role_forms_render()
    {
        $this->assertForm($this->get('admin/roles/create')->assertOk(), 'POST', 'admin/roles');

        $role = Role::factory()->create(['name' => 'Penningmeester']);
        $response = $this->get("admin/roles/$role->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/roles/$role->id");
        $this->assertFieldValue($response, 'name', 'Penningmeester');
    }

    #[Test]
    public function the_event_category_forms_render()
    {
        $this->assertForm($this->get('admin/event-categories/create')->assertOk(), 'POST', 'admin/event-categories');

        $category = EventCategory::factory()->create(['name' => 'Training']);
        $response = $this->get("admin/event-categories/$category->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/event-categories/$category->id");
        $this->assertFieldValue($response, 'name', 'Training');
    }

    #[Test]
    public function the_news_item_forms_render()
    {
        $this->assertForm($this->get('admin/news-items/create')->assertOk(), 'POST', 'admin/news-items');

        $newsItem = NewsItem::factory()->create(['title' => 'Nieuwe muur', 'body' => '<p>Open</p>']);
        $response = $this->get("admin/news-items/$newsItem->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/news-items/$newsItem->id");
        $this->assertFieldValue($response, 'title', 'Nieuwe muur');
        $this->assertFieldValue($response, 'body', '<p>Open</p>');
    }

    #[Test]
    public function the_page_forms_render()
    {
        $this->assertForm($this->get('admin/pages/create')->assertOk(), 'POST', 'admin/pages');

        $page = MenuItem::forceCreate(['name' => 'Over ons', 'slug' => 'over-ons', 'content' => '<p>Wij</p>', 'show_in_menu' => true, 'requires_login' => false, 'deletable' => true, 'editable' => true]);
        $response = $this->get("admin/pages/$page->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/pages/$page->id");
        $this->assertFieldValue($response, 'name', 'Over ons');
        $this->assertFieldValue($response, 'content', '<p>Wij</p>');
        $this->assertChecked($response, 'show_in_menu');
        $this->assertChecked($response, 'requires_login', false);
    }

    #[Test]
    public function the_registration_form_forms_render()
    {
        $this->assertForm($this->get('admin/registration-forms/create')->assertOk(), 'POST', 'admin/registration-forms');

        $registrationForm = RegistrationForm::factory()->create(['name' => 'Weekend']);
        $response = $this->get("admin/registration-forms/$registrationForm->id/edit")->assertOk();

        $this->assertForm($response, 'PUT', "admin/registration-forms/$registrationForm->id");
        $this->assertFieldValue($response, 'name', 'Weekend');
    }

    #[Test]
    public function the_event_forms_render()
    {
        $this->assertForm($this->get('admin/events/create')->assertOk(), 'POST', 'admin/events');

        RegistrationForm::factory()->create();
        $registrationForm = RegistrationForm::factory()->create();
        EventCategory::factory()->create();
        $event = Event::factory()->create([
            'title' => 'Fontainebleau',
            'registration_form_id' => $registrationForm->id,
            'shows_certificates' => false,
        ]);
        $response = $this->get("admin/events/$event->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/events/$event->id");
        $this->assertFieldValue($response, 'title', 'Fontainebleau');
        $this->assertSelected($response, 'registration_form_id', (string) $registrationForm->id);
        $this->assertSelected($response, 'event_category_id', (string) $event->event_category_id);
        $this->assertChecked($response, 'shows_certificates', false);
    }

    #[Test]
    public function the_member_forms_render()
    {
        $response = $this->get('admin/members/create')->assertOk();
        $this->assertForm($response, 'POST', 'admin/members');
        $this->assertTrue($this->field($response, 'password')->hasAttribute('required'));
        $this->assertSelected($response, 'country', 'NL');

        $member = User::factory()->create(['first_name' => 'Piet', 'country' => 'BE', 'member_type' => 'reunist', 'direct_debit' => true]);
        $response = $this->get("admin/members/$member->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/members/$member->id");
        $this->assertFieldValue($response, 'first_name', 'Piet');
        $this->assertFalse($this->field($response, 'password')->hasAttribute('required'), 'Editing a member keeps the password');
        $this->assertSelected($response, 'country', 'BE');
        $this->assertSelected($response, 'member_type', 'reunist');
        $this->assertChecked($response, 'direct_debit');
    }

    #[Test]
    public function a_member_can_edit_their_own_profile_but_not_their_member_type()
    {
        $this->user->roles()->detach();

        $response = $this->get("admin/members/{$this->user->id}/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/members/{$this->user->id}");
        $this->assertTrue($this->field($response, 'member_type')->hasAttribute('disabled'));
        $this->get('admin/members/'.User::factory()->create()->id.'/edit')->assertStatus(403);
    }

    #[Test]
    public function the_member_certificate_forms_render()
    {
        $member = User::factory()->create();
        Certificate::create(['name' => 'Toprope', 'abbreviation' => 'TR']);
        $certificate = Certificate::create(['name' => 'Voorklimmen', 'abbreviation' => 'VK']);

        $this->assertForm($this->get("admin/members/$member->id/certificates/create")->assertOk(), 'POST', "admin/members/$member->id/certificates");

        $member->certificates()->attach($certificate->id);
        $response = $this->get("admin/members/$member->id/certificates/$certificate->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/members/$member->id/certificates/$certificate->id");
        $this->assertSelected($response, 'certificate_id', (string) $certificate->id);
    }

    #[Test]
    public function the_member_page_forms_render()
    {
        $member = User::factory()->create();

        $response = $this->get("admin/members/$member->id")->assertOk();

        $this->assertForm($response, 'DELETE', "admin/members/$member->id/membership");
    }

    #[Test]
    public function a_pending_member_page_has_the_approve_form()
    {
        $member = User::factory()->create(['pending_since' => now()]);

        $response = $this->get("admin/members/$member->id")->assertOk();

        $this->assertForm($response, 'PATCH', "admin/pending-members/$member->id/approve");
    }

    #[Test]
    public function a_former_member_page_has_the_restore_membership_form()
    {
        $member = User::factory()->create(['membership_ended_on' => now()]);

        $response = $this->get("admin/members/$member->id")->assertOk();

        $this->assertForm($response, 'POST', "admin/members/$member->id/membership");
    }

    #[Test]
    public function the_settings_form_renders()
    {
        $this->assertForm($this->get('admin/settings/edit')->assertOk(), 'PUT', 'admin/settings');
    }

    #[Test]
    public function the_mail_list_form_renders()
    {
        $this->assertForm($this->get('admin/mail-lists/create')->assertOk(), 'POST', 'admin/mail-lists');
    }

    #[Test]
    public function the_penno_export_form_renders()
    {
        $this->assertForm($this->get('admin/penno-export/create')->assertOk(), 'POST', 'admin/penno-export');
    }

    #[Test]
    public function the_admin_registration_form_renders()
    {
        $event = Event::factory()->create(['registration_form_id' => RegistrationForm::factory()->create()->id]);

        $response = $this->get("admin/events/$event->id/registrations/create")->assertOk();

        $this->assertForm($response, 'POST', "admin/events/$event->id/registrations");
        $this->field($response, 'user');
    }
}
