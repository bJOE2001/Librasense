<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10),
            'subject' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'sentiment' => fake()->randomElement(['positive', 'neutral', 'negative']),
            'topics' => json_encode(['library', 'books', 'service']),
            'frequent_patterns' => json_encode(['pattern1', 'pattern2']),
            'is_anomaly' => fake()->boolean(10),
            'user_segment' => json_encode(['student', 'regular']),
            'trend_data' => json_encode([
                'visits' => fake()->numberBetween(1, 50),
                'borrows' => fake()->numberBetween(1, 20)
            ])
        ];
    }
} 