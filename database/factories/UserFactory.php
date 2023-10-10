<?php

namespace Database\Factories;

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'preposition' => $faker->word,
        'street' => $faker->streetName,
        'houseNumber' => $faker->randomNumber(),
        'city' => $faker->city,
        'zipcode' => $faker->postcode,
        'country' => $faker->country,
        'phonenumber' => $faker->phoneNumber,
        'phonenumber_alt' => $faker->phoneNumber,
        'emergencyNumber' => $faker->phoneNumber,
        'emergencystreet' => $faker->streetName,
        'emergencyHouseNumber' => $faker->randomNumber(),
        'emergencycity' => $faker->city,
        'emergencyzipcode' => $faker->postcode,
        'emergencycountry' => $faker->country,
        'birthday' => $faker->date(),
        'kind_of_member' => 'member',
        'IBAN' => $faker->iban(),
        'BIC' => $faker->bankAccountNumber,
        'incasso' => $faker->boolean,
        'remark' => $faker->sentence,
    ];
});
