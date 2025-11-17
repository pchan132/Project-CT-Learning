<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Teacher user
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@lms.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Create Student users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@lms.com",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }
    }
}