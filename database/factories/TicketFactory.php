<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'                 => fake()->name(),
            'user_id'               => 1,
            'open'                  => 2,
            'stars'                 => fake()->buildingNumber(),
            'last_message'          => fake()->dateTime()
        ];
    }
}
