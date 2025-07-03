<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;

use App\Models\Documento;
use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\UnidadAdministrativa;
use App\Models\Subserie;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RadicacionSalidaController extends Controller
{
    /**
     * Mostrar el formulario de radicación de salida
     */
    public function index()
    {
        $dependencias = Dependencia::activas()->orderBy('nombre')->get();
        $ciudades = Ciudad::with('departamento')->activo()->ordenado()->get();
        $departamentos = Departamento::activo()->ordenado()->get();
        $unidadesAdministrativas = UnidadAdministrativa::activas()->orderBy('codigo')->get();

        $tiposSolicitud = \App\Models\TipoSolicitud::activos()->ordenado()->get();

        return view('radicacion.salida.index', compact('dependencias', 'ciudades', 'departamentos', 'unidadesAdministrativas', 'tiposSolicitud'));
    }

    /**
     * Procesar la radicación de salida
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Datos del destinatario externo
            'tipo_destinatario' => 'required|in:persona_natural,persona_juridica,entidad_publica',
            'tipo_documento_destinatario' => 'required_if:tipo_destinatario,persona_natural|in:CC,CE,TI,PP,OTRO',
            'numero_documento_destinatario' => 'required_if:tipo_destinatario,persona_natural|string|max:20',
            'nit_destinatario' => 'required_if:tipo_destinatario,persona_juridica,entidad_publica|string|max:20',
            'nombre_destinatario' => 'required|string|max:255',
            'telefono_destinatario' => 'nullable|string|max:20',
            'email_destinatario' => 'nullable|email|max:255',
            'direccion_destinatario' => 'required|string',
            'ciudad_destinatario' => 'required|string|max:100',
            'departamento_destinatario' => 'required|string|max:100',

            // Datos del remitente interno (dependencia origen)
            'dependencia_origen_id' => 'required|exists:dependencias,id',
            'funcionario_remitente' => 'required|string|max:255',
            'cargo_remitente' => 'nullable|string|max:255',

            // Datos del documento
            'asunto' => 'required|string|max:500',
            'tipo_comunicacion' => 'required|exists:tipos_solicitud,codigo',
            'numero_folios' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',
            'prioridad' => 'required|in:baja,normal,alta,urgente',

            // TRD (Subserie)
            'trd_id' => 'required|exists:subseries,id',

            // Información de envío
            'medio_envio' => 'required|in:correo_fisico,correo_electronico,mensajeria,entrega_personal',
            'requiere_acuse_recibo' => 'required|boolean',
            'fecha_limite_respuesta' => 'nullable|date|after:today',
            'tipo_anexo' => 'required|in:original,copia,ninguno',

            // Documento
            'documento' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ], [
            'tipo_destinatario.required' => 'Debe seleccionar el tipo de destinatario',
            'tipo_documento_destinatario.required_if' => 'El tipo de documento es obligatorio para personas naturales',
            'numero_documento_destinatario.required_if' => 'El número de documento es obligatorio para personas naturales',
            'nit_destinatario.required_if' => 'El NIT es obligatorio para personas jurídicas y entidades públicas',
            'nombre_destinatario.required' => 'El nombre del destinatario es obligatorio',
            'direccion_destinatario.required' => 'La dirección del destinatario es obligatoria',
            'ciudad_destinatario.required' => 'La ciudad del destinatario es obligatoria',
            'departamento_destinatario.required' => 'El departamento del destinatario es obligatorio',
            'dependencia_origen_id.required' => 'Debe seleccionar la dependencia de origen',
            'funcionario_remitente.required' => 'El nombre del funcionario remitente es obligatorio',
            'asunto.required' => 'El asunto del documento es obligatorio',
            'asunto.max' => 'El asunto no puede superar los 500 caracteres',
            'tipo_comunicacion.required' => 'Debe seleccionar el tipo de comunicación',
            'numero_folios.required' => 'El número de folios es obligatorio',
            'numero_folios.min' => 'El número de folios debe ser al menos 1',
            'prioridad.required' => 'Debe seleccionar la prioridad del documento',
            'trd_id.required' => 'Debe seleccionar un TRD',
            'medio_envio.required' => 'Debe seleccionar el medio de envío',
            'requiere_acuse_recibo.required' => 'Debe indicar si requiere acuse de recibo',
            'fecha_limite_respuesta.after' => 'La fecha límite debe ser posterior a hoy',
            'tipo_anexo.required' => 'Debe seleccionar el tipo de anexo',
            'documento.required' => 'Debe adjuntar un documento',
            'documento.mimes' => 'El documento debe ser PDF, Word o imagen',
            'documento.max' => 'El documento no puede superar los 10MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Crear destinatario externo como remitente (para mantener consistencia en el modelo)
            $tipoDocumento = null;
            $numeroDocumento = null;

            if ($request->tipo_destinatario === 'persona_natural') {
                $tipoDocumento = $request->tipo_documento_destinatario;
                $numeroDocumento = $request->numero_documento_destinatario;
            } else {
                $tipoDocumento = 'NIT';
                $numeroDocumento = $request->nit_destinatario;
            }

            // Obtener nombres de ciudad y departamento si se seleccionaron
            $ciudadNombre = null;
            $departamentoNombre = null;

            if ($request->ciudad_destinatario_id) {
                $ciudad = Ciudad::find($request->ciudad_destinatario_id);
                $ciudadNombre = $ciudad ? $ciudad->nombre : null;
            }

            if ($request->departamento_destinatario_id) {
                $departamento = Departamento::find($request->departamento_destinatario_id);
                $departamentoNombre = $departamento ? $departamento->nombre : null;
            }

            $destinatario = Remitente::create([
                'tipo' => 'registrado',
                'tipo_documento' => $tipoDocumento,
                'numero_documento' => $numeroDocumento,
                'nombre_completo' => $request->nombre_destinatario,
                'telefono' => $request->telefono_destinatario,
                'email' => $request->email_destinatario,
                'direccion' => $request->direccion_destinatario,
                'ciudad' => $ciudadNombre,
                'departamento' => $departamentoNombre,
                'entidad' => $request->tipo_destinatario === 'persona_natural' ? null : $request->nombre_destinatario,
                'observaciones' => "DESTINATARIO EXTERNO - Tipo: " . ucfirst(str_replace('_', ' ', $request->tipo_destinatario)),
            ]);

            // Generar número de radicado de salida
            $numeroRadicado = Radicado::generarNumeroRadicado('salida');

            // Crear radicado
            $radicado = Radicado::create([
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'salida',
                'fecha_radicado' => Carbon::now()->toDateString(),
                'hora_radicado' => Carbon::now()->toTimeString(),
                'remitente_id' => $destinatario->id, // En salida, el "remitente" es el destinatario externo
                'subserie_id' => $request->trd_id, // trd_id viene del formulario pero se guarda como subserie_id
                'dependencia_destino_id' => $request->dependencia_origen_id, // La dependencia origen es quien envía
                'usuario_radica_id' => auth()->id(),
                'medio_recepcion' => 'salida',
                'tipo_comunicacion' => 'fisico',
                'numero_folios' => $request->numero_folios,
                'observaciones' => $request->observaciones,
                'medio_respuesta' => $request->requiere_acuse_recibo ? $request->medio_envio : 'no_requiere',
                'tipo_anexo' => $request->tipo_anexo,
                'fecha_limite_respuesta' => $request->fecha_limite_respuesta,
                'estado' => 'pendiente',
            ]);

            // Agregar campos específicos para documentos de salida en observaciones
            $dependenciaOrigen = Dependencia::findOrFail($request->dependencia_origen_id);
            $observacionesSalida = [
                "DOCUMENTO DE SALIDA",
                "Dependencia Origen: {$dependenciaOrigen->nombre_completo}",
                "Funcionario: {$request->funcionario_remitente}",
                "Destinatario: {$request->nombre_destinatario}",
                "Tipo: " . ucfirst($request->tipo_comunicacion),
                "Asunto: {$request->asunto}",
                "Prioridad: " . ucfirst($request->prioridad),
                "Medio de Envío: " . ucfirst(str_replace('_', ' ', $request->medio_envio)),
            ];

            if ($request->cargo_remitente) {
                $observacionesSalida[] = "Cargo: {$request->cargo_remitente}";
            }

            if ($request->requiere_acuse_recibo) {
                $observacionesSalida[] = "Requiere Acuse de Recibo: Sí";
            }

            if ($request->observaciones) {
                $observacionesSalida[] = "Observaciones: {$request->observaciones}";
            }

            $radicado->update([
                'observaciones' => implode(" | ", $observacionesSalida)
            ]);

            // Procesar documento adjunto
            if ($request->hasFile('documento')) {
                $archivo = $request->file('documento');
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = $numeroRadicado . '_' . time() . '.' . $extension;

                // Guardar archivo en directorio específico para documentos de salida
                $rutaArchivo = $archivo->storeAs('documentos/salida', $nombreArchivo, 'public');

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
                    'descripcion' => "Documento de salida: {$request->tipo_comunicacion} - {$request->asunto}",
                    'es_principal' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('radicacion.salida.show', $radicado->id)
                           ->with('success', "Radicado de salida {$numeroRadicado} creado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Error al crear el radicado de salida: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Mostrar previsualización del radicado de salida
     */
    public function preview(Request $request)
    {
        // Validar datos básicos
        $validator = Validator::make($request->all(), [
            'dependencia_origen_id' => 'required|exists:dependencias,id',
            'funcionario_remitente' => 'required|string|max:255',
            'trd_id' => 'required|exists:subseries,id',
            'asunto_salida' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtener datos para la previsualización
        $dependenciaOrigen = Dependencia::findOrFail($request->dependencia_origen_id);
        $subserie = Subserie::with(['serie.unidadAdministrativa'])->findOrFail($request->trd_id);

        // Generar número de radicado temporal
        $numeroRadicado = Radicado::generarNumeroRadicado('salida');

        // Preparar datos para la vista
        $datosPreview = [
            'numero_radicado' => $numeroRadicado,
            'tipo' => 'salida',
            'fecha_radicado' => now()->format('d/m/Y'),
            'hora_radicado' => now()->format('H:i:s'),
            'dependencia_origen' => $dependenciaOrigen,
            'funcionario_remitente' => $request->funcionario_remitente,
            'cargo_remitente' => $request->cargo_remitente,
            'telefono_remitente' => $request->telefono_remitente,
            'email_remitente' => $request->email_remitente,
            'nombre_destinatario' => $request->nombre_destinatario,
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
            'asunto' => $request->asunto_salida,
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

    /**
     * Mostrar un radicado de salida específico
     */
    public function show($id)
    {
        $radicado = Radicado::with(['remitente', 'trd', 'dependenciaDestino', 'usuarioRadica', 'documentos'])
                           ->where('tipo', 'salida')
                           ->findOrFail($id);

        return view('radicacion.salida.show', compact('radicado'));
    }
}
