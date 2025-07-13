<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RadicacionInternaController;
use App\Models\Dependencia;
use App\Models\User;
use App\Models\Subserie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SimularFormularioInterno extends Command
{
    protected $signature = 'radicados:simular-formulario-interno {cantidad=8 : Cantidad de formularios a simular}';
    protected $description = 'Simula el envío del formulario de radicación interna con datos aleatorios';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        
        $this->info("🚀 Simulando {$cantidad} envíos del formulario de radicación interna...");

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
        $usuarios = User::count();

        if ($dependencias < 2) {
            $this->error('❌ Se necesitan al menos 2 dependencias activas para radicados internos.');
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
            'subseries' => Subserie::with(['serie.unidadAdministrativa'])->get(),
            'usuarios' => User::all(),
        ];
    }

    private function generarDatosFormulario(int $numero, array $datos): array
    {
        // Seleccionar dependencias diferentes para origen y destino
        $dependenciaOrigen = $datos['dependencias']->random();
        $dependenciasDestino = $datos['dependencias']->where('id', '!=', $dependenciaOrigen->id);
        $dependenciaDestino = $dependenciasDestino->random();

        $funcionarios = [
            'Dr. Carlos Alberto Martínez Silva', 'Dra. María Elena Rodríguez López', 
            'Ing. Luis Fernando Castro Morales', 'Lic. Ana Sofía González Herrera',
            'Dr. José Miguel Vargas Ruiz', 'Dra. Patricia Isabel Mendoza Cruz',
            'Ing. Roberto Carlos Sánchez Díaz', 'Lic. Claudia Marcela Ramírez Vega',
            'Dr. Diego Alejandro Moreno Ortiz', 'Dra. Lucía Fernanda Aguilar Rojas'
        ];

        $cargos = [
            'Director Médico', 'Jefe de Enfermería', 'Coordinador Administrativo',
            'Supervisor de Calidad', 'Jefe de Recursos Humanos', 'Director Financiero',
            'Coordinador de Sistemas', 'Jefe de Mantenimiento', 'Supervisor de Seguridad',
            'Coordinador de Servicios Generales'
        ];

        $asuntos = [
            'Solicitud de autorización para procedimiento médico especializado',
            'Informe mensual de actividades del departamento',
            'Requerimiento de equipos médicos para unidad de cuidados intensivos',
            'Propuesta de mejora en procesos administrativos',
            'Solicitud de capacitación para personal médico',
            'Informe de auditoría interna de calidad',
            'Requerimiento de mantenimiento preventivo de equipos',
            'Solicitud de contratación de personal especializado',
            'Propuesta de actualización de protocolos médicos',
            'Informe de gestión de riesgos hospitalarios'
        ];

        $tiposComunicacion = ['memorando', 'circular', 'oficio', 'informe', 'acta', 'otro'];
        $prioridades = ['baja', 'normal', 'alta', 'urgente'];
        $tiposAnexo = ['original', 'copia', 'ninguno'];

        // Requiere respuesta (70% sí, 30% no)
        $requiereRespuesta = rand(1, 10) <= 7 ? 'si' : 'no';

        $formulario = [
            // Información del remitente interno
            'dependencia_origen_id' => $dependenciaOrigen->id,
            'funcionario_remitente' => $funcionarios[array_rand($funcionarios)],
            'cargo_remitente' => rand(1, 3) === 1 ? $cargos[array_rand($cargos)] : null,
            'telefono_remitente' => rand(1, 3) === 1 ? '300' . rand(1000000, 9999999) : null,
            'email_remitente' => rand(1, 3) === 1 ? 'funcionario' . $numero . '@hospital.gov.co' : null,

            // Datos del documento
            'asunto' => $asuntos[array_rand($asuntos)],
            'tipo_comunicacion' => $tiposComunicacion[array_rand($tiposComunicacion)],
            'numero_folios' => rand(1, 15),
            'numero_anexos' => rand(0, 5),
            'observaciones' => rand(1, 3) === 1 ? 'Observaciones adicionales para el radicado interno #' . $numero : null,
            'prioridad' => $prioridades[array_rand($prioridades)],

            // Destino
            'dependencia_destino_id' => $dependenciaDestino->id,
            'requiere_respuesta' => $requiereRespuesta,
            'fecha_limite_respuesta' => $requiereRespuesta === 'si' ? 
                now()->addDays(rand(5, 30))->format('Y-m-d') : null,
            'tipo_anexo' => $tiposAnexo[array_rand($tiposAnexo)],
        ];

        // TRD opcional (70% con TRD, 30% sin TRD)
        if (rand(1, 10) <= 7 && $datos['subseries']->isNotEmpty()) {
            $subserie = $datos['subseries']->random();
            $formulario['trd_id'] = $subserie->id;
            $formulario['unidad_administrativa_id'] = $subserie->serie->unidad_administrativa_id;
            $formulario['serie_id'] = $subserie->serie_id;
            $formulario['subserie_id'] = $subserie->id;
        }

        return $formulario;
    }

    private function enviarFormulario(array $datosFormulario): array
    {
        try {
            // Crear request simulado
            $request = new Request($datosFormulario);
            
            // Crear instancia del controlador
            $controller = new RadicacionInternaController();
            
            // Llamar al método store
            $response = $controller->store($request);
            
            // Verificar si fue exitoso
            if ($response->getStatusCode() === 302) {
                // Redirección exitosa
                $funcionario = $datosFormulario['funcionario_remitente'];
                $asunto = substr($datosFormulario['asunto'], 0, 50) . '...';
                $trdInfo = isset($datosFormulario['trd_id']) ? 'Con TRD' : 'Sin TRD';
                $prioridad = ucfirst($datosFormulario['prioridad']);
                
                return [
                    'exito' => true,
                    'mensaje' => "Radicado interno creado - {$funcionario} - {$asunto} - {$trdInfo} - Prioridad: {$prioridad}"
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
