<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Fiction', 'Non-Fiction', 'Science', 'History', 'Biography']),
            'quantity' => fake()->numberBetween(1, 10),
            'is_available' => fake()->boolean(80),
        ];
    }
} 