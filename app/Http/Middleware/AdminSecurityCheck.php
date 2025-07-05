<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminSecurityCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar a rutas administrativas
        if (!$this->isAdminRoute($request)) {
            return $next($request);
        }

        // Verificar autenticación
        if (!auth()->check()) {
            Log::channel('security')->warning('Intento de acceso no autenticado a ruta administrativa', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now()->toISOString()
            ]);
            
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta área.');
        }

        // Verificar rol de administrador
        if (!auth()->user()->hasRole('administrador')) {
            Log::channel('security')->warning('Intento de acceso no autorizado a área administrativa', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'timestamp' => now()->toISOString()
            ]);
            
            abort(403, 'No tiene permisos para acceder a esta área.');
        }

        // Log de acceso exitoso a área administrativa
        Log::channel('security')->info('Acceso autorizado a área administrativa', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'timestamp' => now()->toISOString()
        ]);

        // Verificar actividad sospechosa del usuario
        $this->checkSuspiciousActivity($request);

        return $next($request);
    }

    /**
     * Verificar si es una ruta administrativa
     */
    private function isAdminRoute(Request $request): bool
    {
        $adminPaths = [
            'admin/*',
            'gestion/*',
            'reportes/*'
        ];

        foreach ($adminPaths as $path) {
            if ($request->is($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar actividad sospechosa del usuario
     */
    private function checkSuspiciousActivity(Request $request): void
    {
        $userId = auth()->id();
        $cacheKey = "admin_activity_{$userId}";
        
        // Obtener actividad reciente del cache
        $recentActivity = cache()->get($cacheKey, []);
        
        // Agregar actividad actual
        $currentActivity = [
            'timestamp' => now()->timestamp,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent()
        ];
        
        $recentActivity[] = $currentActivity;
        
        // Mantener solo los últimos 10 accesos
        $recentActivity = array_slice($recentActivity, -10);
        
        // Guardar en cache por 1 hora
        cache()->put($cacheKey, $recentActivity, 3600);
        
        // Verificar patrones sospechosos
        $this->detectSuspiciousPatterns($recentActivity, $userId);
    }

    /**
     * Detectar patrones sospechosos en la actividad
     */
    private function detectSuspiciousPatterns(array $activity, int $userId): void
    {
        if (count($activity) < 5) {
            return; // No hay suficiente actividad para analizar
        }

        // Verificar múltiples IPs en poco tiempo
        $ips = array_column($activity, 'ip');
        $uniqueIps = array_unique($ips);
        
        if (count($uniqueIps) > 3) {
            Log::channel('security')->warning('Usuario con múltiples IPs en área administrativa', [
                'user_id' => $userId,
                'unique_ips' => $uniqueIps,
                'total_requests' => count($activity),
                'timestamp' => now()->toISOString()
            ]);
        }

        // Verificar múltiples User-Agents
        $userAgents = array_column($activity, 'user_agent');
        $uniqueUserAgents = array_unique($userAgents);
        
        if (count($uniqueUserAgents) > 2) {
            Log::channel('security')->warning('Usuario con múltiples User-Agents en área administrativa', [
                'user_id' => $userId,
                'unique_user_agents' => count($uniqueUserAgents),
                'timestamp' => now()->toISOString()
            ]);
        }

        // Verificar acceso muy rápido (posible bot)
        $timestamps = array_column($activity, 'timestamp');
        $recentTimestamps = array_slice($timestamps, -5); // Últimos 5 accesos
        
        if (count($recentTimestamps) >= 5) {
            $timeSpan = max($recentTimestamps) - min($recentTimestamps);
            
            // Si 5 accesos en menos de 30 segundos
            if ($timeSpan < 30) {
                Log::channel('security')->warning('Actividad muy rápida detectada en área administrativa', [
                    'user_id' => $userId,
                    'requests_in_seconds' => $timeSpan,
                    'total_requests' => count($recentTimestamps),
                    'timestamp' => now()->toISOString()
                ]);
            }
        }
    }
}
