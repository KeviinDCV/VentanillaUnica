<x-app-layout>
    <div data-page="admin-series"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Series
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de Series para las Unidades Administrativas
                </p>
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
                    <div class="mb-8 p-6 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Proceso de Creación de TRD</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</span>
                                <span class="text-gray-600">Códigos de Unidades Administrativas creados</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-uniradical-blue text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                <span class="font-medium text-gray-700">Agregar Series a las unidades</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                <span class="text-gray-600">Crear Sub-series para las Series</span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">
                            <strong>Paso actual:</strong> Agregar Series a las Unidades Administrativas.
                            Ejemplo: Código: 100. Número de Serie: 11. Nombre Serie: PQRS.
                        </p>
                    </div>

                    <!-- Filtros -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="filtro-unidad" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Unidad Administrativa</label>
                                <select id="filtro-unidad" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todas las unidades</option>
                                    @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->codigo }} - {{ $unidad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="buscar-series" class="block text-sm font-medium text-gray-700 mb-1">Buscar Series</label>
                                <input type="text"
                                       id="buscar-series"
                                       placeholder="Buscar por número, nombre..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>
                            <div class="flex items-end">
                                <button id="limpiar-filtros" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                                    Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Series -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-800">Series por Unidad Administrativa</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad Admin.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Serie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Serie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Respuesta</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subseries</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="series-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach($series as $serie)
                                    <tr class="serie-row hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">{{ $serie->unidadAdministrativa->codigo }}</span>
                                                <p class="text-xs text-gray-500">{{ Str::limit($serie->unidadAdministrativa->nombre, 30) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $serie->numero_serie }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-900">{{ $serie->nombre }}</span>
                                            @if($serie->descripcion)
                                            <p class="text-xs text-gray-500">{{ Str::limit($serie->descripcion, 50) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($serie->dias_respuesta)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $serie->dias_respuesta }} días
                                            </span>
                                            @else
                                            <span class="text-xs text-gray-400">No definido</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $serie->subseries_count }} subseries
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $serie->activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $serie->activa ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button data-action="edit-serie"
                                                    data-serie-id="{{ $serie->id }}"
                                                    data-serie-unidad="{{ $serie->unidad_administrativa_id }}"
                                                    data-serie-numero="{{ $serie->numero_serie }}"
                                                    data-serie-nombre="{{ $serie->nombre }}"
                                                    data-serie-descripcion="{{ $serie->descripcion }}"
                                                    data-serie-dias="{{ $serie->dias_respuesta }}"
                                                    data-serie-activa="{{ $serie->activa ? 'true' : 'false' }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                Editar
                                            </button>
                                            <button data-action="toggle-status"
                                                    data-serie-id="{{ $serie->id }}"
                                                    data-serie-nombre="{{ $serie->nombre }}"
                                                    class="text-yellow-600 hover:text-yellow-900">
                                                {{ $serie->activa ? 'Desactivar' : 'Activar' }}
                                            </button>
                                            @if($serie->subseries_count == 0)
                                            <button data-action="delete-serie"
                                                    data-serie-id="{{ $serie->id }}"
                                                    data-serie-nombre="{{ $serie->nombre }}"
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
                                <button data-action="create-serie"
                                        class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                                    Agregar Serie
                                </button>
                                <div>
                                    {{ $series->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación -->
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('admin.unidades-administrativas.index') }}"
                           class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            ← Anterior: Unidades Administrativas
                        </a>
                        <a href="{{ route('admin.subseries.index') }}"
                           class="px-6 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                            Siguiente: Gestionar Subseries →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Los scripts están incluidos en el bundle principal de Vite -->
</x-app-layout>
