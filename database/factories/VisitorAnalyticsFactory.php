<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorAnalyticsFactory extends Factory
{
    public function definition(): array
    {
        $entryTime = fake()->dateTimeBetween('-30 days', 'now');
        $exitTime = fake()->dateTimeBetween($entryTime, '+4 hours');

        return [
            'user_id' => fake()->numberBetween(1, 10),
            'visitor_type' => fake()->randomElement(['student', 'non_student', 'guest']),
            'entry_time' => $entryTime,
            'exit_time' => $exitTime,
            'location' => fake()->randomElement(['Main Library', 'Reading Room', 'Computer Lab', 'Study Area']),
            'purpose' => fake()->randomElement(['Study', 'Research', 'Borrow Books', 'Return Books', 'Use Computers']),
            'qr_code' => fake()->unique()->uuid(),
        ];
    }
} 