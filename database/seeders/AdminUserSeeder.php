<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@merstylehub.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Contraseña1234'),
            ]
        );

        // Asignar rol de administrador
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $this->command->info('Usuario administrador creado:');
        $this->command->info('Email: admin@merstylehub.com');
        $this->command->info('Password: password');
    }
}
