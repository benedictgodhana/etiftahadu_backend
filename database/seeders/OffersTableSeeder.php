<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OffersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('offers')->insert([
            [
                'name' => '10% Discount on Commuter Pass',
                'expiry' => Carbon::now()->addDays(30)->toDateString(),
                'user_id' => 1, // Example user_id
            ],
            [
                'name' => 'Buy 1 Get 1 Free on Bus Tickets',
                'expiry' => Carbon::now()->addDays(60)->toDateString(),
                'user_id' => 1, // Example user_id
            ],
            [
                'name' => 'Free Ride on Weekends',
                'expiry' => Carbon::now()->addDays(90)->toDateString(),
                'user_id' => 1, // Example user_id
            ],
            [
                'name' => '20% Discount on Monthly Pass',
                'expiry' => Carbon::now()->addDays(45)->toDateString(),
                'user_id' => 1, // Example user_id
            ],
            [
                'name' => 'Seasonal Commuter Offer',
                'expiry' => Carbon::now()->addDays(120)->toDateString(),
                'user_id' => 1, // Example user_id
            ],
        ]);
    }
}
