<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'user_id' => 1,
            'date' => Carbon::now(),
            'status' => 'no-paid',
            'total_price' => 120000
        ]);
        Order::create([
            'user_id' => 2,
            'date' => Carbon::now(),
            'status' => 'paid',
            'total_price' => 240000
        ]);
        Order::create([
            'user_id' => 3,
            'date' => Carbon::now(),
            'status' => 'paid',
            'total_price' => 220000
        ]);
    }
}
