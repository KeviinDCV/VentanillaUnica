<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TipoSolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposSolicitud = TipoSolicitud::withCount('radicados')
            ->ordenado()
            ->paginate(15);

        return view('admin.tipos-solicitud.index', compact('tiposSolicitud'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:20|unique:tipos_solicitud,codigo',
            'descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $tipoSolicitud = TipoSolicitud::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo ? strtolower($request->codigo) : null,
                'descripcion' => $request->descripcion,
                'activo' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de solicitud creado exitosamente',
                'data' => $tipoSolicitud
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear tipo de solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tipo de solicitud'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoSolicitud $tipoSolicitud)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:20|unique:tipos_solicitud,codigo,' . $tipoSolicitud->id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        try {
            $tipoSolicitud->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo ? strtolower($request->codigo) : null,
                'descripcion' => $request->descripcion,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de solicitud actualizado exitosamente',
                'data' => $tipoSolicitud->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar tipo de solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tipo de solicitud'
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(TipoSolicitud $tipoSolicitud)
    {
        try {
            $tipoSolicitud->update([
                'activo' => !$tipoSolicitud->activo
            ]);

            $estado = $tipoSolicitud->activo ? 'activado' : 'desactivado';

            return response()->json([
                'success' => true,
                'message' => "Tipo de solicitud {$estado} exitosamente",
                'data' => $tipoSolicitud->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del tipo de solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del tipo de solicitud'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoSolicitud $tipoSolicitud)
    {
        try {
            // Verificar si tiene radicados asociados
            if ($tipoSolicitud->radicados()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el tipo de solicitud porque tiene radicados asociados'
                ], 400);
            }

            $tipoSolicitud->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tipo de solicitud eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar tipo de solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tipo de solicitud'
            ], 500);
        }
    }

    /**
     * Search tipos de solicitud
     */
    public function buscar(Request $request)
    {
        $termino = $request->get('termino');
        $estado = $request->get('estado');

        $query = TipoSolicitud::withCount('radicados');

        // Aplicar filtro de búsqueda por texto si existe
        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('nombre', 'LIKE', "%{$termino}%")
                  ->orWhere('codigo', 'LIKE', "%{$termino}%")
                  ->orWhere('descripcion', 'LIKE', "%{$termino}%");
            });
        }

        // Aplicar filtro de estado si existe
        if ($estado) {
            if ($estado === 'activo') {
                $query->where('activo', true);
            } elseif ($estado === 'inactivo') {
                $query->where('activo', false);
            }
        }

        $tiposSolicitud = $query->ordenado()->get();

        return response()->json($tiposSolicitud);
    }
}
