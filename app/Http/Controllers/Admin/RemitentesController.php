<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Remitente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RemitentesController extends Controller
{
    public function index(Request $request)
    {
        $query = Remitente::query();

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre_completo', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%")
                  ->orWhere('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('entidad', 'like', "%{$buscar}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $remitentes = $query->orderBy('nombre_completo')->paginate(15);

        return view('admin.remitentes.index', compact('remitentes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'tipo' => 'required|in:anonimo,registrado',
            'tipo_documento' => 'required|in:CC,CE,TI,PP,NIT,OTRO',
            'numero_documento' => 'required|string|max:20|unique:remitentes,numero_documento',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'entidad' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $remitente = Remitente::create([
                'tipo' => $request->tipo,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'departamento' => $request->departamento,
                'entidad' => $request->entidad,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Remitente creado exitosamente',
                'remitente' => $remitente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el remitente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $remitente = Remitente::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'tipo' => 'required|in:anonimo,registrado',
            'tipo_documento' => 'required|in:CC,CE,TI,PP,NIT,OTRO',
            'numero_documento' => 'required|string|max:20|unique:remitentes,numero_documento,' . $id,
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'entidad' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $remitente->update([
                'tipo' => $request->tipo,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nombre_completo' => $request->nombre_completo,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'departamento' => $request->departamento,
                'entidad' => $request->entidad,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Remitente actualizado exitosamente',
                'remitente' => $remitente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el remitente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleEstado($id)
    {
        // Por ahora, esta funcionalidad no está disponible ya que la tabla remitentes
        // no tiene una columna de estado activo/inactivo
        return response()->json([
            'success' => false,
            'message' => 'La funcionalidad de activar/desactivar no está disponible para remitentes'
        ], 400);
    }

    public function destroy($id)
    {
        try {
            $remitente = Remitente::findOrFail($id);
            
            // Verificar si el remitente tiene radicados asociados
            if ($remitente->radicados()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el remitente porque tiene radicados asociados'
                ], 400);
            }

            $remitente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Remitente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el remitente: ' . $e->getMessage()
            ], 500);
        }
    }
}
