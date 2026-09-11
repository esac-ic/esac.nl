<?php

namespace Database\Factories;

use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Faker\Generator as Faker;

class AgendaItemFactory extends Factory
{
    public function definition(): array
    {
        $user = User::factory()->create();
        $category = factory(AgendaItemCategory::class)->create();

        return [
            'title' => fake()->word(),
            'text'=> fake()->text(),
            'shortDescription' => fake()->word(),
            'createdBy' => $user->id,
            'category' => $category->id,
            'startDate' => fake()->date(),
            'endDate' => fake()->date(),
            'subscription_endDate' => fake()->date(),
            'image_url' => fake()->imageUrl(),
            'climbing_activity' => fake()->boolean(),
            'hidden' => fake()->randomElement([0,1,2,3,4,5]),
        ];
    }

    // hidden variations
    
    public function not_hidden(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'hidden' => 0,
            ];
        });
    }

    public function hidden_1week(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'hidden' => 1,
            ];
        });
    }

    public function hidden_2weeks(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'hidden' => 2,
            ];
        });
    }

    public function hidden_3weeks(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'hidden' => 3,
            ];
        });
    }

    public function hidden_4weeks(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'hidden' => 4,
            ];
        });
    }

    public function hidden(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'hidden' => 5,
            ];
        });
    }

}