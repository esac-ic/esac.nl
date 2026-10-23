<?php

namespace Database\Factories;

use App\Models\RegistrationFormField;
use App\Models\RegistrationFormFieldOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegistrationFormFieldOption>
 */
class RegistrationFormFieldOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'value' => fake()->word(),
            'name' => fake()->word(),
            'registration_form_field_id' => RegistrationFormField::factory(),
        ];
    }
}
