<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Debug: Log del request completo para verificar remember
        Log::debug('AuthenticatedSessionController: Request data', [
            'remember_param' => $request->input('remember'),
            'remember_boolean' => $request->boolean('remember'),
            'all_inputs' => $request->all(),
            'ip' => $request->ip()
        ]);

        $request->authenticate();

        // Verificar si el usuario está activo
        $user = Auth::user();
        if (!$user->isActive()) {
            Auth::logout();

            Log::warning('Intento de login con cuenta inactiva', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->input('email'),
                'user_id' => $user->id,
                'timestamp' => now()->toISOString()
            ]);

            return back()->withErrors([
                'email' => 'Su cuenta está inactiva. Contacte al administrador.',
            ]);
        }

        // Regenerar ID de sesión para prevenir session fixation
        $request->session()->regenerate();

        // Establecer configuración de sesión segura
        $this->configureSecureSession($request);

        // Log del login exitoso completo
        Log::info('Login completado exitosamente', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $user->id,
            'user_role' => $user->role,
            'remember' => $request->boolean('remember'),
            'session_id' => $request->session()->getId(),
            'timestamp' => now()->toISOString()
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Configurar sesión segura
     */
    private function configureSecureSession($request): void
    {
        // Establecer la actividad inicial para el timeout
        $request->session()->put('last_activity', time());

        // Almacenar información de seguridad de la sesión
        $request->session()->put('login_ip', $request->ip());
        $request->session()->put('login_user_agent', $request->userAgent());
        $request->session()->put('login_time', now()->timestamp);

        // Configurar cookies seguras si estamos en HTTPS
        if ($request->secure()) {
            config([
                'session.secure' => true,
                'session.http_only' => true,
                'session.same_site' => 'strict'
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Log del logout
        Log::info('Usuario cerró sesión', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $user ? $user->id : null,
            'session_id' => $request->session()->getId(),
            'timestamp' => now()->toISOString()
        ]);

        // Cerrar sesión
        Auth::guard('web')->logout();

        // Invalidar sesión completamente
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Limpiar cookies de remember me si existen
        $response = redirect('/');
        $response->withCookie(cookie()->forget('remember_web'));

        return $response;
    }
}
