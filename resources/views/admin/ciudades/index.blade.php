<x-app-layout>
    <div data-page="admin-ciudades"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Ciudades
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de ciudades del sistema
                </p>
            </div>
            <button id="btn-crear-ciudad" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Ciudad
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Búsqueda en tiempo real -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   id="buscar-ciudad" 
                                   placeholder="Buscar ciudades..." 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>
                        <div class="flex gap-2">
                            <select id="filtro-departamento" class="border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos los departamentos</option>
                                @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                @endforeach
                            </select>
                            <select id="filtro-estado" class="border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos los estados</option>
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de ciudades -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ciudad
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Departamento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabla-ciudades" class="bg-white divide-y divide-gray-200">
                            @foreach($ciudades as $ciudad)
                            <tr data-id="{{ $ciudad->id }}" data-departamento-id="{{ $ciudad->departamento_id }}" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $ciudad->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $ciudad->departamento->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $ciudad->codigo ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ciudad->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $ciudad->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button class="btn-editar text-uniradical-blue hover:text-blue-900" 
                                                data-id="{{ $ciudad->id }}"
                                                data-nombre="{{ $ciudad->nombre }}"
                                                data-codigo="{{ $ciudad->codigo }}"
                                                data-departamento-id="{{ $ciudad->departamento_id }}"
                                                data-activo="{{ $ciudad->activo ? 'true' : 'false' }}"
                                                title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button class="btn-toggle-estado text-{{ $ciudad->activo ? 'red' : 'green' }}-600 hover:text-{{ $ciudad->activo ? 'red' : 'green' }}-900" 
                                                data-id="{{ $ciudad->id }}"
                                                title="{{ $ciudad->activo ? 'Desactivar' : 'Activar' }}">
                                            @if($ciudad->activo)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                        <button class="btn-eliminar text-red-600 hover:text-red-900" 
                                                data-id="{{ $ciudad->id }}"
                                                data-nombre="{{ $ciudad->nombre }}"
                                                title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($ciudades->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $ciudades->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar ciudad -->
    <div id="modal-ciudad" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">Nueva Ciudad</h3>
                    <button id="btn-cerrar-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="form-ciudad">
                    <input type="hidden" id="ciudad-id">
                    
                    <div class="mb-4">
                        <label for="ciudad-departamento" class="block text-sm font-medium text-gray-700 mb-2">
                            Departamento <span class="text-red-500">*</span>
                        </label>
                        <select id="ciudad-departamento" 
                                name="departamento_id" 
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            <option value="">Seleccionar departamento...</option>
                            @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="ciudad-nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Ciudad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="ciudad-nombre" 
                               name="nombre" 
                               required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Ej: Cali">
                    </div>
                    
                    <div class="mb-4">
                        <label for="ciudad-codigo" class="block text-sm font-medium text-gray-700 mb-2">
                            Código (Opcional)
                        </label>
                        <input type="text" 
                               id="ciudad-codigo" 
                               name="codigo" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Ej: 76001">
                    </div>
                    
                    <div class="mb-6" id="campo-activo" style="display: none;">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   id="ciudad-activo" 
                                   name="activo" 
                                   class="rounded border-gray-300 text-uniradical-blue shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Ciudad activa</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="btn-cancelar" class="btn-neutral">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-guardar" class="btn-primary">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="modal-confirmacion" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="confirmacion-titulo">Confirmar Eliminación</h3>
                <p class="text-sm text-gray-500 mb-4" id="confirmacion-mensaje"></p>
                <div class="flex justify-center space-x-3">
                    <button id="btn-cancelar-confirmacion" class="btn-neutral">Cancelar</button>
                    <button id="btn-confirmar-accion" class="btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Variables globales
        let ciudadIdParaEliminar = null;
        let accionConfirmacion = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeCiudadesManagement();
        });

        function initializeCiudadesManagement() {
            // Event listeners para botones
            document.getElementById('btn-crear-ciudad').addEventListener('click', abrirModalCrear);
            document.getElementById('btn-cerrar-modal').addEventListener('click', cerrarModal);
            document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
            document.getElementById('form-ciudad').addEventListener('submit', guardarCiudad);
            
            // Event listeners para confirmación
            document.getElementById('btn-cancelar-confirmacion').addEventListener('click', cerrarModalConfirmacion);
            document.getElementById('btn-confirmar-accion').addEventListener('click', ejecutarAccionConfirmada);
            
            // Event listeners para tabla
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-editar')) {
                    const btn = e.target.closest('.btn-editar');
                    abrirModalEditar(btn);
                } else if (e.target.closest('.btn-eliminar')) {
                    const btn = e.target.closest('.btn-eliminar');
                    confirmarEliminacion(btn);
                } else if (e.target.closest('.btn-toggle-estado')) {
                    const btn = e.target.closest('.btn-toggle-estado');
                    toggleEstado(btn);
                }
            });
            
            // Búsqueda en tiempo real
            document.getElementById('buscar-ciudad').addEventListener('input', filtrarTabla);
            document.getElementById('filtro-departamento').addEventListener('change', filtrarTabla);
            document.getElementById('filtro-estado').addEventListener('change', filtrarTabla);
        }

        function abrirModalCrear() {
            document.getElementById('modal-title').textContent = 'Nueva Ciudad';
            document.getElementById('form-ciudad').reset();
            document.getElementById('ciudad-id').value = '';
            document.getElementById('campo-activo').style.display = 'none';
            document.getElementById('modal-ciudad').classList.remove('hidden');
        }

        function abrirModalEditar(btn) {
            document.getElementById('modal-title').textContent = 'Editar Ciudad';
            document.getElementById('ciudad-id').value = btn.dataset.id;
            document.getElementById('ciudad-nombre').value = btn.dataset.nombre;
            document.getElementById('ciudad-codigo').value = btn.dataset.codigo || '';
            document.getElementById('ciudad-departamento').value = btn.dataset.departamentoId;
            document.getElementById('ciudad-activo').checked = btn.dataset.activo === 'true';
            document.getElementById('campo-activo').style.display = 'block';
            document.getElementById('modal-ciudad').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-ciudad').classList.add('hidden');
        }

        function cerrarModalConfirmacion() {
            document.getElementById('modal-confirmacion').classList.add('hidden');
            ciudadIdParaEliminar = null;
            accionConfirmacion = null;
        }

        async function guardarCiudad(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const ciudadId = document.getElementById('ciudad-id').value;
            const isEdit = ciudadId !== '';
            
            const data = {
                nombre: formData.get('nombre'),
                codigo: formData.get('codigo') || null,
                departamento_id: formData.get('departamento_id'),
            };
            
            if (isEdit) {
                data.activo = document.getElementById('ciudad-activo').checked;
            }
            
            try {
                const url = isEdit ? `/admin/ciudades/${ciudadId}` : '/admin/ciudades';
                const method = isEdit ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.UniRadicNotifications.success('Éxito', result.message);
                    cerrarModal();
                    location.reload(); // Recargar para mostrar cambios
                } else {
                    window.UniRadicNotifications.error('Error', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                window.UniRadicNotifications.error('Error', 'Error al procesar la solicitud');
            }
        }

        function confirmarEliminacion(btn) {
            ciudadIdParaEliminar = btn.dataset.id;
            accionConfirmacion = 'eliminar';
            document.getElementById('confirmacion-titulo').textContent = 'Confirmar Eliminación';
            document.getElementById('confirmacion-mensaje').textContent = `¿Está seguro de que desea eliminar la ciudad "${btn.dataset.nombre}"?`;
            document.getElementById('btn-confirmar-accion').textContent = 'Eliminar';
            document.getElementById('btn-confirmar-accion').className = 'btn-danger';
            document.getElementById('modal-confirmacion').classList.remove('hidden');
        }

        async function ejecutarAccionConfirmada() {
            if (accionConfirmacion === 'eliminar' && ciudadIdParaEliminar) {
                try {
                    const response = await fetch(`/admin/ciudades/${ciudadIdParaEliminar}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        window.UniRadicNotifications.success('Éxito', result.message);
                        location.reload();
                    } else {
                        window.UniRadicNotifications.error('Error', result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    window.UniRadicNotifications.error('Error', 'Error al eliminar la ciudad');
                }
            }
            cerrarModalConfirmacion();
        }

        async function toggleEstado(btn) {
            const ciudadId = btn.dataset.id;
            
            try {
                const response = await fetch(`/admin/ciudades/${ciudadId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.UniRadicNotifications.success('Éxito', result.message);
                    location.reload();
                } else {
                    window.UniRadicNotifications.error('Error', result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                window.UniRadicNotifications.error('Error', 'Error al cambiar el estado');
            }
        }

        function filtrarTabla() {
            const busqueda = document.getElementById('buscar-ciudad').value.toLowerCase();
            const filtroDepartamento = document.getElementById('filtro-departamento').value;
            const filtroEstado = document.getElementById('filtro-estado').value;
            const filas = document.querySelectorAll('#tabla-ciudades tr');
            
            filas.forEach(fila => {
                const nombre = fila.querySelector('td:first-child .text-gray-900')?.textContent.toLowerCase() || '';
                const departamento = fila.querySelector('td:nth-child(2) .text-gray-500')?.textContent.toLowerCase() || '';
                const departamentoId = fila.dataset.departamentoId;
                const estadoElement = fila.querySelector('.bg-green-100, .bg-red-100');
                const esActivo = estadoElement?.classList.contains('bg-green-100');
                
                const coincideBusqueda = nombre.includes(busqueda) || departamento.includes(busqueda);
                const coincideDepartamento = filtroDepartamento === '' || departamentoId === filtroDepartamento;
                const coincidefiltro = filtroEstado === '' || 
                    (filtroEstado === '1' && esActivo) || 
                    (filtroEstado === '0' && !esActivo);
                
                fila.style.display = coincideBusqueda && coincideDepartamento && coincidefiltro ? '' : 'none';
            });
        }
    </script>
    @endpush
</x-app-layout>
