<?php

namespace Database\Factories;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ApplicationResponse::class, function (Faker $faker) {
    $user = User::factory()->create();
    $agendaItem = AgendaItem::factory()->not_hidden()->create();
    $applicationForm = factory(ApplicationForm::class)->create();
    return [
        'user_id' => $user->id,
        'inschrijf_form_id' => $applicationForm->id,
        'agenda_id' => $agendaItem->id,
    ];
});
