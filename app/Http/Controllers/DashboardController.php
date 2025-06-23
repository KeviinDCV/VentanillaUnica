<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radicado;
use App\Models\User;
use App\Models\Dependencia;
use App\Models\Trd;
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

        // Estadísticas personales del usuario
        $estadisticasPersonales = [
            'mis_radicados' => Radicado::where('usuario_radica_id', $user->id)->count(),
            'mis_radicados_mes' => Radicado::where('usuario_radica_id', $user->id)
                                          ->whereMonth('fecha_radicado', Carbon::now()->month)
                                          ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'mis_pendientes' => Radicado::where('usuario_radica_id', $user->id)
                                       ->where('estado', 'pendiente')->count(),
        ];

        // Radicados recientes del usuario
        $misRadicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'trd'])
                                        ->where('usuario_radica_id', $user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();

        // Radicados recientes del sistema (últimos 10) - solo para admin
        $radicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'usuarioRadica'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        // Actividad del día
        $actividadHoy = [
            'radicados_creados' => Radicado::whereDate('created_at', Carbon::today())->count(),
            'radicados_respondidos' => Radicado::whereDate('fecha_respuesta', Carbon::today())->count(),
            'usuarios_activos' => User::whereDate('updated_at', Carbon::today())->count(),
        ];

        // Accesos rápidos para administradores
        $accesosRapidos = $this->getAccesosRapidos($user);

        $view = view('dashboard', compact(
            'user',
            'estadisticasGenerales',
            'estadisticasTipo',
            'estadisticasPersonales',
            'misRadicadosRecientes',
            'radicadosRecientes',
            'actividadHoy',
            'accesosRapidos'
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
        // Estadísticas personales del usuario (solo sus propios datos)
        $estadisticasPersonales = [
            'mis_radicados' => Radicado::where('usuario_radica_id', $user->id)->count(),
            'mis_radicados_mes' => Radicado::where('usuario_radica_id', $user->id)
                                          ->whereMonth('fecha_radicado', Carbon::now()->month)
                                          ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'mis_pendientes' => Radicado::where('usuario_radica_id', $user->id)
                                       ->where('estado', 'pendiente')->count(),
        ];

        // Solo radicados del usuario actual
        $misRadicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'trd'])
                                        ->where('usuario_radica_id', $user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();

        // Actividad del día (solo datos básicos)
        $actividadHoy = [
            'radicados_creados' => Radicado::whereDate('created_at', Carbon::today())->count(),
            'radicados_respondidos' => Radicado::whereDate('fecha_respuesta', Carbon::today())->count(),
            'usuarios_activos' => User::whereDate('updated_at', Carbon::today())->count(),
        ];

        // Accesos rápidos limitados para ventanilla
        $accesosRapidos = $this->getAccesosRapidos($user);

        $view = view('dashboard-ventanilla', compact(
            'user',
            'estadisticasPersonales',
            'misRadicadosRecientes',
            'actividadHoy',
            'accesosRapidos'
        ));

        // Si es una petición AJAX/SPA, devolver solo el contenido
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $view;
        }

        return $view;
    }

    /**
     * Get quick access links based on user role
     */
    private function getAccesosRapidos($user)
    {
        // Accesos básicos para todos los usuarios
        $accesos = [
            [
                'titulo' => 'Radicación de Entrada',
                'descripcion' => 'Radicar documentos externos',
                'url' => route('radicacion.entrada.index'),
                'icono' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'color' => 'blue'
            ],
            [
                'titulo' => 'Radicación Interna',
                'descripcion' => 'Comunicación entre dependencias',
                'url' => route('radicacion.interna.index'),
                'icono' => 'M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v2',
                'color' => 'green'
            ],
            [
                'titulo' => 'Consultar Radicados',
                'descripcion' => 'Buscar y consultar documentos',
                'url' => route('radicacion.index'),
                'icono' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                'color' => 'yellow'
            ]
        ];

        // Accesos específicos según el rol
        if ($user->isAdmin()) {
            // Administradores tienen acceso completo
            $accesos[] = [
                'titulo' => 'Radicación de Salida',
                'descripcion' => 'Documentos hacia entidades externas',
                'url' => route('radicacion.salida.index'),
                'icono' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8',
                'color' => 'purple'
            ];

            $accesos[] = [
                'titulo' => 'Panel de Administración',
                'descripcion' => 'Gestión completa del sistema',
                'url' => route('admin.index'),
                'icono' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'color' => 'red'
            ];
        } else {
            // Usuarios de ventanilla tienen acceso limitado
            // Solo accesos básicos de radicación
        }

        return $accesos;
    }
}
