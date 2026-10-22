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
            'body' => fake()->text(),
            'image_path' => '',
            'thumbnail_path' => '',
            'author' => fake()->firstName(),
        ];
    }
}
