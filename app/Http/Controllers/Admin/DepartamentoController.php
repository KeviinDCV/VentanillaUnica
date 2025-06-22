<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = Departamento::withCount('ciudades')
            ->ordenado()
            ->paginate(15);

        return view('admin.departamentos.index', compact('departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'codigo' => 'nullable|string|max:10|unique:departamentos,codigo',
        ]);

        try {
            $departamento = Departamento::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'activo' => true
            ]);

            Log::info('Departamento creado', [
                'departamento_id' => $departamento->id,
                'nombre' => $departamento->nombre,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departamento creado exitosamente',
                'departamento' => $departamento
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear departamento', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el departamento'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $departamento->id,
            'codigo' => 'nullable|string|max:10|unique:departamentos,codigo,' . $departamento->id,
            'activo' => 'boolean'
        ]);

        try {
            $departamento->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'activo' => $request->has('activo')
            ]);

            Log::info('Departamento actualizado', [
                'departamento_id' => $departamento->id,
                'nombre' => $departamento->nombre,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departamento actualizado exitosamente',
                'departamento' => $departamento
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar departamento', [
                'error' => $e->getMessage(),
                'departamento_id' => $departamento->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el departamento'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departamento $departamento)
    {
        try {
            // Verificar si tiene ciudades asociadas
            if ($departamento->ciudades()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el departamento porque tiene ciudades asociadas'
                ], 400);
            }

            $nombre = $departamento->nombre;
            $departamento->delete();

            Log::info('Departamento eliminado', [
                'departamento_nombre' => $nombre,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Departamento eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar departamento', [
                'error' => $e->getMessage(),
                'departamento_id' => $departamento->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el departamento'
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Departamento $departamento)
    {
        try {
            $departamento->update(['activo' => !$departamento->activo]);

            Log::info('Estado de departamento cambiado', [
                'departamento_id' => $departamento->id,
                'nuevo_estado' => $departamento->activo,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'activo' => $departamento->activo
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de departamento', [
                'error' => $e->getMessage(),
                'departamento_id' => $departamento->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado'
            ], 500);
        }
    }
}
