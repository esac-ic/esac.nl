<?php

namespace Database\Factories;

use App\Text;
use App\AgendaItemCategorie;
use Faker\Generator as Faker;

$factory->define(AgendaItemCategorie::class, function (Faker $faker) {
    $text = factory(Text::class)->create();
    return [
        'name' => $text->id
    ];
});