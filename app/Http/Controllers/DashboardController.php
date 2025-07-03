<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radicado;
use App\Models\User;
use App\Models\Dependencia;

use App\Models\Documento;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard based on user role.
     */
    public function index(Request $request)
    {
        // Verificación manual de autenticación
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Por favor, inicia sesión para acceder al dashboard.');
        }

        $user = auth()->user();

        // Redirigir según el rol del usuario
        if ($user->isAdmin()) {
            return $this->adminDashboard($user, $request);
        } else {
            return $this->ventanillaDashboard($user, $request);
        }
    }

    /**
     * Dashboard para administradores con acceso completo
     */
    private function adminDashboard($user, $request)
    {
        // Estadísticas generales del sistema (solo para administradores)
        $estadisticasGenerales = [
            'total_radicados' => Radicado::count(),
            'radicados_hoy' => Radicado::whereDate('fecha_radicado', Carbon::today())->count(),
            'radicados_mes' => Radicado::whereMonth('fecha_radicado', Carbon::now()->month)
                                     ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'pendientes' => Radicado::where('estado', 'pendiente')->count(),
        ];

        // Estadísticas por tipo de radicado
        $estadisticasTipo = [
            'entrada' => Radicado::where('tipo', 'entrada')->count(),
            'interno' => Radicado::where('tipo', 'interno')->count(),
            'salida' => Radicado::where('tipo', 'salida')->count(),
        ];

        // Estadísticas de radicados (todos los radicados del sistema)
        $estadisticasPersonales = [
            'mis_radicados' => Radicado::count(),
            'mis_radicados_mes' => Radicado::whereMonth('fecha_radicado', Carbon::now()->month)
                                          ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'mis_pendientes' => Radicado::where('estado', 'pendiente')->count(),
        ];

        // Radicados recientes del sistema
        $misRadicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'subserie.serie.unidadAdministrativa'])
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();

        // Radicados recientes del sistema (últimos 10) - solo para admin
        $radicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'usuarioRadica', 'subserie.serie.unidadAdministrativa'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        // Actividad del día
        $actividadHoy = [
            'radicados_creados' => Radicado::whereDate('created_at', Carbon::today())->count(),
            'radicados_respondidos' => Radicado::whereDate('fecha_respuesta', Carbon::today())->count(),
            'usuarios_activos' => User::whereDate('updated_at', Carbon::today())->count(),
        ];

        $view = view('dashboard', compact(
            'user',
            'estadisticasGenerales',
            'estadisticasTipo',
            'estadisticasPersonales',
            'misRadicadosRecientes',
            'radicadosRecientes',
            'actividadHoy'
        ));

        // Si es una petición AJAX/SPA, devolver solo el contenido
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $view;
        }

        return $view;
    }

    /**
     * Dashboard para usuarios de ventanilla con acceso limitado
     */
    private function ventanillaDashboard($user, $request)
    {
        // Estadísticas de radicados (todos los radicados del sistema)
        $estadisticasPersonales = [
            'mis_radicados' => Radicado::count(),
            'mis_radicados_mes' => Radicado::whereMonth('fecha_radicado', Carbon::now()->month)
                                          ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'mis_pendientes' => Radicado::where('estado', 'pendiente')->count(),
        ];

        // Radicados recientes del sistema
        $misRadicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'subserie.serie.unidadAdministrativa'])
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();

        // Actividad del día (solo datos básicos)
        $actividadHoy = [
            'radicados_creados' => Radicado::whereDate('created_at', Carbon::today())->count(),
            'radicados_respondidos' => Radicado::whereDate('fecha_respuesta', Carbon::today())->count(),
            'usuarios_activos' => User::whereDate('updated_at', Carbon::today())->count(),
        ];

        $view = view('dashboard-ventanilla', compact(
            'user',
            'estadisticasPersonales',
            'misRadicadosRecientes',
            'actividadHoy'
        ));

        // Si es una petición AJAX/SPA, devolver solo el contenido
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $view;
        }

        return $view;
    }

    /**
     * Contar radicados aplicando la misma lógica de filtros por rol que RadicacionController
     */
    private function contarRadicadosPorRol($user, $tipo = 'total')
    {
        $query = Radicado::query();

        // Aplicar filtros por rol (misma lógica que RadicacionController)
        if ($user->isVentanilla()) {
            $query->where('usuario_radica_id', $user->id);
        }
        // Los administradores ven todos los radicados (sin filtro adicional)

        // Aplicar filtros adicionales según el tipo
        switch ($tipo) {
            case 'mes':
                $query->whereMonth('fecha_radicado', Carbon::now()->month)
                      ->whereYear('fecha_radicado', Carbon::now()->year);
                break;
            case 'pendientes':
                $query->where('estado', 'pendiente');
                break;
            case 'total':
            default:
                // Sin filtros adicionales
                break;
        }

        return $query->count();
    }
}
