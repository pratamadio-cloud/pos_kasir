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
                'barcode' => '0000000000001',
                'category_id' => '2',
                'name' => 'Espresso',
                'price' => '18000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000002',
                'category_id' => '2',
                'name' => 'Americano',
                'price' => '20000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000003',
                'category_id' => '2',
                'name' => 'Cappuccino',
                'price' => '25000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000004',
                'category_id' => '2',
                'name' => 'Latte',
                'price' => '20000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000005',
                'category_id' => '2',
                'name' => 'Chocolate',
                'price' => '25000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000006',
                'category_id' => '1',
                'name' => 'Kentang Goreng',
                'price' => '12000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000007',
                'category_id' => '1',
                'name' => 'Waffle',
                'price' => '15000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000008',
                'category_id' => '3',
                'name' => 'Sandwich',
                'price' => '10000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000009',
                'category_id' => '1',
                'name' => 'Nasi Goreng',
                'price' => '15000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
            [
                'barcode' => '0000000000010',
                'category_id' => '1',
                'name' => 'Pasta',
                'price' => '18000',
                'photo' => '1.jpg',
                'stock' => '100',
            ],
        ];

        Products::insert($products);
    }
}
