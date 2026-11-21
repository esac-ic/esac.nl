<?php

namespace Database\Factories;

use App\Models\Reimbursement\ReimbursementReceipt;
use App\Models\Reimbursement\Reimbursement;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(ReimbursementReceipt::class, function (Faker $faker) {
    $reimbursement = factory(Reimbursement::class)->create();

    return [
        'image_url' => $faker->imageUrl,
        'reimbursement_id' => $reimbursement->id
    ];
});
