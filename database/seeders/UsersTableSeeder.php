<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Make sure to use the correct model
use Illuminate\Support\Facades\Hash;  // Import Hash facade to hash passwords

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create multiple users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'john_doe',
            'password' => Hash::make('password123'),  // Always hash passwords
            'phone' => '123-456-7890',

        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'username' => 'jane_smith',
            'password' => Hash::make('password456'),
            'phone' => '987-654-3210',
           
        ]);

        // Add more users as needed
    }
}
