<?php

namespace Tests\Feature\Views;

use App\Enums\AdminRole;
use App\Models\EventCategory;
use App\Models\RegistrationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * After a validation error, the form should show what was submitted instead of default values
 */
class FormOldInputTest extends TestCase
{
    use InspectsForms, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->be(User::factory()->withRole(AdminRole::Administrator, AdminRole::ContentAdministrator)->create());

        session()->start();
    }

    #[Test]
    public function text_fields_keep_the_submitted_values()
    {
        $response = $this->from('admin/books/create')->followingRedirects()->post('admin/books', [
            'year' => '1999',
            'country' => 'NL',
            'type' => 'Gids',
            'code' => 'K1',
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'title', '');
        $this->assertFieldValue($response, 'year', '1999');
        $this->assertFieldValue($response, 'code', 'K1');
    }

    #[Test]
    public function page_checkboxes_keep_the_submitted_state()
    {
        $showInMenu = $this->field($this->get('admin/pages/create'), 'show_in_menu')->getAttribute('value');

        $response = $this->from('admin/pages/create')->followingRedirects()->post('admin/pages', [
            'slug' => 'test',
            'body' => 'Inhoud',
            'show_in_menu' => $showInMenu,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'body', 'Inhoud');
        $this->assertChecked($response, 'show_in_menu');
        $this->assertChecked($response, 'requires_login', false);
    }

    #[Test]
    public function event_fields_keep_the_submitted_state()
    {
        EventCategory::factory()->create();
        $category = EventCategory::factory()->create();
        $registrationForm = RegistrationForm::factory()->create();

        $response = $this->from('admin/events/create')->followingRedirects()->post('admin/events', [
            'event_category_id' => $category->id,
            'registration_form_id' => $registrationForm->id,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertSelected($response, 'event_category_id', (string) $category->id);
        $this->assertSelected($response, 'registration_form_id', (string) $registrationForm->id);
        $this->assertChecked($response, 'shows_certificates', false);
    }

    #[Test]
    public function an_unchecked_direct_debit_stays_unchecked()
    {
        $member = User::factory()->create(['direct_debit' => true]);

        $response = $this->from("admin/members/$member->id/edit")->followingRedirects()->patch("admin/members/$member->id", [
            'first_name' => 'Piet',
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'first_name', 'Piet');
        $this->assertChecked($response, 'direct_debit', false);
    }
}
