<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $ticket_types = [
          [
            'id' => 1,
            'name' => 'standart',
            'coefficient' => 1,
            'min_quantity' => 1,
            'max_quantity' => null

          ],
          [
              'id' => 2,
              'name' => 'discount',
              'coefficient' => 0.5,
              'min_quantity' => 1,
              'max_quantity' => null

            ],
            [
              'id' => 3,
              'name' => 'free',
              'coefficient' => 0,
              'min_quantity' => 1,
              'max_quantity' => null

            ],
            [
              'id' => 4,
              'name' => 'subscription',
              'coefficient' => null,
              'min_quantity' => 1,
              'max_quantity' => 5

            ],
            [
              'id' => 5,
              'name' => 'united',
              'coefficient' => null,
              'min_quantity' => 1,
              'max_quantity' => 5

            ],
            [
              'id' => 6,
              'name' => 'coupon_1',
              'coefficient' => 0.05,
              'min_quantity' => 100,
              'max_quantity' => 500

            ],
            [
              'id' => 7,
              'name' => 'coupon_2',
              'coefficient' => 0.1,
              'min_quantity' => 501,
              'max_quantity' => null

            ],
            [
              'id' => 8,
              'name' => 'event',
              'coefficient' => 1,
              'min_quantity' => 1,
              'max_quantity' => 10

            ]


        ];


        foreach ($ticket_types as $type) {
          TicketType::updateOrCreate($type);
        }
    }
}
