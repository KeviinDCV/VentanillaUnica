<x-app-layout>
    <div data-page="admin-unidades-administrativas"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('gestion.index') }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </a>
                <div>
                    <h2 class="font-light text-xl text-gray-800 leading-tight">
                        Gestión de Unidades Administrativas
                    </h2>
                    <p class="text-sm text-gray-600 font-light mt-1">
                        Administración de códigos de unidades administrativas para TRD
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <x-hospital-brand />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Información del proceso -->
                    <div class="mb-8 p-6 bg-blue-50 border-l-4 border-uniradical-blue rounded-r-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Proceso de Creación de TRD</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-uniradical-blue text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                <span class="font-medium text-gray-700">Crear códigos de Unidades Administrativas</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                <span class="text-gray-600">Agregar Series a las unidades</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                <span class="text-gray-600">Crear Sub-series para las Series</span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">
                            <strong>Paso actual:</strong> Creación de códigos de Unidades Administrativas.
                            Una vez creados, podrá agregar Series y Sub-series según lo establezca la TRD.
                        </p>
                    </div>

                    <!-- Tabla de Unidades Administrativas -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-800">Códigos de Unidades Administrativas</h3>
                                <div class="flex-1 max-w-md ml-6">
                                    <div class="relative">
                                        <input type="text"
                                               id="buscar-unidades"
                                               placeholder="Buscar por código, nombre..."
                                               class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Series</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="unidades-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach($unidades as $unidad)
                                    <tr class="unidad-row hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $unidad->codigo }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-900">{{ $unidad->nombre }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ Str::limit($unidad->descripcion, 50) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $unidad->series_count }} series
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $unidad->activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $unidad->activa ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button data-action="edit-unidad"
                                                    data-unidad-id="{{ $unidad->id }}"
                                                    data-unidad-codigo="{{ $unidad->codigo }}"
                                                    data-unidad-nombre="{{ $unidad->nombre }}"
                                                    data-unidad-descripcion="{{ $unidad->descripcion }}"
                                                    data-unidad-activa="{{ $unidad->activa ? 'true' : 'false' }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                Editar
                                            </button>
                                            <button data-action="toggle-status"
                                                    data-unidad-id="{{ $unidad->id }}"
                                                    data-unidad-nombre="{{ $unidad->nombre }}"
                                                    class="text-yellow-600 hover:text-yellow-900">
                                                {{ $unidad->activa ? 'Desactivar' : 'Activar' }}
                                            </button>
                                            @if($unidad->series_count == 0)
                                            <button data-action="delete-unidad"
                                                    data-unidad-id="{{ $unidad->id }}"
                                                    data-unidad-nombre="{{ $unidad->nombre }}"
                                                    class="text-red-600 hover:text-red-900">
                                                Eliminar
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Botón Agregar y Paginación -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <button data-action="create-unidad"
                                        class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                                    Agregar nuevo código
                                </button>
                                <div>
                                    {{ $unidades->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación a siguientes pasos -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.series.index') }}"
                           class="px-6 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                            Siguiente: Gestionar Series →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Los scripts están incluidos en el bundle principal de Vite -->
</x-app-layout>
