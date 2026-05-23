<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Administrador
        User::create([
            'name' => 'Fernando Admin',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // [cite: 30]
        ]);

        // Crear Cajero
        User::create([
            'name' => 'Juan Cajero',
            'email' => 'caja@restaurant.com',
            'password' => Hash::make('caja123'),
            'role' => 'cajero', // [cite: 30]
        ]);

        // Crear Mesero
        User::create([
            'name' => 'Pedro Mesero',
            'email' => 'mesero@restaurant.com',
            'password' => Hash::make('mesero123'),
            'role' => 'mesero', // [cite: 30]
        ]);
    }
}