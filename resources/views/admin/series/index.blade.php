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
                                        <td class="px-3 py-4 text-sm font-medium">
                                            <div class="relative inline-block text-left">
                                                <button type="button"
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                        onclick="toggleDropdown('dropdown-serie-{{ $serie->id }}')">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                    </svg>
                                                </button>

                                                <div id="dropdown-serie-{{ $serie->id }}"
                                                     class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                                     style="z-index: 9999;"
                                                     data-dropdown-menu>
                                                    <div class="py-1" role="menu">
                                                        <!-- Editar -->
                                                        <button data-action="edit-serie"
                                                                data-serie-id="{{ $serie->id }}"
                                                                data-serie-unidad="{{ $serie->unidad_administrativa_id }}"
                                                                data-serie-numero="{{ $serie->numero_serie }}"
                                                                data-serie-nombre="{{ $serie->nombre }}"
                                                                data-serie-descripcion="{{ $serie->descripcion }}"
                                                                data-serie-dias="{{ $serie->dias_respuesta }}"
                                                                data-serie-activa="{{ $serie->activa ? 'true' : 'false' }}"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Editar
                                                        </button>

                                                        <!-- Activar/Desactivar -->
                                                        <button data-action="toggle-status"
                                                                data-serie-id="{{ $serie->id }}"
                                                                data-serie-nombre="{{ $serie->nombre }}"
                                                                class="w-full text-left px-4 py-2 text-sm {{ $serie->activa ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center">
                                                            @if($serie->activa)
                                                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                </svg>
                                                                Desactivar
                                                            @else
                                                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Activar
                                                            @endif
                                                        </button>

                                                        <!-- Separador -->
                                                        <div class="border-t border-gray-100"></div>

                                                        <!-- Eliminar -->
                                                        @if($serie->subseries_count > 0)
                                                            <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                                                 title="No se puede eliminar: tiene {{ $serie->subseries_count }} subserie(s) asociada(s)">
                                                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Eliminar
                                                            </div>
                                                        @else
                                                            <button data-action="delete-serie"
                                                                    data-serie-id="{{ $serie->id }}"
                                                                    data-serie-nombre="{{ $serie->nombre }}"
                                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Eliminar
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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

    <!-- Modal de Confirmación -->
    <div id="confirmStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="confirmModalTitle" class="text-lg font-medium text-gray-900">Confirmar Acción</h3>
                <button data-action="close-confirm-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="mt-4">
                <div class="flex items-center mb-4">
                    <div id="confirmModalIcon" class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full">
                        <!-- Icono se agregará dinámicamente -->
                    </div>
                    <div class="ml-4 flex-1">
                        <p id="confirmModalMessage" class="text-sm text-gray-600">
                            <!-- Mensaje se agregará dinámicamente -->
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button"
                            data-action="close-confirm-modal"
                            class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="button"
                            id="confirmModalAction"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors min-w-[100px]">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Los scripts están incluidos en el bundle principal de Vite -->
</x-app-layout>
