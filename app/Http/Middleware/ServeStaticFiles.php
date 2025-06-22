<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ServeStaticFiles
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): BaseResponse
    {
        $path = $request->getPathInfo();

        // Solo procesar si es una solicitud GET o HEAD
        if (!in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return $next($request);
        }

        // Lista de extensiones de archivos estáticos
        $staticExtensions = [
            'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg',
            'woff', 'woff2', 'ttf', 'eot', 'map', 'webp', 'pdf'
        ];

        // Verificar si es una solicitud de archivo estático por extensión
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension && in_array(strtolower($extension), $staticExtensions)) {
            $staticResponse = $this->serveStaticFile($request, $path);
            if ($staticResponse) {
                return $staticResponse;
            }
        }

        // Verificar rutas específicas de assets
        if ($this->isAssetPath($path)) {
            $staticResponse = $this->serveStaticFile($request, $path);
            if ($staticResponse) {
                return $staticResponse;
            }
        }

        // Si no es un archivo estático o no se pudo servir, continuar con Laravel
        return $next($request);
    }
    
    /**
     * Verificar si es una ruta de assets
     */
    private function isAssetPath(string $path): bool
    {
        $assetPaths = [
            '/build/',
            '/images/',
            '/storage/',
            '/css/',
            '/js/',
            '/fonts/',
            '/assets/'
        ];
        
        foreach ($assetPaths as $assetPath) {
            if (str_starts_with($path, $assetPath)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Servir archivo estático
     */
    private function serveStaticFile(Request $request, string $path): ?BaseResponse
    {
        // Validación de seguridad: evitar path traversal
        if (str_contains($path, '..') || str_contains($path, '\\')) {
            return null;
        }

        $publicPath = public_path(ltrim($path, '/'));

        // Verificar que el archivo esté dentro del directorio public
        $realPublicPath = realpath($publicPath);
        $realPublicDir = realpath(public_path());

        if (!$realPublicPath || !str_starts_with($realPublicPath, $realPublicDir)) {
            return null;
        }

        // Verificar si el archivo existe
        if (!file_exists($publicPath) || !is_file($publicPath)) {
            // Retornar null para que Laravel maneje la ruta
            return null;
        }
        
        // Obtener información del archivo con manejo de errores
        try {
            $mimeType = $this->getMimeType($publicPath);
            $lastModified = filemtime($publicPath);
            $etag = md5_file($publicPath);

            if ($lastModified === false || $etag === false) {
                return null;
            }
        } catch (\Exception $e) {
            // Si hay error leyendo el archivo, dejar que Laravel lo maneje
            return null;
        }
        
        // Verificar caché del navegador
        if ($this->isNotModified($request, $lastModified, $etag)) {
            return response('', 304)
                ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT')
                ->header('ETag', '"' . $etag . '"')
                ->header('Cache-Control', 'public, max-age=31536000'); // 1 año
        }
        
        // Crear respuesta con el archivo
        try {
            $response = new BinaryFileResponse($publicPath);
        } catch (\Exception $e) {
            // Si hay error creando la respuesta, dejar que Laravel lo maneje
            return null;
        }
        
        // Configurar headers
        $response->headers->set('Content-Type', $mimeType);
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
        $response->headers->set('ETag', '"' . $etag . '"');
        
        // Configurar caché según el tipo de archivo
        if ($this->isCacheableAsset($path)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 año para assets con hash
        } else {
            $response->headers->set('Cache-Control', 'public, max-age=3600'); // 1 hora para otros archivos
        }
        
        // Headers de seguridad
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        return $response;
    }
    
    /**
     * Obtener tipo MIME del archivo
     */
    private function getMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'map' => 'application/json',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf'
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
    
    /**
     * Verificar si el archivo no ha sido modificado
     */
    private function isNotModified(Request $request, int $lastModified, string $etag): bool
    {
        $ifModifiedSince = $request->header('If-Modified-Since');
        $ifNoneMatch = $request->header('If-None-Match');
        
        if ($ifNoneMatch && $ifNoneMatch === '"' . $etag . '"') {
            return true;
        }
        
        if ($ifModifiedSince) {
            $ifModifiedSinceTime = strtotime($ifModifiedSince);
            if ($ifModifiedSinceTime && $ifModifiedSinceTime >= $lastModified) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verificar si es un asset cacheable (con hash en el nombre)
     */
    private function isCacheableAsset(string $path): bool
    {
        // Assets de Vite tienen hash en el nombre
        return str_contains($path, '/build/assets/') && 
               preg_match('/\-[a-zA-Z0-9]{8,}\.(css|js)$/', $path);
    }
}
