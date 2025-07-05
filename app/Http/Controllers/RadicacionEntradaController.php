<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\Subserie;
use App\Models\Documento;
use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\UnidadAdministrativa;
use Carbon\Carbon;

class RadicacionEntradaController extends Controller
{
    /**
     * Constructor - aplicar middleware de autenticación
     */
    public function __construct()
    {
        // En Laravel 11+, el middleware se aplica en las rutas
        // La verificación de autenticación se hace manualmente en cada método
    }



    /**
     * Procesar la radicación de entrada
     */
    public function store(Request $request)
    {
        // Log para debugging
        \Log::info('RadicacionEntrada::store - Iniciando proceso', [
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_file_documento' => $request->hasFile('documento')
        ]);

        $validator = Validator::make($request->all(), [
            // Datos del remitente
            'tipo_remitente' => 'required|in:anonimo,registrado',
            'tipo_documento' => 'required_if:tipo_remitente,registrado|nullable|in:CC,CE,TI,PP,NIT,OTRO',
            'numero_documento' => 'required_if:tipo_remitente,registrado|nullable|string|max:20',
            'nombre_completo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'entidad' => 'nullable|string|max:255',

            // Datos del radicado
            'medio_recepcion' => 'required|in:fisico,email,web,telefono,fax,otro',
            'tipo_comunicacion' => 'required|exists:tipos_solicitud,codigo',
            'numero_folios' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',

            // TRD (Subserie)
            'trd_id' => 'required|exists:subseries,id',

            // Destino
            'dependencia_destino_id' => 'required|exists:dependencias,id',
            'medio_respuesta' => 'required|in:fisico,email,telefono,presencial,no_requiere',
            'tipo_anexo' => 'required|in:original,copia,ninguno',
            'fecha_limite_respuesta' => 'nullable|date|after:today',

            // Documento
            'documento' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240', // 10MB max
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        // Verificar MIME type real
                        $realMimeType = mime_content_type($value->getRealPath());
                        $allowedMimes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/jpeg',
                            'image/png'
                        ];

                        if (!in_array($realMimeType, $allowedMimes)) {
                            $fail('Tipo de archivo no permitido por seguridad.');
                            \Log::channel('security')->warning('Intento de subida de archivo con MIME type no permitido', [
                                'ip' => request()->ip(),
                                'user_id' => auth()->id(),
                                'filename' => $value->getClientOriginalName(),
                                'detected_mime' => $realMimeType,
                                'timestamp' => now()->toISOString()
                            ]);
                        }
                    }
                }
            ]
        ], [
            'tipo_remitente.required' => 'Debe seleccionar el tipo de remitente',
            'tipo_documento.required_if' => 'El tipo de documento es obligatorio para remitentes registrados',
            'numero_documento.required_if' => 'El número de documento es obligatorio para remitentes registrados',
            'nombre_completo.required' => 'El nombre completo es obligatorio',
            'medio_recepcion.required' => 'Debe seleccionar el medio de recepción',
            'tipo_comunicacion.required' => 'Debe seleccionar el tipo de comunicación',
            'numero_folios.required' => 'El número de folios es obligatorio',
            'numero_folios.min' => 'El número de folios debe ser al menos 1',
            'trd_id.required' => 'Debe seleccionar un TRD',
            'trd_id.exists' => 'El TRD seleccionado no es válido',
            'dependencia_destino_id.required' => 'Debe seleccionar la dependencia destino',
            'dependencia_destino_id.exists' => 'La dependencia seleccionada no es válida',
            'medio_respuesta.required' => 'Debe seleccionar el medio de respuesta',
            'tipo_anexo.required' => 'Debe seleccionar el tipo de anexo',
            'fecha_limite_respuesta.after' => 'La fecha límite debe ser posterior a hoy',
            'documento.required' => 'Debe adjuntar un documento',
            'documento.mimes' => 'El documento debe ser PDF, Word o imagen',
            'documento.max' => 'El documento no puede superar los 10MB',
        ]);

        if ($validator->fails()) {
            \Log::error('RadicacionEntrada::store - Validación fallida', [
                'errors' => $validator->errors()->toArray()
            ]);
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            \Log::info('RadicacionEntrada::store - Transacción iniciada');

            // Obtener nombres de ciudad y departamento si se seleccionaron
            $ciudadNombre = null;
            $departamentoNombre = null;

            if ($request->ciudad_id) {
                $ciudad = Ciudad::find($request->ciudad_id);
                $ciudadNombre = $ciudad ? $ciudad->nombre : null;
            }

            if ($request->departamento_id) {
                $departamento = Departamento::find($request->departamento_id);
                $departamentoNombre = $departamento ? $departamento->nombre : null;
            }

            // Crear o buscar remitente
            $remitenteData = [
                'tipo' => $request->tipo_remitente,
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'ciudad' => $ciudadNombre,
                'departamento' => $departamentoNombre,
                'entidad' => $request->entidad,
                'observaciones' => $request->observaciones_remitente,
            ];

            \Log::info('RadicacionEntrada::store - Datos del remitente preparados', $remitenteData);

            if ($request->tipo_remitente === 'registrado') {
                $remitenteData['tipo_documento'] = $request->tipo_documento;
                $remitenteData['numero_documento'] = $request->numero_documento;

                // Buscar remitente existente
                $remitente = Remitente::where('tipo_documento', $request->tipo_documento)
                                    ->where('numero_documento', $request->numero_documento)
                                    ->first();

                if ($remitente) {
                    // Actualizar datos del remitente existente
                    $remitente->update($remitenteData);
                    \Log::info('RadicacionEntrada::store - Remitente existente actualizado', ['id' => $remitente->id]);
                } else {
                    // Crear nuevo remitente
                    $remitente = Remitente::create($remitenteData);
                    \Log::info('RadicacionEntrada::store - Nuevo remitente creado', ['id' => $remitente->id]);
                }
            } else {
                // Crear remitente anónimo
                $remitente = Remitente::create($remitenteData);
                \Log::info('RadicacionEntrada::store - Remitente anónimo creado', ['id' => $remitente->id]);
            }

            // Generar número de radicado
            $numeroRadicado = Radicado::generarNumeroRadicado('entrada');
            \Log::info('RadicacionEntrada::store - Número de radicado generado', ['numero' => $numeroRadicado]);

            // Crear radicado
            $radicadoData = [
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'entrada',
                'fecha_radicado' => Carbon::now()->toDateString(),
                'hora_radicado' => Carbon::now()->toTimeString(),
                'remitente_id' => $remitente->id,
                'subserie_id' => $request->trd_id, // trd_id viene del formulario pero se guarda como subserie_id
                'dependencia_destino_id' => $request->dependencia_destino_id,
                'usuario_radica_id' => auth()->id(),
                'medio_recepcion' => $request->medio_recepcion,
                'tipo_comunicacion' => $request->tipo_comunicacion,
                'numero_folios' => $request->numero_folios,
                'observaciones' => $request->observaciones,
                'medio_respuesta' => $request->medio_respuesta,
                'tipo_anexo' => $request->tipo_anexo,
                'fecha_limite_respuesta' => $request->fecha_limite_respuesta,
                'estado' => 'pendiente',
            ];

            \Log::info('RadicacionEntrada::store - Datos del radicado preparados', $radicadoData);

            $radicado = Radicado::create($radicadoData);
            \Log::info('RadicacionEntrada::store - Radicado creado exitosamente', ['id' => $radicado->id]);

            // Procesar documento adjunto
            if ($request->hasFile('documento')) {
                \Log::info('RadicacionEntrada::store - Procesando documento adjunto');
                $archivo = $request->file('documento');
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = $numeroRadicado . '_' . time() . '.' . $extension;

                // Guardar archivo
                $rutaArchivo = $archivo->storeAs('documentos/entrada', $nombreArchivo, 'public');

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
                    'descripcion' => 'Documento principal del radicado',
                    'es_principal' => true,
                ]);
                \Log::info('RadicacionEntrada::store - Documento guardado exitosamente');
            } else {
                \Log::info('RadicacionEntrada::store - No se adjuntó documento');
            }

            DB::commit();
            \Log::info('RadicacionEntrada::store - Transacción confirmada exitosamente');

            return redirect()->route('radicacion.index')
                           ->with('success', "Radicado de entrada {$numeroRadicado} creado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('RadicacionEntrada::store - Error en el proceso', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Error al crear el radicado: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Buscar remitente por documento para autocompletado
     */
    public function buscarRemitente(Request $request)
    {
        $numeroDocumento = $request->get('numero_documento');

        if (!$numeroDocumento) {
            return response()->json(['found' => false]);
        }

        $remitente = Remitente::where('numero_documento', $numeroDocumento)
                             ->where('tipo', 'registrado')
                             ->first();

        if ($remitente) {
            // Buscar IDs de ciudad y departamento basados en los nombres
            $ciudadId = null;
            $departamentoId = null;

            if ($remitente->ciudad) {
                $ciudad = Ciudad::where('nombre', $remitente->ciudad)->first();
                $ciudadId = $ciudad ? $ciudad->id : null;
            }

            if ($remitente->departamento) {
                $departamento = Departamento::where('nombre', $remitente->departamento)->first();
                $departamentoId = $departamento ? $departamento->id : null;
            }

            return response()->json([
                'found' => true,
                'data' => [
                    'tipo_documento' => $remitente->tipo_documento,
                    'numero_documento' => $remitente->numero_documento,
                    'nombre_completo' => $remitente->nombre_completo,
                    'telefono' => $remitente->telefono,
                    'email' => $remitente->email,
                    'direccion' => $remitente->direccion,
                    'ciudad' => $remitente->ciudad,
                    'departamento' => $remitente->departamento,
                    'ciudad_id' => $ciudadId,
                    'departamento_id' => $departamentoId,
                    'entidad' => $remitente->entidad,
                ]
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Generar previsualización del radicado
     */
    public function previsualizacion(Request $request)
    {
        // Validar datos básicos para la previsualización
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'trd_id' => 'required|exists:subseries,id',
            'dependencia_destino_id' => 'required|exists:dependencias,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Datos incompletos para la previsualización'], 400);
        }

        // Obtener datos relacionados
        $subserie = Subserie::with(['serie.unidadAdministrativa'])->find($request->trd_id);
        $dependencia = Dependencia::find($request->dependencia_destino_id);

        // Generar número de radicado temporal para previsualización
        $numeroRadicado = Radicado::generarNumeroRadicado('entrada');

        // Datos para la previsualización
        $datosPreview = [
            'numero_radicado' => $numeroRadicado,
            'fecha_radicado' => Carbon::now()->format('d/m/Y'),
            'hora_radicado' => Carbon::now()->format('H:i:s'),
            'remitente' => [
                'nombre_completo' => $request->nombre_completo,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ],
            'trd' => [
                'codigo' => $subserie->serie->unidadAdministrativa->codigo . '.' . $subserie->serie->numero_serie . '.' . $subserie->numero_subserie,
                'serie' => $subserie->serie->nombre,
                'subserie' => $subserie->nombre,
                'asunto' => $subserie->descripcion,
            ],
            'dependencia_destino' => $dependencia->nombre,
            'medio_recepcion' => $request->medio_recepcion,
            'tipo_comunicacion' => $request->tipo_comunicacion,
            'numero_folios' => $request->numero_folios,
            'observaciones' => $request->observaciones,
        ];

        return view('radicacion.entrada.preview', compact('datosPreview'));
    }

    /**
     * Mostrar un radicado específico
     */
    public function show($id)
    {
        $radicado = Radicado::with(['remitente', 'subserie.serie.unidadAdministrativa', 'dependenciaDestino', 'usuarioRadica', 'documentos'])
                           ->findOrFail($id);

        return view('radicacion.entrada.show', compact('radicado'));
    }
}
