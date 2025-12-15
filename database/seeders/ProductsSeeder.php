<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'barcode' => '01',
                'category_id' => '2',
                'name' => 'Espresso',
                'price' => '18000',
                'stock' => '100',
            ],
            [
                'barcode' => '02',
                'category_id' => '2',
                'name' => 'Americano',
                'price' => '20000',
                'stock' => '100',
            ],
            [
                'barcode' => '03',
                'category_id' => '2',
                'name' => 'Cappuccino',
                'price' => '25000',
                'stock' => '100',
            ],
            [
                'barcode' => '04',
                'category_id' => '2',
                'name' => 'Latte',
                'price' => '20000',
                'stock' => '100',
            ],
            [
                'barcode' => '05',
                'category_id' => '2',
                'name' => 'Chocolate',
                'price' => '25000',
                'stock' => '100',
            ],
            [
                'barcode' => '06',
                'category_id' => '1',
                'name' => 'Kentang Goreng',
                'price' => '12000',
                'stock' => '100',
            ],
            [
                'barcode' => '07',
                'category_id' => '1',
                'name' => 'Waffle',
                'price' => '15000',
                'stock' => '100',
            ],
            [
                'barcode' => '08',
                'category_id' => '3',
                'name' => 'Sandwich',
                'price' => '10000',
                'stock' => '100',
            ],
            [
                'barcode' => '09',
                'category_id' => '1',
                'name' => 'Nasi Goreng',
                'price' => '15000',
                'stock' => '100',
            ],
            [
                'barcode' => '10',
                'category_id' => '1',
                'name' => 'Pasta',
                'price' => '18000',
                'stock' => '100',
            ],
        ];

        Products::insert($products);
    }
}
