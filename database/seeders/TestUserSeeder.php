<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear usuario de prueba si no existe
        $testUser = User::where('email', 'test@uniradical.com')->first();
        
        if (!$testUser) {
            User::create([
                'name' => 'Usuario de Prueba',
                'email' => 'test@uniradical.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'active' => true,
                'role' => 'administrador',
            ]);
            
            $this->command->info('Usuario de prueba creado: test@uniradical.com / password123');
        } else {
            $this->command->info('Usuario de prueba ya existe: test@uniradical.com');
        }
    }
}
