<?php

use Faker\Generator as Faker;
use App\NewsItem;

$factory->define(NewsItem::class, function (Faker $faker) {
    return [
        'title' => $faker->realText,
        'text' => $faker->realText,
        'image_url' =>$faker->imageUrl,
        'thumbnail_url' => $faker->imageUrl,
        'author' => $faker->firstName
    ];
});
