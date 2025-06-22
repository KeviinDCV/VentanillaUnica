<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta para refrescar token CSRF
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->middleware('web');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas del módulo de radicación de entrada
    Route::prefix('radicacion')->name('radicacion.')->group(function () {
        Route::prefix('entrada')->name('entrada.')->group(function () {
            Route::get('/', [App\Http\Controllers\RadicacionEntradaController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\RadicacionEntradaController::class, 'store'])->name('store');
            Route::get('/buscar-remitente', [App\Http\Controllers\RadicacionEntradaController::class, 'buscarRemitente'])->name('buscar-remitente');
            Route::post('/preview', [App\Http\Controllers\RadicacionEntradaController::class, 'previsualizacion'])->name('preview');
            Route::get('/{id}', [App\Http\Controllers\RadicacionEntradaController::class, 'show'])->name('show');
        });

        // Rutas del módulo de radicación interna
        Route::prefix('interna')->name('interna.')->group(function () {
            Route::get('/', [App\Http\Controllers\RadicacionInternaController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\RadicacionInternaController::class, 'store'])->name('store');
            Route::post('/preview', [App\Http\Controllers\RadicacionInternaController::class, 'previsualizacion'])->name('preview');
            Route::get('/buscar-radicados', [App\Http\Controllers\RadicacionInternaController::class, 'buscarRadicados'])->name('buscar-radicados');
            Route::get('/{id}', [App\Http\Controllers\RadicacionInternaController::class, 'show'])->name('show');
        });

        // Rutas del módulo de radicación de salida
        Route::prefix('salida')->name('salida.')->group(function () {
            Route::get('/', [App\Http\Controllers\RadicacionSalidaController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\RadicacionSalidaController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\RadicacionSalidaController::class, 'show'])->name('show');
        });
    });

    // Rutas del módulo de consultar radicados
    Route::prefix('consultar')->name('consultar.')->group(function () {
        Route::get('/', [App\Http\Controllers\ConsultarRadicadosController::class, 'index'])->name('index');
        Route::get('/exportar', [App\Http\Controllers\ConsultarRadicadosController::class, 'exportar'])->name('exportar');
    });

    // Rutas del módulo de administración (solo para administradores)
    Route::prefix('admin')->name('admin.')->middleware('role:administrador')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('index');
        Route::get('/usuarios', [App\Http\Controllers\Admin\AdminController::class, 'usuarios'])->name('usuarios');
        Route::get('/usuarios/buscar', [App\Http\Controllers\Admin\AdminController::class, 'buscarUsuarios'])->name('usuarios.buscar');
        Route::get('/usuarios/crear', [App\Http\Controllers\Admin\AdminController::class, 'crearUsuario'])->name('usuarios.crear');
        Route::post('/usuarios', [App\Http\Controllers\Admin\AdminController::class, 'guardarUsuario'])->name('usuarios.guardar');
        Route::get('/usuarios/{id}/editar', [App\Http\Controllers\Admin\AdminController::class, 'editarUsuario'])->name('usuarios.editar');
        Route::put('/usuarios/{id}', [App\Http\Controllers\Admin\AdminController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
        Route::patch('/usuarios/{id}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleUserStatus'])->name('usuarios.toggle-status');
        Route::delete('/usuarios/{id}', [App\Http\Controllers\Admin\AdminController::class, 'eliminarUsuario'])->name('usuarios.eliminar');
        Route::get('/dependencias', [App\Http\Controllers\Admin\AdminController::class, 'dependencias'])->name('dependencias');
        Route::get('/dependencias/buscar', [App\Http\Controllers\Admin\AdminController::class, 'buscarDependencias'])->name('dependencias.buscar');
        Route::post('/dependencias', [App\Http\Controllers\Admin\AdminController::class, 'guardarDependencia'])->name('dependencias.guardar');
        Route::put('/dependencias/{id}', [App\Http\Controllers\Admin\AdminController::class, 'actualizarDependencia'])->name('dependencias.actualizar');
        Route::patch('/dependencias/{id}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleDependenciaStatus'])->name('dependencias.toggle-status');
        Route::delete('/dependencias/{id}', [App\Http\Controllers\Admin\AdminController::class, 'eliminarDependencia'])->name('dependencias.eliminar');
        Route::get('/trds', [App\Http\Controllers\Admin\AdminController::class, 'trds'])->name('trds');
        Route::get('/trds/buscar', [App\Http\Controllers\Admin\AdminController::class, 'buscarTrds'])->name('trds.buscar');
        Route::post('/trds', [App\Http\Controllers\Admin\AdminController::class, 'guardarTrd'])->name('trds.guardar');
        Route::put('/trds/{id}', [App\Http\Controllers\Admin\AdminController::class, 'actualizarTrd'])->name('trds.actualizar');
        Route::patch('/trds/{id}/toggle-status', [App\Http\Controllers\Admin\AdminController::class, 'toggleTrdStatus'])->name('trds.toggle-status');
        Route::delete('/trds/{id}', [App\Http\Controllers\Admin\AdminController::class, 'eliminarTrd'])->name('trds.eliminar');
        Route::get('/reportes', [App\Http\Controllers\Admin\AdminController::class, 'reportes'])->name('reportes');
        Route::get('/logs', [App\Http\Controllers\Admin\AdminController::class, 'logs'])->name('logs');

        // Gestión de Departamentos
        Route::get('/departamentos', [App\Http\Controllers\Admin\DepartamentoController::class, 'index'])->name('departamentos.index');
        Route::post('/departamentos', [App\Http\Controllers\Admin\DepartamentoController::class, 'store'])->name('departamentos.store');
        Route::put('/departamentos/{departamento}', [App\Http\Controllers\Admin\DepartamentoController::class, 'update'])->name('departamentos.update');
        Route::delete('/departamentos/{departamento}', [App\Http\Controllers\Admin\DepartamentoController::class, 'destroy'])->name('departamentos.destroy');
        Route::patch('/departamentos/{departamento}/toggle-status', [App\Http\Controllers\Admin\DepartamentoController::class, 'toggleStatus'])->name('departamentos.toggle-status');

        // Gestión de Ciudades
        Route::get('/ciudades', [App\Http\Controllers\Admin\CiudadController::class, 'index'])->name('ciudades.index');
        Route::post('/ciudades', [App\Http\Controllers\Admin\CiudadController::class, 'store'])->name('ciudades.store');
        Route::put('/ciudades/{ciudad}', [App\Http\Controllers\Admin\CiudadController::class, 'update'])->name('ciudades.update');
        Route::delete('/ciudades/{ciudad}', [App\Http\Controllers\Admin\CiudadController::class, 'destroy'])->name('ciudades.destroy');
        Route::patch('/ciudades/{ciudad}/toggle-status', [App\Http\Controllers\Admin\CiudadController::class, 'toggleStatus'])->name('ciudades.toggle-status');

        Route::get('/suspender', [App\Http\Controllers\SistemaController::class, 'mostrarSuspension'])->name('suspender');
        Route::post('/suspender', [App\Http\Controllers\SistemaController::class, 'suspender'])->name('suspender.procesar');
    });

});

// Rutas API para obtener ciudades por departamento (para AJAX)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/ciudades/por-departamento', [App\Http\Controllers\Admin\CiudadController::class, 'porDepartamento'])->name('api.ciudades.por-departamento');
});

// Rutas del sistema de suspensión (fuera del middleware de autenticación)
Route::prefix('sistema')->name('sistema.')->group(function () {
    Route::get('/suspendido', [App\Http\Controllers\SistemaController::class, 'suspendido'])->name('suspendido');
    Route::get('/reactivar', [App\Http\Controllers\SistemaController::class, 'reactivar'])->name('reactivar');
    Route::post('/reactivar', [App\Http\Controllers\SistemaController::class, 'procesarReactivacion'])->name('reactivar.procesar');
    Route::get('/estado', [App\Http\Controllers\SistemaController::class, 'estado'])->name('estado');
});

require __DIR__.'/auth.php';
