<?php

/* @var $factory Factory */

use App\Models\ApplicationForm\ApplicationForm;
use App\Text;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ApplicationForm::class, function (Faker $faker) {
    $text = \factory(Text::class)->create();
    return [
        'name' => $text->id
    ];
});
