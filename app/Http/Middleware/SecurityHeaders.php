<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Headers de seguridad para producción
        $headers = [
            // Prevenir ataques de clickjacking
            'X-Frame-Options' => 'DENY',
            
            // Prevenir MIME type sniffing
            'X-Content-Type-Options' => 'nosniff',
            
            // Habilitar XSS protection del navegador
            'X-XSS-Protection' => '1; mode=block',
            
            // Referrer Policy para controlar información enviada
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            
            // Permissions Policy (antes Feature Policy)
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
        ];

        // HSTS solo en producción y con HTTPS
        if (app()->environment('production') && $request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }

        // Content Security Policy
        $csp = $this->getContentSecurityPolicy($request);
        if ($csp) {
            $headers['Content-Security-Policy'] = $csp;
        }

        // Headers de cache control para páginas autenticadas (prevenir navegación hacia atrás)
        if (auth()->check()) {
            $headers['Cache-Control'] = 'no-cache, no-store, must-revalidate, private, max-age=0';
            $headers['Pragma'] = 'no-cache';
            $headers['Expires'] = 'Thu, 01 Jan 1970 00:00:00 GMT';
        }

        // Aplicar headers
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    /**
     * Generar Content Security Policy
     */
    private function getContentSecurityPolicy(Request $request): string
    {
        $nonce = base64_encode(random_bytes(16));

        // Almacenar nonce en la sesión para uso en las vistas
        // Solo si la sesión está disponible
        if (session()->isStarted()) {
            session(['csp_nonce' => $nonce]);
        }

        $policies = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' https://fonts.bunny.net",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
            "font-src 'self' https://fonts.bunny.net",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-src 'none'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'"
        ];

        // Solo agregar upgrade-insecure-requests en producción con HTTPS
        if (app()->environment('production') && $request->secure()) {
            $policies[] = "upgrade-insecure-requests";
        }

        // En desarrollo, ser más permisivo para Vite y debugging
        if (app()->environment('local')) {
            $policies = array_map(function($policy) {
                if (str_starts_with($policy, 'script-src')) {
                    // No incluir 'unsafe-inline' cuando se usa nonce para evitar conflictos CSP
                    return $policy . " 'unsafe-eval' http://localhost:* http://127.0.0.1:* http://192.168.2.202:* ws://localhost:* ws://127.0.0.1:*";
                }
                if (str_starts_with($policy, 'style-src')) {
                    return $policy . " http://localhost:* http://127.0.0.1:* http://192.168.2.202:*";
                }
                if (str_starts_with($policy, 'connect-src')) {
                    return $policy . " http://localhost:* http://127.0.0.1:* http://192.168.2.202:* ws://localhost:* ws://127.0.0.1:*";
                }
                if (str_starts_with($policy, 'img-src')) {
                    return $policy . " http: blob:";
                }
                return $policy;
            }, $policies);
        }

        return implode('; ', $policies);
    }
}
