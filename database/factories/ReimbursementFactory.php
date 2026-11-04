<?php

namespace Database\Factories;

use App\Models\Reimbursement;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Reimbursement::class, function (Faker $faker) {
    $user = User::factory()->create();

    return [
        'title' => $faker->word,
        'text'=> $faker->text,
        'status' => $faker->randomElement(["pending", "accepted", "rejected"]),
        'type' => $faker->randomElement(["activity", "driver", "other"]),
        'createdOn' => $faker->date,
    ];
});