<?php

namespace Tests\Feature\Views;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\Rol;
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
        $this->user = $user = User::factory()->create();

        $user->roles()->attach([Role::Administrator->value, Role::ContentAdministrator->value]);
        $this->be($user);
    }

    #[Test]
    public function the_book_forms_render()
    {
        $this->assertForm($this->get('books/create')->assertOk(), 'POST', 'books');

        $book = Book::create(['title' => 'Klimgids', 'year' => 1999, 'country' => 'NL', 'type' => 'Gids', 'code' => 'K1']);
        $response = $this->get("books/$book->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "books/$book->id");
        $this->assertFieldValue($response, 'title', 'Klimgids');
        $this->assertFieldValue($response, 'year', '1999');
        $this->assertFieldValue($response, 'code', 'K1');
    }

    #[Test]
    public function the_certificate_forms_render()
    {
        $this->assertForm($this->get('certificates/create')->assertOk(), 'POST', 'certificates');

        $certificate = Certificate::create(['name' => 'Toprope', 'abbreviation' => 'TR']);
        $response = $this->get("certificates/$certificate->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "certificates/$certificate->id");
        $this->assertFieldValue($response, 'name', 'Toprope');
        $this->assertFieldValue($response, 'abbreviation', 'TR');
    }

    #[Test]
    public function the_role_forms_render()
    {
        $this->assertForm($this->get('rols/create')->assertOk(), 'POST', 'rols');

        $role = Rol::factory()->create(['name' => 'Penningmeester']);
        $response = $this->get("rols/$role->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "rols/$role->id");
        $this->assertFieldValue($response, 'name', 'Penningmeester');
    }

    #[Test]
    public function the_agenda_item_category_forms_render()
    {
        $this->assertForm($this->get('agendaItemCategories/create')->assertOk(), 'POST', 'agendaItemCategories');

        $category = AgendaItemCategory::factory()->create(['name' => 'Training']);
        $response = $this->get("agendaItemCategories/$category->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "agendaItemCategories/$category->id");
        $this->assertFieldValue($response, 'name', 'Training');
    }

    #[Test]
    public function the_news_item_forms_render()
    {
        $this->assertForm($this->get('newsItems/create')->assertOk(), 'POST', 'newsItems');

        $newsItem = NewsItem::factory()->create(['title' => 'Nieuwe muur', 'text' => '<p>Open</p>']);
        $response = $this->get("newsItems/$newsItem->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "newsItems/$newsItem->id");
        $this->assertFieldValue($response, 'title', 'Nieuwe muur');
        $this->assertFieldValue($response, 'text', '<p>Open</p>');
    }

    #[Test]
    public function the_page_forms_render()
    {
        $this->assertForm($this->get('pages/create')->assertOk(), 'POST', 'pages');

        $page = MenuItem::forceCreate(['name' => 'Over ons', 'url_name' => 'over-ons', 'content' => '<p>Wij</p>', 'menu_item' => true, 'login' => false, 'deletable' => true, 'editable' => true]);
        $response = $this->get("pages/$page->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "pages/$page->id");
        $this->assertFieldValue($response, 'name', 'Over ons');
        $this->assertFieldValue($response, 'content', '<p>Wij</p>');
        $this->assertChecked($response, 'menu_item');
        $this->assertChecked($response, 'login', false);
    }

    #[Test]
    public function the_application_form_forms_render()
    {
        $this->assertForm($this->get('beheer/applicationForms/create')->assertOk(), 'POST', 'beheer/applicationForms');

        $applicationForm = ApplicationForm::factory()->create(['name' => 'Weekend']);
        $response = $this->get("beheer/applicationForms/$applicationForm->id/edit")->assertOk();

        $this->assertForm($response, 'PUT', "beheer/applicationForms/$applicationForm->id");
        $this->assertFieldValue($response, 'name', 'Weekend');
    }

    #[Test]
    public function the_agenda_item_forms_render()
    {
        $this->assertForm($this->get('agendaItems/create')->assertOk(), 'POST', 'agendaItems');

        ApplicationForm::factory()->create();
        $applicationForm = ApplicationForm::factory()->create();
        AgendaItemCategory::factory()->create();
        $agendaItem = AgendaItem::factory()->create([
            'title' => 'Fontainebleau',
            'application_form_id' => $applicationForm->id,
            'climbing_activity' => false,
        ]);
        $response = $this->get("agendaItems/$agendaItem->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "agendaItems/$agendaItem->id");
        $this->assertFieldValue($response, 'title', 'Fontainebleau');
        $this->assertSelected($response, 'applicationForm', (string) $applicationForm->id);
        $this->assertSelected($response, 'category', (string) $agendaItem->category);
        $this->assertChecked($response, 'climbing_activity', false);
    }

    #[Test]
    public function the_user_forms_render()
    {
        $response = $this->get('users/create')->assertOk();
        $this->assertForm($response, 'POST', 'users');
        $this->assertTrue($this->field($response, 'password')->hasAttribute('required'));
        $this->assertSelected($response, 'country', 'NL');

        $member = User::factory()->create(['firstname' => 'Piet', 'country' => 'BE', 'kind_of_member' => 'reunist', 'incasso' => true]);
        $response = $this->get("users/$member->id/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "users/$member->id");
        $this->assertFieldValue($response, 'firstname', 'Piet');
        $this->assertFalse($this->field($response, 'password')->hasAttribute('required'), 'Editing a user keeps the password');
        $this->assertSelected($response, 'country', 'BE');
        $this->assertSelected($response, 'kind_of_member', 'reunist');
        $this->assertChecked($response, 'incasso');
    }

    #[Test]
    public function a_member_can_edit_their_own_profile_but_not_their_kind_of_member()
    {
        $this->user->roles()->detach();

        $response = $this->get("users/{$this->user->id}/edit")->assertOk();

        $this->assertForm($response, 'PATCH', "users/{$this->user->id}");
        $this->assertTrue($this->field($response, 'kind_of_member')->hasAttribute('disabled'));
        $this->get('users/'.User::factory()->create()->id.'/edit')->assertStatus(403);
    }

    #[Test]
    public function the_user_certificate_forms_render()
    {
        $member = User::factory()->create();
        Certificate::create(['name' => 'Toprope', 'abbreviation' => 'TR']);
        $certificate = Certificate::create(['name' => 'Voorklimmen', 'abbreviation' => 'VK']);

        $this->assertForm($this->get("users/$member->id/addCertificate")->assertOk(), 'POST', "users/$member->id/addCertificate");

        $member->certificates()->attach($certificate->id);
        $response = $this->get("users/$member->id/addCertificate/$certificate->id")->assertOk();

        $this->assertForm($response, 'PATCH', "users/$member->id/addCertificate/$certificate->id");
        $this->assertSelected($response, 'certificate_id', (string) $certificate->id);
    }

    #[Test]
    public function the_user_page_forms_render()
    {
        $member = User::factory()->create();

        $response = $this->get("users/$member->id")->assertOk();

        $this->assertForm($response, 'PATCH', "users/$member->id/removeAsActiveMember");
    }

    #[Test]
    public function a_pending_member_page_has_the_approve_form()
    {
        $member = User::factory()->create(['pending_user' => now()]);

        $response = $this->get("users/$member->id")->assertOk();

        $this->assertForm($response, 'PATCH', "users/$member->id/approveAsPendingMember");
    }

    #[Test]
    public function an_old_member_page_has_the_make_active_form()
    {
        $member = User::factory()->create(['lid_af' => now()]);

        $response = $this->get("users/$member->id")->assertOk();

        $this->assertForm($response, 'PATCH', "users/$member->id/makeActiveMember");
    }

    #[Test]
    public function the_settings_form_renders()
    {
        $this->assertForm($this->get('beheer/settings/edit')->assertOk(), 'PUT', 'beheer/settings');
    }

    #[Test]
    public function the_mail_list_form_renders()
    {
        $this->assertForm($this->get('mailList/create')->assertOk(), 'POST', 'mailList');
    }

    #[Test]
    public function the_penno_export_form_renders()
    {
        $this->assertForm($this->get('users/pennoMemberExportView')->assertOk(), 'GET', route('users.pennoMemberExport'));
    }

    #[Test]
    public function the_admin_registration_form_renders()
    {
        $agendaItem = AgendaItem::factory()->create(['application_form_id' => ApplicationForm::factory()->create()->id]);

        $response = $this->get("forms/admin/$agendaItem->id")->assertOk();

        $this->assertForm($response, 'POST', "forms/admin/$agendaItem->id");
        $this->field($response, 'user');
    }
}
