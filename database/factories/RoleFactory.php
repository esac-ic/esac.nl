<?php

namespace Database\Factories;

use App\Models\Rol;
use Faker\Generator as Faker;

$factory->define(Rol::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
