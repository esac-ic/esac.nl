<?php

namespace Database\Factories;

use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use Faker\Generator as Faker;

$factory->define(ApplicationFormRowOption::class, function (Faker $faker) {
    $row = factory(ApplicationFormRow::class)->create();
    return [
        'value' => $faker->word,
        'name' => $faker->word,
        'application_form_row_id' => $row->id,
    ];
});
