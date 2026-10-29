<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'registration_form_id' => RegistrationForm::factory(),
            'event_id' => Event::factory(),
        ];
    }
}
