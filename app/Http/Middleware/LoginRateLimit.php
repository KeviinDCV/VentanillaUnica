<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LoginRateLimit
{
    /**
     * Máximo número de intentos de login por IP
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Tiempo de bloqueo en minutos
     */
    const LOCKOUT_TIME = 60;

    /**
     * Tiempo de ventana para contar intentos (en minutos)
     */
    const ATTEMPT_WINDOW = 1;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar rate limiting a rutas de login POST
        if ($request->isMethod('POST') && $request->routeIs('login')) {
            $ip = $request->ip();
            $key = 'login_attempts_' . $ip;
            $lockoutKey = 'login_lockout_' . $ip;

            // Verificar si la IP está bloqueada
            if (Cache::has($lockoutKey)) {
                $remainingTime = Cache::get($lockoutKey) - now()->timestamp;
                
                Log::warning('Intento de login desde IP bloqueada', [
                    'ip' => $ip,
                    'user_agent' => $request->userAgent(),
                    'remaining_lockout_seconds' => $remainingTime
                ]);

                return response()->json([
                    'message' => 'Demasiados intentos fallidos. Intente nuevamente en ' . ceil($remainingTime / 60) . ' minutos.',
                    'lockout_remaining' => $remainingTime
                ], 429);
            }

            // Obtener número de intentos actuales
            $attempts = Cache::get($key, 0);

            // Si se excede el límite, bloquear IP
            if ($attempts >= self::MAX_ATTEMPTS) {
                $lockoutUntil = now()->addMinutes(self::LOCKOUT_TIME)->timestamp;
                Cache::put($lockoutKey, $lockoutUntil, self::LOCKOUT_TIME * 60);
                Cache::forget($key); // Limpiar contador de intentos

                Log::warning('IP bloqueada por exceso de intentos de login', [
                    'ip' => $ip,
                    'attempts' => $attempts,
                    'lockout_until' => date('Y-m-d H:i:s', $lockoutUntil),
                    'user_agent' => $request->userAgent()
                ]);

                return response()->json([
                    'message' => 'Demasiados intentos fallidos. Su IP ha sido bloqueada por ' . self::LOCKOUT_TIME . ' minutos.',
                    'lockout_time' => self::LOCKOUT_TIME
                ], 429);
            }
        }

        return $next($request);
    }

    /**
     * Incrementar contador de intentos fallidos
     */
    public static function incrementFailedAttempts(Request $request): void
    {
        $ip = $request->ip();
        $key = 'login_attempts_' . $ip;
        
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, self::ATTEMPT_WINDOW * 60);

        Log::info('Intento de login fallido registrado', [
            'ip' => $ip,
            'attempts' => $attempts,
            'max_attempts' => self::MAX_ATTEMPTS,
            'user_agent' => $request->userAgent(),
            'email_attempted' => $request->input('email')
        ]);
    }

    /**
     * Limpiar intentos fallidos después de login exitoso
     */
    public static function clearFailedAttempts(Request $request): void
    {
        $ip = $request->ip();
        $key = 'login_attempts_' . $ip;
        $lockoutKey = 'login_lockout_' . $ip;
        
        Cache::forget($key);
        Cache::forget($lockoutKey);

        Log::info('Intentos de login limpiados después de login exitoso', [
            'ip' => $ip,
            'user_agent' => $request->userAgent()
        ]);
    }
}
