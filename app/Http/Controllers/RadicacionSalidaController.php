<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\Subserie;
use App\Models\TipoSolicitud;
use App\Models\Documento;
use Carbon\Carbon;

class RadicacionSalidaController extends Controller
{
    /**
     * Almacenar un nuevo radicado de salida
     */
    public function store(Request $request)
    {
        // Log inmediato para verificar que el método se ejecuta
        \Log::emergency('🚨 RADICACION SALIDA STORE EJECUTÁNDOSE 🚨');

        \Log::info('RadicacionSalida::store - INICIO', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'timestamp' => now(),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'all_request_data' => $request->except(['documento', '_token']),
            'has_file' => $request->hasFile('documento'),
            'content_type' => $request->header('Content-Type')
        ]);

        // Verificar si es una petición POST
        if (!$request->isMethod('POST')) {
            \Log::error('RadicacionSalida::store - MÉTODO INCORRECTO', ['method' => $request->method()]);
            return back()->withErrors(['error' => 'Método de petición incorrecto']);
        }



        // Validaciones específicas para radicación de salida
        $rules = [
            // Destinatario
            'tipo_destinatario' => 'required|in:persona_natural,persona_juridica,entidad_publica',
            'nombre_destinatario' => 'required|string|max:255',
            'direccion_destinatario' => 'required|string|max:500',
            'departamento_destinatario' => 'required|string|max:100',
            'ciudad_destinatario' => 'required|string|max:100',

            // Documento
            'asunto' => 'required|string|max:500',
            'numero_folios' => 'nullable|integer|min:1',

            // TRD - Opcional
            'trd_id' => 'nullable|exists:subseries,id',

            // Envío
            'dependencia_origen_id' => 'required|exists:dependencias,id',
            'funcionario_remitente' => 'required|string|max:255',
            'tipo_anexo' => 'required|in:original,copia,ninguno',
            'requiere_acuse_recibo' => 'required|boolean',

            // Archivo
            'documento' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];

        // Validaciones condicionales según el tipo de destinatario
        if ($request->tipo_destinatario === 'persona_natural') {
            $rules['tipo_documento_destinatario'] = 'required|in:CC,CE,TI,PP,OTRO';
            $rules['numero_documento_destinatario'] = 'required|string|max:20';
        } elseif (in_array($request->tipo_destinatario, ['persona_juridica', 'entidad_publica'])) {
            $rules['nit_destinatario'] = 'required|string|max:20';
        }

