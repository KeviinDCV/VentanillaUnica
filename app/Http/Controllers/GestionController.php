<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnidadAdministrativa;
use App\Models\Serie;
use App\Models\Subserie;

class GestionController extends Controller
{
    /**
     * Mostrar la vista principal de gestión
     */
    public function index(Request $request)
    {
        // Verificación manual de autenticación
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Por favor, inicia sesión para acceder a esta página.');
        }

        // Verificar que sea administrador
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Estadísticas del sistema de gestión
        $estadisticas = [
            'unidades_administrativas' => UnidadAdministrativa::count(),
            'series' => Serie::count(),
            'subseries' => Subserie::count(),
        ];

        $view = view('gestion.index', compact('estadisticas'));

        // Si es una petición AJAX/SPA, devolver solo el contenido
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $view;
        }

        return $view;
    }
}
