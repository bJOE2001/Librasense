<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = \App\Models\Role::where('name', 'student')->first();
        $nonStudentRole = \App\Models\Role::where('name', 'non-student')->first();

        // Create student user
        \App\Models\User::create([
            'name' => 'Student User',
            'email' => 'student@librasense.com',
            'password' => \Illuminate\Support\Facades\Hash::make('student123'),
            'role_id' => $studentRole->id,
            'school' => 'University of Mindanao – Tagum College (UMTC)',
            'phone' => '+639123456789',
            'address' => '123 Student St, Tagum City',
            'is_active' => true,
        ]);

        // Create non-student user
        \App\Models\User::create([
            'name' => 'Non-Student User',
            'email' => 'nonstudent@librasense.com',
            'password' => \Illuminate\Support\Facades\Hash::make('nonstudent123'),
            'role_id' => $nonStudentRole->id,
            'school' => null,
            'phone' => '+639987654321',
            'address' => '456 Nonstudent Ave, Tagum City',
            'is_active' => true,
        ]);
    }
} 