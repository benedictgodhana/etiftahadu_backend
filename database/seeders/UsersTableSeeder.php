<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'john_doe',
            'password' => Hash::make('password123'),
            'phone' => '123-456-7890',
        ]);

        $john->assignRole('Super Admin');

        // Create Admin
        $jane = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'username' => 'jane_smith',
            'password' => Hash::make('password456'),
            'phone' => '987-654-3210',
        ]);

        $jane->assignRole('Admin');
    }
}
