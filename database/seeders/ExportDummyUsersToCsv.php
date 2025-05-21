<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ExportDummyUsersToCsv extends Seeder
{
    public function run(): void
    {
        $file = fopen(base_path('dummy_users.csv'), 'w');
        fputcsv($file, ['email', 'password']);

        $excluded = [
            'admin@librasense.com',
            'student@librasense.com',
            'nonstudent@librasense.com',
        ];

        $users = User::whereNotIn('email', $excluded)->get();
        foreach ($users as $user) {
            fputcsv($file, [$user->email, 'password123']);
        }
        fclose($file);
    }
} 