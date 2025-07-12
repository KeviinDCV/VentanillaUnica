<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RadicacionEntradaController;
use App\Models\Dependencia;
use App\Models\TipoSolicitud;
use App\Models\User;
use App\Models\Subserie;
use App\Models\Departamento;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimularFormularioEntrada extends Command
{
    protected $signature = 'radicados:simular-formulario-entrada {cantidad=5 : Cantidad de formularios a simular}';
    protected $description = 'Simula el envío del formulario de radicación de entrada con datos aleatorios';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        
        $this->info("🚀 Simulando {$cantidad} envíos del formulario de radicación de entrada...");

        // Verificar datos necesarios
        if (!$this->verificarDatos()) {
            return 1;
        }

        // Obtener datos
        $datos = $this->obtenerDatos();
        
        // Autenticar como primer usuario disponible
        $usuario = $datos['usuarios']->first();
        Auth::login($usuario);
        
        $this->info("👤 Autenticado como: {$usuario->name}");
        $this->newLine();

        $exitosos = 0;
        $errores = 0;

        for ($i = 1; $i <= $cantidad; $i++) {
            try {
                $datosFormulario = $this->generarDatosFormulario($i, $datos);
                $resultado = $this->enviarFormulario($datosFormulario);
                
                if ($resultado['exito']) {
                    $exitosos++;
                    $this->info("   ✓ #{$i}: {$resultado['mensaje']}");
                } else {
                    $errores++;
                    $this->error("   ❌ #{$i}: {$resultado['mensaje']}");
                }
                
            } catch (\Exception $e) {
                $errores++;
                $this->error("   ❌ Error en formulario #{$i}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("✅ Simulación completada:");
        $this->info("   - Exitosos: {$exitosos}");
        $this->info("   - Errores: {$errores}");

        return 0;
    }

    private function verificarDatos(): bool
    {
        $dependencias = Dependencia::activas()->count();
        $tiposSolicitud = TipoSolicitud::activos()->count();
        $usuarios = User::count();

        if ($dependencias === 0) {
            $this->error('❌ No hay dependencias activas.');
            return false;
        }

        if ($tiposSolicitud === 0) {
            $this->error('❌ No hay tipos de solicitud activos.');
            return false;
        }

        if ($usuarios === 0) {
            $this->error('❌ No hay usuarios.');
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

    private function generarDatosFormulario(int $numero, array $datos): array
    {
        // Tipo de remitente (60% registrado, 40% anónimo)
        $tipoRemitente = rand(1, 10) <= 6 ? 'registrado' : 'anonimo';
        
        $nombres = [
            'Juan Carlos Pérez García', 'María Elena Rodríguez López', 'Carlos Alberto Martínez Silva',
            'Ana Sofía González Herrera', 'Luis Fernando Castro Morales', 'Carmen Rosa Jiménez Torres',
            'José Miguel Vargas Ruiz', 'Patricia Isabel Mendoza Cruz', 'Roberto Carlos Sánchez Díaz',
            'Claudia Marcela Ramírez Vega', 'Diego Alejandro Moreno Ortiz', 'Lucía Fernanda Aguilar Rojas'
        ];

        $entidades = [
            'Alcaldía Municipal', 'Gobernación de Antioquia', 'Universidad Nacional',
            'Hospital San Vicente', 'Empresa de Servicios Públicos', 'Cámara de Comercio',
            'Ministerio de Salud', 'DIAN', 'Policía Nacional', 'Bomberos Voluntarios'
        ];

        $formulario = [
            // Información del remitente
            'tipo_remitente' => $tipoRemitente,
            'nombre_completo' => $nombres[array_rand($nombres)],
            'telefono' => rand(1, 3) === 1 ? '300' . rand(1000000, 9999999) : null,
            'email' => rand(1, 3) === 1 ? 'usuario' . $numero . '@email.com' : null,
            'direccion' => rand(1, 3) === 1 ? 'Calle ' . rand(10, 80) . ' # ' . rand(10, 50) . '-' . rand(10, 99) : null,
            'entidad' => rand(1, 4) === 1 ? $entidades[array_rand($entidades)] : null,

            // Datos del radicado
            'medio_recepcion' => ['fisico', 'email', 'web', 'telefono', 'fax', 'otro'][array_rand(['fisico', 'email', 'web', 'telefono', 'fax', 'otro'])],
            'tipo_comunicacion' => $datos['tipos_solicitud']->random()->codigo,
            'numero_folios' => rand(1, 20),
            'numero_anexos' => rand(0, 8),
            'observaciones' => rand(1, 3) === 1 ? 'Observaciones de prueba para el radicado #' . $numero : null,

            // Destino
            'dependencia_destino_id' => $datos['dependencias']->random()->id,
            'medio_respuesta' => ['fisico', 'email', 'telefono', 'presencial', 'no_requiere'][array_rand(['fisico', 'email', 'telefono', 'presencial', 'no_requiere'])],
            'tipo_anexo' => ['original', 'copia', 'ninguno'][array_rand(['original', 'copia', 'ninguno'])],
            'fecha_limite_respuesta' => rand(1, 4) === 1 ? now()->addDays(rand(5, 30))->format('Y-m-d') : null,
        ];

        // Campos específicos para remitente registrado
        if ($tipoRemitente === 'registrado') {
            $tiposDocumento = ['CC', 'CE', 'TI', 'PP', 'NIT', 'OTRO'];
            $formulario['tipo_documento'] = $tiposDocumento[array_rand($tiposDocumento)];
            $formulario['numero_documento'] = $formulario['tipo_documento'] === 'NIT' ? 
                rand(800000000, 999999999) . '-' . rand(1, 9) : 
                rand(10000000, 99999999);
        }

        // TRD opcional (70% con TRD, 30% sin TRD)
        if (rand(1, 10) <= 7 && $datos['subseries']->isNotEmpty()) {
            $subserie = $datos['subseries']->random();
            $formulario['trd_id'] = $subserie->id;
            $formulario['unidad_administrativa_id'] = $subserie->serie->unidad_administrativa_id;
            $formulario['serie_id'] = $subserie->serie_id;
            $formulario['subserie_id'] = $subserie->id;
        }

        // Departamento y ciudad (50% de probabilidad)
        if (rand(1, 2) === 1 && $datos['departamentos']->isNotEmpty()) {
            $departamento = $datos['departamentos']->random();
            $ciudadesDepartamento = $datos['ciudades']->where('departamento_id', $departamento->id);
            
            if ($ciudadesDepartamento->isNotEmpty()) {
                $ciudad = $ciudadesDepartamento->random();
                $formulario['departamento_id'] = $departamento->id;
                $formulario['departamento'] = $departamento->nombre;
                $formulario['ciudad_id'] = $ciudad->id;
                $formulario['ciudad'] = $ciudad->nombre;
            }
        }

        return $formulario;
    }

    private function enviarFormulario(array $datosFormulario): array
    {
        try {
            // Crear request simulado
            $request = new Request($datosFormulario);
            
            // Crear instancia del controlador
            $controller = new RadicacionEntradaController();
            
            // Llamar al método store
            $response = $controller->store($request);
            
            // Verificar si fue exitoso
            if ($response->getStatusCode() === 302) {
                // Redirección exitosa
                $tipoRemitente = $datosFormulario['tipo_remitente'];
                $nombreCompleto = $datosFormulario['nombre_completo'];
                $trdInfo = isset($datosFormulario['trd_id']) ? 'Con TRD' : 'Sin TRD';
                
                return [
                    'exito' => true,
                    'mensaje' => "Radicado creado - {$nombreCompleto} ({$tipoRemitente}) - {$trdInfo}"
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Respuesta inesperada del controlador'
                ];
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errores = collect($e->errors())->flatten()->implode(', ');
            return [
                'exito' => false,
                'mensaje' => "Errores de validación: {$errores}"
            ];
            
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => "Error: " . $e->getMessage()
            ];
        }
    }
}
