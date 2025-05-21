<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    public function definition(): array
    {
        $now = Carbon::now();
        $loanDate = $now->copy()->subDays(rand(1, 30));
        $dueDate = $loanDate->copy()->addDays(rand(1, 14));
        $isOverdue = fake()->boolean(20);
        $returnDate = $isOverdue ? $dueDate->copy()->addDays(rand(1, 7)) : null;

        return [
            'user_id' => fake()->numberBetween(1, 10),
            'book_id' => fake()->numberBetween(1, 50),
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'return_date' => $returnDate,
            'is_overdue' => $isOverdue,
        ];
    }
} 