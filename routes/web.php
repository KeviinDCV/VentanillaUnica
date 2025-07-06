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
    ->middleware(['auth'])
    ->name('dashboard');

// Radicación - Vista principal con consulta integrada
Route::get('/radicacion', [App\Http\Controllers\RadicacionController::class, 'index'])
    ->middleware(['auth'])
    ->name('radicacion.index');

// Radicación - Exportar consulta
Route::get('/radicacion/exportar', [App\Http\Controllers\RadicacionController::class, 'exportar'])
    ->middleware(['auth'])
    ->name('radicacion.exportar');

// Radicación - Cargar formularios para modal
Route::get('/radicacion/form/{tipo}', [App\Http\Controllers\RadicacionController::class, 'cargarFormulario'])
    ->middleware(['auth'])
    ->name('radicacion.form');

// Gestión - Vista principal (solo administradores)
Route::get('/gestion', [App\Http\Controllers\GestionController::class, 'index'])
    ->middleware(['auth'])
    ->name('gestion.index');

// Rutas del módulo de gestión (solo para administradores)
Route::prefix('gestion')->name('gestion.')->middleware(['auth', 'role:administrador', 'admin.security'])->group(function () {
    // Gestión de Series
    Route::get('/series', [App\Http\Controllers\Gestion\SerieController::class, 'index'])->name('series.index');
    Route::post('/series', [App\Http\Controllers\Gestion\SerieController::class, 'store'])->name('series.store');
    Route::put('/series/{serie}', [App\Http\Controllers\Gestion\SerieController::class, 'update'])->name('series.update');
    Route::delete('/series/{serie}', [App\Http\Controllers\Gestion\SerieController::class, 'destroy'])->name('series.destroy');
    Route::patch('/series/{serie}/toggle-status', [App\Http\Controllers\Gestion\SerieController::class, 'toggleStatus'])->name('series.toggle-status');
    Route::get('/series/buscar', [App\Http\Controllers\Gestion\SerieController::class, 'buscar'])->name('series.buscar');
    Route::get('/series/por-unidad/{unidad}', [App\Http\Controllers\Gestion\SerieController::class, 'porUnidad'])->name('series.por-unidad');
});

