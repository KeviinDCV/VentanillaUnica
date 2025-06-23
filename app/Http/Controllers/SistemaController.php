<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SuspenderSistema;
use App\Models\User;
use App\Models\Radicado;
use Carbon\Carbon;

class SistemaController extends Controller
{
    /**
     * Mostrar la vista principal de sistema
     */
    public function index()
    {
        // Verificación manual de autenticación
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Por favor, inicia sesión para acceder a esta página.');
        }

        // Estadísticas del sistema para el usuario actual
        $estadisticas = [
            'radicados_usuario_hoy' => Radicado::where('usuario_radica_id', auth()->id())
                                              ->whereDate('fecha_radicado', Carbon::today())
                                              ->count(),
            'total_usuarios' => User::count(),
            'total_radicados' => Radicado::count(),
        ];

        return view('sistema.index', compact('estadisticas'));
    }

    /**
     * Mostrar página de sistema suspendido
     */
    public function suspendido()
    {
        $estado = SuspenderSistema::estadoSuspension();

        if (!$estado['suspendido']) {
            return redirect()->route('dashboard');
        }

        return view('sistema.suspendido', compact('estado'));
    }

    /**
     * Mostrar formulario de reactivación
     */
    public function reactivar()
    {
        $estado = SuspenderSistema::estadoSuspension();

        if (!$estado['suspendido']) {
            return redirect()->route('dashboard');
        }

        return view('sistema.reactivar', compact('estado'));
    }

    /**
     * Procesar reactivación del sistema
     */
    public function procesarReactivacion(Request $request)
    {
        $estado = SuspenderSistema::estadoSuspension();

        if (!$estado['suspendido']) {
            return redirect()->route('dashboard');
        }

        $validator = Validator::make($request->all(), [
            'password' => $estado['requiere_password'] ? 'required|string' : 'nullable|string'
        ], [
            'password.required' => 'La contraseña es obligatoria para reactivar el sistema'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $resultado = SuspenderSistema::reactivar($request->password);

        if ($resultado['success']) {
            return redirect()->route('dashboard')->with('success', $resultado['mensaje']);
        } else {
            return back()->withErrors(['password' => $resultado['mensaje']]);
        }
    }

    /**
     * Suspender el sistema (solo para administradores)
     */
    public function suspender(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'minutos' => 'required|integer|min:1|max:1440', // Máximo 24 horas
            'password' => 'nullable|string|min:4|max:50',
            'motivo' => 'nullable|string|max:255'
        ], [
            'minutos.required' => 'Debe especificar los minutos de suspensión',
            'minutos.integer' => 'Los minutos deben ser un número entero',
            'minutos.min' => 'La suspensión debe ser de al menos 1 minuto',
            'minutos.max' => 'La suspensión no puede superar las 24 horas (1440 minutos)',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres',
            'password.max' => 'La contraseña no puede superar los 50 caracteres'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $resultado = SuspenderSistema::suspender(
            $request->minutos,
            $request->password
        );

        // Log de la suspensión
        \Log::info('Sistema suspendido', [
            'usuario' => auth()->user()->name,
            'minutos' => $request->minutos,
            'motivo' => $request->motivo,
            'tiempo_reactivacion' => $resultado['tiempo_reactivacion'],
            'requiere_password' => !empty($request->password)
        ]);

        return redirect()->route('sistema.suspendido')->with('success', $resultado['mensaje']);
    }

    /**
     * API para obtener estado del sistema
     */
    public function estado()
    {
        $estado = SuspenderSistema::estadoSuspension();

        return response()->json($estado);
    }

    /**
     * Mostrar formulario de suspensión (solo para administradores)
     */
    public function mostrarSuspension()
    {
        return view('admin.sistema.suspender');
    }
}
