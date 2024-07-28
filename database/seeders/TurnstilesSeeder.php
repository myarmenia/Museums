<?php

namespace Database\Seeders;

use App\Models\Turnstile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurnstilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $turnstiles = [
                          [
                            'id' => 1,
                            'museum_id' => 1,
                            'mac' => '12:45:ab:33:30'
                          ],
                          [
                            'id' => 2,
                            'museum_id' => 2,
                            'mac' => '12:45:ab:33:32'
                          ]

        ];


        foreach ($turnstiles as $turnstile) {
          Turnstile::updateOrCreate(['mac' => $turnstile['mac']], $turnstile);
        }
    }
}
