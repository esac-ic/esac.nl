<?php

namespace Database\Factories;

use App\Models\RegistrationForm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegistrationForm>
 */
class RegistrationFormFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
