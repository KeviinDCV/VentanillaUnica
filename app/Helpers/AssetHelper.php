<?php

namespace App\Helpers;

class AssetHelper
{
    /**
     * Generar URL de asset forzando HTTP en desarrollo
     */
    public static function asset(string $path): string
    {
        $url = asset($path);
        
        // En desarrollo, forzar HTTP
        if (app()->environment('local')) {
            $url = str_replace('https://', 'http://', $url);
        }
        
        return $url;
    }
    
    /**
     * Generar URL de imagen forzando HTTP en desarrollo
     */
    public static function image(string $path): string
    {
        return self::asset("images/{$path}");
    }
    
    /**
     * Generar URL de build asset forzando HTTP en desarrollo
     */
    public static function build(string $path): string
    {
        return self::asset("build/{$path}");
    }
}
