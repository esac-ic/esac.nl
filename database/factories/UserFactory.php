<?php

namespace Database\Factories;

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        //generate sensible matching email, name pairs
        $firstName = fake()->firstName();
        $preposition = fake()->randomElement(["", "van", "ten", "van de", "de"]);
        $lastName = fake()->lastName();
        $domain = fake()->freeEmailDomain();
        $email = str_replace(" ", "", $firstName . $preposition . $lastName . "@" . $domain);
        
        return [
            'email' => $email,
            'password' => bcrypt(fake()->password()),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'preposition' => $preposition,
            'street' => fake()->streetName(),
            'houseNumber' => fake()->randomNumber(),
            'city' => fake()->city(),
            'zipcode' => fake()->postcode(),
            'country' => fake()->country(),
            'phonenumber' => fake()->unique()->phoneNumber(),
            'phonenumber_alt' => fake()->phoneNumber(),
            'emergencyNumber' => fake()->phoneNumber(),
            'emergencystreet' => fake()->streetName(),
            'emergencyHouseNumber' => fake()->randomNumber(),
            'emergencycity' => fake()->city(),
            'emergencyzipcode' => fake()->postcode(),
            'emergencycountry' => fake()->country(),
            'birthday' => fake()->date(),
            'kind_of_member' => fake()->randomElement(User::KINDS_OF_MEMBER),
            'IBAN' => fake()->iban(),
            'BIC' => fake()->bankAccountNumber(),
            'incasso' => fake()->boolean(),
            'remark' => fake()->sentence(),
        ];
    }
    
    //member type variations
    
    public function member(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'kind_of_member' => "member",
            ];
        });
    }
    
    public function reunist(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'kind_of_member' => "reunist",
            ];
        });
    }
    
    public function lidAf(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'lid_af' => fake()->dateTimeThisDecade(),
            ];
        });
    }
    
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'pending_user' => fake()->dateTimeThisMonth(),
                'kind_of_member' => "member",
            ];
        });
    }
    
}