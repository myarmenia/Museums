<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionTableSeeder::class,
            CreateUserSeeder::class,
            CreateRolesSeeder::class,
            CountriesSeeder::class,
            RegionSeeder::class,
            TicketTypeSeeder::class,
            ProductCategorySeeder::class,
            ProductCategoryTranslationSeeder::class,
            TurnstilesSeeder::class

    ]);
    }
}
