<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Makanan'],
            ['category_name' => 'Minuman'],
            ['category_name' => 'Snack'],
            ['category_name' => 'Lainnya'],
        ];

        Category::insert($categories);
    }
}
