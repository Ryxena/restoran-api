<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderDetail::create([
            'order_id' => 1,
            'product_id' => 2,
            'product_count' => 1,
            'total_price' => 20000,
        ]);
        OrderDetail::create([
            'order_id' => 1,
            'product_id' => 3,
            'product_count' => 1,
            'total_price' => 100000
        ]);
        OrderDetail::create([
            'order_id' => 2,
            'product_id' => 1,
            'product_count' => 2,
            'total_price' => 240000
        ]);
        OrderDetail::create([
            'order_id' => 3,
            'product_id' => 2,
            'product_count' => 3,
            'total_price' => 40000
        ]);
        OrderDetail::create([
            'order_id' => 3,
            'product_id' => 3,
            'product_count' => 1,
            'total_price' => 100000
        ]);
    }
}
