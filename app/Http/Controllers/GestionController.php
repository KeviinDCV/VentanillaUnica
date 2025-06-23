<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dependencia;
use App\Models\Trd;
use App\Models\Radicado;
use Carbon\Carbon;

class GestionController extends Controller
{
    /**
     * Mostrar la vista principal de gestión
     */
    public function index()
    {
        // Verificación manual de autenticación
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Por favor, inicia sesión para acceder a esta página.');
        }

        // Verificar que sea administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Estadísticas del sistema
        $estadisticas = [
            'usuarios' => User::count(),
            'dependencias' => Dependencia::count(),
            'trds' => Trd::count(),
            'radicados' => Radicado::count(),
            'usuarios_activos' => User::where('active', true)->count(),
            'radicados_hoy' => Radicado::whereDate('fecha_radicado', Carbon::today())->count(),
        ];

        return view('gestion.index', compact('estadisticas'));
    }
}
