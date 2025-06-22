<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionForLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si es una petición GET a la página de login, forzar logout de cualquier sesión activa
        if ($request->isMethod('GET') && $request->routeIs('login')) {

            // Si hay un usuario autenticado, cerrar su sesión automáticamente
            if (Auth::check()) {
                $user = Auth::user();

                Log::info('Logout automático al acceder a página de login', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'session_id' => $request->session()->getId(),
                    'timestamp' => now()->toISOString()
                ]);

                // Cerrar sesión completamente
                Auth::guard('web')->logout();

                // Invalidar sesión completamente
                $request->session()->invalidate();

                // Regenerar token CSRF para la nueva sesión
                $request->session()->regenerateToken();

                // Crear respuesta con cookie para limpiar remember me
                $response = response()->view('auth.login')->withCookie(cookie()->forget('remember_web'));

                // Agregar mensaje flash a la nueva sesión
                $request->session()->flash('info', 'Su sesión anterior ha sido cerrada por seguridad.');

                return $response;
            }
        }

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
