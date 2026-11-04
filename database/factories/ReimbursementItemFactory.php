<?php

namespace Database\Factories;

use App\Models\ReimbursementItem;
use App\Models\Reimbursement;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ApplicationResponse::class, function (Faker $faker) {
    $reimbursement = factory(Reimbursement::class)->create();

    return [
        'item' => $faker->word,
        'amount' => $faker->randomNumber(),
        'esacpas' => $faker->boolean,
        'image_url' => $faker->imageUrl,
        'reimbursement_id' => $reimbursement->id
    ];
});
