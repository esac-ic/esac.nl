<?php

namespace Database\Factories;

use App\AgendaItemCategory;
use Faker\Generator as Faker;

$factory->define(AgendaItemCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
