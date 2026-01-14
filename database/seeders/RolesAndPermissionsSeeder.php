<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {

        // Crear roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'worker']);
        Role::create(['name' => 'customer']);
     
    }
}
