<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product_categories = [
          [
            'id' => 1,
            'key' => 'book'

          ],
          [
            'id' => 2,
            'key' => 'souvenir'
          ],
          [
            'id' => 3,
            'key' => 'clothes'
          ],
          [
            'id' => 4,
            'key' => 'other'
          ]

        ];


        foreach ($product_categories as $category) {
          ProductCategory::updateOrCreate($category);
        }
    }
}
