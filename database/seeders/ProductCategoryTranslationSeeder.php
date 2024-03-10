<?php

namespace Database\Seeders;

use App\Models\ProductCategoryTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategoryTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $category_translation = [
          [
              'product_category_id' => '1',
              'name' => 'Գիրք',
              'lang' => 'am'
          ],
          [
            'product_category_id' => '1',
            'name' => 'Книга',
            'lang' => 'ru'
          ],
          [
            'product_category_id' => '1',
            'name' => 'Book',
            'lang' => 'en'
          ],

          [
            'product_category_id' => '2',
            'name' => 'Հուշանվեր',
            'lang' => 'am'
          ],
          [
            'product_category_id' => '2',
            'name' => 'Сувенир',
            'lang' => 'ru'
          ],
          [
            'product_category_id' => '2',
            'name' => 'Souvenir',
            'lang' => 'en'
          ],
          [
            'product_category_id' => '3',
            'name' => 'Հագուստ',
            'lang' => 'am'
        ],
        [
          'product_category_id' => '3',
          'name' => 'Одежда',
          'lang' => 'ru'
        ],
        [
          'product_category_id' => '3',
          'name' => 'Сlothes',
          'lang' => 'en'
        ],
        [
          'product_category_id' => '4',
          'name' => 'Այլ',
          'lang' => 'am'
        ],
        [
          'product_category_id' => '4',
          'name' => 'Другой',
          'lang' => 'ru'
        ],
        [
          'product_category_id' => '4',
          'name' => 'Other',
          'lang' => 'en'
        ],
      ];
      foreach ($category_translation as $item) {

        ProductCategoryTranslation::updateOrInsert($item);
    }

    }
}
