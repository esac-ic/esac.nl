<?php

namespace Database\Factories;

use App\Rol;
use App\Text;
use Faker\Generator as Faker;

$factory->define(Rol::class, function (Faker $faker) {
    $text = factory(Text::class)->create();
    return [
        'name' => $text->id
    ];
});
