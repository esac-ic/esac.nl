<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'year' => fake()->year(),
            'type' => fake()->randomElement(['Guide', 'Topo', 'Novel']),
            'country' => fake()->countryCode(),
            'code' => fake()->unique()->bothify('??##'),
        ];
    }
}
