<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SPAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Detectar si es una petición SPA antes de procesar
        $isSpaRequest = $request->ajax() ||
                       $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                       $request->header('X-SPA-Request') === 'true';

        if ($isSpaRequest &&
            !$request->is('api/*') &&
            !$request->is('logout') &&
            !$request->is('login') &&
            !$request->is('csrf-token')) {

            // Marcar que es una petición SPA
            $request->attributes->set('spa_request', true);

            // Log para depuración (solo en desarrollo)
            if (config('app.debug')) {
                \Log::info('SPA Request detected', [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'ajax' => $request->ajax(),
                    'xhr_header' => $request->header('X-Requested-With'),
                    'spa_header' => $request->header('X-SPA-Request')
                ]);
            }
        }

        $response = $next($request);

        // Para peticiones SPA exitosas, devolver solo el contenido del main
        if ($request->attributes->get('spa_request') &&
            $response->getStatusCode() === 200 &&
            str_contains($response->headers->get('content-type', ''), 'text/html')) {

            $content = $response->getContent();

            // Si el contenido incluye el layout completo, extraer solo el main con sus atributos
            if (strpos($content, '<main') !== false && strpos($content, '<!DOCTYPE') !== false) {
                // Usar una expresión regular más robusta para extraer el main completo
                if (preg_match('/<main[^>]*>.*?<\/main>/s', $content, $matches)) {
                    $response->setContent($matches[0]);

                    // Agregar header para indicar que es contenido SPA
                    $response->headers->set('X-SPA-Content', 'true');
                }
            }
        }

        return $response;
    }
}
