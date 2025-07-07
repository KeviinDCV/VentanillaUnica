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
        // Si es una petición GET a la página de login, verificar si debe hacer logout
        if ($request->isMethod('GET') && $request->routeIs('login')) {

            // Si hay un usuario autenticado, verificar si es una navegación legítima
            if (Auth::check()) {
                $user = Auth::user();

                // Verificar si es una navegación reciente (menos de 5 segundos después del login)
                $loginTime = session('login_time');
                $currentTime = now()->timestamp;
                $timeSinceLogin = $loginTime ? ($currentTime - $loginTime) : null;

                // Si el login fue muy reciente, podría ser una redirección incorrecta
                if ($timeSinceLogin && $timeSinceLogin < 5) {
                    Log::warning('Acceso a login muy pronto después del login exitoso - Redirigiendo a dashboard', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'session_id' => $request->session()->getId(),
                        'time_since_login' => $timeSinceLogin,
                        'referer' => $request->header('Referer'),
                        'timestamp' => now()->toISOString()
                    ]);

                    // En lugar de logout, redirigir al dashboard
                    return redirect()->route('dashboard');
                }

                // Si ha pasado más tiempo, proceder con logout automático
                Log::info('Logout automático al acceder a página de login', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'session_id' => $request->session()->getId(),
                    'time_since_login' => $timeSinceLogin,
                    'referer' => $request->header('Referer'),
                    'timestamp' => now()->toISOString()
                ]);

                // Redirigir a ruta especial que maneja el logout automático
                return redirect()->route('auto-logout');
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
