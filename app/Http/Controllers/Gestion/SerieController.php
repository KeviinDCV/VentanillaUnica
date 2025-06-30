<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SerieController extends Controller
{
    /**
     * Mostrar lista de series
     */
    public function index()
    {
        $series = Serie::with(['unidadAdministrativa'])
                      ->withCount('subseries')
                      ->orderBy('unidad_administrativa_id')
                      ->orderBy('numero_serie')
                      ->paginate(15);

        $unidades = UnidadAdministrativa::activas()->orderBy('codigo')->get();

        return view('gestion.series.index', compact('series', 'unidades'));
    }

    /**
     * Buscar series
     */
    public function buscar(Request $request)
    {
        $termino = $request->get('termino', '');
        $unidadId = $request->get('unidad_id', '');

        $query = Serie::with(['unidadAdministrativa'])
                     ->withCount('subseries');

        if (!empty($termino)) {
            $query->where(function($q) use ($termino) {
                $q->where('numero_serie', 'like', "%{$termino}%")
                  ->orWhere('nombre', 'like', "%{$termino}%")
                  ->orWhere('descripcion', 'like', "%{$termino}%");
            });
        }

        if (!empty($unidadId)) {
            $query->where('unidad_administrativa_id', $unidadId);
        }

        $series = $query->orderBy('unidad_administrativa_id')
                       ->orderBy('numero_serie')
                       ->get();

        return response()->json([
            'success' => true,
            'series' => $series
        ]);
    }

    /**
     * Guardar nueva serie
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unidad_administrativa_id' => 'required|exists:unidades_administrativas,id',
            'numero_serie' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'dias_respuesta' => 'nullable|integer|min:1|max:365',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que no exista otra serie con el mismo número en la misma unidad
        $existeSerie = Serie::where('unidad_administrativa_id', $request->unidad_administrativa_id)
                           ->where('numero_serie', $request->numero_serie)
                           ->exists();

        if ($existeSerie) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una serie con este número en la unidad administrativa seleccionada'
            ], 422);
        }

        $serie = Serie::create([
            'unidad_administrativa_id' => $request->unidad_administrativa_id,
            'numero_serie' => $request->numero_serie,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dias_respuesta' => $request->dias_respuesta,
            'activa' => $request->has('activa')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Serie creada exitosamente',
            'serie' => $serie->load(['unidadAdministrativa', 'subseries'])
        ]);
    }

    /**
     * Actualizar serie
     */
    public function update(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'unidad_administrativa_id' => 'required|exists:unidades_administrativas,id',
            'numero_serie' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'dias_respuesta' => 'nullable|integer|min:1|max:365',
            'activa' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que no exista otra serie con el mismo número en la misma unidad (excluyendo la actual)
        $existeSerie = Serie::where('unidad_administrativa_id', $request->unidad_administrativa_id)
                           ->where('numero_serie', $request->numero_serie)
                           ->where('id', '!=', $id)
                           ->exists();

        if ($existeSerie) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe otra serie con este número en la unidad administrativa seleccionada'
            ], 422);
        }

        $serie->update([
            'unidad_administrativa_id' => $request->unidad_administrativa_id,
            'numero_serie' => $request->numero_serie,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dias_respuesta' => $request->dias_respuesta,
            'activa' => $request->has('activa')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Serie actualizada exitosamente',
            'serie' => $serie->load(['unidadAdministrativa', 'subseries'])
        ]);
    }

    /**
     * Cambiar estado de serie
     */
    public function toggleStatus($id)
    {
        $serie = Serie::findOrFail($id);
        $serie->activa = !$serie->activa;
        $serie->save();

        return response()->json([
            'success' => true,
            'message' => $serie->activa ? 'Serie activada' : 'Serie desactivada',
            'activa' => $serie->activa
        ]);
    }

    /**
     * Eliminar serie
     */
    public function destroy($id)
    {
        $serie = Serie::findOrFail($id);

        // Verificar si tiene subseries asociadas
        if ($serie->subseries()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la serie porque tiene subseries asociadas'
            ], 422);
        }

        $serie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Serie eliminada exitosamente'
        ]);
    }

    /**
     * Obtener series por unidad administrativa
     */
    public function porUnidad($unidadId)
    {
        $series = Serie::where('unidad_administrativa_id', $unidadId)
                      ->activas()
                      ->orderBy('numero_serie')
                      ->get(['id', 'numero_serie', 'nombre', 'dias_respuesta']);

        return response()->json([
            'success' => true,
            'series' => $series
        ]);
    }
}
