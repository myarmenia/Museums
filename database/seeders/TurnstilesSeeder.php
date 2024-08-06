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
                            'mac' => '12:45:ab:33:30',
                            'local_ip' => '192.168.231.11'
                          ],

                          [
                            'id' => 2,
                            'museum_id' => 2,
                            'mac' => '84:FC:E6:00:D3:D4',
                            'local_ip' => '192.168.231.12'
                          ]

        ];


        foreach ($turnstiles as $turnstile) {
          Turnstile::updateOrCreate(['id' => $turnstile['id'], 'mac' => $turnstile['mac']], $turnstile);
        }
    }
}
