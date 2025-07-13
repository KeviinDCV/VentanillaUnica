<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RadicacionSalidaController;
use App\Models\Dependencia;
use App\Models\User;
use App\Models\Subserie;
use App\Models\Departamento;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimularFormularioSalida extends Command
{
    protected $signature = 'radicados:simular-formulario-salida {cantidad=8 : Cantidad de formularios a simular}';
    protected $description = 'Simula el envío del formulario de radicación de salida con datos aleatorios';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        
        $this->info("🚀 Simulando {$cantidad} envíos del formulario de radicación de salida...");

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
        $departamentos = Departamento::activo()->count();

        if ($dependencias === 0) {
            $this->error('❌ No hay dependencias activas.');
            return false;
        }

        if ($usuarios === 0) {
            $this->error('❌ No hay usuarios.');
            return false;
        }

        if ($departamentos === 0) {
            $this->error('❌ No hay departamentos.');
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
            'departamentos' => Departamento::activo()->get(),
            'ciudades' => Ciudad::activo()->get(),
        ];
    }

    private function generarDatosFormulario(int $numero, array $datos): array
    {
        $dependenciaOrigen = $datos['dependencias']->random();

        $funcionarios = [
            'Dr. Carlos Alberto Martínez Silva', 'Dra. María Elena Rodríguez López',
            'Ing. Luis Fernando Castro Morales', 'Lic. Ana Sofía González Herrera',
            'Dr. José Miguel Vargas Ruiz', 'Dra. Patricia Isabel Mendoza Cruz',
            'Ing. Roberto Carlos Sánchez Díaz', 'Lic. Claudia Marcela Ramírez Vega'
        ];

        $asuntos = [
            'Respuesta a solicitud de información médica especializada',
            'Envío de certificación médica para trámites legales',
            'Remisión de paciente a institución especializada',
            'Comunicación oficial sobre procedimientos administrativos',
            'Respuesta a requerimiento de autoridades competentes',
            'Envío de informes médicos solicitados',
            'Comunicación sobre cambios en protocolos de atención',
            'Respuesta a solicitud de documentación institucional'
        ];

        // Tipo de destinatario (40% persona natural, 30% persona jurídica, 30% entidad pública)
        $tiposDestinatario = ['persona_natural', 'persona_juridica', 'entidad_publica'];
        $probabilidades = [40, 30, 30];
        $tipoDestinatario = $this->seleccionarPorProbabilidad($tiposDestinatario, $probabilidades);

        $formulario = [
            // 1. Información del destinatario (exactamente como el formulario)
            'tipo_destinatario' => $tipoDestinatario,
            'nombre_destinatario' => $this->generarNombreDestinatario($tipoDestinatario, $numero),
            'telefono_destinatario' => rand(1, 3) === 1 ? '300' . rand(1000000, 9999999) : '',
            'email_destinatario' => rand(1, 3) === 1 ? 'destinatario' . $numero . '@email.com' : '',
            'direccion_destinatario' => $this->generarDireccion(),
            'entidad_destinatario' => rand(1, 4) === 1 ? 'Hospital San Vicente' : '',

            // 2. Información del documento (exactamente como el formulario)
            'tipo_comunicacion' => 'OFIC', // Usar código de tipo de solicitud
            'medio_envio' => ['correo_fisico', 'correo_electronico', 'mensajeria', 'entrega_personal'][array_rand(['correo_fisico', 'correo_electronico', 'mensajeria', 'entrega_personal'])],
            'prioridad' => ['baja', 'normal', 'alta', 'urgente'][array_rand(['baja', 'normal', 'alta', 'urgente'])],
            'asunto' => $asuntos[array_rand($asuntos)],
            'numero_folios' => rand(1, 10),
            'numero_anexos' => rand(0, 5),
            'observaciones' => rand(1, 3) === 1 ? 'Observaciones adicionales para el radicado de salida #' . $numero : '',

            // 4. Información de envío (exactamente como el formulario)
            'dependencia_origen_id' => $dependenciaOrigen->id,
            'funcionario_remitente' => $funcionarios[array_rand($funcionarios)],
            'cargo_remitente' => rand(1, 3) === 1 ? 'Director Médico' : '',
            'telefono_remitente' => rand(1, 3) === 1 ? '300' . rand(1000000, 9999999) : '',
            'email_remitente' => rand(1, 3) === 1 ? 'funcionario' . $numero . '@hospital.gov.co' : '',
            'tipo_anexo' => ['original', 'copia', 'ninguno'][array_rand(['original', 'copia', 'ninguno'])],
            'requiere_acuse_recibo' => rand(1, 3) === 1 ? '1' : '0',
            'instrucciones_envio' => rand(1, 4) === 1 ? 'Entregar únicamente al destinatario' : '',
        ];

        // Campos específicos según tipo de destinatario
        if ($tipoDestinatario === 'persona_natural') {
            $tiposDocumento = ['CC', 'CE', 'TI', 'PP', 'OTRO'];
            $formulario['tipo_documento_destinatario'] = $tiposDocumento[array_rand($tiposDocumento)];
            $formulario['numero_documento_destinatario'] = rand(10000000, 99999999);
        } else {
            $formulario['nit_destinatario'] = rand(800000000, 999999999) . '-' . rand(1, 9);
        }

        // Departamento y ciudad
        if ($datos['departamentos']->isNotEmpty()) {
            $departamento = $datos['departamentos']->random();
            $ciudadesDepartamento = $datos['ciudades']->where('departamento_id', $departamento->id);
            
            if ($ciudadesDepartamento->isNotEmpty()) {
                $ciudad = $ciudadesDepartamento->random();
                $formulario['departamento_destinatario'] = $departamento->nombre;
                $formulario['ciudad_destinatario'] = $ciudad->nombre;
            } else {
                $formulario['departamento_destinatario'] = 'Antioquia';
                $formulario['ciudad_destinatario'] = 'Medellín';
            }
        } else {
            $formulario['departamento_destinatario'] = 'Antioquia';
            $formulario['ciudad_destinatario'] = 'Medellín';
        }

        // TRD opcional (70% con TRD, 30% sin TRD)
        if (rand(1, 10) <= 7 && $datos['subseries']->isNotEmpty()) {
            $subserie = $datos['subseries']->random();
            $formulario['trd_id'] = $subserie->id;
            $formulario['unidad_administrativa_id'] = $subserie->serie->unidad_administrativa_id;
            $formulario['serie_id'] = $subserie->serie_id;
            $formulario['subserie_id'] = $subserie->id;
        }

        // Fecha límite si requiere acuse de recibo
        if ($formulario['requiere_acuse_recibo']) {
            $formulario['fecha_limite_respuesta'] = now()->addDays(rand(5, 30))->format('Y-m-d');
        }

        return $formulario;
    }

    private function seleccionarPorProbabilidad(array $opciones, array $probabilidades): string
    {
        $random = rand(1, 100);
        $acumulado = 0;
        
        foreach ($probabilidades as $index => $probabilidad) {
            $acumulado += $probabilidad;
            if ($random <= $acumulado) {
                return $opciones[$index];
            }
        }
        
        return $opciones[0];
    }

    private function generarNombreDestinatario(string $tipo, int $numero): string
    {
        if ($tipo === 'persona_natural') {
            $nombres = [
                'Juan Carlos Pérez García', 'María Elena Rodríguez López', 'Carlos Alberto Martínez Silva',
                'Ana Sofía González Herrera', 'Luis Fernando Castro Morales', 'Carmen Rosa Jiménez Torres',
                'José Miguel Vargas Ruiz', 'Patricia Isabel Mendoza Cruz', 'Roberto Carlos Sánchez Díaz'
            ];
            return $nombres[array_rand($nombres)];
        } elseif ($tipo === 'persona_juridica') {
            $empresas = [
                'Clínica Las Américas S.A.', 'Hospital Pablo Tobón Uribe', 'Clínica Cardiovascular Santa María',
                'IPS Universitaria', 'Clínica Medellín S.A.', 'Hospital San Vicente Fundación',
                'Clínica El Rosario', 'Hospital General de Medellín'
            ];
            return $empresas[array_rand($empresas)];
        } else { // entidad_publica
            $entidades = [
                'Secretaría de Salud de Antioquia', 'Ministerio de Salud y Protección Social',
                'Superintendencia Nacional de Salud', 'INVIMA - Instituto Nacional de Vigilancia',
                'Alcaldía de Medellín', 'Gobernación de Antioquia',
                'ADRES - Administradora de los Recursos del Sistema', 'Instituto Nacional de Salud'
            ];
            return $entidades[array_rand($entidades)];
        }
    }

    private function generarDireccion(): string
    {
        $direcciones = [
            'Calle ' . rand(10, 80) . ' # ' . rand(10, 50) . '-' . rand(10, 99),
            'Carrera ' . rand(10, 80) . ' # ' . rand(10, 50) . '-' . rand(10, 99),
            'Avenida ' . rand(10, 80) . ' # ' . rand(10, 50) . '-' . rand(10, 99),
            'Transversal ' . rand(10, 80) . ' # ' . rand(10, 50) . '-' . rand(10, 99)
        ];
        
        return $direcciones[array_rand($direcciones)];
    }

    private function enviarFormulario(array $datosFormulario): array
    {
        try {
            // Crear request simulado con método POST
            $request = Request::create('/radicacion/salida', 'POST', $datosFormulario);

            // Crear instancia del controlador
            $controller = new RadicacionSalidaController();
            
            // Llamar al método store
            $response = $controller->store($request);
            
            // Verificar si fue exitoso
            if ($response->getStatusCode() === 302) {
                // Redirección exitosa
                $destinatario = $datosFormulario['nombre_destinatario'];
                $asunto = substr($datosFormulario['asunto'], 0, 40) . '...';
                $trdInfo = isset($datosFormulario['trd_id']) ? 'Con TRD' : 'Sin TRD';
                $tipoDestinatario = ucfirst(str_replace('_', ' ', $datosFormulario['tipo_destinatario']));
                
                return [
                    'exito' => true,
                    'mensaje' => "Radicado salida creado - {$destinatario} ({$tipoDestinatario}) - {$asunto} - {$trdInfo}"
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
