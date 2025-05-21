<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'student',
            'description' => 'Student user with access to library resources'
        ]);

        Role::create([
            'name' => 'non-student',
            'description' => 'Regular user with access to library resources'
        ]);

        Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access to the system'
        ]);
    }
} 