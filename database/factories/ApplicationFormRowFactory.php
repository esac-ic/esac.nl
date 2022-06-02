<?php

namespace Database\Factories;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Text;
use Faker\Generator as Faker;

$factory->define(ApplicationFormRow::class, function (Faker $faker) {
    $form = factory(ApplicationForm::class)->create();
    $text = factory(Text::class)->create();
    return [
        'name' => $text->id,
        'application_form_id' => $form->id,
        'type' => $faker->randomElement([
            ApplicationFormRow::FORM_TYPE_CHECK_BOX,
            ApplicationFormRow::FORM_TYPE_NUMBER,
            ApplicationFormRow::FORM_TYPE_RADIO,
            ApplicationFormRow::FORM_TYPE_SELECT,
            ApplicationFormRow::FORM_TYPE_TEXT,
            ApplicationFormRow::FORM_TYPE_TEXT_BOX,
        ]),
        'required' => $faker->boolean
    ];
});
