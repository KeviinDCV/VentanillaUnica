<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Radicado;
use App\Models\Dependencia;

use App\Models\Remitente;
use App\Models\Documento;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\TipoSolicitud;
use App\Models\UserLoginActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Dashboard principal de administración
     */
    public function index(Request $request)
    {
        // Estadísticas generales del sistema
        $estadisticas = [
            'total_radicados' => Radicado::count(),
            'radicados_hoy' => Radicado::whereDate('fecha_radicado', Carbon::today())->count(),
            'radicados_mes' => Radicado::whereMonth('fecha_radicado', Carbon::now()->month)
                                     ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'total_usuarios' => User::count(),
            'total_dependencias' => Dependencia::count(),
            'total_comunicaciones' => \App\Models\Comunicacion::count(),
            'total_departamentos' => Departamento::count(),
            'total_ciudades' => Ciudad::count(),
            'total_tipos_solicitud' => TipoSolicitud::count(),
            'total_remitentes' => Remitente::count(),
            'total_documentos' => Documento::count(),
            'pendientes' => Radicado::where('estado', 'pendiente')->count(),
            'vencidos' => Radicado::whereNotNull('fecha_limite_respuesta')
                                 ->where('fecha_limite_respuesta', '<', Carbon::now())
                                 ->where('estado', '!=', 'respondido')->count(),
        ];

        // Estadísticas por tipo de radicado
        $estadisticasTipo = [
            'entrada' => Radicado::where('tipo', 'entrada')->count(),
            'interno' => Radicado::where('tipo', 'interno')->count(),
            'salida' => Radicado::where('tipo', 'salida')->count(),
        ];

        // Estadísticas por estado
        $estadisticasEstado = [
            'pendiente' => Radicado::where('estado', 'pendiente')->count(),
            'en_proceso' => Radicado::where('estado', 'en_proceso')->count(),
            'respondido' => Radicado::where('estado', 'respondido')->count(),
            'archivado' => Radicado::where('estado', 'archivado')->count(),
        ];

        // Radicados recientes
        $radicadosRecientes = Radicado::with(['remitente', 'dependenciaDestino', 'usuarioRadica'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();

        // Usuarios más activos
        $usuariosActivos = User::withCount(['radicados' => function($query) {
                                    $query->whereMonth('fecha_radicado', Carbon::now()->month);
                                }])
                              ->orderBy('radicados_count', 'desc')
                              ->limit(5)
                              ->get();

        // Dependencias más activas
        $dependenciasActivas = Dependencia::withCount(['radicadosDestino' => function($query) {
                                              $query->whereMonth('fecha_radicado', Carbon::now()->month);
                                          }])
                                         ->orderBy('radicados_destino_count', 'desc')
                                         ->limit(5)
                                         ->get();

        // Gráfico de radicados por día (últimos 30 días)
        $radicadosPorDia = [];
        for ($i = 29; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $radicadosPorDia[] = [
                'fecha' => $fecha->format('d/m'),
                'cantidad' => Radicado::whereDate('fecha_radicado', $fecha)->count()
            ];
        }

        // Actividad reciente del sistema
        $actividadReciente = $this->obtenerActividadReciente();

        $view = view('admin.index', compact(
            'estadisticas',
            'estadisticasTipo',
            'estadisticasEstado',
            'radicadosRecientes',
            'usuariosActivos',
            'dependenciasActivas',
            'radicadosPorDia',
            'actividadReciente'
        ));

        // Si es una petición AJAX/SPA, devolver solo el contenido
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $view;
        }

        return $view;
    }

    /**
     * Gestión de usuarios
     */
    public function usuarios()
    {
        $usuarios = User::withCount('radicados')
                       ->orderBy('name')
                       ->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Buscar usuarios para búsqueda en tiempo real
     */
    public function buscarUsuarios(Request $request)
    {
        $termino = $request->get('termino', '');

        $usuarios = User::withCount('radicados')
                       ->when($termino, function ($query, $termino) {
                           return $query->where(function ($q) use ($termino) {
                               $q->where('name', 'LIKE', "%{$termino}%")
                                 ->orWhere('email', 'LIKE', "%{$termino}%")
                                 ->orWhere('role', 'LIKE', "%{$termino}%");
                           });
                       })
                       ->orderBy('name')
                       ->get();

        return response()->json([
            'usuarios' => $usuarios->map(function ($usuario) {
                return [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'role' => $usuario->role,
                    'role_display' => $usuario->getRoleDisplayName(),
                    'active' => $usuario->active,
                    'radicados_count' => $usuario->radicados_count,
                    'created_at' => $usuario->created_at->format('d/m/Y'),
                    'can_delete' => $usuario->id !== auth()->id() && $usuario->radicados_count === 0,
                    'initials' => strtoupper(substr($usuario->name, 0, 2))
                ];
            })
        ]);
    }

    /**
     * Gestión de dependencias
     */
    public function dependencias()
    {
        $dependencias = Dependencia::withCount(['radicadosDestino', 'radicadosOrigen'])
                                  ->orderBy('nombre')
                                  ->paginate(15);

        return view('admin.dependencias.index', compact('dependencias'));
    }

    /**
     * Buscar dependencias
     */
    public function buscarDependencias(Request $request)
    {
        $termino = $request->get('termino');
        $estado = $request->get('estado');

        $query = Dependencia::withCount(['radicadosDestino', 'radicadosOrigen']);

        // Aplicar filtro de búsqueda por texto si existe
        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('nombre', 'LIKE', "%{$termino}%")
                  ->orWhere('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('sigla', 'LIKE', "%{$termino}%")
                  ->orWhere('responsable', 'LIKE', "%{$termino}%");
            });
        }

        // Aplicar filtro de estado si existe
        if ($estado) {
            if ($estado === 'activa') {
                $query->where('activa', true);
            } elseif ($estado === 'inactiva') {
                $query->where('activa', false);
            }
        }

        $dependencias = $query->orderBy('nombre')->get();

        return response()->json([
            'dependencias' => $dependencias->map(function($dependencia) {
                return [
                    'id' => $dependencia->id,
                    'codigo' => $dependencia->codigo,
                    'nombre' => $dependencia->nombre,
                    'sigla' => $dependencia->sigla,
                    'descripcion' => $dependencia->descripcion,
                    'responsable' => $dependencia->responsable,
                    'telefono' => $dependencia->telefono,
                    'email' => $dependencia->email,
                    'activa' => $dependencia->activa,
                    'radicados_destino_count' => $dependencia->radicados_destino_count,
                    'radicados_origen_count' => $dependencia->radicados_origen_count,
                    'created_at' => $dependencia->created_at->format('d/m/Y'),
                ];
            })
        ]);
    }

    /**
     * Crear nueva dependencia
     */
    public function guardarDependencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:10|unique:dependencias,codigo',
            'nombre' => 'required|string|max:255',
            'sigla' => 'nullable|string|max:10',
            'descripcion' => 'nullable|string|max:500',
            'responsable' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dependencia = Dependencia::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Dependencia creada exitosamente',
            'dependencia' => $dependencia
        ]);
    }

    /**
     * Actualizar dependencia
     */
    public function actualizarDependencia(Request $request, $id)
    {
        $dependencia = Dependencia::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:10|unique:dependencias,codigo,' . $id,
            'nombre' => 'required|string|max:255',
            'sigla' => 'nullable|string|max:10',
            'descripcion' => 'nullable|string|max:500',
            'responsable' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dependencia->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Dependencia actualizada exitosamente',
            'dependencia' => $dependencia
        ]);
    }

    /**
     * Cambiar estado de dependencia
     */
    public function toggleDependenciaStatus($id)
    {
        $dependencia = Dependencia::findOrFail($id);
        $dependencia->activa = !$dependencia->activa;
        $dependencia->save();

        return response()->json([
            'success' => true,
            'message' => $dependencia->activa ? 'Dependencia activada exitosamente' : 'Dependencia desactivada exitosamente',
            'activa' => $dependencia->activa
        ]);
    }

    /**
     * Eliminar dependencia
     */
    public function eliminarDependencia($id)
    {
        $dependencia = Dependencia::findOrFail($id);

        // Verificar si la dependencia tiene radicados asociados
        $radicadosCount = $dependencia->radicadosDestino()->count() + $dependencia->radicadosOrigen()->count();

        if ($radicadosCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar la dependencia '{$dependencia->nombre}' porque tiene {$radicadosCount} radicado(s) asociado(s). Desactívela en su lugar."
            ], 422);
        }

        $nombreDependencia = $dependencia->nombre;
        $dependencia->delete();

        return response()->json([
            'success' => true,
            'message' => "Dependencia '{$nombreDependencia}' eliminada exitosamente"
        ]);
    }



    /**
     * Reportes avanzados
     */
    public function reportes()
    {
        // Reporte por período
        $reportePeriodo = [
            'hoy' => Radicado::whereDate('fecha_radicado', Carbon::today())->count(),
            'ayer' => Radicado::whereDate('fecha_radicado', Carbon::yesterday())->count(),
            'esta_semana' => Radicado::whereBetween('fecha_radicado', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'semana_pasada' => Radicado::whereBetween('fecha_radicado', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ])->count(),
            'este_mes' => Radicado::whereMonth('fecha_radicado', Carbon::now()->month)
                                 ->whereYear('fecha_radicado', Carbon::now()->year)->count(),
            'mes_pasado' => Radicado::whereMonth('fecha_radicado', Carbon::now()->subMonth()->month)
                                   ->whereYear('fecha_radicado', Carbon::now()->subMonth()->year)->count(),
        ];

        // Reporte por dependencia
        $reporteDependencias = Dependencia::select('dependencias.*')
                                         ->withCount('radicadosDestino')
                                         ->orderBy('radicados_destino_count', 'desc')
                                         ->limit(10)
                                         ->get();

        // Reporte de eficiencia (tiempo promedio de respuesta)
        $reporteEficiencia = DB::table('radicados')
                              ->select(
                                  DB::raw('AVG(DATEDIFF(fecha_respuesta, fecha_radicado)) as promedio_dias'),
                                  DB::raw('COUNT(*) as total_respondidos')
                              )
                              ->whereNotNull('fecha_respuesta')
                              ->whereMonth('fecha_radicado', Carbon::now()->month)
                              ->first();

        return view('admin.reportes.index', compact(
            'reportePeriodo',
            'reporteDependencias',
            'reporteEficiencia'
        ));
    }





    /**
     * Mostrar formulario para crear nuevo usuario
     */
    public function crearUsuario()
    {
        return view('admin.usuarios.crear');
    }

    /**
     * Guardar nuevo usuario
     */
    public function guardarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'documento_identidad' => 'nullable|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrador,ventanilla',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $usuario = User::create([
            'name' => $request->name,
            'documento_identidad' => $request->documento_identidad,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'active' => $request->has('active'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente.'
            ]);
        }

        return redirect()->route('admin.usuarios')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.editar', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'documento_identidad' => 'nullable|string|max:20|unique:users,documento_identidad,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:administrador,ventanilla',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datos = [
            'name' => $request->name,
            'documento_identidad' => $request->documento_identidad,
            'email' => $request->email,
            'role' => $request->role,
            'active' => $request->has('active'),
        ];

        if ($request->filled('password')) {
            $datos['password'] = bcrypt($request->password);
        }

        $usuario->update($datos);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente.'
            ]);
        }

        return redirect()->route('admin.usuarios')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Cambiar estado del usuario (activar/desactivar)
     */
    public function toggleUserStatus($id)
    {
        $usuario = User::findOrFail($id);

        // Verificar que no sea el usuario actual
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios')
                            ->with('error', 'No puedes cambiar el estado de tu propia cuenta.');
        }

        // Si se está desactivando el último administrador activo, no permitirlo
        if ($usuario->role === 'administrador' && $usuario->active) {
            $adminActivosCount = User::where('role', 'administrador')
                                   ->where('active', true)
                                   ->where('id', '!=', $usuario->id)
                                   ->count();

            if ($adminActivosCount === 0) {
                return redirect()->route('admin.usuarios')
                                ->with('error', 'No se puede desactivar el último administrador activo del sistema.');
            }
        }

        // Cambiar el estado
        $usuario->active = !$usuario->active;
        $usuario->save();

        $accion = $usuario->active ? 'activado' : 'desactivado';

        return redirect()->route('admin.usuarios')
                        ->with('success', "Usuario {$accion} exitosamente.");
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);

        // Verificar que no sea el último administrador
        if ($usuario->role === 'administrador') {
            $adminCount = User::where('role', 'administrador')->where('active', true)->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.usuarios')
                                ->with('error', 'No se puede eliminar el último administrador activo del sistema.');
            }
        }

        // Verificar que no sea el usuario actual
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios')
                            ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Verificar si el usuario tiene radicados asociados
        $radicadosCount = $usuario->radicados()->count();
        if ($radicadosCount > 0) {
            return redirect()->route('admin.usuarios')
                            ->with('error', "No se puede eliminar el usuario porque tiene {$radicadosCount} radicado(s) asociado(s). Primero debe reasignar o eliminar los radicados.");
        }

        // Verificar si el usuario ha respondido radicados
        $radicadosRespondidosCount = $usuario->radicadosRespondidos()->count();
        if ($radicadosRespondidosCount > 0) {
            return redirect()->route('admin.usuarios')
                            ->with('error', "No se puede eliminar el usuario porque ha respondido {$radicadosRespondidosCount} radicado(s). Primero debe reasignar las respuestas.");
        }

        try {
            $usuario->delete();
            return redirect()->route('admin.usuarios')
                            ->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios')
                            ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Obtener actividad reciente del sistema (solo administradores ven lo que crean otros administradores)
     */
    private function obtenerActividadReciente()
    {
        $actividades = collect();

        // Obtener actividades de los últimos 7 días
        $fechaLimite = Carbon::now()->subDays(7);

        // Obtener IDs de todos los usuarios administradores
        $adminIds = User::where('role', 'administrador')->pluck('id');

        // 1. Usuarios creados (solo los creados por administradores)
        // Nota: Para usuarios, asumimos que solo los administradores pueden crear otros usuarios
        $usuarios = User::where('created_at', '>=', $fechaLimite)
                       ->orderBy('created_at', 'desc')
                       ->limit(5)
                       ->get();

        foreach ($usuarios as $usuario) {
            $actividades->push([
                'tipo' => 'usuario',
                'accion' => 'creado',
                'descripcion' => "Usuario '{$usuario->name}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $usuario->created_at,
                'icono' => 'user',
                'color' => 'blue'
            ]);
        }

        // 2. Dependencias creadas
        $dependencias = Dependencia::where('created_at', '>=', $fechaLimite)
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        foreach ($dependencias as $dependencia) {
            $actividades->push([
                'tipo' => 'dependencia',
                'accion' => 'creada',
                'descripcion' => "Dependencia '{$dependencia->nombre}' fue creada",
                'usuario_responsable' => 'Administrador',
                'fecha' => $dependencia->created_at,
                'icono' => 'office-building',
                'color' => 'green'
            ]);
        }

        // 3. Comunicaciones creadas
        $comunicaciones = \App\Models\Comunicacion::where('created_at', '>=', $fechaLimite)
                                                 ->orderBy('created_at', 'desc')
                                                 ->limit(5)
                                                 ->get();

        foreach ($comunicaciones as $comunicacion) {
            $actividades->push([
                'tipo' => 'comunicacion',
                'accion' => 'creada',
                'descripcion' => "Tipo de comunicación '{$comunicacion->nombre}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $comunicacion->created_at,
                'icono' => 'chat',
                'color' => 'purple'
            ]);
        }

        // 4. Tipos de Solicitud creados
        $tiposSolicitud = TipoSolicitud::where('created_at', '>=', $fechaLimite)
                                      ->orderBy('created_at', 'desc')
                                      ->limit(5)
                                      ->get();

        foreach ($tiposSolicitud as $tipo) {
            $actividades->push([
                'tipo' => 'tipo_solicitud',
                'accion' => 'creado',
                'descripcion' => "Tipo de solicitud '{$tipo->nombre}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $tipo->created_at,
                'icono' => 'clipboard',
                'color' => 'orange'
            ]);
        }

        // 5. Departamentos creados
        $departamentos = Departamento::where('created_at', '>=', $fechaLimite)
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        foreach ($departamentos as $departamento) {
            $actividades->push([
                'tipo' => 'departamento',
                'accion' => 'creado',
                'descripcion' => "Departamento '{$departamento->nombre}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $departamento->created_at,
                'icono' => 'map',
                'color' => 'indigo'
            ]);
        }

        // 6. Ciudades creadas
        $ciudades = Ciudad::where('created_at', '>=', $fechaLimite)
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();

        foreach ($ciudades as $ciudad) {
            $actividades->push([
                'tipo' => 'ciudad',
                'accion' => 'creada',
                'descripcion' => "Ciudad '{$ciudad->nombre}' fue creada",
                'usuario_responsable' => 'Administrador',
                'fecha' => $ciudad->created_at,
                'icono' => 'location',
                'color' => 'teal'
            ]);
        }

        // 7. Remitentes creados
        $remitentes = Remitente::where('created_at', '>=', $fechaLimite)
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        foreach ($remitentes as $remitente) {
            $actividades->push([
                'tipo' => 'remitente',
                'accion' => 'creado',
                'descripcion' => "Remitente '{$remitente->nombre_completo}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $remitente->created_at,
                'icono' => 'user-plus',
                'color' => 'pink'
            ]);
        }

        // 8. Actividades de login de usuarios
        $loginActivities = UserLoginActivity::with('user')
                                           ->where('login_at', '>=', $fechaLimite)
                                           ->orderBy('login_at', 'desc')
                                           ->limit(10)
                                           ->get();

        foreach ($loginActivities as $login) {
            $actividades->push([
                'tipo' => 'login',
                'accion' => 'inicio_sesion',
                'descripcion' => "Usuario '{$login->user->name}' inició sesión",
                'usuario_responsable' => $login->user->name,
                'fecha' => $login->login_at,
                'icono' => 'login',
                'color' => 'indigo'
            ]);
        }

        // Ordenar por fecha descendente y limitar a 10 elementos
        return $actividades->sortByDesc('fecha')->take(10)->values();
    }

    /**
     * Obtener actividad reciente para el modal (AJAX)
     */
    public function obtenerActividadModal(Request $request)
    {
        $busqueda = $request->get('busqueda', '');
        $actividades = collect();

        // Obtener actividades de los últimos 30 días para el modal
        $fechaLimite = Carbon::now()->subDays(30);

        // 1. Usuarios creados
        $usuarios = User::where('created_at', '>=', $fechaLimite)
                       ->when($busqueda, function($query, $busqueda) {
                           return $query->where('name', 'like', "%{$busqueda}%");
                       })
                       ->orderBy('created_at', 'desc')
                       ->limit(20)
                       ->get();

        foreach ($usuarios as $usuario) {
            $actividades->push([
                'tipo' => 'usuario',
                'accion' => 'creado',
                'descripcion' => "Usuario '{$usuario->name}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $usuario->created_at,
                'icono' => 'user',
                'color' => 'blue'
            ]);
        }

        // 2. Dependencias creadas
        $dependencias = Dependencia::where('created_at', '>=', $fechaLimite)
                                  ->when($busqueda, function($query, $busqueda) {
                                      return $query->where('nombre', 'like', "%{$busqueda}%");
                                  })
                                  ->orderBy('created_at', 'desc')
                                  ->limit(20)
                                  ->get();

        foreach ($dependencias as $dependencia) {
            $actividades->push([
                'tipo' => 'dependencia',
                'accion' => 'creada',
                'descripcion' => "Dependencia '{$dependencia->nombre}' fue creada",
                'usuario_responsable' => 'Administrador',
                'fecha' => $dependencia->created_at,
                'icono' => 'office-building',
                'color' => 'green'
            ]);
        }

        // 3. Comunicaciones creadas
        $comunicaciones = \App\Models\Comunicacion::where('created_at', '>=', $fechaLimite)
                                                 ->when($busqueda, function($query, $busqueda) {
                                                     return $query->where('nombre', 'like', "%{$busqueda}%");
                                                 })
                                                 ->orderBy('created_at', 'desc')
                                                 ->limit(20)
                                                 ->get();

        foreach ($comunicaciones as $comunicacion) {
            $actividades->push([
                'tipo' => 'comunicacion',
                'accion' => 'creada',
                'descripcion' => "Tipo de comunicación '{$comunicacion->nombre}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $comunicacion->created_at,
                'icono' => 'chat',
                'color' => 'purple'
            ]);
        }

        // 4. Tipos de Solicitud creados
        $tiposSolicitud = TipoSolicitud::where('created_at', '>=', $fechaLimite)
                                      ->when($busqueda, function($query, $busqueda) {
                                          return $query->where('nombre', 'like', "%{$busqueda}%");
                                      })
                                      ->orderBy('created_at', 'desc')
                                      ->limit(20)
                                      ->get();

        foreach ($tiposSolicitud as $tipo) {
            $actividades->push([
                'tipo' => 'tipo_solicitud',
                'accion' => 'creado',
                'descripcion' => "Tipo de solicitud '{$tipo->nombre}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $tipo->created_at,
                'icono' => 'clipboard',
                'color' => 'orange'
            ]);
        }

        // 5. Departamentos creados
        $departamentos = Departamento::where('created_at', '>=', $fechaLimite)
                                   ->when($busqueda, function($query, $busqueda) {
                                       return $query->where('nombre', 'like', "%{$busqueda}%");
                                   })
                                   ->orderBy('created_at', 'desc')
                                   ->limit(20)
                                   ->get();

        foreach ($departamentos as $departamento) {
            $actividades->push([
                'tipo' => 'departamento',
                'accion' => 'creado',
                'descripcion' => "Departamento '{$departamento->nombre}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $departamento->created_at,
                'icono' => 'map',
                'color' => 'indigo'
            ]);
        }

        // 6. Ciudades creadas
        $ciudades = Ciudad::where('created_at', '>=', $fechaLimite)
                         ->when($busqueda, function($query, $busqueda) {
                             return $query->where('nombre', 'like', "%{$busqueda}%");
                         })
                         ->orderBy('created_at', 'desc')
                         ->limit(20)
                         ->get();

        foreach ($ciudades as $ciudad) {
            $actividades->push([
                'tipo' => 'ciudad',
                'accion' => 'creada',
                'descripcion' => "Ciudad '{$ciudad->nombre}' fue creada",
                'usuario_responsable' => 'Administrador',
                'fecha' => $ciudad->created_at,
                'icono' => 'location',
                'color' => 'teal'
            ]);
        }

        // 7. Remitentes creados
        $remitentes = Remitente::where('created_at', '>=', $fechaLimite)
                              ->when($busqueda, function($query, $busqueda) {
                                  return $query->where('nombre_completo', 'like', "%{$busqueda}%");
                              })
                              ->orderBy('created_at', 'desc')
                              ->limit(20)
                              ->get();

        foreach ($remitentes as $remitente) {
            $actividades->push([
                'tipo' => 'remitente',
                'accion' => 'creado',
                'descripcion' => "Remitente '{$remitente->nombre_completo}' fue creado",
                'usuario_responsable' => 'Administrador',
                'fecha' => $remitente->created_at,
                'icono' => 'user-plus',
                'color' => 'pink'
            ]);
        }

        // 8. Actividades de login de usuarios
        $loginActivities = UserLoginActivity::with('user')
                                           ->where('login_at', '>=', $fechaLimite)
                                           ->when($busqueda, function($query, $busqueda) {
                                               return $query->whereHas('user', function($q) use ($busqueda) {
                                                   $q->where('name', 'like', "%{$busqueda}%");
                                               });
                                           })
                                           ->orderBy('login_at', 'desc')
                                           ->limit(30)
                                           ->get();

        foreach ($loginActivities as $login) {
            $actividades->push([
                'tipo' => 'login',
                'accion' => 'inicio_sesion',
                'descripcion' => "Usuario '{$login->user->name}' inició sesión",
                'usuario_responsable' => $login->user->name,
                'fecha' => $login->login_at,
                'icono' => 'login',
                'color' => 'indigo'
            ]);
        }

        // Ordenar por fecha descendente y limitar a 50 elementos para el modal
        $actividadesFiltradas = $actividades->sortByDesc('fecha')->take(50)->values();

        return response()->json([
            'success' => true,
            'actividades' => $actividadesFiltradas
        ]);
    }
}
