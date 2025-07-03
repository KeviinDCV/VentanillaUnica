<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\Subserie;
use App\Models\User;
use Carbon\Carbon;

class RadicadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $dependencias = Dependencia::all();
        $subseries = Subserie::all();
        $usuarios = User::all();

        if ($dependencias->isEmpty() || $subseries->isEmpty() || $usuarios->isEmpty()) {
            $this->command->error('Asegúrate de que existan dependencias, subseries y usuarios antes de ejecutar este seeder.');
            return;
        }

        // Crear remitentes de prueba
        $remitentes = [
            [
                'tipo' => 'registrado',
                'tipo_documento' => 'CC',
                'numero_documento' => '12345678',
                'nombre_completo' => 'Juan Carlos Pérez García',
                'telefono' => '3001234567',
                'email' => 'juan.perez@email.com',
                'direccion' => 'Calle 123 #45-67',
                'ciudad' => 'Bogotá',
                'departamento' => 'Cundinamarca',
                'entidad' => null,
            ],
            [
                'tipo' => 'registrado',
                'tipo_documento' => 'CC',
                'numero_documento' => '87654321',
                'nombre_completo' => 'María Elena Rodríguez López',
                'telefono' => '3109876543',
                'email' => 'maria.rodriguez@email.com',
                'direccion' => 'Carrera 45 #12-34',
                'ciudad' => 'Medellín',
                'departamento' => 'Antioquia',
                'entidad' => 'EPS Salud Total',
            ],
            [
                'tipo' => 'anonimo',
                'tipo_documento' => null,
                'numero_documento' => null,
                'nombre_completo' => 'Ciudadano Anónimo',
                'telefono' => null,
                'email' => null,
                'direccion' => null,
                'ciudad' => null,
                'departamento' => null,
                'entidad' => null,
            ],
            [
                'tipo' => 'registrado',
                'tipo_documento' => 'NIT',
                'numero_documento' => '900123456-1',
                'nombre_completo' => 'Empresa ABC S.A.S.',
                'telefono' => '6012345678',
                'email' => 'contacto@empresaabc.com',
                'direccion' => 'Zona Industrial Km 5',
                'ciudad' => 'Cali',
                'departamento' => 'Valle del Cauca',
                'entidad' => 'Empresa ABC S.A.S.',
            ],
            [
                'tipo' => 'registrado',
                'tipo_documento' => 'CC',
                'numero_documento' => '98765432',
                'nombre_completo' => 'Ana Sofía Martínez Gómez',
                'telefono' => '3157894561',
                'email' => 'ana.martinez@email.com',
                'direccion' => 'Avenida 68 #45-12',
                'ciudad' => 'Barranquilla',
                'departamento' => 'Atlántico',
                'entidad' => null,
            ],
        ];

        $remitenteModels = [];
        foreach ($remitentes as $remitenteData) {
            $remitenteModels[] = Remitente::create($remitenteData);
        }

        // Crear radicados de prueba
        $fechasBase = [
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(25),
            Carbon::now()->subDays(20),
            Carbon::now()->subDays(15),
            Carbon::now()->subDays(10),
            Carbon::now()->subDays(5),
            Carbon::now()->subDays(3),
            Carbon::now()->subDays(1),
            Carbon::now(),
        ];

        $estados = ['pendiente', 'en_proceso', 'respondido', 'archivado'];
        $mediosRecepcion = ['fisico', 'email', 'web', 'telefono'];
        $tiposComunicacion = ['fisico', 'verbal'];
        $mediosRespuesta = ['fisico', 'email', 'telefono', 'presencial', 'no_requiere'];
        $tiposAnexo = ['original', 'copia', 'ninguno'];

        $contador = 1;
        foreach ($fechasBase as $fecha) {
            // Crear 2-3 radicados por fecha
            $cantidadPorFecha = rand(2, 3);

            for ($i = 0; $i < $cantidadPorFecha; $i++) {
                $numeroRadicado = 'E-' . $fecha->format('Y') . '-' . str_pad($contador, 6, '0', STR_PAD_LEFT);
                $remitente = $remitenteModels[array_rand($remitenteModels)];
                $dependencia = $dependencias->random();
                $subserie = $subseries->random();
                $usuario = $usuarios->random();
                $estado = $estados[array_rand($estados)];

                // Calcular fecha límite (algunos con fecha límite, otros sin)
                $fechaLimite = null;
                if (rand(0, 1)) {
                    $diasLimite = rand(5, 30);
                    $fechaLimite = $fecha->copy()->addDays($diasLimite);
                }

                // Crear el radicado
                $radicado = Radicado::create([
                    'numero_radicado' => $numeroRadicado,
                    'tipo' => 'entrada',
                    'fecha_radicado' => $fecha->toDateString(),
                    'hora_radicado' => $fecha->setTime(rand(8, 17), rand(0, 59), rand(0, 59))->toTimeString(),
                    'remitente_id' => $remitente->id,
                    'subserie_id' => $subserie->id,
                    'dependencia_destino_id' => $dependencia->id,
                    'usuario_radica_id' => $usuario->id,
                    'medio_recepcion' => $mediosRecepcion[array_rand($mediosRecepcion)],
                    'tipo_comunicacion' => $tiposComunicacion[array_rand($tiposComunicacion)],
                    'numero_folios' => rand(1, 10),
                    'observaciones' => $this->generarObservacion(),
                    'medio_respuesta' => $mediosRespuesta[array_rand($mediosRespuesta)],
                    'tipo_anexo' => $tiposAnexo[array_rand($tiposAnexo)],
                    'fecha_limite_respuesta' => $fechaLimite?->toDateString(),
                    'estado' => $estado,
                    'respuesta' => $estado === 'respondido' ? 'Respuesta generada automáticamente para pruebas.' : null,
                    'fecha_respuesta' => $estado === 'respondido' ? $fecha->copy()->addDays(rand(1, 10))->toDateString() : null,
                    'usuario_responde_id' => $estado === 'respondido' ? $usuario->id : null,
                ]);

                $contador++;
            }
        }

        $this->command->info("Se crearon " . ($contador - 1) . " radicados de prueba exitosamente.");
    }

    /**
     * Generar observación aleatoria
     */
    private function generarObservacion(): string
    {
        $observaciones = [
            'Solicitud de información sobre servicios médicos.',
            'Queja sobre atención recibida en urgencias.',
            'Petición de certificado médico.',
            'Solicitud de autorización para procedimiento.',
            'Reclamo por facturación incorrecta.',
            'Petición de historia clínica.',
            'Solicitud de segunda opinión médica.',
            'Queja sobre demora en cita médica.',
            'Petición de traslado a otra EPS.',
            'Solicitud de información sobre tratamientos.',
            'Reclamo por negación de servicio.',
            'Petición de medicamentos especiales.',
        ];

        return $observaciones[array_rand($observaciones)];
    }
}
