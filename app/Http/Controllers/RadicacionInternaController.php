<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\UnidadAdministrativa;
use App\Models\Subserie;
use App\Models\Documento;
use Carbon\Carbon;

class RadicacionInternaController extends Controller
{


    /**
     * Buscar radicados para respuesta
     */
    public function buscarRadicados(Request $request)
    {
        $termino = $request->get('termino');

        if (!$termino) {
            return response()->json([]);
        }

        $radicados = Radicado::with(['remitente', 'dependenciaDestino', 'subserie.serie'])
                            ->where(function($query) use ($termino) {
                                $query->where('numero_radicado', 'like', "%{$termino}%")
                                      ->orWhereHas('remitente', function($q) use ($termino) {
                                          $q->where('nombre_completo', 'like', "%{$termino}%");
                                      });
                            })
                            ->where('estado', '!=', 'archivado')
                            ->limit(10)
                            ->get()
                            ->map(function($radicado) {
                                return [
                                    'id' => $radicado->id,
                                    'numero_radicado' => $radicado->numero_radicado,
                                    'remitente' => $radicado->remitente->nombre_completo,
                                    'dependencia' => $radicado->dependenciaDestino->nombre,
                                    'fecha' => $radicado->fecha_radicado,
                                    'asunto' => $radicado->subserie->serie->nombre ?? 'Sin asunto'
                                ];
                            });

        return response()->json($radicados);
    }

