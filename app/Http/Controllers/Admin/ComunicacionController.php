<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComunicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunicaciones = Comunicacion::withCount('radicados')
            ->ordenado()
            ->paginate(15);

        return view('admin.comunicaciones.index', compact('comunicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:comunicaciones,codigo',
            'descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $comunicacion = Comunicacion::create([
                'nombre' => $request->nombre,
                'codigo' => strtolower($request->codigo),
                'descripcion' => $request->descripcion,
                'activo' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de comunicación creado exitosamente',
                'data' => $comunicacion
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear comunicación', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tipo de comunicación'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comunicacion $comunicacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'required|string|max:20|unique:comunicaciones,codigo,' . $comunicacion->id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $comunicacion->update([
                'nombre' => $request->nombre,
                'codigo' => strtolower($request->codigo),
                'descripcion' => $request->descripcion,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de comunicación actualizado exitosamente',
                'data' => $comunicacion->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar comunicación', [
                'error' => $e->getMessage(),
                'comunicacion_id' => $comunicacion->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tipo de comunicación'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comunicacion $comunicacion)
    {
        try {
            // Verificar si tiene radicados asociados
            if ($comunicacion->radicados()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el tipo de comunicación porque tiene radicados asociados'
                ], 400);
            }

            $comunicacion->delete();

            Log::info('Comunicación eliminada', [
                'comunicacion_id' => $comunicacion->id,
                'nombre' => $comunicacion->nombre,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de comunicación eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar comunicación', [
                'error' => $e->getMessage(),
                'comunicacion_id' => $comunicacion->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tipo de comunicación'
            ], 500);
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(Comunicacion $comunicacion)
    {
        try {
            $comunicacion->update([
                'activo' => !$comunicacion->activo
            ]);

            $estado = $comunicacion->activo ? 'activado' : 'desactivado';

            Log::info('Estado de comunicación cambiado', [
                'comunicacion_id' => $comunicacion->id,
                'nombre' => $comunicacion->nombre,
                'nuevo_estado' => $comunicacion->activo,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Tipo de comunicación {$estado} exitosamente",
                'data' => $comunicacion->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de comunicación', [
                'error' => $e->getMessage(),
                'comunicacion_id' => $comunicacion->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del tipo de comunicación'
            ], 500);
        }
    }
}
