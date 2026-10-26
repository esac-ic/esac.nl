<?php

namespace Tests\Feature\Migrations;

use App\Enums\FormFieldType;
use App\Models\Event;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\TestsMigrations;
use Tests\TestCase;

class StoreTheQuestionWithEachAnswerTest extends TestCase
{
    use TestsMigrations;

    #[Test]
    public function existing_answers_get_their_question_and_a_readable_value()
    {
        $migration = require database_path('migrations/2026_10_26_100600_store_the_question_with_each_answer.php');
        $form = RegistrationForm::factory()->create();
        $checkbox = RegistrationFormField::factory()->create(['registration_form_id' => $form->id, 'name' => 'Bring a tent', 'type' => FormFieldType::Checkbox]);
        $days = RegistrationFormField::factory()->create(['registration_form_id' => $form->id, 'name' => 'Days', 'type' => FormFieldType::Checkboxes]);
        $remark = RegistrationFormField::factory()->create(['registration_form_id' => $form->id, 'name' => 'Remark', 'type' => FormFieldType::Text]);
        $event = Event::factory()->for($form)->create();
        $registration = Registration::factory()->create(['event_id' => $event->id, 'registration_form_id' => $form->id]);
        $migration->down();
        DB::table('registration_answers')->insert([
            ['registration_id' => $registration->id, 'registration_form_field_id' => $checkbox->id, 'value' => 'on'],
            ['registration_id' => $registration->id, 'registration_form_field_id' => $days->id, 'value' => 'sat,sun'],
            ['registration_id' => $registration->id, 'registration_form_field_id' => $remark->id, 'value' => 'No,thanks'],
        ]);

        $migration->up();

        $this->assertSame(
            [['Bring a tent', 'Yes'], ['Days', 'sat, sun'], ['Remark', 'No,thanks']],
            DB::table('registration_answers')->orderBy('id')->get(['question', 'value'])->map(fn ($answer) => [$answer->question, $answer->value])->all(),
        );
    }
}
