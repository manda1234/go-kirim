<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BonusRate;          

class BonusRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BonusRate::insert([['tier_name' => 'bronze', 'min_orders' => 1, 'max_orders' => 4, 'bonus_amount' => 0, 'rating_bonus' => 5000, 'rating_threshold' => 4.8], ['tier_name' => 'silver', 'min_orders' => 5, 'max_orders' => 9, 'bonus_amount' => 5000, 'rating_bonus' => 5000, 'rating_threshold' => 4.8], ['tier_name' => 'gold', 'min_orders' => 10, 'max_orders' => 19, 'bonus_amount' => 15000, 'rating_bonus' => 5000, 'rating_threshold' => 4.8], ['tier_name' => 'platinum', 'min_orders' => 20, 'max_orders' => null, 'bonus_amount' => 30000, 'rating_bonus' => 5000, 'rating_threshold' => 4.8]]);
    }
}
