<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadAdministrativa;

class UnidadAdministrativaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            [
                'codigo' => '100',
                'nombre' => 'Atención al Usuario',
                'descripcion' => 'Unidad encargada de la atención y servicio al usuario',
                'activa' => true,
            ],
            [
                'codigo' => '200',
                'nombre' => 'Gestión Administrativa',
                'descripcion' => 'Unidad encargada de los procesos administrativos',
                'activa' => true,
            ],
            [
                'codigo' => '300',
                'nombre' => 'Gestión Jurídica',
                'descripcion' => 'Unidad encargada de los asuntos legales y jurídicos',
                'activa' => true,
            ],
            [
                'codigo' => '400',
                'nombre' => 'Gestión Financiera',
                'descripcion' => 'Unidad encargada de la gestión financiera y contable',
                'activa' => true,
            ],
            [
                'codigo' => '500',
                'nombre' => 'Talento Humano',
                'descripcion' => 'Unidad encargada de la gestión del talento humano',
                'activa' => true,
            ],
        ];

        foreach ($unidades as $unidad) {
            UnidadAdministrativa::create($unidad);
        }
    }
}
