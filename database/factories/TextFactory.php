<?php

namespace Database\Factories;

use App\Text;
use Faker\Generator as Faker;

$factory->define(Text::class, function (Faker $faker) {
    return [
        'NL_text' => $faker->word,
        'EN_text' => $faker->word,
    ];
});
