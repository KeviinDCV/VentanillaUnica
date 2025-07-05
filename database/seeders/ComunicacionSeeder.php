<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comunicacion;

class ComunicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comunicaciones = [
            [
                'nombre' => 'Físico',
                'codigo' => 'fisico',
                'descripcion' => 'Documento físico recibido en ventanilla o por correo postal',
                'activo' => true,
            ],
            [
                'nombre' => 'Verbal',
                'codigo' => 'verbal',
                'descripcion' => 'Comunicación verbal recibida por teléfono o presencialmente',
                'activo' => true,
            ],
            [
                'nombre' => 'Correo Electrónico',
                'codigo' => 'email',
                'descripcion' => 'Comunicación recibida por correo electrónico',
                'activo' => true,
            ],
            [
                'nombre' => 'Fax',
                'codigo' => 'fax',
                'descripcion' => 'Documento recibido por fax',
                'activo' => true,
            ],
            [
                'nombre' => 'Plataforma Web',
                'codigo' => 'web',
                'descripcion' => 'Comunicación recibida a través de plataforma web institucional',
                'activo' => true,
            ],
            [
                'nombre' => 'Mensajería Instantánea',
                'codigo' => 'chat',
                'descripcion' => 'Comunicación recibida por WhatsApp, Telegram u otros medios de mensajería',
                'activo' => true,
            ],
            [
                'nombre' => 'Redes Sociales',
                'codigo' => 'social',
                'descripcion' => 'Comunicación recibida a través de redes sociales oficiales',
                'activo' => true,
            ],
            [
                'nombre' => 'Otro',
                'codigo' => 'otro',
                'descripcion' => 'Otros medios de comunicación no especificados',
                'activo' => true,
            ],
        ];

        foreach ($comunicaciones as $comunicacion) {
            Comunicacion::create($comunicacion);
        }
    }
}
