<?php

namespace Database\Factories\ApplicationForm;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationResponse>
 */
class ApplicationResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'inschrijf_form_id' => ApplicationForm::factory(),
            'agenda_id' => AgendaItem::factory(),
        ];
    }
}
