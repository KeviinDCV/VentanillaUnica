<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subserie;
use App\Models\Serie;

class SubserieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener las series
        $pqrs = Serie::whereHas('unidadAdministrativa', function($q) {
            $q->where('codigo', '100');
        })->where('numero_serie', '11')->first();

        $solicitudesInfo = Serie::whereHas('unidadAdministrativa', function($q) {
            $q->where('codigo', '100');
        })->where('numero_serie', '12')->first();

        $derechosPeticion = Serie::whereHas('unidadAdministrativa', function($q) {
            $q->where('codigo', '100');
        })->where('numero_serie', '13')->first();

        $correspondencia = Serie::whereHas('unidadAdministrativa', function($q) {
            $q->where('codigo', '200');
        })->where('numero_serie', '21')->first();

        $subseries = [
            // Subseries para PQRS (100-11)
            [
                'serie_id' => $pqrs->id,
                'numero_subserie' => '1',
                'nombre' => 'Petición',
                'descripcion' => 'Peticiones de los usuarios',
                'dias_respuesta' => 15,
                'activa' => true,
            ],
            [
                'serie_id' => $pqrs->id,
                'numero_subserie' => '2',
                'nombre' => 'Queja',
                'descripcion' => 'Quejas de los usuarios',
                'dias_respuesta' => 15,
                'activa' => true,
            ],
            [
                'serie_id' => $pqrs->id,
                'numero_subserie' => '3',
                'nombre' => 'Reclamo',
                'descripcion' => 'Reclamos de los usuarios',
                'dias_respuesta' => 15,
                'activa' => true,
            ],
            [
                'serie_id' => $pqrs->id,
                'numero_subserie' => '4',
                'nombre' => 'Sugerencia',
                'descripcion' => 'Sugerencias de los usuarios',
                'dias_respuesta' => 15,
                'activa' => true,
            ],

            // Subseries para Solicitudes de Información (100-12)
            [
                'serie_id' => $solicitudesInfo->id,
                'numero_subserie' => '1',
                'nombre' => 'Información General',
                'descripcion' => 'Solicitudes de información general',
                'dias_respuesta' => 10,
                'activa' => true,
            ],
            [
                'serie_id' => $solicitudesInfo->id,
                'numero_subserie' => '2',
                'nombre' => 'Información Específica',
                'descripcion' => 'Solicitudes de información específica',
                'dias_respuesta' => 10,
                'activa' => true,
            ],

            // Subseries para Derechos de Petición (100-13)
            [
                'serie_id' => $derechosPeticion->id,
                'numero_subserie' => '1',
                'nombre' => 'Interés General',
                'descripcion' => 'Derechos de petición de interés general',
                'dias_respuesta' => 15,
                'activa' => true,
            ],
            [
                'serie_id' => $derechosPeticion->id,
                'numero_subserie' => '2',
                'nombre' => 'Interés Particular',
                'descripcion' => 'Derechos de petición de interés particular',
                'dias_respuesta' => 15,
                'activa' => true,
            ],

            // Subseries para Correspondencia General (200-21)
            [
                'serie_id' => $correspondencia->id,
                'numero_subserie' => '1',
                'nombre' => 'Correspondencia Interna',
                'descripcion' => 'Correspondencia entre dependencias internas',
                'dias_respuesta' => 3,
                'activa' => true,
            ],
            [
                'serie_id' => $correspondencia->id,
                'numero_subserie' => '2',
                'nombre' => 'Correspondencia Externa',
                'descripcion' => 'Correspondencia con entidades externas',
                'dias_respuesta' => 5,
                'activa' => true,
            ],
        ];

        foreach ($subseries as $subserie) {
            Subserie::create($subserie);
        }
    }
}
