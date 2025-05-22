<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles first
        $this->call(RoleSeeder::class);

        // Create admin users
        $this->call(AdminSeeder::class);

        // Create regular users
        $this->call(UserSeeder::class);
        $this->call(DummyUsersSeeder::class);

        // Create books
        $this->call(BooksTableSeeder::class);

        // Create feedback data
        $this->call(DummyFeedbackSeeder::class);

        // Create library visits
        $this->call(DummyLibraryVisitsSeeder::class);
    }
}
