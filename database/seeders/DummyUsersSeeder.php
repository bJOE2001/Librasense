<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    public function run(): void
    {
        // List of schools
        $schools = [
            'University of Mindanao – Tagum College (UMTC)',
            'University of Southeastern Philippines – Tagum-Mabini Campus',
            'St. Mary\'s College of Tagum, Inc.',
            'Arriesgado College Foundation, Inc. (ACFI)',
            'North Davao College – Tagum Foundation',
            'Liceo de Davao – Tagum City',
            'Tagum Doctors College, Inc.',
            'ACES Tagum College',
            'STI College – Tagum',
            'Queen of Apostles College Seminary',
            'Tagum City College of Science and Technology Foundation, Inc.',
            'Tagum Longford College',
            'St. Thomas More School of Law and Business',
            'Philippine Nippon Technical College',
            'CARD-MRI Development Institute, Inc. (CMDI)',
            'AMA Computer Learning Center – Tagum Campus (ACLC)',
            'Computer Innovation Center (CIC)',
            'Philippine Institute of Technical Education (PITE)',
            'St. John Learning Center of Tagum City',
            'St. Michael Technical School'
        ];

        // Get role IDs
        $studentRole = \App\Models\Role::where('name', 'student')->first();
        $nonStudentRole = \App\Models\Role::where('name', 'non-student')->first();

        // Create 10 dummy users with static data
        $users = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@librasense.com',
                'role' => 'student',
                'school' => $schools[0],
                'phone' => '+639123456789',
                'address' => '123 Main St, Tagum City',
            ],
            [
                'name' => 'Mary Johnson',
                'email' => 'mary.johnson@librasense.com',
                'role' => 'student',
                'school' => $schools[1],
                'phone' => '+639234567890',
                'address' => '456 Oak St, Tagum City',
            ],
            [
                'name' => 'James Williams',
                'email' => 'james.williams@librasense.com',
                'role' => 'non-student',
                'school' => null,
                'phone' => '+639345678901',
                'address' => '789 Pine St, Tagum City',
            ],
            [
                'name' => 'Patricia Brown',
                'email' => 'patricia.brown@librasense.com',
                'role' => 'student',
                'school' => $schools[2],
                'phone' => '+639456789012',
                'address' => '321 Elm St, Tagum City',
            ],
            [
                'name' => 'Robert Jones',
                'email' => 'robert.jones@librasense.com',
                'role' => 'non-student',
                'school' => null,
                'phone' => '+639567890123',
                'address' => '654 Maple St, Tagum City',
            ],
            [
                'name' => 'Jennifer Garcia',
                'email' => 'jennifer.garcia@librasense.com',
                'role' => 'student',
                'school' => $schools[3],
                'phone' => '+639678901234',
                'address' => '987 Cedar St, Tagum City',
            ],
            [
                'name' => 'Michael Miller',
                'email' => 'michael.miller@librasense.com',
                'role' => 'non-student',
                'school' => null,
                'phone' => '+639789012345',
                'address' => '147 Birch St, Tagum City',
            ],
            [
                'name' => 'Linda Davis',
                'email' => 'linda.davis@librasense.com',
                'role' => 'student',
                'school' => $schools[4],
                'phone' => '+639890123456',
                'address' => '258 Walnut St, Tagum City',
            ],
            [
                'name' => 'William Rodriguez',
                'email' => 'william.rodriguez@librasense.com',
                'role' => 'non-student',
                'school' => null,
                'phone' => '+639901234567',
                'address' => '369 Cherry St, Tagum City',
            ],
            [
                'name' => 'Elizabeth Martinez',
                'email' => 'elizabeth.martinez@librasense.com',
                'role' => 'student',
                'school' => $schools[5],
                'phone' => '+639012345678',
                'address' => '741 Spruce St, Tagum City',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'role_id' => $userData['role'] === 'student' ? $studentRole->id : $nonStudentRole->id,
                'school' => $userData['school'],
                'phone' => $userData['phone'],
                'address' => $userData['address'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 