<?php

namespace Database\Factories;

use App\Enums\AdminRole;
use App\Enums\MemberType;
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
            'first_name' => $firstName,
            'last_name' => $lastName,
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
            'member_type' => fake()->randomElement(MemberType::cases()),
            'iban' => fake()->iban(),
            'bic' => fake()->swiftBicNumber(),
            'direct_debit' => fake()->boolean(),
            'remark' => fake()->sentence(),
        ];
    }

    public function withRole(AdminRole ...$roles): static
    {
        return $this->afterCreating(fn (User $user) => $user->roles()->attach(array_map(fn (AdminRole $role) => $role->value, $roles)));
    }

    public function member(): static
    {
        return $this->state(['member_type' => 'member']);
    }

    public function alumnus(): static
    {
        return $this->state(['member_type' => 'alumnus']);
    }

    public function extraordinaryMember(): static
    {
        return $this->state(['member_type' => 'extraordinary_member']);
    }

    public function honoraryMember(): static
    {
        return $this->state(['member_type' => 'honorary_member']);
    }

    public function memberOfMerit(): static
    {
        return $this->state(['member_type' => 'member_of_merit']);
    }

    public function trainer(): static
    {
        return $this->state(['member_type' => 'trainer']);
    }

    public function relationship(): static
    {
        return $this->state(['member_type' => 'relationship']);
    }

    public function former(): static
    {
        return $this->state(fn () => ['membership_ended_on' => fake()->dateTimeThisDecade()]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'pending_since' => fake()->dateTimeThisMonth(),
            'member_type' => 'member',
        ]);
    }
}
