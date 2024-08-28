<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Product 1', 'price' => 29.99, 'description' => 'Description for Product 1'],
            ['name' => 'Product 2', 'price' => 49.99, 'description' => 'Description for Product 2'],
            ['name' => 'Product 3', 'price' => 99.99, 'description' => 'Description for Product 3'],
        ]);
    }
}
