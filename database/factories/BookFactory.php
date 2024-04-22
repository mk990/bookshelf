<?php

namespace Database\Factories;

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
            'user_id' => 1,
            'title' => fake()->name(),
            'author' => fake()->name(),
            'price' => fake()->numberBetween(0,100),
            'picture' => fake()->imageUrl()
            
        ];
    }
}
