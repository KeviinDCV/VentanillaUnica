<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar timeout a usuarios autenticados
        // No interferir con el proceso de login/logout
        if (Auth::check() && !$this->isExcludedRoute($request)) {
            $user = Auth::user();
            $lastActivity = session('last_activity');
            $timeout = config('session.lifetime') * 60; // Convertir minutos a segundos
            $currentTime = time();

            // Verificar timeout de sesión solo si hay actividad previa registrada
            if ($lastActivity && ($currentTime - $lastActivity) > $timeout) {
                Log::info('Sesión expirada por inactividad', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'last_activity' => date('Y-m-d H:i:s', $lastActivity),
                    'timeout_seconds' => $timeout,
                    'inactive_time' => $currentTime - $lastActivity
                ]);

                return $this->logoutUser($request, 'Su sesión ha expirado por inactividad.', 'warning');
            }

            // Verificar cambio de IP (más tolerante para desarrollo)
            $loginIp = session('login_ip');
            if ($loginIp && $loginIp !== $request->ip() && !$this->isLocalEnvironment()) {
                Log::warning('Cambio de IP detectado en sesión activa', [
                    'user_id' => $user->id,
                    'original_ip' => $loginIp,
                    'current_ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return $this->logoutUser($request, 'Sesión invalidada por motivos de seguridad.', 'error');
            }

            // Verificar cambio de User Agent (más tolerante)
            $loginUserAgent = session('login_user_agent');
            if ($loginUserAgent && $loginUserAgent !== $request->userAgent() && !$this->isMinorUserAgentChange($loginUserAgent, $request->userAgent())) {
                Log::warning('Cambio de User Agent detectado en sesión activa', [
                    'user_id' => $user->id,
                    'original_user_agent' => $loginUserAgent,
                    'current_user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ]);

                return $this->logoutUser($request, 'Sesión invalidada por motivos de seguridad.', 'error');
            }

            // Actualizar última actividad solo en rutas que requieren actividad
            if (!$this->isPassiveRoute($request)) {
                session(['last_activity' => $currentTime]);
            }
        }

        return $next($request);
    }

    /**
     * Verificar si la ruta debe ser excluida del timeout
     */
    private function isExcludedRoute(Request $request): bool
    {
        $excludedRoutes = [
            'logout',
            'login',
            'sistema/estado',
            'sistema/suspendido',
            'sistema/reactivar'
        ];

        foreach ($excludedRoutes as $route) {
            if ($request->is($route) || $request->is($route . '/*')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar si es un entorno local
     */
    private function isLocalEnvironment(): bool
    {
        return app()->environment('local') ||
               in_array(request()->ip(), ['127.0.0.1', '::1', 'localhost']);
    }

    /**
     * Verificar si es un cambio menor en el User Agent
     */
    private function isMinorUserAgentChange(string $original, string $current): bool
    {
        // Permitir cambios menores como actualizaciones de versión del navegador
        $originalBase = preg_replace('/\d+\.\d+\.\d+/', 'X.X.X', $original);
        $currentBase = preg_replace('/\d+\.\d+\.\d+/', 'X.X.X', $current);

        return $originalBase === $currentBase;
    }

    /**
     * Verificar si es una ruta pasiva que no debe actualizar actividad
     */
    private function isPassiveRoute(Request $request): bool
    {
        $passiveRoutes = [
            'sistema/estado',
            'api/*'
        ];

        foreach ($passiveRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cerrar sesión del usuario de forma segura
     */
    private function logoutUser(Request $request, string $message, string $type = 'info')
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with($type, $message);
    }
}
