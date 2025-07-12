<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Ciudad;

class DepartamentoCiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando departamentos y ciudades...');

        // Datos de departamentos y sus ciudades
        $departamentosData = [
            [
                'nombre' => 'Valle del Cauca',
                'codigo' => '76',
                'ciudades' => [
                    ['nombre' => 'Cali', 'codigo' => '76001'],
                    ['nombre' => 'Buenaventura', 'codigo' => '76109'],
                    ['nombre' => 'Palmira', 'codigo' => '76520'],
                    ['nombre' => 'Tuluá', 'codigo' => '76834'],
                    ['nombre' => 'Cartago', 'codigo' => '76147'],
                    ['nombre' => 'Buga', 'codigo' => '76111'],
                    ['nombre' => 'Jamundí', 'codigo' => '76364'],
                    ['nombre' => 'Puerto Merizalde', 'codigo' => '76109001'],
                ]
            ],
            [
                'nombre' => 'Cundinamarca',
                'codigo' => '25',
                'ciudades' => [
                    ['nombre' => 'Bogotá D.C.', 'codigo' => '11001'],
                    ['nombre' => 'Soacha', 'codigo' => '25754'],
                    ['nombre' => 'Girardot', 'codigo' => '25307'],
                    ['nombre' => 'Zipaquirá', 'codigo' => '25899'],
                    ['nombre' => 'Facatativá', 'codigo' => '25269'],
                    ['nombre' => 'Chía', 'codigo' => '25175'],
                    ['nombre' => 'Mosquera', 'codigo' => '25473'],
                    ['nombre' => 'Madrid', 'codigo' => '25430'],
                ]
            ],
            [
                'nombre' => 'Antioquia',
                'codigo' => '05',
                'ciudades' => [
                    ['nombre' => 'Medellín', 'codigo' => '05001'],
                    ['nombre' => 'Bello', 'codigo' => '05088'],
                    ['nombre' => 'Itagüí', 'codigo' => '05360'],
                    ['nombre' => 'Envigado', 'codigo' => '05266'],
                    ['nombre' => 'Apartadó', 'codigo' => '05045'],
                    ['nombre' => 'Turbo', 'codigo' => '05837'],
                    ['nombre' => 'Rionegro', 'codigo' => '05615'],
                    ['nombre' => 'Sabaneta', 'codigo' => '05631'],
                ]
            ],
            [
                'nombre' => 'Atlántico',
                'codigo' => '08',
                'ciudades' => [
                    ['nombre' => 'Barranquilla', 'codigo' => '08001'],
                    ['nombre' => 'Soledad', 'codigo' => '08758'],
                    ['nombre' => 'Malambo', 'codigo' => '08433'],
                    ['nombre' => 'Sabanagrande', 'codigo' => '08634'],
                    ['nombre' => 'Puerto Colombia', 'codigo' => '08573'],
                    ['nombre' => 'Galapa', 'codigo' => '08296'],
                ]
            ],
            [
                'nombre' => 'Santander',
                'codigo' => '68',
                'ciudades' => [
                    ['nombre' => 'Bucaramanga', 'codigo' => '68001'],
                    ['nombre' => 'Floridablanca', 'codigo' => '68276'],
                    ['nombre' => 'Girón', 'codigo' => '68307'],
                    ['nombre' => 'Piedecuesta', 'codigo' => '68547'],
                    ['nombre' => 'Barrancabermeja', 'codigo' => '68081'],
                    ['nombre' => 'San Gil', 'codigo' => '68679'],
                ]
            ],
            [
                'nombre' => 'Bolívar',
                'codigo' => '13',
                'ciudades' => [
                    ['nombre' => 'Cartagena', 'codigo' => '13001'],
                    ['nombre' => 'Magangué', 'codigo' => '13430'],
                    ['nombre' => 'Turbaco', 'codigo' => '13836'],
                    ['nombre' => 'Arjona', 'codigo' => '13052'],
                ]
            ],
            [
                'nombre' => 'Nariño',
                'codigo' => '52',
                'ciudades' => [
                    ['nombre' => 'Pasto', 'codigo' => '52001'],
                    ['nombre' => 'Tumaco', 'codigo' => '52835'],
                    ['nombre' => 'Ipiales', 'codigo' => '52356'],
                    ['nombre' => 'Túquerres', 'codigo' => '52838'],
                ]
            ],
            [
                'nombre' => 'Cauca',
                'codigo' => '19',
                'ciudades' => [
                    ['nombre' => 'Popayán', 'codigo' => '19001'],
                    ['nombre' => 'Santander de Quilichao', 'codigo' => '19698'],
                    ['nombre' => 'Puerto Tejada', 'codigo' => '19573'],
                    ['nombre' => 'Guapi', 'codigo' => '19318'],
                ]
            ]
        ];

        foreach ($departamentosData as $deptoData) {
            $this->command->info("Creando departamento: {$deptoData['nombre']}");
            
            // Crear o actualizar departamento
            $departamento = Departamento::updateOrCreate(
                ['codigo' => $deptoData['codigo']],
                [
                    'nombre' => $deptoData['nombre'],
                    'activo' => true
                ]
            );

            // Crear ciudades del departamento
            foreach ($deptoData['ciudades'] as $ciudadData) {
                $this->command->info("  - Creando ciudad: {$ciudadData['nombre']}");
                
                Ciudad::updateOrCreate(
                    [
                        'nombre' => $ciudadData['nombre'],
                        'departamento_id' => $departamento->id
                    ],
                    [
                        'codigo' => $ciudadData['codigo'],
                        'activo' => true
                    ]
                );
            }
        }

        $this->command->info('¡Departamentos y ciudades creados exitosamente!');
        $this->command->info('Total departamentos: ' . Departamento::count());
        $this->command->info('Total ciudades: ' . Ciudad::count());
    }
}
