<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class SuspenderSistema
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el sistema está suspendido
        $sistemaActivo = Cache::get('sistema_activo', true);
        $tiempoSuspension = Cache::get('tiempo_suspension');

        // Si el sistema no está suspendido, continuar normalmente
        if ($sistemaActivo) {
            return $next($request);
        }

        // Verificar si el tiempo de suspensión ha expirado
        if ($tiempoSuspension && Carbon::now()->greaterThan($tiempoSuspension)) {
            // Reactivar automáticamente el sistema
            Cache::forget('sistema_activo');
            Cache::forget('tiempo_suspension');
            Cache::forget('password_suspension');
            return $next($request);
        }

        // Permitir acceso a rutas específicas del sistema de suspensión
        if ($request->is('sistema/suspendido') ||
            $request->is('sistema/reactivar') ||
            $request->is('sistema/reactivar/*') ||
            $request->is('sistema/estado')) {
            return $next($request);
        }

        // Permitir acceso a rutas de autenticación
        if ($request->is('logout') ||
            $request->is('login') ||
            $request->is('login/*')) {
            return $next($request);
        }

        // Permitir acceso a assets y archivos estáticos
        if ($request->is('build/*') ||
            $request->is('storage/*') ||
            $request->is('css/*') ||
            $request->is('js/*')) {
            return $next($request);
        }

        // Redirigir a la página de sistema suspendido
        return redirect()->route('sistema.suspendido');
    }

    /**
     * Suspender el sistema por un tiempo determinado
     */
    public static function suspender($minutos = 30, $password = null)
    {
        $tiempoSuspension = Carbon::now()->addMinutes($minutos);

        Cache::put('sistema_activo', false, $tiempoSuspension);
        Cache::put('tiempo_suspension', $tiempoSuspension, $tiempoSuspension);

        if ($password) {
            Cache::put('password_suspension', Hash::make($password), $tiempoSuspension);
        }

        return [
            'success' => true,
            'mensaje' => "Sistema suspendido por {$minutos} minutos",
            'tiempo_reactivacion' => $tiempoSuspension->format('d/m/Y H:i:s')
        ];
    }

    /**
     * Reactivar el sistema con contraseña
     */
    public static function reactivar($password = null)
    {
        $passwordSuspension = Cache::get('password_suspension');

        // Si hay contraseña configurada, verificarla
        if ($passwordSuspension && $password) {
            if (!Hash::check($password, $passwordSuspension)) {
                return [
                    'success' => false,
                    'mensaje' => 'Contraseña incorrecta'
                ];
            }
        } elseif ($passwordSuspension && !$password) {
            return [
                'success' => false,
                'mensaje' => 'Se requiere contraseña para reactivar el sistema'
            ];
        }

        // Reactivar el sistema
        Cache::forget('sistema_activo');
        Cache::forget('tiempo_suspension');
        Cache::forget('password_suspension');

        return [
            'success' => true,
            'mensaje' => 'Sistema reactivado exitosamente'
        ];
    }

    /**
     * Obtener información del estado de suspensión
     */
    public static function estadoSuspension()
    {
        $sistemaActivo = Cache::get('sistema_activo', true);
        $tiempoSuspension = Cache::get('tiempo_suspension');
        $tienePassword = Cache::has('password_suspension');

        if ($sistemaActivo) {
            return [
                'suspendido' => false,
                'tiempo_restante' => null,
                'requiere_password' => false
            ];
        }

        $tiempoRestante = null;
        if ($tiempoSuspension) {
            $tiempoRestante = Carbon::now()->diffInMinutes($tiempoSuspension, false);
            if ($tiempoRestante <= 0) {
                // El tiempo ha expirado, reactivar automáticamente
                self::reactivar();
                return [
                    'suspendido' => false,
                    'tiempo_restante' => null,
                    'requiere_password' => false
                ];
            }
        }

        return [
            'suspendido' => true,
            'tiempo_restante' => $tiempoRestante,
            'tiempo_reactivacion' => $tiempoSuspension ? $tiempoSuspension->format('d/m/Y H:i:s') : null,
            'requiere_password' => $tienePassword
        ];
    }
}
