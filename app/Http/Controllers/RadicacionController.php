<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radicado;
use App\Models\Dependencia;
use App\Models\Documento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RadicacionController extends Controller
{
    /**
     * Mostrar la vista principal de radicación con funcionalidad de consulta integrada
     */
    public function index(Request $request)
    {
        // Verificación manual de autenticación
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Por favor, inicia sesión para acceder a esta página.');
        }

        $user = auth()->user();

        // Obtener datos para los filtros de consulta
        $dependencias = Dependencia::activas()->orderBy('nombre')->get();

        // Inicializar query para consulta
        $query = Radicado::with(['remitente', 'dependenciaDestino', 'usuarioRadica', 'documentos', 'subserie.serie.unidadAdministrativa']);

        // Aplicar filtros basados en rol
        $this->aplicarFiltrosPorRol($query, $user);

        // Aplicar filtros de búsqueda si existen
        $filtros = $this->aplicarFiltros($query, $request);

        // Obtener resultados paginados para consulta
        $radicadosConsulta = null;
        $estadisticas = null;

        // Solo ejecutar consulta si hay filtros aplicados
        if (count($filtros) > 0 || $request->has('buscar')) {
            $radicadosConsulta = $query->orderBy('fecha_radicado', 'desc')
                                      ->orderBy('hora_radicado', 'desc')
                                      ->paginate(15)
                                      ->withQueryString();

            // Estadísticas rápidas
            $estadisticas = $this->obtenerEstadisticas($request, $user);
        }

        // Radicados recientes con paginación
        $queryRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'usuarioRadica', 'subserie.serie.unidadAdministrativa']);

        // Aplicar filtros por rol también a los radicados recientes
        $this->aplicarFiltrosPorRol($queryRecientes, $user);

        $radicadosRecientes = $queryRecientes->orderBy('created_at', 'desc')
                                           ->paginate(10);

        $view = view('radicacion.index', compact(
            'radicadosRecientes',
            'radicadosConsulta',
            'dependencias',
            'filtros',
            'estadisticas'
        ));

        // Si es una petición AJAX/SPA, devolver solo el contenido
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $view;
        }

        return $view;
    }

    /**
     * Aplicar filtros basados en el rol del usuario
     */
    private function aplicarFiltrosPorRol($query, $user)
    {
        // Tanto administradores como usuarios pueden ver todos los radicados
        // Los permisos se manejan a nivel de acciones (editar, eliminar, etc.)
        // No se aplica ningún filtro por rol para la visualización
    }

    /**
     * Aplicar filtros a la consulta
     */
    private function aplicarFiltros($query, Request $request)
    {
        $filtros = [];

        // Filtro por número de radicado
        if ($request->filled('numero_radicado')) {
            $numeroRadicado = trim($request->numero_radicado);
            $query->where('numero_radicado', 'like', "%{$numeroRadicado}%");
            $filtros['numero_radicado'] = $numeroRadicado;
        }

        // Filtro por documento de identidad del remitente
        if ($request->filled('documento_remitente')) {
            $documento = trim($request->documento_remitente);
            $query->whereHas('remitente', function($q) use ($documento) {
                $q->where('numero_documento', 'like', "%{$documento}%");
            });
            $filtros['documento_remitente'] = $documento;
        }

        // Filtro por nombre del remitente
        if ($request->filled('nombre_remitente')) {
            $nombre = trim($request->nombre_remitente);
            $query->whereHas('remitente', function($q) use ($nombre) {
                $q->where('nombres', 'like', "%{$nombre}%")
                  ->orWhere('apellidos', 'like', "%{$nombre}%");
            });
            $filtros['nombre_remitente'] = $nombre;
        }

        // Filtro por dependencia destino
        if ($request->filled('dependencia_destino_id')) {
            $query->where('dependencia_destino_id', $request->dependencia_destino_id);
            $filtros['dependencia_destino_id'] = $request->dependencia_destino_id;
        }

        // Filtro por TRD (Subserie)
        if ($request->filled('trd_id')) {
            $query->where('subserie_id', $request->trd_id);
            $filtros['trd_id'] = $request->trd_id;
        }

        // Filtro por tipo de radicado
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
            $filtros['tipo'] = $request->tipo;
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
            $filtros['estado'] = $request->estado;
        }

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_radicado', '>=', $request->fecha_desde);
            $filtros['fecha_desde'] = $request->fecha_desde;
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_radicado', '<=', $request->fecha_hasta);
            $filtros['fecha_hasta'] = $request->fecha_hasta;
        }

        // Filtro por fecha límite de respuesta
        if ($request->filled('fecha_limite_respuesta')) {
            $query->where('fecha_limite_respuesta', '<=', $request->fecha_limite_respuesta);
            $filtros['fecha_limite_respuesta'] = $request->fecha_limite_respuesta;
        }

        // Filtro por documentos vencidos
        if ($request->filled('solo_vencidos') && $request->solo_vencidos == '1') {
            $query->where('fecha_limite_respuesta', '<', Carbon::now()->toDateString())
                  ->whereNotIn('estado', ['respondido', 'archivado']);
            $filtros['solo_vencidos'] = true;
        }

        // Filtro por medio de recepción
        if ($request->filled('medio_recepcion')) {
            $query->where('medio_recepcion', $request->medio_recepcion);
            $filtros['medio_recepcion'] = $request->medio_recepcion;
        }

        return $filtros;
    }

    /**
     * Obtener estadísticas rápidas
     */
    private function obtenerEstadisticas(Request $request, $user)
    {
        $baseQuery = Radicado::query();

        // Aplicar filtros por rol
        $this->aplicarFiltrosPorRol($baseQuery, $user);

        // Aplicar los mismos filtros base (sin estado)
        if ($request->filled('numero_radicado')) {
            $baseQuery->where('numero_radicado', 'like', "%{$request->numero_radicado}%");
        }

        if ($request->filled('documento_remitente')) {
            $baseQuery->whereHas('remitente', function($q) use ($request) {
                $q->where('numero_documento', 'like', "%{$request->documento_remitente}%");
            });
        }

        if ($request->filled('dependencia_destino_id')) {
            $baseQuery->where('dependencia_destino_id', $request->dependencia_destino_id);
        }

        if ($request->filled('fecha_desde')) {
            $baseQuery->where('fecha_radicado', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $baseQuery->where('fecha_radicado', '<=', $request->fecha_hasta);
        }

        return [
            'total' => (clone $baseQuery)->count(),
            'pendientes' => (clone $baseQuery)->where('estado', 'pendiente')->count(),
            'en_proceso' => (clone $baseQuery)->where('estado', 'en_proceso')->count(),
            'respondidos' => (clone $baseQuery)->where('estado', 'respondido')->count(),
            'vencidos' => (clone $baseQuery)->where('fecha_limite_respuesta', '<', Carbon::now()->toDateString())
                                           ->whereNotIn('estado', ['respondido', 'archivado'])
                                           ->count(),
        ];
    }

    /**
     * Exportar resultados de consulta
     */
    public function exportar(Request $request)
    {
        $user = auth()->user();

        // Inicializar query
        $query = Radicado::with(['remitente', 'trd', 'dependenciaDestino', 'usuarioRadica']);

        // Aplicar filtros
        $this->aplicarFiltrosPorRol($query, $user);
        $this->aplicarFiltros($query, $request);

        $radicados = $query->orderBy('fecha_radicado', 'desc')->get();

        $filename = 'radicados_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($radicados) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($file, [
                'Número Radicado',
                'Fecha',
                'Hora',
                'Tipo',
                'Estado',
                'Remitente',
                'Documento',
                'TRD',
                'Dependencia Destino',
                'Medio Recepción',
                'Folios',
                'Fecha Límite',
                'Usuario Radica'
            ]);

            // Datos
            foreach ($radicados as $radicado) {
                fputcsv($file, [
                    $radicado->numero_radicado,
                    $radicado->fecha_radicado->format('d/m/Y'),
                    Carbon::parse($radicado->hora_radicado)->format('H:i:s'),
                    ucfirst($radicado->tipo),
                    ucfirst(str_replace('_', ' ', $radicado->estado)),
                    $radicado->remitente->nombre_completo,
                    $radicado->remitente->identificacion_completa,
                    $radicado->trd->descripcion_completa,
                    $radicado->dependenciaDestino->nombre_completo,
                    ucfirst($radicado->medio_recepcion),
                    $radicado->numero_folios,
                    $radicado->fecha_limite_respuesta ? $radicado->fecha_limite_respuesta->format('d/m/Y') : 'N/A',
                    $radicado->usuarioRadica->name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Subir documento digitalizado
     */
    public function uploadDigitalized(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento_digitalizado' => 'required|file|mimes:pdf|max:10240', // 10MB máximo
            'numero_radicado' => 'required|string',
            'tipo_radicado' => 'required|in:entrada,interno,salida'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buscar el radicado
            $radicado = Radicado::where('numero_radicado', $request->numero_radicado)
                              ->where('tipo', $request->tipo_radicado)
                              ->first();

            if (!$radicado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Radicado no encontrado'
                ], 404);
            }

            // Procesar archivo digitalizado
            $archivo = $request->file('documento_digitalizado');
            $nombreOriginal = $archivo->getClientOriginalName();
            $nombreArchivo = $request->numero_radicado . '_digitalizado_' . time() . '.pdf';

            // Guardar archivo
            $rutaArchivo = $archivo->storeAs('documentos/digitalizados', $nombreArchivo, 'public');

            // Calcular hash para integridad
            $contenido = file_get_contents($archivo->getPathname());
            $hashArchivo = hash('sha256', $contenido);

            // Crear registro de documento digitalizado
            Documento::create([
                'radicado_id' => $radicado->id,
                'nombre_archivo' => $nombreOriginal,
                'ruta_archivo' => $rutaArchivo,
                'tipo_mime' => $archivo->getMimeType(),
                'tamaño_archivo' => $archivo->getSize(),
                'hash_archivo' => $hashArchivo,
                'descripcion' => 'Documento digitalizado con sello de radicado',
                'es_principal' => false,
                'es_digitalizado' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Documento digitalizado cargado exitosamente',
                'archivo' => [
                    'nombre' => $nombreOriginal,
                    'tamaño' => $archivo->getSize(),
                    'ruta' => $rutaArchivo
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar documento digitalizado', [
                'error' => $e->getMessage(),
                'numero_radicado' => $request->numero_radicado,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el documento digitalizado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Finalizar proceso de radicación
     */
    public function finalizar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_radicado' => 'required|string',
            'tipo_radicado' => 'required|in:entrada,interno,salida',
            'posicion_sello' => 'nullable|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Buscar el radicado
            $radicado = Radicado::where('numero_radicado', $request->numero_radicado)
                              ->where('tipo', $request->tipo_radicado)
                              ->first();

            if (!$radicado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Radicado no encontrado'
                ], 404);
            }

            // Verificar que tenga documento digitalizado
            $tieneDocumentoDigitalizado = $radicado->documentos()
                                                  ->where('es_digitalizado', true)
                                                  ->exists();

            if (!$tieneDocumentoDigitalizado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe cargar el documento digitalizado antes de finalizar'
                ], 400);
            }

            // Actualizar estado del radicado
            $radicado->update([
                'estado' => 'en_proceso',
                'fecha_finalizacion' => now(),
                'usuario_finaliza_id' => auth()->id(),
                'posicion_sello' => $request->posicion_sello
            ]);

            // Registrar en log de actividad
            Log::info('Radicado finalizado exitosamente', [
                'numero_radicado' => $radicado->numero_radicado,
                'tipo' => $radicado->tipo,
                'user_id' => auth()->id(),
                'radicado_id' => $radicado->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Radicado {$radicado->numero_radicado} finalizado exitosamente",
                'radicado' => [
                    'id' => $radicado->id,
                    'numero_radicado' => $radicado->numero_radicado,
                    'tipo' => $radicado->tipo,
                    'estado' => $radicado->estado,
                    'fecha_finalizacion' => $radicado->fecha_finalizacion
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al finalizar radicado', [
                'error' => $e->getMessage(),
                'numero_radicado' => $request->numero_radicado,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar el radicado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cargar formulario específico para el modal
     */
    public function cargarFormulario($tipo)
    {
        \Log::info('RadicacionController::cargarFormulario - Iniciando', [
            'tipo' => $tipo,
            'user_authenticated' => auth()->check(),
            'session_id' => session()->getId(),
            'user_id' => auth()->id()
        ]);

        // Verificación de autenticación
        if (!auth()->check()) {
            \Log::warning('RadicacionController::cargarFormulario - Usuario no autenticado');
            return response()->json(['error' => 'No autorizado'], 401);
        }

        try {
            $user = auth()->user();

            // Verificar permisos para radicación de salida
            if ($tipo === 'salida' && !$user->isAdmin()) {
                \Log::warning('RadicacionController::cargarFormulario - Sin permisos para salida', ['user_id' => $user->id]);
                return response()->json(['error' => 'No tienes permisos para radicar documentos de salida'], 403);
            }

            \Log::info('RadicacionController::cargarFormulario - Cargando datos', ['tipo' => $tipo]);

            // Obtener datos necesarios para los formularios
            $dependencias = \App\Models\Dependencia::activas()->orderBy('nombre')->get();
            $unidadesAdministrativas = \App\Models\UnidadAdministrativa::activas()->orderBy('codigo')->get();
            $ciudades = \App\Models\Ciudad::with('departamento')->activo()->ordenado()->get();
            $departamentos = \App\Models\Departamento::activo()->ordenado()->get();
            $tiposSolicitud = \App\Models\TipoSolicitud::activos()->ordenado()->get();

            \Log::info('RadicacionController::cargarFormulario - Datos cargados exitosamente', [
                'dependencias_count' => $dependencias->count(),
                'unidades_count' => $unidadesAdministrativas->count()
            ]);

            switch ($tipo) {
                case 'entrada':
                    return view('radicacion.forms.entrada', compact('dependencias', 'unidadesAdministrativas', 'ciudades', 'departamentos', 'tiposSolicitud'));

                case 'interno':
                    \Log::info('RadicacionController::cargarFormulario - Renderizando vista interno');
                    return view('radicacion.forms.interno', compact('dependencias', 'unidadesAdministrativas', 'tiposSolicitud'));

                case 'salida':
                    return view('radicacion.forms.salida', compact('dependencias', 'unidadesAdministrativas', 'ciudades', 'departamentos', 'tiposSolicitud'));

                default:
                    \Log::warning('RadicacionController::cargarFormulario - Tipo no válido', ['tipo' => $tipo]);
                    return response()->json(['error' => 'Tipo de formulario no válido'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('RadicacionController::cargarFormulario - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tipo' => $tipo
            ]);
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener detalles del radicado para modal (AJAX)
     */
    public function detalles($id)
    {
        try {
            $radicado = Radicado::with([
                'remitente',
                'dependenciaDestino',
                'dependenciaOrigen',
                'usuarioRadica',
                'usuarioResponde',
                'usuarioFinaliza',
                'documentos',
                'subserie.serie.unidadAdministrativa'
            ])->findOrFail($id);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $radicado->usuario_radica_id !== $user->id) {
                return response()->json([
                    'error' => 'No tiene permisos para ver este radicado'
                ], 403);
            }

            // Formatear datos para el modal
            $data = [
                'id' => $radicado->id,
                'numero_radicado' => $radicado->numero_radicado,
                'estado' => $radicado->estado,
                'fecha_radicado' => $radicado->fecha_radicado->format('d/m/Y'),
                'hora_radicado' => \Carbon\Carbon::parse($radicado->hora_radicado)->format('H:i:s'),
                'usuario_radica' => $radicado->usuarioRadica->name,
                'medio_recepcion' => $radicado->medio_recepcion,
                'tipo_comunicacion' => $radicado->tipo_comunicacion,
                'numero_folios' => $radicado->numero_folios,
                'tipo_anexo' => $radicado->tipo_anexo,
                'observaciones' => $radicado->observaciones,
                'medio_respuesta' => $radicado->medio_respuesta,
                'fecha_limite_respuesta' => $radicado->fecha_limite_respuesta ? $radicado->fecha_limite_respuesta->format('d/m/Y') : null,
                'esta_vencido' => $radicado->estaVencido(),
                'dias_restantes' => $radicado->dias_restantes,
                'dependencia_destino' => $radicado->dependenciaDestino->nombre_completo,
                'remitente' => [
                    'tipo' => $radicado->remitente->tipo,
                    'nombre_completo' => $radicado->remitente->nombre_completo,
                    'identificacion_completa' => $radicado->remitente->identificacion_completa,
                    'contacto_completo' => $radicado->remitente->contacto_completo,
                    'direccion' => $radicado->remitente->direccion,
                    'entidad' => $radicado->remitente->entidad,
                ],
                'trd' => [
                    'codigo' => ($radicado->subserie && $radicado->subserie->serie && $radicado->subserie->serie->unidadAdministrativa)
                        ? $radicado->subserie->serie->unidadAdministrativa->codigo . '.' . $radicado->subserie->serie->numero_serie . '.' . $radicado->subserie->numero_subserie
                        : 'N/A',
                    'serie' => $radicado->subserie->serie->nombre ?? 'N/A',
                    'subserie' => $radicado->subserie->nombre ?? 'N/A',
                    'descripcion' => $radicado->subserie->descripcion ?? 'Sin descripción',
                ],
                'documentos' => $radicado->documentos->map(function ($documento) {
                    return [
                        'nombre_archivo' => $documento->nombre_archivo,
                        'tipo_archivo' => $documento->tipo_archivo,
                        'tamaño_legible' => $documento->tamaño_legible,
                        'es_principal' => $documento->es_principal,
                        'url_descarga' => $documento->url_archivo,
                    ];
                })->toArray(),
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar los detalles del radicado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener datos del radicado para edición (AJAX)
     */
    public function editar($id)
    {
        try {
            $radicado = Radicado::with([
                'remitente',
                'dependenciaDestino',
                'dependenciaOrigen',
                'subserie.serie.unidadAdministrativa'
            ])->findOrFail($id);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $radicado->usuario_radica_id !== $user->id) {
                return response()->json([
                    'error' => 'No tiene permisos para editar este radicado'
                ], 403);
            }

            // Verificar si el radicado se puede editar según el rol del usuario
            if ($user->isVentanilla()) {
                // Los usuarios ventanilla solo pueden editar radicados pendientes y en proceso
                if (!in_array($radicado->estado, ['pendiente', 'en_proceso'])) {
                    $estadoTexto = match($radicado->estado) {
                        'respondido' => 'respondido',
                        'archivado' => 'archivado',
                        default => $radicado->estado
                    };

                    return response()->json([
                        'error' => "No se puede editar este radicado porque ya ha sido {$estadoTexto}. Solo los administradores pueden editar radicados finalizados."
                    ], 422);
                }
            }
            // Los administradores pueden editar cualquier radicado sin restricciones

            // Cargar datos necesarios para el formulario
            $dependencias = \App\Models\Dependencia::activas()->orderBy('nombre')->get();
            $unidadesAdministrativas = \App\Models\UnidadAdministrativa::with(['series.subseries'])
                ->activas()
                ->orderBy('codigo')
                ->get();
            $tiposSolicitud = \App\Models\TipoSolicitud::activos()->orderBy('nombre')->get();

            // Formatear datos del radicado para el formulario
            $data = [
                'radicado' => [
                    'id' => $radicado->id,
                    'numero_radicado' => $radicado->numero_radicado,
                    'tipo' => $radicado->tipo,
                    'medio_recepcion' => $radicado->medio_recepcion,
                    'tipo_comunicacion' => $radicado->tipo_comunicacion,
                    'numero_folios' => $radicado->numero_folios,
                    'numero_anexos' => $radicado->numero_anexos ?? 0,
                    'observaciones' => $radicado->observaciones,
                    'medio_respuesta' => $radicado->medio_respuesta,
                    'tipo_anexo' => $radicado->tipo_anexo,
                    'fecha_limite_respuesta' => $radicado->fecha_limite_respuesta ? $radicado->fecha_limite_respuesta->format('Y-m-d') : null,
                    'dependencia_destino_id' => $radicado->dependencia_destino_id,
                    'dependencia_origen_id' => $radicado->dependencia_origen_id,
                    'subserie_id' => $radicado->subserie_id,
                    'unidad_administrativa_id' => $radicado->subserie->serie->unidad_administrativa_id ?? null,
                    'serie_id' => $radicado->subserie->serie_id ?? null,
                ],
                'remitente' => [
                    'tipo' => $radicado->remitente->tipo,
                    'tipo_documento' => $radicado->remitente->tipo_documento,
                    'numero_documento' => $radicado->remitente->numero_documento,
                    'nombre_completo' => $radicado->remitente->nombre_completo,
                    'telefono' => $radicado->remitente->telefono,
                    'email' => $radicado->remitente->email,
                    'direccion' => $radicado->remitente->direccion,
                    'ciudad' => $radicado->remitente->ciudad,
                    'departamento' => $radicado->remitente->departamento,
                    'entidad' => $radicado->remitente->entidad,
                ],
                'dependencias' => $dependencias,
                'unidades_administrativas' => $unidadesAdministrativas,
                'tipos_solicitud' => $tiposSolicitud,
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar los datos del radicado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un radicado
     */
    public function actualizar(Request $request, $id)
    {
        try {
            $radicado = Radicado::with('remitente')->findOrFail($id);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $radicado->usuario_radica_id !== $user->id) {
                return response()->json([
                    'error' => 'No tiene permisos para editar este radicado'
                ], 403);
            }

            // Verificar si el radicado se puede editar según el rol del usuario
            if ($user->isVentanilla()) {
                // Los usuarios ventanilla solo pueden editar radicados pendientes y en proceso
                if (!in_array($radicado->estado, ['pendiente', 'en_proceso'])) {
                    $estadoTexto = match($radicado->estado) {
                        'respondido' => 'respondido',
                        'archivado' => 'archivado',
                        default => $radicado->estado
                    };

                    return response()->json([
                        'error' => "No se puede editar este radicado porque ya ha sido {$estadoTexto}. Solo los administradores pueden editar radicados finalizados."
                    ], 422);
                }
            }
            // Los administradores pueden editar cualquier radicado sin restricciones

            // Validar datos según el tipo de radicado
            $rules = $this->getValidationRules($radicado->tipo);
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            \DB::beginTransaction();

            // Actualizar datos del remitente
            $radicado->remitente->update([
                'tipo' => $request->tipo_remitente,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'departamento' => $request->departamento,
                'entidad' => $request->entidad,
            ]);

            // Actualizar datos del radicado
            $updateData = [
                'medio_recepcion' => $request->medio_recepcion,
                'tipo_comunicacion' => $request->tipo_comunicacion,
                'numero_folios' => $request->numero_folios,
                'numero_anexos' => $request->numero_anexos ?? 0,
                'observaciones' => $request->observaciones,
                'medio_respuesta' => $request->medio_respuesta,
                'tipo_anexo' => $request->tipo_anexo,
                'subserie_id' => $request->trd_id,
                'dependencia_destino_id' => $request->dependencia_destino_id,
            ];

            // Solo actualizar dependencia origen para radicados internos y de salida
            if (in_array($radicado->tipo, ['interno', 'salida'])) {
                $updateData['dependencia_origen_id'] = $request->dependencia_origen_id;
            }

            // Actualizar fecha límite si se proporciona
            if ($request->fecha_limite_respuesta) {
                $updateData['fecha_limite_respuesta'] = $request->fecha_limite_respuesta;
            }

            $radicado->update($updateData);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Radicado actualizado exitosamente',
                'radicado' => $radicado->fresh(['remitente', 'dependenciaDestino', 'subserie.serie.unidadAdministrativa'])
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'error' => 'Error al actualizar el radicado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener reglas de validación según el tipo de radicado
     */
    private function getValidationRules($tipo)
    {
        $baseRules = [
            // Datos del remitente
            'tipo_remitente' => 'required|in:anonimo,registrado',
            'tipo_documento' => 'required_if:tipo_remitente,registrado|in:CC,CE,TI,PP,NIT,OTRO',
            'numero_documento' => 'required_if:tipo_remitente,registrado|string|max:20',
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
            'numero_anexos' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
            'trd_id' => 'nullable|exists:subseries,id',
            'dependencia_destino_id' => 'required|exists:dependencias,id',
            'medio_respuesta' => 'required|in:fisico,email,telefono,presencial,no_requiere',
            'tipo_anexo' => 'required|in:original,copia,ninguno',
            'fecha_limite_respuesta' => 'nullable|date|after:today',
        ];

        // Agregar validaciones específicas según el tipo
        if (in_array($tipo, ['interno', 'salida'])) {
            $baseRules['dependencia_origen_id'] = 'required|exists:dependencias,id';
        }

        return $baseRules;
    }

    /**
     * Eliminar un radicado (solo administradores)
     */
    public function destroy($id)
    {
        try {
            $radicado = Radicado::findOrFail($id);

            // Verificar que el usuario sea administrador
            if (!auth()->user()->hasRole('admin')) {
                return response()->json([
                    'error' => 'No tiene permisos para eliminar radicados'
                ], 403);
            }

            // Eliminar documentos asociados si existen
            if ($radicado->documentos()->exists()) {
                foreach ($radicado->documentos as $documento) {
                    // Eliminar archivo físico si existe
                    if (Storage::exists($documento->ruta_archivo)) {
                        Storage::delete($documento->ruta_archivo);
                    }
                    $documento->delete();
                }
            }

            // Eliminar el radicado
            $numeroRadicado = $radicado->numero_radicado;
            $radicado->delete();

            return response()->json([
                'success' => true,
                'message' => "Radicado {$numeroRadicado} eliminado exitosamente"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el radicado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener documentos de un radicado
     */
    public function obtenerDocumentos($id)
    {
        try {
            $radicado = Radicado::with('documentos')->findOrFail($id);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $radicado->usuario_radica_id !== $user->id) {
                return response()->json([
                    'error' => 'No tiene permisos para ver los documentos de este radicado'
                ], 403);
            }

            $documentos = $radicado->documentos->map(function ($documento) {
                return [
                    'id' => $documento->id,
                    'nombre_archivo' => $documento->nombre_archivo,
                    'tipo_archivo' => $documento->tipo_archivo,
                    'tamaño_legible' => $documento->tamaño_legible,
                    'descripcion' => $documento->descripcion,
                    'es_principal' => $documento->es_principal,
                    'es_digitalizado' => $documento->es_digitalizado,
                    'url_archivo' => $documento->url_archivo,
                    'created_at' => $documento->created_at->format('d/m/Y H:i'),
                ];
            });

            return response()->json([
                'success' => true,
                'numero_radicado' => $radicado->numero_radicado,
                'documentos' => $documentos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar los documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir múltiples documentos a un radicado
     */
    public function subirDocumentos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'radicado_id' => 'required|exists:radicados,id',
            'documentos' => 'required|array|min:1|max:10', // Máximo 10 archivos por vez
            'documentos.*' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240', // 10MB máximo por archivo
            ]
        ], [
            'documentos.required' => 'Debe seleccionar al menos un documento',
            'documentos.*.mimes' => 'Solo se permiten archivos PDF, Word, JPG y PNG',
            'documentos.*.max' => 'Cada archivo no puede superar los 10MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $radicado = Radicado::findOrFail($request->radicado_id);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $radicado->usuario_radica_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para subir documentos a este radicado'
                ], 403);
            }

            DB::beginTransaction();

            $documentosSubidos = [];
            $errores = [];

            foreach ($request->file('documentos') as $index => $archivo) {
                try {
                    // Generar nombre único para el archivo
                    $nombreOriginal = $archivo->getClientOriginalName();
                    $extension = $archivo->getClientOriginalExtension();
                    $nombreArchivo = $radicado->numero_radicado . '_adicional_' . time() . '_' . $index . '.' . $extension;

                    // Determinar directorio según el tipo de radicado
                    $directorio = match($radicado->tipo) {
                        'entrada' => 'documentos/entrada',
                        'interno' => 'documentos/interno',
                        'salida' => 'documentos/salida',
                        default => 'documentos/otros'
                    };

                    // Guardar archivo
                    $rutaArchivo = $archivo->storeAs($directorio, $nombreArchivo, 'public');

                    // Calcular hash para integridad
                    $contenido = file_get_contents($archivo->getPathname());
                    $hashArchivo = hash('sha256', $contenido);

                    // Crear registro de documento
                    $documento = Documento::create([
                        'radicado_id' => $radicado->id,
                        'nombre_archivo' => $nombreOriginal,
                        'ruta_archivo' => $rutaArchivo,
                        'tipo_mime' => $archivo->getMimeType(),
                        'tamaño_archivo' => $archivo->getSize(),
                        'hash_archivo' => $hashArchivo,
                        'descripcion' => 'Documento adicional subido posteriormente',
                        'es_principal' => false,
                        'es_digitalizado' => false,
                    ]);

                    $documentosSubidos[] = [
                        'nombre' => $nombreOriginal,
                        'tamaño' => $documento->tamaño_legible,
                        'id' => $documento->id
                    ];

                } catch (\Exception $e) {
                    $errores[] = "Error al subir {$nombreOriginal}: " . $e->getMessage();
                    Log::error('Error al subir documento individual', [
                        'archivo' => $nombreOriginal,
                        'radicado_id' => $radicado->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if (empty($documentosSubidos) && !empty($errores)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo subir ningún documento',
                    'errors' => $errores
                ], 500);
            }

            DB::commit();

            $mensaje = count($documentosSubidos) . ' documento(s) subido(s) exitosamente';
            if (!empty($errores)) {
                $mensaje .= '. Algunos archivos presentaron errores.';
            }

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'documentos_subidos' => $documentosSubidos,
                'errores' => $errores
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al subir documentos', [
                'radicado_id' => $request->radicado_id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al subir documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de un documento específico
     */
    public function obtenerDocumento($documentoId)
    {
        try {
            $documento = Documento::with('radicado')->findOrFail($documentoId);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $documento->radicado->usuario_radica_id !== $user->id) {
                return response()->json([
                    'error' => 'No tiene permisos para ver este documento'
                ], 403);
            }

            // Verificar que el archivo existe
            if (!$documento->archivoExiste()) {
                Log::error('Archivo no encontrado', [
                    'documento_id' => $documentoId,
                    'ruta_archivo' => $documento->ruta_archivo,
                    'archivo_existe' => $documento->archivoExiste()
                ]);

                return response()->json([
                    'error' => 'El archivo no se encuentra disponible'
                ], 404);
            }

            Log::info('Documento obtenido exitosamente', [
                'documento_id' => $documentoId,
                'nombre_archivo' => $documento->nombre_archivo,
                'url_archivo' => $documento->url_archivo,
                'ruta_archivo' => $documento->ruta_archivo
            ]);

            return response()->json([
                'success' => true,
                'id' => $documento->id,
                'nombre_archivo' => $documento->nombre_archivo,
                'tipo_archivo' => $documento->tipo_archivo,
                'tipo_mime' => $documento->tipo_mime,
                'tamaño_legible' => $documento->tamaño_legible,
                'descripcion' => $documento->descripcion,
                'es_principal' => $documento->es_principal,
                'url_archivo' => $documento->url_archivo,
                'radicado_numero' => $documento->radicado->numero_radicado,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener documento', [
                'documento_id' => $documentoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al cargar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar un documento específico con headers apropiados
     */
    public function descargarDocumento($documentoId)
    {
        try {
            $documento = Documento::with('radicado')->findOrFail($documentoId);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $documento->radicado->usuario_radica_id !== $user->id) {
                abort(403, 'No tiene permisos para descargar este documento');
            }

            // Verificar que el archivo existe
            if (!$documento->archivoExiste()) {
                Log::error('Archivo no encontrado para descarga', [
                    'documento_id' => $documentoId,
                    'ruta_archivo' => $documento->ruta_archivo,
                    'archivo_existe' => $documento->archivoExiste()
                ]);

                abort(404, 'El archivo no se encuentra disponible');
            }

            // Obtener la ruta completa del archivo
            $rutaCompleta = Storage::disk('public')->path($documento->ruta_archivo);

            Log::info('Descarga de documento iniciada', [
                'documento_id' => $documentoId,
                'nombre_archivo' => $documento->nombre_archivo,
                'usuario_id' => $user->id,
                'ruta_archivo' => $documento->ruta_archivo
            ]);

            // Retornar el archivo con headers apropiados para forzar descarga
            return response()->download($rutaCompleta, $documento->nombre_archivo, [
                'Content-Type' => $documento->tipo_mime,
                'Content-Disposition' => 'attachment; filename="' . $documento->nombre_archivo . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al descargar documento', [
                'documento_id' => $documentoId,
                'error' => $e->getMessage(),
                'usuario_id' => auth()->id()
            ]);

            abort(500, 'Error al descargar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Ver un documento específico en el navegador (para iframe y nueva pestaña)
     */
    public function verDocumento($documentoId)
    {
        try {
            Log::info('Iniciando visualización de documento', [
                'documento_id' => $documentoId,
                'authenticated' => auth()->check(),
                'user_id' => auth()->id()
            ]);

            // Verificar autenticación
            if (!auth()->check()) {
                Log::warning('Usuario no autenticado intentando ver documento', [
                    'documento_id' => $documentoId,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                return response('Usuario no autenticado', 401);
            }

            $documento = Documento::with('radicado')->findOrFail($documentoId);
            Log::info('Documento encontrado', [
                'documento_id' => $documentoId,
                'nombre_archivo' => $documento->nombre_archivo,
                'ruta_archivo' => $documento->ruta_archivo
            ]);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $documento->radicado->usuario_radica_id !== $user->id) {
                Log::warning('Usuario sin permisos intentando ver documento', [
                    'documento_id' => $documentoId,
                    'usuario_id' => $user->id,
                    'radicado_usuario_id' => $documento->radicado->usuario_radica_id
                ]);
                return response('No tiene permisos para ver este documento', 403);
            }

            // Verificar que el archivo existe
            if (!$documento->archivoExiste()) {
                Log::error('Archivo no encontrado para visualización', [
                    'documento_id' => $documentoId,
                    'ruta_archivo' => $documento->ruta_archivo,
                    'archivo_existe' => $documento->archivoExiste()
                ]);

                return response('El archivo no se encuentra disponible', 404);
            }

            // Obtener la ruta completa del archivo
            $rutaCompleta = Storage::disk('public')->path($documento->ruta_archivo);
            Log::info('Ruta completa del archivo', [
                'ruta_completa' => $rutaCompleta,
                'existe_archivo' => file_exists($rutaCompleta)
            ]);

            if (!file_exists($rutaCompleta)) {
                Log::error('Archivo físico no existe', [
                    'ruta_completa' => $rutaCompleta
                ]);
                return response('Archivo físico no encontrado', 404);
            }

            Log::info('Visualización de documento exitosa', [
                'documento_id' => $documentoId,
                'nombre_archivo' => $documento->nombre_archivo,
                'usuario_id' => $user->id
            ]);

            // Retornar el archivo con headers apropiados para visualización
            return response()->file($rutaCompleta, [
                'Content-Type' => $documento->tipo_mime,
                'Content-Disposition' => 'inline; filename="' . $documento->nombre_archivo . '"',
                'Cache-Control' => 'public, max-age=3600',
                'X-Frame-Options' => 'SAMEORIGIN'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al visualizar documento', [
                'documento_id' => $documentoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => auth()->id()
            ]);

            return response('Error interno del servidor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generar token temporal para visualización de documento
     */
    public function generarTokenVisualizacion($documentoId)
    {
        try {
            // Verificar autenticación
            if (!auth()->check()) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $documento = Documento::with('radicado')->findOrFail($documentoId);

            // Verificar permisos de acceso
            $user = auth()->user();
            if ($user->isVentanilla() && $documento->radicado->usuario_radica_id !== $user->id) {
                return response()->json(['error' => 'No tiene permisos para ver este documento'], 403);
            }

            // Verificar que el archivo existe
            if (!$documento->archivoExiste()) {
                return response()->json(['error' => 'El archivo no se encuentra disponible'], 404);
            }

            // Generar token temporal (válido por 1 hora)
            $token = base64_encode(json_encode([
                'documento_id' => $documentoId,
                'usuario_id' => $user->id,
                'expires_at' => now()->addHour()->timestamp,
                'hash' => hash('sha256', $documentoId . $user->id . config('app.key'))
            ]));

            Log::info('Token de visualización generado', [
                'documento_id' => $documentoId,
                'usuario_id' => $user->id,
                'token' => substr($token, 0, 20) . '...'
            ]);

            return response()->json([
                'success' => true,
                'token' => $token,
                'url' => route('documento.ver-con-token', $token)
            ]);

        } catch (\Exception $e) {
            Log::error('Error al generar token de visualización', [
                'documento_id' => $documentoId,
                'error' => $e->getMessage(),
                'usuario_id' => auth()->id()
            ]);

            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Ver documento con token temporal
     */
    public function verDocumentoConToken($token)
    {
        try {
            // Decodificar token
            $tokenData = json_decode(base64_decode($token), true);

            if (!$tokenData || !isset($tokenData['documento_id'], $tokenData['usuario_id'], $tokenData['expires_at'], $tokenData['hash'])) {
                Log::warning('Token de visualización inválido', ['token' => substr($token, 0, 20) . '...']);
                abort(403, 'Token inválido');
            }

            // Verificar expiración
            if ($tokenData['expires_at'] < now()->timestamp) {
                Log::warning('Token de visualización expirado', [
                    'documento_id' => $tokenData['documento_id'],
                    'expires_at' => $tokenData['expires_at']
                ]);
                abort(403, 'Token expirado');
            }

            // Verificar hash
            $expectedHash = hash('sha256', $tokenData['documento_id'] . $tokenData['usuario_id'] . config('app.key'));
            if ($tokenData['hash'] !== $expectedHash) {
                Log::warning('Token de visualización con hash inválido', [
                    'documento_id' => $tokenData['documento_id']
                ]);
                abort(403, 'Token inválido');
            }

            $documento = Documento::findOrFail($tokenData['documento_id']);

            // Verificar que el archivo existe
            if (!$documento->archivoExiste()) {
                Log::error('Archivo no encontrado para token', [
                    'documento_id' => $tokenData['documento_id'],
                    'ruta_archivo' => $documento->ruta_archivo
                ]);
                abort(404, 'El archivo no se encuentra disponible');
            }

            // Obtener la ruta completa del archivo
            $rutaCompleta = Storage::disk('public')->path($documento->ruta_archivo);

            Log::info('Visualización con token exitosa', [
                'documento_id' => $tokenData['documento_id'],
                'usuario_id' => $tokenData['usuario_id'],
                'nombre_archivo' => $documento->nombre_archivo
            ]);

            // Retornar el archivo con headers apropiados para visualización
            return response()->file($rutaCompleta, [
                'Content-Type' => $documento->tipo_mime,
                'Content-Disposition' => 'inline; filename="' . $documento->nombre_archivo . '"',
                'Cache-Control' => 'public, max-age=3600',
                'X-Frame-Options' => 'SAMEORIGIN'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al visualizar documento con token', [
                'token' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage()
            ]);

            abort(500, 'Error al visualizar el documento');
        }
    }
}
