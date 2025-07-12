<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\Subserie;
use App\Models\TipoSolicitud;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Ciudad;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RadicadoFormularioCompletoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando radicados simulando formulario completo...');

        // Obtener todos los datos disponibles
        $dependencias = Dependencia::activas()->get();
        $subseries = Subserie::all();
        $tiposSolicitud = TipoSolicitud::activos()->get();
        $usuarios = User::all();
        $departamentos = Departamento::activo()->get();

        if ($dependencias->isEmpty() || $usuarios->isEmpty() || $departamentos->isEmpty()) {
            $this->command->error('Faltan datos básicos. Ejecuta los seeders necesarios primero.');
            return;
        }

        // Configuraciones de prueba que simulan diferentes formas de llenar el formulario
        $configuracionesPrueba = [
            // 1. Remitente registrado con CC, con TRD, Valle del Cauca
            [
                'descripcion' => 'Remitente CC con TRD completo - Valle del Cauca',
                'tipo_remitente' => 'registrado',
                'tipo_documento' => 'CC',
                'numero_documento' => '12345678',
                'nombre_completo' => 'Juan Carlos Pérez García',
                'telefono' => '3001234567',
                'email' => 'juan.perez@email.com',
                'direccion' => 'Calle 123 #45-67',
                'departamento_nombre' => 'Valle del Cauca',
                'ciudad_nombre' => 'Cali',
                'entidad' => null,
                'medio_recepcion' => 'fisico',
                'tipo_comunicacion' => null, // Se asignará aleatoriamente
                'numero_folios' => 5,
                'observaciones' => 'Solicitud de información sobre servicios médicos',
                'con_trd' => true,
                'medio_respuesta' => 'fisico',
                'tipo_anexo' => 'original',
            ],
            
            // 2. Remitente anónimo sin TRD, Cundinamarca
            [
                'descripcion' => 'Remitente anónimo sin TRD - Cundinamarca',
                'tipo_remitente' => 'anonimo',
                'tipo_documento' => null,
                'numero_documento' => null,
                'nombre_completo' => 'Ciudadano Anónimo',
                'telefono' => null,
                'email' => null,
                'direccion' => 'Dirección no especificada',
                'departamento_nombre' => 'Cundinamarca',
                'ciudad_nombre' => 'Bogotá D.C.',
                'entidad' => null,
                'medio_recepcion' => 'web',
                'tipo_comunicacion' => null,
                'numero_folios' => 1,
                'observaciones' => 'Queja anónima sobre atención',
                'con_trd' => false,
                'medio_respuesta' => 'no_requiere',
                'tipo_anexo' => 'ninguno',
            ],

            // 3. Empresa con NIT, con TRD, Antioquia
            [
                'descripcion' => 'Empresa NIT con TRD - Antioquia',
                'tipo_remitente' => 'registrado',
                'tipo_documento' => 'NIT',
                'numero_documento' => '900123456',
                'nombre_completo' => 'Empresa ABC S.A.S.',
                'telefono' => '3157894561',
                'email' => 'contacto@empresaabc.com',
                'direccion' => 'Zona Industrial Km 5',
                'departamento_nombre' => 'Antioquia',
                'ciudad_nombre' => 'Medellín',
                'entidad' => 'Empresa ABC S.A.S.',
                'medio_recepcion' => 'email',
                'tipo_comunicacion' => null,
                'numero_folios' => 10,
                'observaciones' => 'Propuesta comercial para suministros',
                'con_trd' => true,
                'medio_respuesta' => 'email',
                'tipo_anexo' => 'copia',
            ],

            // 4. CE con datos parciales, sin TRD, Atlántico
            [
                'descripcion' => 'CE con datos parciales sin TRD - Atlántico',
                'tipo_remitente' => 'registrado',
                'tipo_documento' => 'CE',
                'numero_documento' => '87654321',
                'nombre_completo' => 'María Elena Rodríguez López',
                'telefono' => null, // Sin teléfono
                'email' => 'maria.rodriguez@email.com',
                'direccion' => null, // Sin dirección
                'departamento_nombre' => 'Atlántico',
                'ciudad_nombre' => 'Barranquilla',
                'entidad' => 'EPS Salud Total',
                'medio_recepcion' => 'telefono',
                'tipo_comunicacion' => null,
                'numero_folios' => 3,
                'observaciones' => null, // Sin observaciones
                'con_trd' => false,
                'medio_respuesta' => 'telefono',
                'tipo_anexo' => 'original',
            ],

            // 5. Pasaporte con TRD, Santander
            [
                'descripcion' => 'Pasaporte con TRD - Santander',
                'tipo_remitente' => 'registrado',
                'tipo_documento' => 'PP',
                'numero_documento' => 'AB123456',
                'nombre_completo' => 'John Smith Williams',
                'telefono' => '3209876543',
                'email' => 'john.smith@email.com',
                'direccion' => 'Hotel Central Piso 5',
                'departamento_nombre' => 'Santander',
                'ciudad_nombre' => 'Bucaramanga',
                'entidad' => null,
                'medio_recepcion' => 'fax',
                'tipo_comunicacion' => null,
                'numero_folios' => 2,
                'observaciones' => 'Consulta sobre trámites migratorios',
                'con_trd' => true,
                'medio_respuesta' => 'presencial',
                'tipo_anexo' => 'copia',
            ],

            // 6. TI sin departamento/ciudad, con TRD
            [
                'descripcion' => 'TI sin ubicación con TRD',
                'tipo_remitente' => 'registrado',
                'tipo_documento' => 'TI',
                'numero_documento' => '1098765432',
                'nombre_completo' => 'Ana Sofía Martínez Gómez',
                'telefono' => '3156789012',
                'email' => null, // Sin email
                'direccion' => 'Calle 45 #12-34',
                'departamento_nombre' => null, // Sin departamento
                'ciudad_nombre' => null, // Sin ciudad
                'entidad' => null,
                'medio_recepcion' => 'web',
                'tipo_comunicacion' => null,
                'numero_folios' => 1,
                'observaciones' => 'Solicitud de menor de edad',
                'con_trd' => true,
                'medio_respuesta' => 'email',
                'tipo_anexo' => 'original',
            ],

            // 7. Otro documento, máximo folios, Bolívar
            [
                'descripcion' => 'Otro documento máximo folios - Bolívar',
                'tipo_remitente' => 'registrado',
                'tipo_documento' => 'OTRO',
                'numero_documento' => 'DOC789123',
                'nombre_completo' => 'Carlos Alberto Mendoza Ruiz',
                'telefono' => '3001112233',
                'email' => 'carlos.mendoza@email.com',
                'direccion' => 'Barrio Centro Histórico',
                'departamento_nombre' => 'Bolívar',
                'ciudad_nombre' => 'Cartagena',
                'entidad' => 'Fundación Cultural',
                'medio_recepcion' => 'fisico',
                'tipo_comunicacion' => null,
                'numero_folios' => 50, // Máximo folios
                'observaciones' => 'Documentación extensa para proyecto cultural',
                'con_trd' => false,
                'medio_respuesta' => 'presencial',
                'tipo_anexo' => 'original',
            ],

            // 8. Anónimo con datos mínimos, Nariño
            [
                'descripcion' => 'Anónimo datos mínimos - Nariño',
                'tipo_remitente' => 'anonimo',
                'tipo_documento' => null,
                'numero_documento' => null,
                'nombre_completo' => 'Anónimo',
                'telefono' => null,
                'email' => null,
                'direccion' => null,
                'departamento_nombre' => 'Nariño',
                'ciudad_nombre' => 'Pasto',
                'entidad' => null,
                'medio_recepcion' => 'otro',
                'tipo_comunicacion' => null,
                'numero_folios' => 1,
                'observaciones' => null,
                'con_trd' => false,
                'medio_respuesta' => 'no_requiere',
                'tipo_anexo' => 'ninguno',
            ],
        ];

        // Crear radicados simulando el formulario
        foreach ($configuracionesPrueba as $index => $config) {
            $this->command->info("Creando: {$config['descripcion']}");

            try {
                // Crear o buscar remitente
                $remitente = $this->crearRemitente($config, $departamentos);
                
                // Seleccionar datos aleatorios
                $dependencia = $dependencias->random();
                $tipoSolicitud = $tiposSolicitud->random();
                $usuario = $usuarios->random();
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
                    'tipo_comunicacion' => $config['tipo_comunicacion'] ?? $tipoSolicitud->codigo ?? 'fisico',
                    'numero_folios' => $config['numero_folios'],
                    'observaciones' => $config['observaciones'],
                    'medio_respuesta' => $config['medio_respuesta'],
                    'tipo_anexo' => $config['tipo_anexo'],
                    'fecha_limite_respuesta' => Carbon::now()->addDays(15)->toDateString(),
                    'estado' => 'pendiente',
                ]);

                $this->command->info("✓ Radicado creado: {$numeroRadicado}");

            } catch (\Exception $e) {
                $this->command->error("Error creando radicado {$index}: " . $e->getMessage());
            }
        }

        $this->command->info('¡Radicados de formulario completo creados exitosamente!');
        $this->command->info('Total de radicados creados: ' . count($configuracionesPrueba));
    }

    private function crearRemitente($config, $departamentos)
    {
        // Buscar departamento y ciudad si están especificados
        $departamento = null;
        $ciudad = null;
        
        if ($config['departamento_nombre']) {
            $departamento = $departamentos->where('nombre', $config['departamento_nombre'])->first();
            if ($departamento && $config['ciudad_nombre']) {
                $ciudad = $departamento->ciudades()->where('nombre', $config['ciudad_nombre'])->first();
            }
        }

        return Remitente::create([
            'tipo' => $config['tipo_remitente'],
            'tipo_documento' => $config['tipo_documento'],
            'numero_documento' => $config['numero_documento'],
            'nombre_completo' => $config['nombre_completo'],
            'telefono' => $config['telefono'],
            'email' => $config['email'],
            'direccion' => $config['direccion'],
            'departamento' => $config['departamento_nombre'],
            'ciudad' => $config['ciudad_nombre'],
            'entidad' => $config['entidad'],
        ]);
    }
}
