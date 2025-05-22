<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        if (!Role::where('name', 'student')->exists()) {
            Role::create([
                'name' => 'student',
                'description' => 'Student user with access to library resources'
            ]);
        }

        if (!Role::where('name', 'non-student')->exists()) {
            Role::create([
                'name' => 'non-student',
                'description' => 'Regular user with access to library resources'
            ]);
        }

        if (!Role::where('name', 'admin')->exists()) {
            Role::create([
                'name' => 'admin',
                'description' => 'Administrator with full access to the system'
            ]);
        }
    }
} 