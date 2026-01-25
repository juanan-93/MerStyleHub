<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //SEEDER PARA CREAR COLORIMETRÍAS
        $this->call(ColorimetrySeeder::class);
        
        //SEEDER PARA CREAR ROLES
        $this->call(RolesAndPermissionsSeeder::class);

    }
}
