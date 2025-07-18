<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'documento_identidad' => '12345678',
            'email' => 'admin@uniradical.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        // Crear usuario de ventanilla
        User::create([
            'name' => 'Usuario Ventanilla',
            'documento_identidad' => '87654321',
            'email' => 'ventanilla@uniradical.com',
            'password' => Hash::make('ventanilla123'),
            'role' => 'ventanilla',
            'active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
