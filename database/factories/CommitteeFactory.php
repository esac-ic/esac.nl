<?php

namespace Database\Factories;

use App\Models\Committee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommitteeFactory extends Factory
{
    protected $model = Committee::class;
    
    public function definition(): array
    {
        $name = ucfirst($this->faker->word()) . " Committee";
        $abbr = strtoupper($name[0]) . "C";
        return [
            'name' => $name,
            'abbreviation' => $abbr,
            'email' => strtolower($abbr) . '@esac.nl',
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