        $messages = [
            'tipo_destinatario.required' => 'Debe seleccionar el tipo de destinatario',
            'nombre_destinatario.required' => 'El nombre del destinatario es obligatorio',
            'tipo_documento_destinatario.required' => 'Debe seleccionar el tipo de documento para personas naturales',
            'numero_documento_destinatario.required' => 'El número de documento es obligatorio para personas naturales',
            'nit_destinatario.required' => 'El NIT es obligatorio para personas jurídicas y entidades públicas',
            'direccion_destinatario.required' => 'La dirección es obligatoria',
            'departamento_destinatario.required' => 'Debe seleccionar el departamento',
            'ciudad_destinatario.required' => 'Debe seleccionar la ciudad',
            'asunto.required' => 'El asunto es obligatorio',
            'numero_folios.min' => 'El número de folios debe ser al menos 1',
            'trd_id.exists' => 'El TRD seleccionado no es válido',
            'dependencia_origen_id.required' => 'Debe seleccionar la dependencia de origen',
            'funcionario_remitente.required' => 'El nombre del funcionario remitente es obligatorio',
            'tipo_anexo.required' => 'Debe seleccionar el tipo de anexo',
            'requiere_acuse_recibo.required' => 'Debe indicar si requiere acuse de recibo',
            'documento.required' => 'Debe adjuntar un documento',
            'documento.mimes' => 'El documento debe ser PDF, Word o imagen',
            'documento.max' => 'El documento no puede superar los 10MB',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            \Log::error('RadicacionSalida::store - VALIDACIÓN FALLIDA', [
                'errors' => $validator->errors()->toArray()
            ]);

            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            \Log::info('RadicacionSalida::store - TRANSACCIÓN INICIADA');

            // 1. Crear destinatario
            \Log::info('RadicacionSalida::store - CREANDO DESTINATARIO', [
                'tipo' => $request->tipo_destinatario,
                'nombre' => $request->nombre_destinatario
            ]);

            $tipoDocumento = null;
            $numeroDocumento = null;

            if ($request->tipo_destinatario === 'persona_natural') {
                $tipoDocumento = $request->tipo_documento_destinatario;
                $numeroDocumento = $request->numero_documento_destinatario;
            } else {
                $tipoDocumento = 'NIT';
                $numeroDocumento = $request->nit_destinatario;
            }

            $destinatario = Remitente::firstOrCreate([
                'tipo_documento' => $tipoDocumento,
                'numero_documento' => $numeroDocumento,
            ], [
                'tipo' => 'registrado',
                'nombre_completo' => $request->nombre_destinatario,
                'telefono' => $request->telefono_destinatario,
                'email' => $request->email_destinatario,
                'direccion' => $request->direccion_destinatario,
                'departamento' => $request->departamento_destinatario,
                'ciudad' => $request->ciudad_destinatario,
                'entidad' => $request->entidad_destinatario,
                'observaciones' => 'DESTINATARIO DE SALIDA - Tipo: ' . ucfirst(str_replace('_', ' ', $request->tipo_destinatario)),
            ]);

            \Log::info('RadicacionSalida::store - DESTINATARIO CREADO', [
                'id' => $destinatario->id,
                'nombre' => $destinatario->nombre_completo
            ]);

            // 2. Generar número de radicado
            \Log::info('RadicacionSalida::store - GENERANDO NÚMERO DE RADICADO');
            $numeroRadicado = Radicado::generarNumeroRadicado('salida');
            \Log::info('RadicacionSalida::store - NÚMERO GENERADO', ['numero' => $numeroRadicado]);

            // 3. Crear radicado
            \Log::info('RadicacionSalida::store - CREANDO RADICADO');
            $radicado = Radicado::create([
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'salida',
                'fecha_radicado' => Carbon::now()->toDateString(),
                'hora_radicado' => Carbon::now()->toTimeString(),
                'remitente_id' => $destinatario->id,
                'subserie_id' => $request->trd_id,
                'dependencia_destino_id' => $request->dependencia_origen_id,
                'usuario_radica_id' => auth()->id(),
                'medio_recepcion' => 'fisico',
                'tipo_comunicacion' => $request->tipo_comunicacion,
                'numero_folios' => $request->numero_folios,
                'observaciones' => $request->observaciones,
                'medio_respuesta' => $request->requiere_acuse_recibo ? 'fisico' : 'no_requiere',
                'tipo_anexo' => $request->tipo_anexo,
                'fecha_limite_respuesta' => $request->fecha_limite_respuesta,
                'estado' => 'pendiente',
            ]);

            \Log::info('RadicacionSalida::store - RADICADO CREADO', [
                'id' => $radicado->id,
                'numero' => $radicado->numero_radicado
            ]);

            // 4. Actualizar observaciones con información completa
            $dependenciaOrigen = Dependencia::findOrFail($request->dependencia_origen_id);
            $observacionesCompletas = [
                "=== DOCUMENTO DE SALIDA ===",
                "Número: {$numeroRadicado}",
                "Dependencia Origen: {$dependenciaOrigen->nombre}",
                "Funcionario: {$request->funcionario_remitente}",
                "Destinatario: {$request->nombre_destinatario}",
                "Asunto: {$request->asunto}",
                "Prioridad: " . ucfirst($request->prioridad),
                "Medio de Envío: " . ucfirst(str_replace('_', ' ', $request->medio_envio)),
                "Tipo de Anexo: " . ucfirst($request->tipo_anexo),
            ];

            if ($request->cargo_remitente) {
                $observacionesCompletas[] = "Cargo: {$request->cargo_remitente}";
            }

            if ($request->requiere_acuse_recibo) {
                $observacionesCompletas[] = "Requiere Acuse de Recibo: SÍ";
            }

            if ($request->instrucciones_envio) {
                $observacionesCompletas[] = "Instrucciones: {$request->instrucciones_envio}";
            }

            if ($request->observaciones) {
                $observacionesCompletas[] = "";
                $observacionesCompletas[] = "Observaciones adicionales:";
                $observacionesCompletas[] = $request->observaciones;
            }

            $radicado->update(['observaciones' => implode("\n", $observacionesCompletas)]);

            // 5. Procesar archivo adjunto
            if ($request->hasFile('documento')) {
                \Log::info('RadicacionSalida::store - PROCESANDO ARCHIVO');
                $archivo = $request->file('documento');
                $nombreArchivo = $numeroRadicado . '_' . time() . '_' . $archivo->getClientOriginalName();
                $rutaArchivo = $archivo->storeAs('documentos/salida', $nombreArchivo, 'public');

                $contenido = file_get_contents($archivo->getPathname());
                $hashArchivo = hash('sha256', $contenido);

                Documento::create([
                    'radicado_id' => $radicado->id,
                    'nombre_archivo' => $archivo->getClientOriginalName(),
                    'ruta_archivo' => $rutaArchivo,
                    'tipo_mime' => $archivo->getMimeType(),
                    'tamaño_archivo' => $archivo->getSize(),
                    'hash_archivo' => $hashArchivo,
                    'descripcion' => "Documento de salida: {$request->asunto}",
                    'es_principal' => true,
                ]);

                \Log::info('RadicacionSalida::store - ARCHIVO PROCESADO', ['ruta' => $rutaArchivo]);
            }

            DB::commit();
            \Log::info('RadicacionSalida::store - ÉXITO COMPLETO', ['radicado_id' => $radicado->id]);

            return redirect()->route('radicacion.index')
                           ->with('success', "Radicado de salida {$numeroRadicado} creado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('RadicacionSalida::store - ERROR CRÍTICO', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Error al crear el radicado de salida: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Mostrar un radicado de salida específico
     */
    public function show($id)
    {
        $radicado = Radicado::with(['remitente', 'subserie.serie.unidadAdministrativa', 'dependenciaDestino', 'usuarioRadica', 'documentos'])
                           ->where('tipo', 'salida')
                           ->findOrFail($id);

        return view('radicacion.salida.show', compact('radicado'));
    }

    /**
     * Generar vista previa del radicado de salida
     */
    public function preview(Request $request)
    {
        // Validar datos básicos para la vista previa
        $request->validate([
            'nombre_destinatario' => 'required|string',
            'departamento_destinatario' => 'required|string',
            'ciudad_destinatario' => 'required|string',
            'dependencia_origen_id' => 'required|exists:dependencias,id',
            'funcionario_remitente' => 'required|string',
            'asunto' => 'required|string',
            'trd_id' => 'required|exists:subseries,id',
        ]);

        // Obtener datos para la vista previa
        $dependenciaOrigen = Dependencia::findOrFail($request->dependencia_origen_id);
        $subserie = Subserie::with(['serie.unidadAdministrativa'])->findOrFail($request->trd_id);

        $datosPreview = [
            'tipo' => 'salida',
            'dependencia_origen' => $dependenciaOrigen,
            'funcionario_remitente' => $request->funcionario_remitente,
            'cargo_remitente' => $request->cargo_remitente,
            'nombre_destinatario' => $request->nombre_destinatario,
            'tipo_documento_destinatario' => $request->tipo_documento_destinatario,
            'numero_documento_destinatario' => $request->numero_documento_destinatario,
            'telefono_destinatario' => $request->telefono_destinatario,
            'email_destinatario' => $request->email_destinatario,
            'direccion_destinatario' => $request->direccion_destinatario,
            'ciudad_destinatario' => $request->ciudad_destinatario,
            'departamento_destinatario' => $request->departamento_destinatario,
            'trd' => [
                'codigo' => $subserie->serie->unidadAdministrativa->codigo . '.' . $subserie->serie->numero_serie . '.' . $subserie->numero_subserie,
                'serie' => $subserie->serie->nombre,
                'subserie' => $subserie->nombre,
                'asunto' => $subserie->descripcion,
            ],
            'asunto' => $request->asunto,
            'tipo_comunicacion' => $request->tipo_comunicacion,
            'numero_folios' => $request->numero_folios ?: 1,
            'observaciones' => $request->observaciones,
            'medio_envio' => $request->medio_envio,
            'requiere_acuse_recibo' => $request->requiere_acuse_recibo,
            'tipo_anexo' => $request->tipo_anexo,
            'usuario_radica' => auth()->user()->name,
        ];

        return view('radicacion.salida.preview', compact('datosPreview'));
    }
}
