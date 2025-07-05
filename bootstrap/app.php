<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configurar redirección para usuarios no autenticados
        $middleware->redirectUsersTo('/login');

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\EnsureUserHasPermission::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
            'login.ratelimit' => \App\Http\Middleware\LoginRateLimit::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'security.logger' => \App\Http\Middleware\SecurityLogger::class,
            'admin.security' => \App\Http\Middleware\AdminSecurityCheck::class,
            'ensure.session.login' => \App\Http\Middleware\EnsureSessionForLogin::class,

        ]);

        // Middleware para servir archivos estáticos (debe ir primero)
        $middleware->web(prepend: [
            \App\Http\Middleware\ServeStaticFiles::class,
        ]);

        // Middleware global para todas las rutas web
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SecurityLogger::class,
            \App\Http\Middleware\SuspenderSistema::class,
        ]);

        // Middleware solo para rutas autenticadas
        $middleware->group('auth', [
            \App\Http\Middleware\SessionTimeout::class,
        ]);

        // Middleware adicional para login rate limiting
        $middleware->group('login', [
            \App\Http\Middleware\LoginRateLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de errores de autenticación
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No autenticado.',
                    'error' => 'unauthenticated'
                ], 401);
            }

            // Redirigir a login
            return redirect()->route('login')->with('message',
                'Por favor, inicia sesión para acceder a esta página.');
        });

        // Manejo de errores 419 (CSRF Token Mismatch)
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Token CSRF expirado. Por favor, recarga la página.',
                    'error' => 'token_mismatch'
                ], 419);
            }

            // Redirigir a login con mensaje informativo
            return redirect()->route('login')->with('warning',
                'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        });
        // Manejo personalizado de errores de base de datos
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            // Verificar si es un error de conexión a base de datos
            if (str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'No se puede establecer una conexión') ||
                str_contains($e->getMessage(), 'Unknown database') ||
                str_contains($e->getMessage(), 'Access denied') ||
                $e->getCode() == 2002 || $e->getCode() == 1049 || $e->getCode() == 1045) {

                // Log del error para administradores
                \Log::error('Database connection error: ' . $e->getMessage());

                // Mostrar página de error personalizada
                return response()->view('errors.database', [], 503);
            }
        });

        $exceptions->render(function (\PDOException $e, $request) {
            // Manejo de errores PDO directos
            if (str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'No se puede establecer una conexión') ||
                str_contains($e->getMessage(), 'Unknown database') ||
                str_contains($e->getMessage(), 'Access denied') ||
                $e->getCode() == 2002 || $e->getCode() == 1049 || $e->getCode() == 1045) {

                \Log::error('PDO connection error: ' . $e->getMessage());
                return response()->view('errors.database', [], 503);
            }
        });
    })->create();
