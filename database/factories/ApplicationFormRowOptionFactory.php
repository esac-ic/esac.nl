<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use App\Text;
use Faker\Generator as Faker;

$factory->define(ApplicationFormRowOption::class, function (Faker $faker) {
    $text = factory(Text::class)->create();
    $row = factory(ApplicationFormRow::class)->create();
    return [
        'value' => $faker->word,
        'name_id' => $text->id,
        'application_form_row_id' => $row->id,
    ];
});
