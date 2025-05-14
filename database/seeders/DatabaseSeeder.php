<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the role seeder
        $this->call([
            RoleSeeder::class,
            UsersTableSeeder::class,
            OffersTableSeeder::class,
            // Add any other seeders you have
            // Any other seeders you have
        ]);
    }
}
