<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Su cuenta está inactiva. Contacte al administrador.');
        }

        // Verificar permisos específicos
        switch ($permission) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'No tiene permisos de administrador para acceder a esta sección.');
                }
                break;
                
            case 'ventanilla':
                if (!$user->isVentanilla() && !$user->isAdmin()) {
                    abort(403, 'No tiene permisos para acceder a esta sección.');
                }
                break;
                
            case 'admin_only':
                if (!$user->isAdmin()) {
                    abort(403, 'Esta funcionalidad está disponible solo para administradores.');
                }
                break;
                
            case 'reports':
                if (!$user->isAdmin()) {
                    abort(403, 'No tiene permisos para acceder a los reportes del sistema.');
                }
                break;
                
            case 'user_management':
                if (!$user->isAdmin()) {
                    abort(403, 'No tiene permisos para gestionar usuarios.');
                }
                break;
                
            case 'system_config':
                if (!$user->isAdmin()) {
                    abort(403, 'No tiene permisos para configurar el sistema.');
                }
                break;
                
            default:
                abort(403, 'Permiso no reconocido.');
        }

        return $next($request);
    }
}
