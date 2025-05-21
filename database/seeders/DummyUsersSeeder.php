<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DummyUsersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
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

        // List of first names
        $firstNames = [
            'John', 'Mary', 'James', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda',
            'William', 'Elizabeth', 'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica',
            'Thomas', 'Sarah', 'Charles', 'Karen', 'Christopher', 'Nancy', 'Daniel', 'Lisa',
            'Matthew', 'Margaret', 'Anthony', 'Betty', 'Mark', 'Sandra', 'Donald', 'Ashley',
            'Steven', 'Kimberly', 'Paul', 'Emily', 'Andrew', 'Donna', 'Joshua', 'Michelle',
            'Kevin', 'Laura', 'Brian', 'Amanda', 'George', 'Melissa', 'Edward', 'Deborah',
            'Ronald', 'Stephanie', 'Timothy', 'Rebecca', 'Jason', 'Sharon', 'Jeffrey', 'Kathleen',
            'Ryan', 'Carolyn', 'Jacob', 'Janet', 'Gary', 'Ruth', 'Nicholas', 'Catherine',
            'Eric', 'Virginia', 'Jonathan', 'Christine', 'Stephen', 'Samantha', 'Larry', 'Carol',
            'Justin', 'Anna', 'Scott', 'Brenda', 'Brandon', 'Pamela', 'Benjamin', 'Emma',
            'Samuel', 'Nicole', 'Gregory', 'Helen', 'Alexander', 'Angela', 'Patrick', 'Katherine',
            'Frank', 'Debra', 'Raymond', 'Shirley', 'Jack', 'Amy', 'Dennis', 'Anna'
        ];

        // List of last names
        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
            'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
            'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson',
            'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker',
            'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores',
            'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell',
            'Carter', 'Roberts', 'Gomez', 'Phillips', 'Evans', 'Turner', 'Diaz', 'Parker',
            'Cruz', 'Edwards', 'Collins', 'Stewart', 'Morris', 'Morales', 'Murphy', 'Cook',
            'Rogers', 'Cooper', 'Peterson', 'Bailey', 'Reed', 'Kelly', 'Howard', 'Ramos',
            'Kim', 'Cox', 'Ward', 'Torres', 'Peterson', 'Gray', 'Ramirez', 'James', 'Watson',
            'Brooks', 'Kelly', 'Sanders', 'Price', 'Bennett', 'Wood', 'Barnes', 'Ross',
            'Henderson', 'Coleman', 'Jenkins', 'Perry', 'Powell', 'Long', 'Patterson', 'Hughes'
        ];

        // Get role IDs
        $studentRole = \App\Models\Role::where('name', 'student')->first();
        $nonStudentRole = \App\Models\Role::where('name', 'non-student')->first();

        // Generate 100 dummy users
        for ($i = 0; $i < 100; $i++) {
            $firstName = $faker->randomElement($firstNames);
            $lastName = $faker->randomElement($lastNames);
            $roleName = $faker->randomElement(['student', 'non-student']);
            $role_id = $roleName === 'student' ? $studentRole->id : $nonStudentRole->id;
            
            // Generate a more realistic email
            $email = strtolower($firstName . '.' . $lastName . $faker->numberBetween(1, 999) . '@' . $faker->randomElement(['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com']));
            
            // Generate a more realistic phone number
            $phone = '+63' . $faker->numberBetween(900000000, 999999999);
            
            // Generate a more realistic address
            $address = $faker->streetAddress() . ', ' . $faker->city() . ', ' . $faker->state() . ' ' . $faker->postcode();
            
            User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role_id' => $role_id,
                'school' => $roleName === 'student' ? $faker->randomElement($schools) : null,
                'phone' => $phone,
                'address' => $address,
                'email_verified_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
} 