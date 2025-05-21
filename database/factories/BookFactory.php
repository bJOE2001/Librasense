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
            'isbn' => fake()->unique()->isbn13(),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Fiction', 'Non-Fiction', 'Science', 'History', 'Biography']),
            'quantity' => fake()->numberBetween(1, 10),
            'location' => fake()->randomElement(['Shelf A1', 'Shelf B2', 'Shelf C3', 'Shelf D4']),
            'is_available' => fake()->boolean(80),
        ];
    }
} 