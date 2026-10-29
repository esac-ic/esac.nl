<?php

namespace Database\Factories;

use App\Models\HeaderSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeaderSlide>
 */
class HeaderSlideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'heading' => fake()->sentence(3),
            'message' => fake()->sentence(),
            'link' => '/events',
        ];
    }
}
