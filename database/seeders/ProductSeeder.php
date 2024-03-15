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
            'name' => 'Americano Coffee',
            'image' => '01.jpg',
            'stock' => 10,
            'category_id' => 2,
            'description' => 'Milk with vanilla flavored',
            'price' => 50000
        ]);
        Product::create([
            'name' => 'Milk Cream Coffee',
            'image' => '02.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 30000
        ]);
        Product::create([
            'name' => 'Fresh Black Coffee',
            'image' => '03.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 90000
        ]);
        Product::create([
            'name' => 'Milk Cream Coffee',
            'image' => '04.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 90000
        ]);
        Product::create([
            'name' => 'Cappuccino Indoneca',
            'image' => '05.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 90000
        ]);
        Product::create([
            'name' => 'Special Raw Coffee',
            'image' => '06.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 90000
        ]);
        Product::create([
            'name' => 'Cappuccino Arabica',
            'image' => '07.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 90000
        ]);
        Product::create([
            'name' => 'Cold Coffee',
            'image' => '08.jpg',
            'stock' => 10,
            'category_id' => 1,
            'description' => 'Milk with vanilla flavored',
            'price' => 90000
        ]);
    }
}
