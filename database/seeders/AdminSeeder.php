<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@librasense.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
            'phone' => '1234567890',
            'address' => 'Admin Address',
            'is_active' => true,
        ]);
    }
} 