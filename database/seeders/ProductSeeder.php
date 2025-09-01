<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        product::create([
            'name' => 'Laptop',
            'price' => '15000000',
            'description' => 'Laptop Gaming Terbaru',
            'stock' => '10'       
        ]);
        product::create([
            'name' => 'Monitor',
            'price' => '25000000',
            'description' => 'Monitor Gaming Terbaru',
            'stock' => '5'       
        ]);
        product::create([
            'name' => 'Keyboard',
            'price' => '15000000',
            'description' => 'Keyboard Gaming Terbaru',
            'stock' => '8'       
        ]);
    }
}
