<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoSolicitud;

class TipoSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposSolicitud = [
            // Tipos para radicación de entrada
            [
                'nombre' => 'Físico',
                'codigo' => 'fisico',
                'descripcion' => 'Documentos recibidos en formato físico o impreso',
                'activo' => true,
            ],
            [
                'nombre' => 'Verbal',
                'codigo' => 'verbal',
                'descripcion' => 'Comunicaciones verbales que requieren radicación',
                'activo' => true,
            ],

            // Tipos para radicación de salida (documentos oficiales)
            [
                'nombre' => 'Oficio',
                'codigo' => 'oficio',
                'descripcion' => 'Comunicación oficial entre entidades públicas',
                'activo' => true,
            ],
            [
                'nombre' => 'Carta',
                'codigo' => 'carta',
                'descripcion' => 'Comunicación formal dirigida a personas naturales o jurídicas',
                'activo' => true,
            ],
            [
                'nombre' => 'Circular',
                'codigo' => 'circular',
                'descripcion' => 'Comunicación dirigida a múltiples destinatarios',
                'activo' => true,
            ],
            [
                'nombre' => 'Resolución',
                'codigo' => 'resolucion',
                'descripcion' => 'Acto administrativo que resuelve situaciones específicas',
                'activo' => true,
            ],
            [
                'nombre' => 'Certificación',
                'codigo' => 'certificacion',
                'descripcion' => 'Documento que certifica hechos o situaciones',
                'activo' => true,
            ],
            [
                'nombre' => 'Otro',
                'codigo' => 'otro',
                'descripcion' => 'Otros tipos de comunicación no especificados',
                'activo' => true,
            ],
        ];

        foreach ($tiposSolicitud as $tipo) {
            TipoSolicitud::firstOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );
        }
    }
}
