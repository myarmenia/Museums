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
            ['name' => 'yerevan'],
            ['name' => 'aragatsotn'],
            ['name' => 'ararat'],
            ['name' => 'armavir'],
            ['name' => 'gegharkunik'],
            ['name' => 'kotayk'],
            ['name' => 'lori'],
            ['name' => 'shirak'],
            ['name' => 'syunik'],
            ['name' => 'tavush'],
            ['name' => 'vayots_dzor'],
        ];
        
        Region::insert($regionInsert);
    }
}
