<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Serie;
use App\Models\UnidadAdministrativa;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener las unidades administrativas
        $atencionUsuario = UnidadAdministrativa::where('codigo', '100')->first();
        $gestionAdmin = UnidadAdministrativa::where('codigo', '200')->first();
        $gestionJuridica = UnidadAdministrativa::where('codigo', '300')->first();

        $series = [
            // Series para Atención al Usuario (100)
            [
                'unidad_administrativa_id' => $atencionUsuario->id,
                'numero_serie' => '11',
                'nombre' => 'PQRS',
                'descripcion' => 'Peticiones, Quejas, Reclamos y Sugerencias',
                'dias_respuesta' => 15,
                'activa' => true,
            ],
            [
                'unidad_administrativa_id' => $atencionUsuario->id,
                'numero_serie' => '12',
                'nombre' => 'Solicitudes de Información',
                'descripcion' => 'Solicitudes de acceso a información pública',
                'dias_respuesta' => 10,
                'activa' => true,
            ],
            [
                'unidad_administrativa_id' => $atencionUsuario->id,
                'numero_serie' => '13',
                'nombre' => 'Derechos de Petición',
                'descripcion' => 'Derechos de petición de interés general',
                'dias_respuesta' => 15,
                'activa' => true,
            ],

            // Series para Gestión Administrativa (200)
            [
                'unidad_administrativa_id' => $gestionAdmin->id,
                'numero_serie' => '21',
                'nombre' => 'Correspondencia General',
                'descripcion' => 'Correspondencia administrativa general',
                'dias_respuesta' => 5,
                'activa' => true,
            ],
            [
                'unidad_administrativa_id' => $gestionAdmin->id,
                'numero_serie' => '22',
                'nombre' => 'Comunicaciones Oficiales',
                'descripcion' => 'Comunicaciones oficiales internas y externas',
                'dias_respuesta' => 3,
                'activa' => true,
            ],

            // Series para Gestión Jurídica (300)
            [
                'unidad_administrativa_id' => $gestionJuridica->id,
                'numero_serie' => '31',
                'nombre' => 'Procesos Judiciales',
                'descripcion' => 'Documentos relacionados con procesos judiciales',
                'dias_respuesta' => 30,
                'activa' => true,
            ],
            [
                'unidad_administrativa_id' => $gestionJuridica->id,
                'numero_serie' => '32',
                'nombre' => 'Contratos',
                'descripcion' => 'Documentos relacionados con contratos',
                'dias_respuesta' => 20,
                'activa' => true,
            ],
        ];

        foreach ($series as $serie) {
            Serie::create($serie);
        }
    }
}
