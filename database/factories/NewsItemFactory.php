<?php

namespace Database\Factories;

use App\NewsItem;
use Faker\Generator as Faker;

$factory->define(NewsItem::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'text' => $faker->text,
        'image_url' =>$faker->imageUrl,
        'thumbnail_url' => $faker->imageUrl,
        'author' => $faker->firstName
    ];
});
