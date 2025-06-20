<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para verificar sesión (sin autenticación requerida para evitar loops)
// Nota: Las rutas API automáticamente tienen el prefijo /api/
Route::get('/verify-session', function (Request $request) {
    if (auth()->check()) {
        return response()->json([
            'authenticated' => true,
            'user_id' => auth()->id(),
            'timestamp' => now()->timestamp
        ]);
    }

    return response()->json([
        'authenticated' => false,
        'message' => 'Session expired'
    ], 401);
})->middleware('web');
