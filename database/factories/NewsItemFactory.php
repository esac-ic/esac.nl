<?php

namespace Database\Factories;

use App\Models\NewsItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewsItem>
 */
class NewsItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'text' => fake()->text(),
            'image_url' => fake()->imageUrl(),
            'thumbnail_url' => fake()->imageUrl(),
            'author' => fake()->firstName(),
        ];
    }
}
