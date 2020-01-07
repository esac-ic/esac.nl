<?php

use App\Text;
use App\ApplicationForm;
use Faker\Generator as Faker;

$factory->define(ApplicationForm::class, function (Faker $faker) {
    $name = factory(Text::class)->create();

    return [
        'name' => $name->id,
    ];
});