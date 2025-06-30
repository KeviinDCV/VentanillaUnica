<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subserie;
use App\Models\Serie;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubserieController extends Controller
{
    /**
     * Mostrar lista de subseries
     */
    public function index()
    {
        $subseries = Subserie::with(['serie.unidadAdministrativa'])
                            ->orderBy('serie_id')
                            ->orderBy('numero_subserie')
                            ->paginate(15);

        $unidades = UnidadAdministrativa::activas()->orderBy('codigo')->get();
        $series = Serie::activas()->with('unidadAdministrativa')->orderBy('unidad_administrativa_id')->orderBy('numero_serie')->get();

        return view('admin.subseries.index', compact('subseries', 'unidades', 'series'));
    }

    /**
     * Buscar subseries
     */
    public function buscar(Request $request)
    {
        $termino = $request->get('termino', '');
        $serieId = $request->get('serie_id');
        $unidadId = $request->get('unidad_id');

        $query = Subserie::with(['serie.unidadAdministrativa']);

        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('numero_subserie', 'like', "%{$termino}%")
                  ->orWhere('nombre', 'like', "%{$termino}%")
                  ->orWhere('descripcion', 'like', "%{$termino}%")
                  ->orWhereHas('serie', function($subq) use ($termino) {
                      $subq->where('numero_serie', 'like', "%{$termino}%")
                           ->orWhere('nombre', 'like', "%{$termino}%")
                           ->orWhereHas('unidadAdministrativa', function($subsubq) use ($termino) {
                               $subsubq->where('codigo', 'like', "%{$termino}%")
                                       ->orWhere('nombre', 'like', "%{$termino}%");
                           });
                  });
            });
        }

        if ($serieId) {
            $query->where('serie_id', $serieId);
        }

        if ($unidadId) {
            $query->whereHas('serie', function($q) use ($unidadId) {
                $q->where('unidad_administrativa_id', $unidadId);
            });
        }

        $subseries = $query->orderBy('serie_id')
                          ->orderBy('numero_subserie')
                          ->get();

        return response()->json([
            'success' => true,
            'subseries' => $subseries
        ]);
    }

    /**
     * Guardar nueva subserie
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serie_id' => 'required|exists:series,id',
            'numero_subserie' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'dias_respuesta' => 'nullable|integer|min:1|max:365',
            'activa' => 'boolean'
        ]);

        // Validar que no exista la misma subserie en la misma serie
        $validator->after(function ($validator) use ($request) {
            $existe = Subserie::where('serie_id', $request->serie_id)
                             ->where('numero_subserie', $request->numero_subserie)
                             ->exists();

            if ($existe) {
                $validator->errors()->add('numero_subserie', 'Ya existe una subserie con este número en la serie seleccionada.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subserie = Subserie::create([
            'serie_id' => $request->serie_id,
            'numero_subserie' => $request->numero_subserie,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dias_respuesta' => $request->dias_respuesta,
            'activa' => $request->has('activa')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subserie creada exitosamente',
            'subserie' => $subserie->load('serie.unidadAdministrativa')
        ]);
    }

    /**
     * Actualizar subserie
     */
    public function update(Request $request, $id)
    {
        $subserie = Subserie::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'serie_id' => 'required|exists:series,id',
            'numero_subserie' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'dias_respuesta' => 'nullable|integer|min:1|max:365',
            'activa' => 'boolean'
        ]);

        // Validar que no exista la misma subserie en la misma serie (excluyendo la actual)
        $validator->after(function ($validator) use ($request, $id) {
            $existe = Subserie::where('serie_id', $request->serie_id)
                             ->where('numero_subserie', $request->numero_subserie)
                             ->where('id', '!=', $id)
                             ->exists();

            if ($existe) {
                $validator->errors()->add('numero_subserie', 'Ya existe una subserie con este número en la serie seleccionada.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subserie->update([
            'serie_id' => $request->serie_id,
            'numero_subserie' => $request->numero_subserie,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dias_respuesta' => $request->dias_respuesta,
            'activa' => $request->has('activa')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subserie actualizada exitosamente',
            'subserie' => $subserie->load('serie.unidadAdministrativa')
        ]);
    }

    /**
     * Cambiar estado de subserie
     */
    public function toggleStatus($id)
    {
        $subserie = Subserie::findOrFail($id);
        $subserie->activa = !$subserie->activa;
        $subserie->save();

        return response()->json([
            'success' => true,
            'message' => $subserie->activa ? 'Subserie activada' : 'Subserie desactivada',
            'activa' => $subserie->activa
        ]);
    }

    /**
     * Eliminar subserie
     */
    public function destroy($id)
    {
        $subserie = Subserie::findOrFail($id);
        $subserie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subserie eliminada exitosamente'
        ]);
    }

    /**
     * Obtener subseries por serie
     */
    public function porSerie($serieId)
    {
        $subseries = Subserie::where('serie_id', $serieId)
                            ->activas()
                            ->orderBy('numero_subserie')
                            ->get(['id', 'numero_subserie', 'nombre', 'dias_respuesta']);

        return response()->json([
            'success' => true,
            'subseries' => $subseries
        ]);
    }

    /**
     * Obtener series por unidad administrativa
     */
    public function seriesPorUnidad($unidadId)
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

    /**
     * Buscar todas las series
     */
    public function buscarSeries(Request $request)
    {
        $termino = $request->get('termino', '');
        $unidadId = $request->get('unidad_id', '');

        $query = Serie::with(['unidadAdministrativa'])
                     ->activas();

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
}
