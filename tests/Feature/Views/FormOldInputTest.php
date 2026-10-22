<?php

namespace Tests\Feature\Views;

use App\Enums\Role;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * After a validation error, the form should show what was submitted instead of default values
 */
class FormOldInputTest extends TestCase
{
    use RefreshDatabase, InspectsForms;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();

        $user->roles()->attach([Role::Administrator->value, Role::ContentAdministrator->value]);
        $this->be($user);

        session()->start();
    }

    #[Test]
    public function text_fields_keep_the_submitted_values()
    {
        $response = $this->from('books/create')->followingRedirects()->post('books', [
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
        $menuItem = $this->field($this->get('pages/create'), 'menu_item')->getAttribute('value');

        $response = $this->from('pages/create')->followingRedirects()->post('pages', [
            'url_name' => 'test',
            'itemType' => 'subItem',
            'parentItem' => 0,
            'afterItem' => 0,
            'content' => 'Inhoud',
            'menu_item' => $menuItem,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'content', 'Inhoud');
        $this->assertChecked($response, 'menu_item');
        $this->assertChecked($response, 'login', false);
    }

    #[Test]
    public function agenda_item_fields_keep_the_submitted_state()
    {
        AgendaItemCategory::factory()->create();
        $category = AgendaItemCategory::factory()->create();
        $applicationForm = ApplicationForm::factory()->create();

        $response = $this->from('agendaItems/create')->followingRedirects()->post('agendaItems', [
            'category' => $category->id,
            'applicationForm' => $applicationForm->id,
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertSelected($response, 'category', (string) $category->id);
        $this->assertSelected($response, 'applicationForm', (string) $applicationForm->id);
        $this->assertChecked($response, 'climbing_activity', false);
    }

    #[Test]
    public function an_unchecked_incasso_stays_unchecked()
    {
        $member = User::factory()->create(['incasso' => true]);

        $response = $this->from("users/$member->id/edit")->followingRedirects()->patch("users/$member->id", [
            'firstname' => 'Piet',
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'firstname', 'Piet');
        $this->assertChecked($response, 'incasso', false);
    }
}
