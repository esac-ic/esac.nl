<?php

namespace Database\Factories;

use App\Enums\ReimbursementType;
use App\Enums\ReimbursementStatus;
use App\Models\Reimbursement;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Reimbursement::class, function (Faker $faker) {
    $user = User::factory()->create();

    return [
        'title' => $faker->word,
        'text'=> $faker->text,
        'status' => $faker->randomElement(ReimbursementType::labels()),
        'type' => $faker->randomElement(ReimbursementStatus::labels()),
        'createdBy' => $user->id,
        'createdOn' => $faker->date,
    ];
});