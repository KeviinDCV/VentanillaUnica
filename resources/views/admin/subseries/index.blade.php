<x-app-layout>
    <div data-page="admin-subseries"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Subseries
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de Subseries para las Series
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
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Proceso de Creación de TRD - ¡Completado!</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</span>
                                <span class="text-gray-600">Códigos de Unidades Administrativas</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</span>
                                <span class="text-gray-600">Series agregadas a las unidades</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 bg-uniradical-blue text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                <span class="font-medium text-gray-700">Crear Sub-series para las Series</span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">
                            <strong>Paso final:</strong> Crear Sub-series para las Series.
                            Ejemplo: Código: 100. Serie: 11 PQRS. Sub-serie: 1 Nombre Sub-serie: Petición.
                        </p>
                    </div>

                    <!-- Filtros -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="filtro-unidad" class="block text-sm font-medium text-gray-700 mb-1">Unidad Administrativa</label>
                                <select id="filtro-unidad" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todas las unidades</option>
                                    @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->codigo }} - {{ $unidad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filtro-serie" class="block text-sm font-medium text-gray-700 mb-1">Serie</label>
                                <select id="filtro-serie" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todas las series</option>
                                </select>
                            </div>
                            <div>
                                <label for="buscar-subseries" class="block text-sm font-medium text-gray-700 mb-1">Buscar Subseries</label>
                                <input type="text"
                                       id="buscar-subseries"
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

                    <!-- Tabla de Subseries -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-800">Subseries por Serie</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código Completo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número Subserie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Subserie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Respuesta</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="subseries-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach($subseries as $subserie)
                                    <tr class="subserie-row hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">{{ $subserie->serie->unidadAdministrativa->codigo }}-{{ $subserie->serie->numero_serie }}-{{ $subserie->numero_subserie }}</span>
                                                <p class="text-xs text-gray-500">{{ Str::limit($subserie->serie->unidadAdministrativa->nombre, 25) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">{{ $subserie->serie->numero_serie }} {{ $subserie->serie->nombre }}</span>
                                                <p class="text-xs text-gray-500">{{ Str::limit($subserie->serie->descripcion, 30) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $subserie->numero_subserie }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-900">{{ $subserie->nombre }}</span>
                                            @if($subserie->descripcion)
                                            <p class="text-xs text-gray-500">{{ Str::limit($subserie->descripcion, 40) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($subserie->dias_respuesta)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $subserie->dias_respuesta }} días
                                            </span>
                                            @elseif($subserie->serie->dias_respuesta)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $subserie->serie->dias_respuesta }} días (serie)
                                            </span>
                                            @else
                                            <span class="text-xs text-gray-400">No definido</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subserie->activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $subserie->activa ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button data-action="edit-subserie"
                                                    data-subserie-id="{{ $subserie->id }}"
                                                    data-subserie-serie="{{ $subserie->serie_id }}"
                                                    data-subserie-numero="{{ $subserie->numero_subserie }}"
                                                    data-subserie-nombre="{{ $subserie->nombre }}"
                                                    data-subserie-descripcion="{{ $subserie->descripcion }}"
                                                    data-subserie-dias="{{ $subserie->dias_respuesta }}"
                                                    data-subserie-activa="{{ $subserie->activa ? 'true' : 'false' }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                Editar
                                            </button>
                                            <button data-action="toggle-status"
                                                    data-subserie-id="{{ $subserie->id }}"
                                                    data-subserie-nombre="{{ $subserie->nombre }}"
                                                    class="text-yellow-600 hover:text-yellow-900">
                                                {{ $subserie->activa ? 'Desactivar' : 'Activar' }}
                                            </button>
                                            <button data-action="delete-subserie"
                                                    data-subserie-id="{{ $subserie->id }}"
                                                    data-subserie-nombre="{{ $subserie->nombre }}"
                                                    class="text-red-600 hover:text-red-900">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Botón Agregar y Paginación -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <button data-action="create-subserie"
                                        class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                                    Agregar Subserie
                                </button>
                                <div>
                                    {{ $subseries->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación -->
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('admin.series.index') }}"
                           class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            ← Anterior: Series
                        </a>
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.trds.index') }}"
                               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                                Ver TRD Tradicional
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Los scripts están incluidos en el bundle principal de Vite -->
</x-app-layout>
