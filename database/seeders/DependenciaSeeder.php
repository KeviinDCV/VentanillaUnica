<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dependencia;

class DependenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dependencias = [
            [
                'codigo' => 'DIR001',
                'nombre' => 'Dirección General',
                'sigla' => 'DG',
                'descripcion' => 'Dirección General del Hospital',
                'responsable' => 'Director General',
                'telefono' => '123-4567',
                'email' => 'direccion@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'ADM001',
                'nombre' => 'Administración',
                'sigla' => 'ADM',
                'descripcion' => 'Departamento de Administración',
                'responsable' => 'Administrador',
                'telefono' => '123-4568',
                'email' => 'admin@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'MED001',
                'nombre' => 'Medicina Interna',
                'sigla' => 'MI',
                'descripcion' => 'Servicio de Medicina Interna',
                'responsable' => 'Jefe de Medicina Interna',
                'telefono' => '123-4569',
                'email' => 'medicina@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'CIR001',
                'nombre' => 'Cirugía General',
                'sigla' => 'CG',
                'descripcion' => 'Servicio de Cirugía General',
                'responsable' => 'Jefe de Cirugía',
                'telefono' => '123-4570',
                'email' => 'cirugia@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'URG001',
                'nombre' => 'Urgencias',
                'sigla' => 'URG',
                'descripcion' => 'Servicio de Urgencias',
                'responsable' => 'Jefe de Urgencias',
                'telefono' => '123-4571',
                'email' => 'urgencias@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'LAB001',
                'nombre' => 'Laboratorio Clínico',
                'sigla' => 'LAB',
                'descripcion' => 'Laboratorio Clínico',
                'responsable' => 'Jefe de Laboratorio',
                'telefono' => '123-4572',
                'email' => 'laboratorio@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'RAD001',
                'nombre' => 'Radiología',
                'sigla' => 'RAD',
                'descripcion' => 'Servicio de Radiología e Imágenes',
                'responsable' => 'Jefe de Radiología',
                'telefono' => '123-4573',
                'email' => 'radiologia@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'FAR001',
                'nombre' => 'Farmacia',
                'sigla' => 'FAR',
                'descripcion' => 'Servicio de Farmacia',
                'responsable' => 'Jefe de Farmacia',
                'telefono' => '123-4574',
                'email' => 'farmacia@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'ENF001',
                'nombre' => 'Enfermería',
                'sigla' => 'ENF',
                'descripcion' => 'Departamento de Enfermería',
                'responsable' => 'Jefe de Enfermería',
                'telefono' => '123-4575',
                'email' => 'enfermeria@hospital.com',
                'activa' => true,
            ],
            [
                'codigo' => 'RH001',
                'nombre' => 'Recursos Humanos',
                'sigla' => 'RH',
                'descripcion' => 'Departamento de Recursos Humanos',
                'responsable' => 'Jefe de Recursos Humanos',
                'telefono' => '123-4576',
                'email' => 'rh@hospital.com',
                'activa' => true,
            ],
        ];

        foreach ($dependencias as $dependencia) {
            Dependencia::create($dependencia);
        }

        $this->command->info('Dependencias creadas exitosamente.');
    }
}
