<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $regionInsert = [
          'yerevan',
          'aragatsotn',
          'ararat',
          'armavir',
          'gegharkunik',
          'kotayk',
          'lori',
          'shirak',
          'syunik',
          'tavush',
          'vayots_dzor'
        ];


        foreach ($regionInsert as $region) {
            Region::updateOrInsert(['name' => $region]);
        }

    }
}
