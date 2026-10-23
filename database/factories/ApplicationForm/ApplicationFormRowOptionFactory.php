<?php

namespace Database\Factories\ApplicationForm;

use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationFormRowOption>
 */
class ApplicationFormRowOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'value' => fake()->word(),
            'name' => fake()->word(),
            'application_form_row_id' => ApplicationFormRow::factory(),
        ];
    }
}
