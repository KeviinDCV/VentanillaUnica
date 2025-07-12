<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

class GenerarRadicadosEntradaPrueba extends Command
{
    protected $signature = 'radicados:generar-entrada-prueba {cantidad=10 : Cantidad de radicados a generar}';
    protected $description = 'Genera radicados de entrada con datos aleatorios para probar todas las posibilidades del formulario';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        
        $this->info("🚀 Generando {$cantidad} radicados de entrada con datos aleatorios...");

        // Verificar datos necesarios
        if (!$this->verificarDatosNecesarios()) {
            return 1;
        }

        // Obtener datos para generar radicados
        $datos = $this->obtenerDatos();

        $this->info("📊 Datos disponibles:");
        $this->info("   - Dependencias: {$datos['dependencias']->count()}");
        $this->info("   - Tipos de solicitud: {$datos['tipos_solicitud']->count()}");
        $this->info("   - Subseries (TRD): {$datos['subseries']->count()}");
        $this->info("   - Departamentos: {$datos['departamentos']->count()}");
        $this->info("   - Usuarios: {$datos['usuarios']->count()}");

        $this->newLine();
        $this->info("🎯 Creando radicados con variaciones aleatorias...");

        $exitosos = 0;
        $errores = 0;

        for ($i = 1; $i <= $cantidad; $i++) {
            try {
                $radicado = $this->crearRadicadoAleatorio($i, $datos);
                $exitosos++;
                
                $trdInfo = $radicado->subserie ? 
                    "{$radicado->subserie->serie->unidadAdministrativa->codigo}.{$radicado->subserie->serie->numero_serie}.{$radicado->subserie->numero_subserie}" : 
                    'Sin TRD';
                
                $this->info("   ✓ #{$i}: {$radicado->numero_radicado} - {$radicado->remitente->nombre_completo} ({$radicado->remitente->tipo}) - TRD: {$trdInfo}");
                
            } catch (\Exception $e) {
                $errores++;
                $this->error("   ❌ Error en radicado #{$i}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("✅ Proceso completado:");
        $this->info("   - Exitosos: {$exitosos}");
        $this->info("   - Errores: {$errores}");

        return 0;
    }

    private function verificarDatosNecesarios(): bool
    {
        $dependencias = Dependencia::activas()->count();
        $tiposSolicitud = TipoSolicitud::activos()->count();
        $usuarios = User::count();

        if ($dependencias === 0) {
            $this->error('❌ No hay dependencias activas. Ejecuta: php artisan db:seed --class=DependenciaSeeder');
            return false;
        }

        if ($tiposSolicitud === 0) {
            $this->error('❌ No hay tipos de solicitud activos. Ejecuta: php artisan db:seed --class=TipoSolicitudSeeder');
            return false;
        }

        if ($usuarios === 0) {
            $this->error('❌ No hay usuarios. Ejecuta: php artisan db:seed --class=UserSeeder');
            return false;
        }

        return true;
    }

    private function obtenerDatos(): array
    {
        return [
            'dependencias' => Dependencia::activas()->get(),
            'tipos_solicitud' => TipoSolicitud::activos()->get(),
            'subseries' => Subserie::with(['serie.unidadAdministrativa'])->get(),
            'usuarios' => User::all(),
            'departamentos' => Departamento::activo()->get(),
            'ciudades' => Ciudad::activo()->get(),
        ];
    }

    private function crearRadicadoAleatorio(int $numero, array $datos): Radicado
    {
        DB::beginTransaction();

        try {
            // 1. Crear remitente con datos aleatorios
            $remitente = $this->crearRemitenteAleatorio($datos);

            // 2. Generar datos del radicado
            $numeroRadicado = Radicado::generarNumeroRadicado('entrada');
            $dependenciaDestino = $datos['dependencias']->random();
            $tipoSolicitud = $datos['tipos_solicitud']->random();
            $usuario = $datos['usuarios']->random();

            // 3. TRD opcional (70% con TRD, 30% sin TRD)
            $subserie = rand(1, 10) <= 7 && $datos['subseries']->isNotEmpty() ? 
                $datos['subseries']->random() : null;

            // 4. Datos aleatorios del formulario
            $mediosRecepcion = ['fisico', 'email', 'web', 'telefono', 'fax', 'otro'];
            $mediosRespuesta = ['fisico', 'email', 'telefono', 'presencial', 'no_requiere'];
            $tiposAnexo = ['original', 'copia', 'ninguno'];

            // 5. Fecha aleatoria (últimos 30 días)
            $fecha = Carbon::now()->subDays(rand(0, 30));

            // 6. Crear radicado
            $radicado = Radicado::create([
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'entrada',
                'fecha_radicado' => $fecha->toDateString(),
                'hora_radicado' => $fecha->setTime(rand(8, 17), rand(0, 59), rand(0, 59))->toTimeString(),
                'remitente_id' => $remitente->id,
                'subserie_id' => $subserie?->id,
                'dependencia_destino_id' => $dependenciaDestino->id,
                'usuario_radica_id' => $usuario->id,
                'medio_recepcion' => $mediosRecepcion[array_rand($mediosRecepcion)],
                'tipo_comunicacion' => $tipoSolicitud->codigo,
                'numero_folios' => rand(1, 20),
                'numero_anexos' => rand(0, 8),
                'observaciones' => $this->generarObservacionesAleatorias(),
                'medio_respuesta' => $mediosRespuesta[array_rand($mediosRespuesta)],
                'tipo_anexo' => $tiposAnexo[array_rand($tiposAnexo)],
                'fecha_limite_respuesta' => rand(1, 4) === 1 ? 
                    $fecha->copy()->addDays(rand(5, 45))->toDateString() : null,
                'estado' => $this->generarEstadoAleatorio(),
            ]);

            DB::commit();
            return $radicado->load(['remitente', 'subserie.serie.unidadAdministrativa']);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function crearRemitenteAleatorio(array $datos): Remitente
    {
        // 60% registrado, 40% anónimo
        $tipoRemitente = rand(1, 10) <= 6 ? 'registrado' : 'anonimo';
        
        $nombres = [
            'Juan Carlos Pérez García', 'María Elena Rodríguez López', 'Carlos Alberto Martínez Silva',
            'Ana Sofía González Herrera', 'Luis Fernando Castro Morales', 'Carmen Rosa Jiménez Torres',
            'José Miguel Vargas Ruiz', 'Patricia Isabel Mendoza Cruz', 'Roberto Carlos Sánchez Díaz',
            'Claudia Marcela Ramírez Vega', 'Diego Alejandro Moreno Ortiz', 'Lucía Fernanda Aguilar Rojas',
            'Andrés Felipe Guerrero Medina', 'Valentina Alejandra Herrera Campos', 'Sebastián David Romero Peña'
        ];

        $entidades = [
            'Alcaldía Municipal de Medellín', 'Gobernación de Antioquia', 'Universidad Nacional de Colombia',
            'Hospital San Vicente Fundación', 'Empresa de Servicios Públicos', 'Cámara de Comercio',
            'Ministerio de Salud', 'DIAN - Dirección de Impuestos', 'Policía Nacional',
            'Bomberos Voluntarios', 'Cruz Roja Colombiana', 'Defensoría del Pueblo'
        ];

        $remitenteData = [
            'tipo' => $tipoRemitente,
            'nombre_completo' => $nombres[array_rand($nombres)],
            'telefono' => rand(1, 3) === 1 ? '300' . rand(1000000, 9999999) : null,
            'email' => rand(1, 3) === 1 ? strtolower(str_replace(' ', '.', explode(' ', $nombres[array_rand($nombres)])[0] . '.' . explode(' ', $nombres[array_rand($nombres)])[1])) . '@email.com' : null,
            'direccion' => rand(1, 3) === 1 ? 'Calle ' . rand(10, 80) . ' # ' . rand(10, 50) . '-' . rand(10, 99) : null,
            'entidad' => rand(1, 4) === 1 ? $entidades[array_rand($entidades)] : null,
        ];

        // Campos específicos para remitente registrado
        if ($tipoRemitente === 'registrado') {
            $tiposDocumento = ['CC', 'CE', 'TI', 'PP', 'NIT', 'OTRO'];
            $remitenteData['tipo_documento'] = $tiposDocumento[array_rand($tiposDocumento)];
            $remitenteData['numero_documento'] = $remitenteData['tipo_documento'] === 'NIT' ? 
                rand(800000000, 999999999) . '-' . rand(1, 9) : 
                rand(10000000, 99999999);
        }

        // Departamento y ciudad (50% de probabilidad)
        if (rand(1, 2) === 1 && $datos['departamentos']->isNotEmpty()) {
            $departamento = $datos['departamentos']->random();
            $ciudadesDepartamento = $datos['ciudades']->where('departamento_id', $departamento->id);
            
            if ($ciudadesDepartamento->isNotEmpty()) {
                $ciudad = $ciudadesDepartamento->random();
                $remitenteData['departamento'] = $departamento->nombre;
                $remitenteData['ciudad'] = $ciudad->nombre;
            }
        }

        return Remitente::create($remitenteData);
    }

    private function generarObservacionesAleatorias(): ?string
    {
        // 60% de probabilidad de tener observaciones
        if (rand(1, 10) <= 6) {
            $observaciones = [
                'Documento urgente que requiere atención prioritaria.',
                'Se adjuntan documentos de soporte adicionales.',
                'Solicitud relacionada con proceso administrativo en curso.',
                'Requiere seguimiento especial por parte de la dependencia.',
                'Documento recibido fuera del horario normal de atención.',
                'Se requiere respuesta en el menor tiempo posible.',
                'Comunicación oficial que debe ser archivada correctamente.',
                'Incluye anexos que deben ser revisados detalladamente.',
            ];
            
            return $observaciones[array_rand($observaciones)];
        }
        
        return null;
    }

    private function generarEstadoAleatorio(): string
    {
        $estados = ['pendiente', 'en_proceso', 'respondido', 'archivado'];
        $probabilidades = [50, 30, 15, 5]; // Porcentajes
        
        $random = rand(1, 100);
        $acumulado = 0;
        
        foreach ($probabilidades as $index => $probabilidad) {
            $acumulado += $probabilidad;
            if ($random <= $acumulado) {
                return $estados[$index];
            }
        }
        
        return 'pendiente';
    }
}
