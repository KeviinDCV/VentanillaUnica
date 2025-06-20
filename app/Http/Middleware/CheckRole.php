<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Su cuenta está inactiva. Contacte al administrador.');
        }

        if ($user->role !== $role) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
