<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityLogger
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Detectar patrones sospechosos en la URL
        $this->detectSuspiciousPatterns($request);
        
        // Detectar intentos de inyección SQL
        $this->detectSqlInjectionAttempts($request);
        
        // Detectar intentos de XSS
        $this->detectXssAttempts($request);
        
        // Detectar intentos de path traversal
        $this->detectPathTraversalAttempts($request);

        $response = $next($request);

        // Log de accesos a rutas administrativas
        $this->logAdminAccess($request);

        return $response;
    }

    /**
     * Detectar patrones sospechosos en URLs
     */
    private function detectSuspiciousPatterns(Request $request): void
    {
        $suspiciousPatterns = [
            '/\.\./i',                    // Path traversal
            '/union.*select/i',           // SQL injection
            '/<script/i',                 // XSS
            '/javascript:/i',             // XSS
            '/eval\(/i',                  // Code injection
            '/base64_decode/i',           // Code injection
            '/system\(/i',                // Command injection
            '/exec\(/i',                  // Command injection
            '/shell_exec/i',              // Command injection
            '/passthru/i',                // Command injection
            '/proc\/self\/environ/i',     // LFI
            '/etc\/passwd/i',             // LFI
            '/wp-admin/i',                // WordPress scanning
            '/phpmyadmin/i',              // phpMyAdmin scanning
            '/admin\.php/i',              // Admin scanning
        ];

        $url = $request->fullUrl();
        $userAgent = $request->userAgent();

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $url) || preg_match($pattern, $userAgent)) {
                Log::channel('security')->warning('Patrón sospechoso detectado', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'url' => $url,
                    'pattern' => $pattern,
                    'user_id' => auth()->id(),
                    'timestamp' => now()->toISOString()
                ]);
                break;
            }
        }
    }

    /**
     * Detectar intentos de inyección SQL
     */
    private function detectSqlInjectionAttempts(Request $request): void
    {
        $sqlPatterns = [
            '/union.*select/i',
            '/select.*from/i',
            '/insert.*into/i',
            '/update.*set/i',
            '/delete.*from/i',
            '/drop.*table/i',
            '/create.*table/i',
            '/alter.*table/i',
            '/exec.*sp_/i',
            '/xp_cmdshell/i',
            '/sp_executesql/i',
            '/\'.*or.*\'/i',
            '/\".*or.*\"/i',
            '/1=1/i',
            '/1\' or \'1\'/i',
            '/admin\'--/i',
            '/\' or 1=1--/i'
        ];

        $allInput = json_encode($request->all());

        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $allInput)) {
                Log::channel('security')->error('Intento de inyección SQL detectado', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'input' => $request->all(),
                    'pattern' => $pattern,
                    'user_id' => auth()->id(),
                    'timestamp' => now()->toISOString()
                ]);
                break;
            }
        }
    }

    /**
     * Detectar intentos de XSS
     */
    private function detectXssAttempts(Request $request): void
    {
        $xssPatterns = [
            '/<script.*?>.*?<\/script>/i',
            '/<iframe.*?>/i',
            '/<object.*?>/i',
            '/<embed.*?>/i',
            '/<link.*?>/i',
            '/<meta.*?>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onclick=/i',
            '/onerror=/i',
            '/onmouseover=/i',
            '/onfocus=/i',
            '/onblur=/i',
            '/alert\(/i',
            '/confirm\(/i',
            '/prompt\(/i',
            '/document\.cookie/i',
            '/document\.write/i'
        ];

        $allInput = json_encode($request->all());

        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $allInput)) {
                Log::channel('security')->error('Intento de XSS detectado', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'input' => $request->all(),
                    'pattern' => $pattern,
                    'user_id' => auth()->id(),
                    'timestamp' => now()->toISOString()
                ]);
                break;
            }
        }
    }

    /**
     * Detectar intentos de path traversal
     */
    private function detectPathTraversalAttempts(Request $request): void
    {
        $pathTraversalPatterns = [
            '/\.\.\//i',
            '/\.\.\\\/i',
            '/%2e%2e%2f/i',
            '/%2e%2e%5c/i',
            '/\.\.%2f/i',
            '/\.\.%5c/i',
            '/%252e%252e%252f/i',
            '/etc\/passwd/i',
            '/proc\/self\/environ/i',
            '/windows\/system32/i',
            '/boot\.ini/i'
        ];

        $allInput = json_encode($request->all());
        $url = $request->fullUrl();

        foreach ($pathTraversalPatterns as $pattern) {
            if (preg_match($pattern, $allInput) || preg_match($pattern, $url)) {
                Log::channel('security')->error('Intento de path traversal detectado', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $url,
                    'input' => $request->all(),
                    'pattern' => $pattern,
                    'user_id' => auth()->id(),
                    'timestamp' => now()->toISOString()
                ]);
                break;
            }
        }
    }

    /**
     * Log de accesos a rutas administrativas
     */
    private function logAdminAccess(Request $request): void
    {
        $adminRoutes = [
            'admin.*',
            'gestion.*',
            'usuarios.*',
            'dependencias.*',
            'reportes.*'
        ];

        $currentRoute = $request->route() ? $request->route()->getName() : '';

        foreach ($adminRoutes as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                Log::channel('security')->info('Acceso a ruta administrativa', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'route' => $currentRoute,
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id(),
                    'user_role' => auth()->user()?->role,
                    'timestamp' => now()->toISOString()
                ]);
                break;
            }
        }
    }
}