    /**
     * Procesar la radicación interna
     */
    public function store(Request $request)
    {
        // Log para debugging MEJORADO
        \Log::info('🔧 RadicacionInterna::store - INICIANDO PROCESO', [
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_file_documento' => $request->hasFile('documento'),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'N/A'
        ]);

        $validator = Validator::make($request->all(), [
            // Datos del remitente interno (dependencia origen)
            'dependencia_origen_id' => 'required|exists:dependencias,id',
            'funcionario_remitente' => 'required|string|max:255',
            'cargo_remitente' => 'nullable|string|max:255',
            'telefono_remitente' => 'nullable|string|max:20',
            'email_remitente' => 'nullable|email|max:255',

            // Datos del radicado
            'asunto' => 'required|string|max:500',
            'tipo_comunicacion' => 'required|in:memorando,circular,oficio,informe,acta,otro',
            'numero_folios' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',
            'prioridad' => 'required|in:baja,normal,alta,urgente',

            // TRD (Subserie) - Opcional
            'trd_id' => 'nullable|exists:subseries,id',

            // Respuesta a documento (opcional) - Funcionalidad pendiente de implementar
            'es_respuesta' => 'nullable|boolean',

            // Destino
            'dependencia_destino_id' => 'required|exists:dependencias,id|different:dependencia_origen_id',
            'requiere_respuesta' => 'required|in:si,no',
            'fecha_limite_respuesta' => 'nullable|date|after:today|required_if:requiere_respuesta,si',
            'tipo_anexo' => 'required|in:original,copia,ninguno',

            // Documento (opcional)
            'documento' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ], [
            'dependencia_origen_id.required' => 'Debe seleccionar la dependencia de origen',
            'dependencia_origen_id.exists' => 'La dependencia de origen no es válida',
            'funcionario_remitente.required' => 'El nombre del funcionario remitente es obligatorio',
            'asunto.required' => 'El asunto del documento es obligatorio',
            'asunto.max' => 'El asunto no puede superar los 500 caracteres',
            'tipo_comunicacion.required' => 'Debe seleccionar el tipo de comunicación',
            'numero_folios.required' => 'El número de folios es obligatorio',
            'numero_folios.min' => 'El número de folios debe ser al menos 1',
            'prioridad.required' => 'Debe seleccionar la prioridad del documento',
            'trd_id.required' => 'Debe seleccionar un TRD',
            'trd_id.exists' => 'El TRD seleccionado no es válido',
            'dependencia_destino_id.required' => 'Debe seleccionar la dependencia destino',
            'dependencia_destino_id.exists' => 'La dependencia destino no es válida',
            'dependencia_destino_id.different' => 'La dependencia destino debe ser diferente a la de origen',
            'requiere_respuesta.required' => 'Debe indicar si requiere respuesta',
            'fecha_limite_respuesta.after' => 'La fecha límite debe ser posterior a hoy',
            'fecha_limite_respuesta.required_if' => 'La fecha límite es obligatoria cuando se requiere respuesta',
            'tipo_anexo.required' => 'Debe seleccionar el tipo de anexo',
            'documento.mimes' => 'El documento debe ser PDF, Word o imagen',
            'documento.max' => 'El documento no puede superar los 10MB',
        ]);

        if ($validator->fails()) {
            \Log::error('RadicacionInterna::store - Validación fallida', [
                'errors' => $validator->errors()->toArray()
            ]);

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        \Log::info('RadicacionInterna::store - Validación exitosa, continuando...');

        try {
            DB::beginTransaction();
            \Log::info('RadicacionInterna::store - Transacción iniciada');

            // Crear remitente interno (representando la dependencia origen)
            \Log::info('RadicacionInterna::store - Buscando dependencia origen', ['id' => $request->dependencia_origen_id]);
            $dependenciaOrigen = Dependencia::findOrFail($request->dependencia_origen_id);
            \Log::info('RadicacionInterna::store - Dependencia origen encontrada', ['nombre' => $dependenciaOrigen->nombre_completo]);

            $remitente = Remitente::create([
                'tipo' => 'registrado',
                'tipo_documento' => 'OTRO', // Usar 'OTRO' en lugar de 'INTERNO' para cumplir con el enum
                'numero_documento' => $dependenciaOrigen->codigo,
                'nombre_completo' => $request->funcionario_remitente,
                'telefono' => $request->telefono_remitente,
                'email' => $request->email_remitente,
                'direccion' => null,
                'ciudad' => null,
                'departamento' => null,
                'entidad' => $dependenciaOrigen->nombre_completo,
                'observaciones' => "Cargo: " . ($request->cargo_remitente ?: 'No especificado') . " | Tipo: INTERNO",
            ]);
            \Log::info('RadicacionInterna::store - Remitente creado', ['id' => $remitente->id]);

            // Generar número de radicado interno
            $numeroRadicado = Radicado::generarNumeroRadicado('interno');
            \Log::info('RadicacionInterna::store - Número de radicado generado', ['numero' => $numeroRadicado]);

            // Convertir requiere_respuesta de string a boolean
            $requiereRespuesta = $request->requiere_respuesta === 'si';

            // Crear radicado
            $radicadoData = [
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'interno',
                'fecha_radicado' => Carbon::now()->toDateString(),
                'hora_radicado' => Carbon::now()->toTimeString(),
                'remitente_id' => $remitente->id,
                'subserie_id' => $request->trd_id, // trd_id viene del formulario pero se guarda como subserie_id
                'dependencia_destino_id' => $request->dependencia_destino_id,
                'dependencia_origen_id' => $request->dependencia_origen_id, // Agregar dependencia de origen
                'usuario_radica_id' => auth()->id(),
                'medio_recepcion' => 'otro', // Para documentos internos
                'tipo_comunicacion' => $request->tipo_comunicacion,
                'numero_folios' => $request->numero_folios,
                'observaciones' => $request->observaciones,
                'medio_respuesta' => $requiereRespuesta ? 'presencial' : 'no_requiere', // Para documentos internos
                'tipo_anexo' => $request->tipo_anexo,
                'fecha_limite_respuesta' => $requiereRespuesta ? $request->fecha_limite_respuesta : null,
                'estado' => 'pendiente',
            ];

            \Log::info('RadicacionInterna::store - Datos del radicado preparados', $radicadoData);

            $radicado = Radicado::create($radicadoData);
            \Log::info('RadicacionInterna::store - Radicado creado exitosamente', ['id' => $radicado->id]);

            // TODO: Implementar lógica de respuesta cuando se agregue el campo radicado_respuesta_id
            // Si es respuesta, actualizar el radicado original
            // if ($request->es_respuesta && $request->radicado_respuesta_id) {
            //     $radicadoOriginal = Radicado::find($request->radicado_respuesta_id);
            //     if ($radicadoOriginal) {
            //         $radicadoOriginal->update(['estado' => 'respondido']);
            //     }
            // }

            // Agregar campos específicos para documentos internos en observaciones
            $observacionesInternas = [
                "DOCUMENTO INTERNO",
                "Dependencia Origen: {$dependenciaOrigen->nombre_completo}",
                "Funcionario: {$request->funcionario_remitente}",
                "Tipo: " . ucfirst($request->tipo_comunicacion),
                "Asunto: {$request->asunto}",
                "Prioridad: " . ucfirst($request->prioridad),
            ];

            if ($request->cargo_remitente) {
                $observacionesInternas[] = "Cargo: {$request->cargo_remitente}";
            }

            if ($request->observaciones) {
                $observacionesInternas[] = "Observaciones: {$request->observaciones}";
            }

            $radicado->update([
                'observaciones' => implode(" | ", $observacionesInternas)
            ]);

            // Procesar documento adjunto
            if ($request->hasFile('documento')) {
                $archivo = $request->file('documento');
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = $numeroRadicado . '_' . time() . '.' . $extension;

                // Guardar archivo en directorio específico para documentos internos
                $rutaArchivo = $archivo->storeAs('documentos/interno', $nombreArchivo, 'public');

                // Calcular hash para integridad
                $contenido = file_get_contents($archivo->getPathname());
                $hashArchivo = hash('sha256', $contenido);

                // Crear registro de documento
                Documento::create([
                    'radicado_id' => $radicado->id,
                    'nombre_archivo' => $nombreOriginal,
                    'ruta_archivo' => $rutaArchivo,
                    'tipo_mime' => $archivo->getMimeType(),
                    'tamaño_archivo' => $archivo->getSize(),
                    'hash_archivo' => $hashArchivo,
                    'descripcion' => "Documento interno: {$request->tipo_comunicacion} - {$request->asunto}",
                    'es_principal' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('radicacion.index')
                           ->with('success', "Radicado interno {$numeroRadicado} creado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('RadicacionInterna::store - Error en el proceso', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al crear el radicado interno: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al crear el radicado interno: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Generar previsualización del radicado interno
     */
    public function previsualizacion(Request $request)
    {
        // Validar campos mínimos requeridos para preview
        $validator = Validator::make($request->all(), [
            'funcionario_remitente' => 'required|string|max:255',
            'dependencia_origen_id' => 'required|exists:dependencias,id',
            'trd_id' => 'required|exists:subseries,id',
            'dependencia_destino_id' => 'required|exists:dependencias,id',
            'asunto' => 'required|string|max:500',
            'tipo_comunicacion' => 'required|in:memorando,circular,oficio,informe,acta,otro',
            'prioridad' => 'required|in:baja,normal,alta,urgente',
        ], [
            'funcionario_remitente.required' => 'El nombre del funcionario remitente es obligatorio',
            'dependencia_origen_id.required' => 'Debe seleccionar la dependencia de origen',
            'dependencia_origen_id.exists' => 'La dependencia de origen no es válida',
            'trd_id.required' => 'Debe seleccionar un TRD',
            'trd_id.exists' => 'El TRD seleccionado no es válido',
            'dependencia_destino_id.required' => 'Debe seleccionar la dependencia destino',
            'dependencia_destino_id.exists' => 'La dependencia destino no es válida',
            'asunto.required' => 'El asunto del documento es obligatorio',
            'asunto.max' => 'El asunto no puede superar los 500 caracteres',
            'tipo_comunicacion.required' => 'Debe seleccionar el tipo de comunicación',
            'prioridad.required' => 'Debe seleccionar la prioridad del documento',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generar número de radicado temporal para previsualización
        $numeroRadicado = Radicado::generarNumeroRadicado('interno');

        // Obtener datos relacionados
        $dependenciaOrigen = Dependencia::findOrFail($request->dependencia_origen_id);
        $dependenciaDestino = Dependencia::findOrFail($request->dependencia_destino_id);
        $subserie = Subserie::with(['serie.unidadAdministrativa'])->findOrFail($request->trd_id);

        // Preparar datos para la vista
        $datosPreview = [
            'numero_radicado' => $numeroRadicado,
            'tipo' => 'interno',
            'fecha_radicado' => now()->format('d/m/Y'),
            'hora_radicado' => now()->format('H:i:s'),
            'dependencia_origen' => $dependenciaOrigen,
            'funcionario_remitente' => $request->funcionario_remitente,
            'cargo_remitente' => $request->cargo_remitente,
            'telefono_remitente' => $request->telefono_remitente,
            'email_remitente' => $request->email_remitente,
            'dependencia_destino' => $dependenciaDestino,
            'trd' => [
                'codigo' => $subserie->serie->unidadAdministrativa->codigo . '.' . $subserie->serie->numero_serie . '.' . $subserie->numero_subserie,
                'serie' => $subserie->serie->nombre,
                'subserie' => $subserie->nombre,
                'asunto' => $subserie->descripcion,
            ],
            'asunto' => $request->asunto,
            'tipo_comunicacion' => $request->tipo_comunicacion,
            'prioridad' => $request->prioridad,
            'numero_folios' => $request->numero_folios ?: 1,
            'observaciones' => $request->observaciones,
            'requiere_respuesta' => $request->requiere_respuesta,
            'fecha_limite_respuesta' => $request->fecha_limite_respuesta,
            'tipo_anexo' => $request->tipo_anexo,
            'usuario_radica' => auth()->user()->name,
        ];

        return view('radicacion.interna.preview', compact('datosPreview'));
    }

    /**
     * Mostrar un radicado interno específico
     */
    public function show($id)
    {
        $radicado = Radicado::with(['remitente', 'subserie.serie.unidadAdministrativa', 'dependenciaDestino', 'usuarioRadica', 'documentos'])
                           ->where('tipo', 'interno')
                           ->findOrFail($id);

        // Obtener dependencia origen desde el remitente
        $dependenciaOrigen = Dependencia::where('codigo', $radicado->remitente->numero_documento)->first();

        return view('radicacion.interna.show', compact('radicado', 'dependenciaOrigen'));
    }
}
