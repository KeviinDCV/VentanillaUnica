<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radicado;
use App\Models\Dependencia;
use App\Models\Trd;
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
        $trds = Trd::activos()->orderBy('codigo')->get();

        // Inicializar query para consulta
        $query = Radicado::with(['remitente', 'trd', 'dependenciaDestino', 'usuarioRadica', 'documentos']);

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

        // Radicados recientes (últimos 10) - query separada
        $radicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'usuarioRadica'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        return view('radicacion.index', compact(
            'radicadosRecientes',
            'radicadosConsulta',
            'dependencias',
            'trds',
            'filtros',
            'estadisticas'
        ));
    }

    /**
     * Aplicar filtros basados en el rol del usuario
     */
    private function aplicarFiltrosPorRol($query, $user)
    {
        // Los usuarios de ventanilla solo pueden ver sus propios radicados
        if ($user->isVentanilla()) {
            $query->where('usuario_radica_id', $user->id);
        }

        // Los administradores pueden ver todos los radicados
        // No se aplica ningún filtro adicional para administradores
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

        // Filtro por TRD
        if ($request->filled('trd_id')) {
            $query->where('trd_id', $request->trd_id);
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
}
