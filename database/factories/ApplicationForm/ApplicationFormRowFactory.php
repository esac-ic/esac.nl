<?php

namespace Database\Factories\ApplicationForm;

use App\Enums\FormRowType;
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
                FormRowType::CheckBox,
                FormRowType::Number,
                FormRowType::Radio,
                FormRowType::Select,
                FormRowType::Text,
                FormRowType::TextBox,
            ]),
            'required' => fake()->boolean(),
        ];
    }
}
