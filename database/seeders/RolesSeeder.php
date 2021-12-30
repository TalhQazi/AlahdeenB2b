<?php

namespace Database\Seeders;

use App\Constants\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => UserRoles::SUPER_ADMIN]);
        Role::create(['name' => UserRoles::ADMIN]);
        Role::create(['name' => UserRoles::CORPORATE]);
        Role::create(['name' => UserRoles::BUSINESS]);
        Role::create(['name' => UserRoles::INDIVIDUAL]);
        Role::create(['name' => UserRoles::BUYER]);
        Role::create(['name' => UserRoles::WAREHOUSE_OWNER]);
        Role::create(['name' => UserRoles::DRIVER]);
    }
}
