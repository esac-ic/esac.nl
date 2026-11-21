<?php

namespace Database\Factories\ApplicationForm;

use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationForm>
 */
class ApplicationFormFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
