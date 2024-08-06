<?php

namespace Database\Seeders;

use App\Models\Turnstile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'info@webex.am')->first();

        if(!$user){
            $user = User::updateOrCreate(
              ['email' => 'info@webex.am'],
              [
                'name' => 'Admin',
                'surname' => 'Adminyan',
                'status' => 1,
                'phone' => '+37494444444',
                'password' => bcrypt('123456')
              ]);

            $role = Role::updateOrCreate(['name' => 'super_admin'], ['g_name' => 'super_admin', 'interface' => 'admin' ]);

            $permissions = Permission::pluck('id', 'id')->all();

            $role->syncPermissions($permissions);

            $user->assignRole([$role->id]);
        }

        $userVisitor = User::updateOrCreate(
          ['email' => 'visit@mail.ru'],
          [
            'name' => 'Visitor',
            'surname' => 'Visitoryan',
            'status' => 1,
            'phone' => '+37455555555',

            'password' => bcrypt('123456')
          ]);

        $roleVisitor = Role::updateOrCreate(['name' => 'visitor'], ['g_name' => 'web', 'interface' => 'web' ]);

        $userVisitor->assignRole([$roleVisitor->id]);

    }
}
