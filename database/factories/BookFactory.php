<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\BookLocation;
use App\Models\Category;
use App\Models\Language;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->randomLetter(),
            'category_id' => $this->faker->randomElement(Category::pluck('id')->toArray()),
            'author_id' => $this->faker->randomElement(Author::pluck('id')->toArray()),
            'publisher_id' => $this->faker->randomElement(Publisher::pluck('id')->toArray()),
            'language_id' => $this->faker->randomElement(Language::pluck('id')->toArray()),
            'location_id' => $this->faker->randomElement(BookLocation::pluck('id')->toArray()),
            'pages' => $this->faker->numberBetween(100,1000),
            'quantity' => $this->faker->numberBetween(1, 200),
            'published_year' => $this->faker->dateTime(),
            'description' => $this->faker->realText(),
            'fee_per_day' => $this->faker->numberBetween(1000, 10000),
        ];
    }
}
