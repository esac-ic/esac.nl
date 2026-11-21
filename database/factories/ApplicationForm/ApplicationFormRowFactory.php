<?php

namespace Database\Factories\ApplicationForm;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationFormRow>
 */
class ApplicationFormRowFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'application_form_id' => ApplicationForm::factory(),
            'type' => fake()->randomElement([
                ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                ApplicationFormRow::FORM_TYPE_NUMBER,
                ApplicationFormRow::FORM_TYPE_RADIO,
                ApplicationFormRow::FORM_TYPE_SELECT,
                ApplicationFormRow::FORM_TYPE_TEXT,
                ApplicationFormRow::FORM_TYPE_TEXT_BOX,
            ]),
            'required' => fake()->boolean(),
        ];
    }
}
