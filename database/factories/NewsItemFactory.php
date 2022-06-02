<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\NewsItem;
use App\Text;

$factory->define(NewsItem::class, function (Faker $faker) {
    return [
        'title' => factory(Text::class)->create(),
        'text' => factory(Text::class)->create(),
        'image_url' =>$faker->imageUrl,
        'thumbnail_url' => $faker->imageUrl,
        'author' => $faker->firstName
    ];
});
