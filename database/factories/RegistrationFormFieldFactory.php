<?php

namespace Database\Factories;

use App\Enums\FormFieldType;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegistrationFormField>
 */
class RegistrationFormFieldFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'registration_form_id' => RegistrationForm::factory(),
            'type' => fake()->randomElement([
                FormFieldType::Checkbox,
                FormFieldType::Number,
                FormFieldType::Radio,
                FormFieldType::Select,
                FormFieldType::Text,
                FormFieldType::Textarea,
            ]),
            'required' => fake()->boolean(),
        ];
    }
}