// Sistema - Vista principal
Route::get('/sistema', [App\Http\Controllers\SistemaController::class, 'index'])
    ->middleware(['auth'])
    ->name('sistema.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas del módulo de radicación de entrada
    Route::prefix('radicacion')->name('radicacion.')->group(function () {
        Route::prefix('entrada')->name('entrada.')->group(function () {
            Route::post('/', [App\Http\Controllers\RadicacionEntradaController::class, 'store'])->name('store');
            Route::get('/buscar-remitente', [App\Http\Controllers\RadicacionEntradaController::class, 'buscarRemitente'])->name('buscar-remitente');
            Route::post('/preview', [App\Http\Controllers\RadicacionEntradaController::class, 'previsualizacion'])->name('preview');
            Route::get('/{id}', [App\Http\Controllers\RadicacionEntradaController::class, 'show'])->name('show');
        });

        // Rutas del módulo de radicación interna
        Route::prefix('interna')->name('interna.')->group(function () {
            Route::post('/', [App\Http\Controllers\RadicacionInternaController::class, 'store'])->name('store');
            Route::post('/preview', [App\Http\Controllers\RadicacionInternaController::class, 'previsualizacion'])->name('preview');
            Route::get('/buscar-radicados', [App\Http\Controllers\RadicacionInternaController::class, 'buscarRadicados'])->name('buscar-radicados');
            Route::get('/{id}', [App\Http\Controllers\RadicacionInternaController::class, 'show'])->name('show');
        });

        // Rutas del módulo de radicación de salida
        Route::prefix('salida')->name('salida.')->group(function () {
            Route::post('/', [App\Http\Controllers\RadicacionSalidaController::class, 'store'])->name('store');
            Route::post('/preview', [App\Http\Controllers\RadicacionSalidaController::class, 'preview'])->name('preview');
            Route::get('/{id}', [App\Http\Controllers\RadicacionSalidaController::class, 'show'])->name('show');
        });

        // Rutas para finalización de radicados
        Route::post('/finalizar', [App\Http\Controllers\RadicacionController::class, 'finalizar'])->name('finalizar');
        Route::post('/upload-digitalized', [App\Http\Controllers\RadicacionController::class, 'uploadDigitalized'])->name('upload-digitalized');

        // Ruta para obtener detalles del radicado (AJAX)
        Route::get('/{id}/detalles', [App\Http\Controllers\RadicacionController::class, 'detalles'])->name('detalles');

        // Rutas para edición de radicados
        Route::get('/{id}/editar', [App\Http\Controllers\RadicacionController::class, 'editar'])->name('editar');
        Route::put('/{id}', [App\Http\Controllers\RadicacionController::class, 'actualizar'])->name('actualizar');

        // Ruta para eliminar radicados (solo administradores)
        Route::delete('/{id}', [App\Http\Controllers\RadicacionController::class, 'destroy'])->name('destroy')->middleware('role:administrador');
    });



    // Rutas del módulo de administración (solo para administradores)
    Route::prefix('admin')->name('admin.')->middleware(['role:administrador', 'admin.security'])->group(function () {
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

        Route::get('/reportes', [App\Http\Controllers\Admin\AdminController::class, 'reportes'])->name('reportes');
        Route::get('/actividad-modal', [App\Http\Controllers\Admin\AdminController::class, 'obtenerActividadModal'])->name('actividad.modal');

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

        // Gestión de Tipos de Solicitud
        Route::get('/tipos-solicitud', [App\Http\Controllers\Admin\TipoSolicitudController::class, 'index'])->name('tipos-solicitud.index');
        Route::post('/tipos-solicitud', [App\Http\Controllers\Admin\TipoSolicitudController::class, 'store'])->name('tipos-solicitud.store');
        Route::put('/tipos-solicitud/{tipoSolicitud}', [App\Http\Controllers\Admin\TipoSolicitudController::class, 'update'])->name('tipos-solicitud.update');
        Route::delete('/tipos-solicitud/{tipoSolicitud}', [App\Http\Controllers\Admin\TipoSolicitudController::class, 'destroy'])->name('tipos-solicitud.destroy');
        Route::patch('/tipos-solicitud/{tipoSolicitud}/toggle-status', [App\Http\Controllers\Admin\TipoSolicitudController::class, 'toggleStatus'])->name('tipos-solicitud.toggle-status');
        Route::get('/tipos-solicitud/buscar', [App\Http\Controllers\Admin\TipoSolicitudController::class, 'buscar'])->name('tipos-solicitud.buscar');

        // Gestión de Unidades Administrativas
        Route::get('/unidades-administrativas', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'index'])->name('unidades-administrativas.index');
        Route::post('/unidades-administrativas', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'store'])->name('unidades-administrativas.store');
        Route::put('/unidades-administrativas/{unidad}', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'update'])->name('unidades-administrativas.update');
        Route::delete('/unidades-administrativas/{unidad}', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'destroy'])->name('unidades-administrativas.destroy');
        Route::patch('/unidades-administrativas/{unidad}/toggle-status', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'toggleStatus'])->name('unidades-administrativas.toggle-status');
        Route::get('/unidades-administrativas/buscar', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'buscar'])->name('unidades-administrativas.buscar');
        Route::get('/unidades-administrativas/para-select', [App\Http\Controllers\Admin\UnidadAdministrativaController::class, 'paraSelect'])->name('unidades-administrativas.para-select');




        // Gestión de Subseries
        Route::get('/subseries', [App\Http\Controllers\Admin\SubserieController::class, 'index'])->name('subseries.index');
        Route::post('/subseries', [App\Http\Controllers\Admin\SubserieController::class, 'store'])->name('subseries.store');
        Route::put('/subseries/{subserie}', [App\Http\Controllers\Admin\SubserieController::class, 'update'])->name('subseries.update');
        Route::delete('/subseries/{subserie}', [App\Http\Controllers\Admin\SubserieController::class, 'destroy'])->name('subseries.destroy');
        Route::patch('/subseries/{subserie}/toggle-status', [App\Http\Controllers\Admin\SubserieController::class, 'toggleStatus'])->name('subseries.toggle-status');
        Route::get('/subseries/buscar', [App\Http\Controllers\Admin\SubserieController::class, 'buscar'])->name('subseries.buscar');
        Route::get('/subseries/por-serie/{serie}', [App\Http\Controllers\Admin\SubserieController::class, 'porSerie'])->name('subseries.por-serie');
        Route::get('/subseries/series-por-unidad/{unidad}', [App\Http\Controllers\Admin\SubserieController::class, 'seriesPorUnidad'])->name('subseries.series-por-unidad');
        Route::get('/subseries/buscar-series', [App\Http\Controllers\Admin\SubserieController::class, 'buscarSeries'])->name('subseries.buscar-series');

        // Gestión de Comunicaciones
        Route::get('/comunicaciones', [App\Http\Controllers\Admin\ComunicacionController::class, 'index'])->name('comunicaciones.index');
        Route::post('/comunicaciones', [App\Http\Controllers\Admin\ComunicacionController::class, 'store'])->name('comunicaciones.store');
        Route::put('/comunicaciones/{comunicacion}', [App\Http\Controllers\Admin\ComunicacionController::class, 'update'])->name('comunicaciones.update');
        Route::delete('/comunicaciones/{comunicacion}', [App\Http\Controllers\Admin\ComunicacionController::class, 'destroy'])->name('comunicaciones.destroy');
        Route::patch('/comunicaciones/{comunicacion}/toggle-active', [App\Http\Controllers\Admin\ComunicacionController::class, 'toggleActive'])->name('comunicaciones.toggle-active');

        // Gestión de Remitentes
        Route::get('/remitentes', [App\Http\Controllers\Admin\RemitentesController::class, 'index'])->name('remitentes.index');
        Route::post('/remitentes', [App\Http\Controllers\Admin\RemitentesController::class, 'store'])->name('remitentes.store');
        Route::put('/remitentes/{remitente}', [App\Http\Controllers\Admin\RemitentesController::class, 'update'])->name('remitentes.update');
        Route::delete('/remitentes/{remitente}', [App\Http\Controllers\Admin\RemitentesController::class, 'destroy'])->name('remitentes.destroy');
        Route::patch('/remitentes/{remitente}/toggle-estado', [App\Http\Controllers\Admin\RemitentesController::class, 'toggleEstado'])->name('remitentes.toggle-estado');

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
