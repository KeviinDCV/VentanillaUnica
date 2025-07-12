<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\Subserie;
use App\Models\TipoSolicitud;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RadicadoEntradaPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando radicados de entrada de prueba...');

        // Obtener datos necesarios
        $dependencias = Dependencia::activas()->get();
        $subseries = Subserie::all();
        $tiposSolicitud = TipoSolicitud::activos()->get();
        $usuarios = User::all();

        if ($dependencias->isEmpty()) {
            $this->command->error('No hay dependencias activas. Ejecuta DependenciaSeeder primero.');
            return;
        }

        if ($tiposSolicitud->isEmpty()) {
            $this->command->error('No hay tipos de solicitud activos. Ejecuta TipoSolicitudSeeder primero.');
            return;
        }

        if ($usuarios->isEmpty()) {
            $this->command->error('No hay usuarios. Crea usuarios primero.');
            return;
        }

        // Datos de prueba para remitentes
        $remitentesData = [
            // Remitente registrado 1
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
            // Remitente registrado 2
            [
                'tipo' => 'registrado',
                'tipo_documento' => 'CE',
                'numero_documento' => '87654321',
                'nombre_completo' => 'María Elena Rodríguez López',
                'telefono' => '3109876543',
                'email' => 'maria.rodriguez@email.com',
                'direccion' => 'Carrera 45 #12-34',
                'ciudad' => 'Medellín',
                'departamento' => 'Antioquia',
                'entidad' => 'EPS Salud Total',
            ],
            // Remitente anónimo 1
            [
                'tipo' => 'anonimo',
                'tipo_documento' => null,
                'numero_documento' => null,
                'nombre_completo' => 'Ciudadano Anónimo',
                'telefono' => null,
                'email' => null,
                'direccion' => 'Dirección no especificada',
                'ciudad' => 'Puerto Merizalde',
                'departamento' => 'Valle del Cauca',
                'entidad' => null,
            ],
            // Remitente registrado 3 (NIT)
            [
                'tipo' => 'registrado',
                'tipo_documento' => 'NIT',
                'numero_documento' => '900123456',
                'nombre_completo' => 'Empresa ABC S.A.S.',
                'telefono' => '3157894561',
                'email' => 'contacto@empresaabc.com',
                'direccion' => 'Zona Industrial Km 5',
                'ciudad' => 'Cali',
                'departamento' => 'Valle del Cauca',
                'entidad' => 'Empresa ABC S.A.S.',
            ],
        ];

        // Crear remitentes
        $remitentes = [];
        foreach ($remitentesData as $data) {
            $remitentes[] = Remitente::create($data);
        }

        // Configuraciones de prueba para radicados
        $configuracionesPrueba = [
            // Prueba 1: Radicado completo con TRD
            [
                'descripcion' => 'Radicado completo con TRD',
                'remitente_index' => 0,
                'medio_recepcion' => 'fisico',
                'numero_folios' => 5,
                'con_trd' => true,
                'medio_respuesta' => 'fisico',
                'tipo_anexo' => 'original',
                'observaciones' => 'Solicitud de información sobre servicios médicos',
            ],
            // Prueba 2: Radicado sin TRD (opcional)
            [
                'descripcion' => 'Radicado sin TRD (campos opcionales)',
                'remitente_index' => 1,
                'medio_recepcion' => 'email',
                'numero_folios' => 2,
                'con_trd' => false,
                'medio_respuesta' => 'email',
                'tipo_anexo' => 'copia',
                'observaciones' => 'Consulta general sin clasificación específica',
            ],
            // Prueba 3: Remitente anónimo
            [
                'descripcion' => 'Remitente anónimo con TRD',
                'remitente_index' => 2,
                'medio_recepcion' => 'web',
                'numero_folios' => 1,
                'con_trd' => true,
                'medio_respuesta' => 'presencial',
                'tipo_anexo' => 'ninguno',
                'observaciones' => 'Queja anónima sobre atención',
            ],
            // Prueba 4: Empresa (NIT) sin TRD
            [
                'descripcion' => 'Empresa con NIT sin TRD',
                'remitente_index' => 3,
                'medio_recepcion' => 'telefono',
                'numero_folios' => 10,
                'con_trd' => false,
                'medio_respuesta' => 'no_requiere',
                'tipo_anexo' => 'original',
                'observaciones' => 'Propuesta comercial para suministros',
            ],
            // Prueba 5: Diferentes medios de recepción
            [
                'descripcion' => 'Medio fax con TRD',
                'remitente_index' => 0,
                'medio_recepcion' => 'fax',
                'numero_folios' => 3,
                'con_trd' => true,
                'medio_respuesta' => 'telefono',
                'tipo_anexo' => 'copia',
                'observaciones' => 'Documento enviado por fax',
            ],
        ];

        // Crear radicados de prueba
        foreach ($configuracionesPrueba as $index => $config) {
            $this->command->info("Creando: {$config['descripcion']}");

            $remitente = $remitentes[$config['remitente_index']];
            $dependencia = $dependencias->random();
            $tipoSolicitud = $tiposSolicitud->random();
            $usuario = $usuarios->random();
            
            // Seleccionar subserie solo si se requiere TRD
            $subserie = $config['con_trd'] && $subseries->isNotEmpty() ? $subseries->random() : null;

            // Generar número de radicado
            $numeroRadicado = Radicado::generarNumeroRadicado('entrada');

            // Crear radicado
            $radicado = Radicado::create([
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'entrada',
                'fecha_radicado' => Carbon::now()->subDays($index)->toDateString(),
                'hora_radicado' => Carbon::now()->setTime(8 + $index, rand(0, 59), rand(0, 59))->toTimeString(),
                'remitente_id' => $remitente->id,
                'subserie_id' => $subserie ? $subserie->id : null,
                'dependencia_destino_id' => $dependencia->id,
                'usuario_radica_id' => $usuario->id,
                'medio_recepcion' => $config['medio_recepcion'],
                'tipo_comunicacion' => $tipoSolicitud->codigo ?? 'fisico',
                'numero_folios' => $config['numero_folios'],
                'observaciones' => $config['observaciones'],
                'medio_respuesta' => $config['medio_respuesta'],
                'tipo_anexo' => $config['tipo_anexo'],
                'fecha_limite_respuesta' => Carbon::now()->addDays(15)->toDateString(),
                'estado' => 'pendiente',
            ]);

            $this->command->info("✓ Radicado creado: {$numeroRadicado}");
        }

        $this->command->info('¡Radicados de entrada de prueba creados exitosamente!');
        $this->command->info('Total de radicados creados: ' . count($configuracionesPrueba));
    }
}
