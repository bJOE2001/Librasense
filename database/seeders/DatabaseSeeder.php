<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Feedback;
use App\Models\VisitorAnalytics;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DummyUsersSeeder::class,
        ]);

        // Wrap everything in a transaction
        DB::transaction(function () {
            // Create roles first
            $this->call(RoleSeeder::class);

            // Create admin users
            $this->call(UserSeeder::class);

            // Create regular users
            $users = User::factory(10)->create();

            // Create books
            $books = Book::factory(50)->create();

            // Create loans using existing users and books
            foreach ($users as $user) {
                // Create 2-3 loans for each user
                $numLoans = rand(2, 3);
                for ($i = 0; $i < $numLoans; $i++) {
                    Loan::factory()->create([
                        'user_id' => $user->id,
                        'book_id' => $books->random()->id,
                    ]);
                }
            }

            // Create feedback for some loans
            foreach ($users as $user) {
                Feedback::factory(rand(1, 3))->create([
                    'user_id' => $user->id,
                ]);
            }

            // Create visitor analytics
            VisitorAnalytics::factory(50)->create([
                'user_id' => fn() => $users->random()->id,
            ]);
        });
    }
}
