<?php

namespace Database\Factories;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\User;
use Faker\Generator as Faker;

$factory->define(AgendaItem::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $category = factory(AgendaItemCategory::class)->create();

    return [
        'title' => $faker->word,
        'text'=> $faker->text,
        'shortDescription' => $faker->word,
        'createdBy' => $user->id,
        'category' => $category->id,
        'startDate' => $faker->date,
        'endDate' => $faker->date,
        'subscription_endDate' => $faker->date,
        'image_url' => $faker->imageUrl,
        'climbing_activity' => $faker->boolean,
    ];
});