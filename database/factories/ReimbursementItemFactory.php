<?php

namespace Database\Factories;

use App\Models\Reimbursement\ReimbursementItem;
use App\Models\Reimbursement\Reimbursement;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ReimbursementItem::class, function (Faker $faker) {
    $reimbursement = factory(Reimbursement::class)->create();

    return [
        'item' => $faker->word,
        'amount' => $faker->randomNumber(),
        'esacpas' => $faker->boolean,
        'reimbursement_id' => $reimbursement->id
    ];
});
