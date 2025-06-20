<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionForLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Asegurar que la sesión esté iniciada para rutas de login
        if ($request->routeIs('login') || $request->routeIs('login.store')) {
            
            // Iniciar sesión si no está iniciada
            if (!session()->isStarted()) {
                session()->start();
                Log::info('Sesión iniciada para login', [
                    'route' => $request->route()->getName(),
                    'method' => $request->method(),
                    'ip' => $request->ip()
                ]);
            }
            
            // Regenerar token CSRF si es una petición GET al login
            if ($request->isMethod('GET') && $request->routeIs('login')) {
                session()->regenerateToken();
                Log::info('Token CSRF regenerado para formulario de login', [
                    'session_id' => session()->getId(),
                    'ip' => $request->ip()
                ]);
            }
            
            // Verificar que el token CSRF esté disponible
            $token = csrf_token();
            if (empty($token)) {
                Log::warning('Token CSRF vacío detectado', [
                    'route' => $request->route()->getName(),
                    'session_id' => session()->getId(),
                    'ip' => $request->ip()
                ]);
                
                // Forzar regeneración del token
                session()->regenerateToken();
            }
        }
        
        return $next($request);
    }
}
