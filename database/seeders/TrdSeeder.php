<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trd;

class TrdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trds = [
            [
                'codigo' => 'TRD001',
                'serie' => 'Correspondencia',
                'subserie' => 'Correspondencia Recibida',
                'asunto' => 'Documentos de correspondencia externa recibida',
                'retencion_archivo_gestion' => 2,
                'retencion_archivo_central' => 5,
                'disposicion_final' => 'eliminacion',
                'observaciones' => 'Correspondencia general recibida de entidades externas',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD002',
                'serie' => 'Correspondencia',
                'subserie' => 'Correspondencia Enviada',
                'asunto' => 'Documentos de correspondencia externa enviada',
                'retencion_archivo_gestion' => 2,
                'retencion_archivo_central' => 5,
                'disposicion_final' => 'eliminacion',
                'observaciones' => 'Correspondencia general enviada a entidades externas',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD003',
                'serie' => 'Comunicaciones Internas',
                'subserie' => 'Memorandos',
                'asunto' => 'Memorandos internos entre dependencias',
                'retencion_archivo_gestion' => 1,
                'retencion_archivo_central' => 3,
                'disposicion_final' => 'eliminacion',
                'observaciones' => 'Comunicaciones internas de carácter administrativo',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD004',
                'serie' => 'Quejas y Reclamos',
                'subserie' => 'Quejas de Usuarios',
                'asunto' => 'Quejas presentadas por usuarios del servicio',
                'retencion_archivo_gestion' => 3,
                'retencion_archivo_central' => 7,
                'disposicion_final' => 'conservacion_total',
                'observaciones' => 'Quejas y reclamos de usuarios que requieren seguimiento',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD005',
                'serie' => 'Solicitudes',
                'subserie' => 'Solicitudes de Información',
                'asunto' => 'Solicitudes de información pública',
                'retencion_archivo_gestion' => 2,
                'retencion_archivo_central' => 5,
                'disposicion_final' => 'conservacion_total',
                'observaciones' => 'Solicitudes de acceso a información pública',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD006',
                'serie' => 'Solicitudes',
                'subserie' => 'Solicitudes de Servicios',
                'asunto' => 'Solicitudes de servicios hospitalarios',
                'retencion_archivo_gestion' => 2,
                'retencion_archivo_central' => 3,
                'disposicion_final' => 'eliminacion',
                'observaciones' => 'Solicitudes de servicios médicos y administrativos',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD007',
                'serie' => 'Contratos',
                'subserie' => 'Contratos de Prestación de Servicios',
                'asunto' => 'Contratos para prestación de servicios',
                'retencion_archivo_gestion' => 5,
                'retencion_archivo_central' => 10,
                'disposicion_final' => 'conservacion_total',
                'observaciones' => 'Contratos de servicios profesionales y técnicos',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD008',
                'serie' => 'Informes',
                'subserie' => 'Informes de Gestión',
                'asunto' => 'Informes de gestión administrativa',
                'retencion_archivo_gestion' => 3,
                'retencion_archivo_central' => 7,
                'disposicion_final' => 'conservacion_total',
                'observaciones' => 'Informes periódicos de gestión institucional',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD009',
                'serie' => 'Certificaciones',
                'subserie' => 'Certificados Médicos',
                'asunto' => 'Solicitudes de certificados médicos',
                'retencion_archivo_gestion' => 2,
                'retencion_archivo_central' => 5,
                'disposicion_final' => 'eliminacion',
                'observaciones' => 'Solicitudes de certificados y constancias médicas',
                'activo' => true,
            ],
            [
                'codigo' => 'TRD010',
                'serie' => 'Autorizaciones',
                'subserie' => 'Autorizaciones de Procedimientos',
                'asunto' => 'Autorizaciones para procedimientos médicos',
                'retencion_archivo_gestion' => 3,
                'retencion_archivo_central' => 5,
                'disposicion_final' => 'conservacion_total',
                'observaciones' => 'Autorizaciones para procedimientos y tratamientos',
                'activo' => true,
            ],
        ];

        foreach ($trds as $trd) {
            Trd::create($trd);
        }

        $this->command->info('TRD creados exitosamente.');
    }
}
