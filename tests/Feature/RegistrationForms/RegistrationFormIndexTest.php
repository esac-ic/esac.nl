<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Models\RegistrationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesRegistrationForms;
use Tests\TestCase;

class RegistrationFormIndexTest extends TestCase
{
    use ManagesRegistrationForms;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RegistrationForm::factory()->create();
    }

    #[Test]
    public function an_event_administrator_can_view_the_page(): void
    {
        $this->actingAsRole(AdminRole::EventAdministrator);

        $this->get('admin/registration-forms')->assertOk();
    }
}
