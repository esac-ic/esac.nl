<?php

namespace Database\Factories;

use App\Models\Committee;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommitteeFactory extends Factory
{
    protected $model = Committee::class;
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'abbreviation' => $this->faker->word(),
            'description' => $this->faker->text(),
            'email' => $this->faker->unique()->safeEmail(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
