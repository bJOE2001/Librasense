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

        // List of real names
        $firstNames = [
            'Maria', 'John', 'James', 'Mary', 'Robert', 'Patricia', 'Michael', 'Jennifer', 'William', 'Linda',
            'David', 'Elizabeth', 'Richard', 'Barbara', 'Joseph', 'Susan', 'Thomas', 'Jessica', 'Charles', 'Sarah',
            'Christopher', 'Karen', 'Daniel', 'Nancy', 'Matthew', 'Lisa', 'Anthony', 'Margaret', 'Mark', 'Betty',
            'Donald', 'Sandra', 'Steven', 'Ashley', 'Paul', 'Kimberly', 'Andrew', 'Emily', 'Joshua', 'Donna',
            'Kenneth', 'Michelle', 'Kevin', 'Dorothy', 'Brian', 'Carol', 'George', 'Amanda', 'Edward', 'Melissa',
            'Ronald', 'Deborah', 'Timothy', 'Stephanie', 'Jason', 'Rebecca', 'Ryan', 'Sharon', 'Jacob', 'Laura',
            'Gary', 'Cynthia', 'Nicholas', 'Kathleen', 'Eric', 'Amy', 'Jonathan', 'Shirley', 'Stephen', 'Angela',
            'Larry', 'Helen', 'Justin', 'Anna', 'Scott', 'Brenda', 'Brandon', 'Pamela', 'Benjamin', 'Nicole',
            'Samuel', 'Emma', 'Gregory', 'Samantha', 'Frank', 'Katherine', 'Alexander', 'Christine', 'Patrick', 'Debra',
            'Raymond', 'Rachel', 'Jack', 'Catherine', 'Dennis', 'Carolyn', 'Jerry', 'Janet', 'Tyler', 'Ruth'
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
            'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
            'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson',
            'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores',
            'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell', 'Carter', 'Roberts',
            'Gomez', 'Phillips', 'Evans', 'Turner', 'Diaz', 'Parker', 'Cruz', 'Edwards', 'Collins', 'Reyes',
            'Stewart', 'Morris', 'Morales', 'Murphy', 'Cook', 'Rogers', 'Cooper', 'Peterson', 'Bailey', 'Reed',
            'Kelly', 'Howard', 'Ramos', 'Kim', 'Cox', 'Ward', 'Torres', 'Peterson', 'Gray', 'Ramirez',
            'James', 'Watson', 'Brooks', 'Sanders', 'Price', 'Bennett', 'Wood', 'Barnes', 'Ross', 'Henderson'
        ];

        // Create 100 dummy users
        for ($i = 0; $i < 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . '@librasense.com');
            $isStudent = rand(0, 1) === 1;
            $school = $isStudent ? $schools[array_rand($schools)] : null;
            $phone = '+63' . rand(9000000000, 9999999999);
            $streetNames = ['Main', 'Oak', 'Pine', 'Elm', 'Maple', 'Cedar', 'Birch', 'Walnut', 'Cherry', 'Spruce'];
            $address = rand(1, 999) . ' ' . $streetNames[array_rand($streetNames)] . ' St, Tagum City';

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role_id' => $isStudent ? $studentRole->id : $nonStudentRole->id,
                'school' => $school,
                'phone' => $phone,
                'address' => $address,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 