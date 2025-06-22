<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
        ];

        if (app()->environment('production') && $request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }

        $csp = $this->getContentSecurityPolicy($request);
        if ($csp) {
            $headers['Content-Security-Policy'] = $csp;
        }

        if (auth()->check()) {
            $headers['Cache-Control'] = 'no-cache, no-store, must-revalidate, private, max-age=0';
            $headers['Pragma'] = 'no-cache';
            $headers['Expires'] = 'Thu, 01 Jan 1970 00:00:00 GMT';
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    private function getContentSecurityPolicy(Request $request): string
    {
        $nonce = base64_encode(random_bytes(16));

        try {
            if (!session()->isStarted()) {
                session()->start();
            }
            session()->put('csp_nonce', $nonce);
            session()->save();
        } catch (\Exception $e) {
            error_log('Error setting CSP nonce in session: ' . $e->getMessage());
        }

        if (app()->environment('local')) {
            $policies = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.bunny.net http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net http://localhost:* http://127.0.0.1:*",
                "font-src 'self' https://fonts.bunny.net",
                "img-src 'self' data: https: http: blob:",
                "connect-src 'self' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*",
                "frame-src 'none'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'"
            ];
        } else {
            $policies = [
                "default-src 'self'",
                "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval' https://fonts.bunny.net",
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

            if ($request->secure()) {
                $policies[] = "upgrade-insecure-requests";
            }
        }

        return implode('; ', $policies);
    }
}
