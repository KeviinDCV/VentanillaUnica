<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CiudadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ciudades = Ciudad::with('departamento')
            ->ordenado()
            ->paginate(15);

        $departamentos = Departamento::activo()->ordenado()->get();

        return view('admin.ciudades.index', compact('ciudades', 'departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:10',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        // Validar que no exista la misma ciudad en el mismo departamento
        $existeCiudad = Ciudad::where('nombre', $request->nombre)
            ->where('departamento_id', $request->departamento_id)
            ->exists();

        if ($existeCiudad) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una ciudad con ese nombre en el departamento seleccionado'
            ], 400);
        }

        try {
            $ciudad = Ciudad::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'departamento_id' => $request->departamento_id,
                'activo' => true
            ]);

            $ciudad->load('departamento');

            Log::info('Ciudad creada', [
                'ciudad_id' => $ciudad->id,
                'nombre' => $ciudad->nombre,
                'departamento' => $ciudad->departamento->nombre,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ciudad creada exitosamente',
                'ciudad' => $ciudad
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear ciudad', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la ciudad'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ciudad $ciudad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:10',
            'departamento_id' => 'required|exists:departamentos,id',
            'activo' => 'boolean'
        ]);

        // Validar que no exista la misma ciudad en el mismo departamento (excluyendo la actual)
        $existeCiudad = Ciudad::where('nombre', $request->nombre)
            ->where('departamento_id', $request->departamento_id)
            ->where('id', '!=', $ciudad->id)
            ->exists();

        if ($existeCiudad) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una ciudad con ese nombre en el departamento seleccionado'
            ], 400);
        }

        try {
            $ciudad->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'departamento_id' => $request->departamento_id,
                'activo' => $request->has('activo')
            ]);

            $ciudad->load('departamento');

            Log::info('Ciudad actualizada', [
                'ciudad_id' => $ciudad->id,
                'nombre' => $ciudad->nombre,
                'departamento' => $ciudad->departamento->nombre,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ciudad actualizada exitosamente',
                'ciudad' => $ciudad
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar ciudad', [
                'error' => $e->getMessage(),
                'ciudad_id' => $ciudad->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la ciudad'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ciudad $ciudad)
    {
        try {
            $nombre = $ciudad->nombre;
            $departamento = $ciudad->departamento->nombre;
            $ciudad->delete();

            Log::info('Ciudad eliminada', [
                'ciudad_nombre' => $nombre,
                'departamento' => $departamento,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ciudad eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar ciudad', [
                'error' => $e->getMessage(),
                'ciudad_id' => $ciudad->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la ciudad'
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Ciudad $ciudad)
    {
        try {
            $ciudad->update(['activo' => !$ciudad->activo]);

            Log::info('Estado de ciudad cambiado', [
                'ciudad_id' => $ciudad->id,
                'nuevo_estado' => $ciudad->activo,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'activo' => $ciudad->activo
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de ciudad', [
                'error' => $e->getMessage(),
                'ciudad_id' => $ciudad->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado'
            ], 500);
        }
    }

    /**
     * Get cities by department (for AJAX)
     */
    public function porDepartamento(Request $request)
    {
        $departamentoId = $request->get('departamento_id');

        if (!$departamentoId) {
            return response()->json([]);
        }

        $ciudades = Ciudad::activo()
            ->porDepartamento($departamentoId)
            ->ordenado()
            ->get(['id', 'nombre']);

        return response()->json($ciudades);
    }
}
