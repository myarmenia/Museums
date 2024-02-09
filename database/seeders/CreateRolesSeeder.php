<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            [
                'name' => 'super_admin',
                'g_name' => 'admin',
                'interface' => 'admin'
            ],
            [
                'name' => 'general_manager',
                'g_name' => 'admin',
                'interface' => 'admin'
            ],
            [
                'name' => 'chief_accountant',
                'g_name' => 'admin',
                'interface' => 'admin'
            ],
            [
                'name' => 'head_technic',
                'g_name' => 'admin',
                'interface' => 'admin'
            ],
            [
                'name' => 'museum_admin',
                'g_name' => 'admin',
                'interface' => 'museum'
            ],

            [
                'name' => 'manager',
                'g_name' => 'museum',
                'interface' => 'museum'
            ],
            [
                'name' => 'accountant',
                'g_name' => 'museum',
                'interface' => 'museum'
            ],
            [
                'name' => 'cashier',
                'g_name' => 'museum',
                'interface' => 'museum'
            ],
            [
                'name' => 'content_manager',
                'g_name' => 'museum',
                'interface' => 'museum'
            ],
            [
                'name' => 'technical_manager',
                'g_name' => 'museum',
                'interface' => 'museum'
            ],

            [
                'name' => 'visitor',
                'g_name' => 'web',
                'interface' => 'web'
            ],


        ];

        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }

    }
}
