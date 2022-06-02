<?php

namespace Database\Factories;

use App\User;
use App\Text;
use App\AgendaItem;
use App\AgendaItemCategorie;
use Faker\Generator as Faker;

$factory->define(AgendaItem::class, function (Faker $faker) {
    $title = factory(Text::class)->create();
    $text = factory(Text::class)->create();
    $shortDescription = factory(Text::class)->create();
    $user = factory(User::class)->create();
    $category = factory(AgendaItemCategorie::class)->create();

    return [
        'title' => $title->id,
        'text'=> $text->id,
        'shortDescription' => $shortDescription->id,
        'createdBy' => $user->id,
        'category' => $category->id,
        'startDate' => $faker->date,
        'endDate' => $faker->date,
        'subscription_endDate' => $faker->date,
        'image_url' => $faker->imageUrl,
        'climbing_activity' => $faker->boolean,
    ];
});