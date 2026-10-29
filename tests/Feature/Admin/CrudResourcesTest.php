<?php

namespace Tests\Feature\Admin;

use App\Enums\AdminRole;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\EventCategory;
use App\Models\User;
use App\Settings\SiteSettings;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CrudResourcesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator, AdminRole::CertificateAdministrator)->create();
    }

    #[Test]
    public function books_can_be_managed()
    {
        $book = ['title' => 'Alpine climbing', 'year' => 1990, 'type' => 'Guide', 'country' => 'CH', 'code' => 'A1'];

        $this->actingAs($this->admin)->post('admin/books', $book)->assertRedirect('admin/books');
        $created = Book::sole();
        $this->assertSame('Alpine climbing', $created->title);

        $this->get('admin/books')->assertOk()->assertSee('Alpine climbing');
        $this->patch("admin/books/{$created->id}", ['title' => 'Ice climbing'] + $book)->assertRedirect('admin/books');
        $this->assertSame('Ice climbing', $created->fresh()->title);

        $this->delete("admin/books/{$created->id}")->assertRedirect('admin/books');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function the_settings_page_shows_the_entered_text_after_a_validation_error()
    {
        $this->actingAs($this->admin)->from('admin/settings/edit')->followingRedirects()
            ->put('admin/settings', ['killswitch' => '0', 'blocked_email_domains' => str_repeat('a', 256), 'mail_lists' => []])
            ->assertOk()
            ->assertSee(str_repeat('a', 256));
    }

    #[Test]
    public function settings_can_be_managed()
    {
        $this->actingAs($this->admin)->get('admin/settings/edit')->assertOk()->assertSee('student.tue.nl')->assertSee('alle-leden.esac.nl');

        $mailLists = array_fill_keys(array_keys(app(SiteSettings::class)->mail_lists), '');
        $this->put('admin/settings', [
            'killswitch' => '1',
            'footer_phone_number' => '',
            'blocked_email_domains' => "student.tue.nl\r\n\r\nstudent.fontys.nl ",
            'mail_lists' => ['member' => "alle-leden.esac.nl\nnieuwsbrief.esac.nl"] + $mailLists,
        ])->assertRedirect('admin/settings/edit');

        $settings = app(SiteSettings::class)->refresh();
        $this->assertTrue($settings->killswitch);
        $this->assertNull($settings->footer_phone_number);
        $this->assertSame(['student.tue.nl', 'student.fontys.nl'], $settings->blocked_email_domains);
        $this->assertSame(['alle-leden.esac.nl', 'nieuwsbrief.esac.nl'], $settings->mail_lists['member']);
        $this->assertSame([], $settings->mail_lists['reunist']);
    }

    #[Test]
    public function certificates_can_be_managed()
    {
        $this->actingAs($this->admin)->post('admin/certificates', ['name' => 'Toprope', 'abbreviation' => 'TR'])->assertRedirect('admin/certificates');
        $created = Certificate::sole();

        $this->get('admin/certificates')->assertOk()->assertSee('Toprope')->assertSee('<td>TR</td>', false);
        $this->patch("admin/certificates/{$created->id}", ['name' => 'Lead', 'abbreviation' => 'L'])->assertRedirect('admin/certificates');
        $this->assertSame('Lead', $created->fresh()->name);

        $this->delete("admin/certificates/{$created->id}")->assertRedirect('admin/certificates');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function event_categories_can_be_managed()
    {
        $this->actingAs($this->admin)->post('admin/event-categories', ['name' => 'Weekend'])->assertRedirect('admin/event-categories');
        $created = EventCategory::sole();

        $this->get('admin/event-categories')->assertOk()->assertSee('Weekend');
        $this->patch("admin/event-categories/{$created->id}", ['name' => 'Trip'])->assertRedirect('admin/event-categories');
        $this->assertSame('Trip', $created->fresh()->name);

        $this->delete("admin/event-categories/{$created->id}")->assertRedirect('admin/event-categories');
        $this->assertSoftDeleted($created);
    }

    #[Test]
    public function a_members_certificates_are_scoped_to_that_member()
    {
        $member = User::factory()->create();
        [$held, $other] = [Certificate::create(['name' => 'Toprope', 'abbreviation' => 'TR']), Certificate::create(['name' => 'Lead', 'abbreviation' => 'L'])];
        $member->certificates()->attach($held);

        $this->actingAs($this->admin)->get(route('admin.members.certificates.edit', [$member, $held]))->assertOk();
        $this->get(route('admin.members.certificates.edit', [$member, $other]))->assertNotFound();
    }

    #[Test]
    public function the_library_and_front_pages_list_their_content()
    {
        $this->seed(PageSeeder::class);
        Book::create(['title' => 'Alpine climbing', 'year' => 1990, 'type' => 'Guide', 'country' => 'CH', 'code' => 'A1']);

        $this->actingAs($this->admin)->get('library')->assertOk()->assertSee('Alpine climbing');
        $this->get('/')->assertOk();
        $this->get('events')->assertOk();
    }
}
