<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnidadAdministrativaController extends Controller
{
    /**
     * Mostrar lista de unidades administrativas
     */
    public function index()
    {
        $unidades = UnidadAdministrativa::withCount('series')
                                      ->orderBy('codigo')
                                      ->paginate(15);

        return view('admin.unidades-administrativas.index', compact('unidades'));
    }

    /**
     * Buscar unidades administrativas
     */
    public function buscar(Request $request)
    {
        $termino = $request->get('termino', '');
        
        $unidades = UnidadAdministrativa::where(function($query) use ($termino) {
                                          $query->where('codigo', 'like', "%{$termino}%")
                                                ->orWhere('nombre', 'like', "%{$termino}%")
                                                ->orWhere('descripcion', 'like', "%{$termino}%");
                                      })
                                      ->withCount('series')
                                      ->orderBy('codigo')
                                      ->get();

        return response()->json([
            'success' => true,
            'unidades' => $unidades
        ]);
    }

    /**
     * Guardar nueva unidad administrativa
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:10|unique:unidades_administrativas,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $unidad = UnidadAdministrativa::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activa' => $request->has('activa')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unidad administrativa creada exitosamente',
            'unidad' => $unidad->load('series')
        ]);
    }

    /**
     * Actualizar unidad administrativa
     */
    public function update(Request $request, $id)
    {
        $unidad = UnidadAdministrativa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:10|unique:unidades_administrativas,codigo,' . $id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $unidad->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activa' => $request->has('activa')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unidad administrativa actualizada exitosamente',
            'unidad' => $unidad->load('series')
        ]);
    }

    /**
     * Cambiar estado de unidad administrativa
     */
    public function toggleStatus($id)
    {
        $unidad = UnidadAdministrativa::findOrFail($id);
        $unidad->activa = !$unidad->activa;
        $unidad->save();

        return response()->json([
            'success' => true,
            'message' => $unidad->activa ? 'Unidad administrativa activada' : 'Unidad administrativa desactivada',
            'activa' => $unidad->activa
        ]);
    }

    /**
     * Eliminar unidad administrativa
     */
    public function destroy($id)
    {
        $unidad = UnidadAdministrativa::findOrFail($id);

        // Verificar si tiene series asociadas
        if ($unidad->series()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la unidad administrativa porque tiene series asociadas'
            ], 422);
        }

        $unidad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unidad administrativa eliminada exitosamente'
        ]);
    }

    /**
     * Obtener unidades administrativas para select
     */
    public function paraSelect()
    {
        $unidades = UnidadAdministrativa::activas()
                                      ->orderBy('codigo')
                                      ->get(['id', 'codigo', 'nombre']);

        return response()->json([
            'success' => true,
            'unidades' => $unidades
        ]);
    }
}
