<?php

namespace Database\Factories;

use App\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use App\User;
use Faker\Generator as Faker;

$factory->define(ApplicationResponse::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $agendaItem = factory(AgendaItem::class)->create();
    $applicationForm = factory(ApplicationForm::class)->create();
    return [
        'user_id' => $user->id,
        'inschrijf_form_id' => $applicationForm->id,
        'agenda_id' => $agendaItem->id,
    ];
});
