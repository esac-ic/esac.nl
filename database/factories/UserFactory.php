<?php

namespace Database\Factories;

use App\Enums\KindOfMember;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        // generate sensible matching email, name pairs
        $firstName = fake()->firstName();
        $preposition = fake()->randomElement(['', 'van', 'ten', 'van de', 'de']);
        $lastName = fake()->lastName();
        $domain = fake()->freeEmailDomain();
        $email = str_replace(' ', '', $firstName.$preposition.$lastName.'@'.$domain);

        return [
            'email' => $email,
            'password' => static::$password ??= Hash::make('password'),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'preposition' => $preposition,
            'street' => fake()->streetName(),
            'house_number' => fake()->randomNumber(),
            'city' => fake()->city(),
            'zipcode' => fake()->postcode(),
            'country' => fake()->country(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'phone_number_alt' => fake()->phoneNumber(),
            'emergency_number' => fake()->phoneNumber(),
            'emergency_street' => fake()->streetName(),
            'emergency_house_number' => fake()->randomNumber(),
            'emergency_city' => fake()->city(),
            'emergency_zipcode' => fake()->postcode(),
            'emergency_country' => fake()->country(),
            'birthday' => fake()->date(),
            'kind_of_member' => fake()->randomElement(KindOfMember::cases()),
            'iban' => fake()->iban(),
            'bic' => fake()->swiftBicNumber(),
            'incasso' => fake()->boolean(),
            'remark' => fake()->sentence(),
        ];
    }

    public function withRole(Role ...$roles): static
    {
        return $this->afterCreating(fn (User $user) => $user->roles()->attach(array_map(fn (Role $role) => $role->value, $roles)));
    }

    public function member(): static
    {
        return $this->state(['kind_of_member' => 'member']);
    }

    public function reunist(): static
    {
        return $this->state(['kind_of_member' => 'reunist']);
    }

    public function extraordinary_participant(): static
    {
        return $this->state(['kind_of_member' => 'extraordinary_member']);
    }

    public function honorary_member(): static
    {
        return $this->state(['kind_of_member' => 'honorary_member']);
    }

    public function member_of_merit(): static
    {
        return $this->state(['kind_of_member' => 'member_of_merit']);
    }

    public function trainer(): static
    {
        return $this->state(['kind_of_member' => 'trainer']);
    }

    public function relationship(): static
    {
        return $this->state(['kind_of_member' => 'relationship']);
    }

    public function lidAf(): static
    {
        return $this->state(fn () => ['lid_af' => fake()->dateTimeThisDecade()]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'pending_user' => fake()->dateTimeThisMonth(),
            'kind_of_member' => 'member',
        ]);
    }
}
