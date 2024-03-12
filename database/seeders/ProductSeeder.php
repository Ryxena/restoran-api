<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Espresso',
            'stock' => 10,
            'category_id' => 2,
            'description' => 'Kopi dengan rasa yang tinggi',
            'price' => 120000
        ]);
        Product::create([
            'name' => 'Kopi Kapal Api',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Kopi dengan rasa kayu kapal',
            'price' => 20000
        ]);
        Product::create([
            'name' => 'Kopi Imitasi',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Kopi ngga bisa diminum',
            'price' => 100000
        ]);
    }
}
