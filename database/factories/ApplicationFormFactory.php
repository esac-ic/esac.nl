<?php

namespace Database\Factories;

use App\Models\ApplicationForm\ApplicationForm;
use Faker\Generator as Faker;

$factory->define(ApplicationForm::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
