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
            <div class="flex items-center space-x-4">
                <x-hospital-brand />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Mensajes de éxito y error -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Estadísticas de Ciudades -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Ciudades</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $ciudades->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Activas</p>
                            <p class="text-lg font-semibold text-green-600">{{ $ciudades->where('activo', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Departamentos</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $departamentos->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Con Código</p>
                            <p class="text-lg font-semibold text-orange-600">{{ $ciudades->whereNotNull('codigo')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Ciudades -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de Ciudades</h3>
                        <div class="flex-1 max-w-md ml-6">
                            <div class="relative">
                                <input type="text"
                                       id="buscar-ciudad"
                                       placeholder="Buscar ciudades..."
                                       class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex space-x-3">
                            <select id="filtro-departamento" class="border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos los departamentos</option>
                                @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                @endforeach
                            </select>
                            <select id="filtro-estado" class="border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos los estados</option>
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-visible">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Ciudad
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 hidden md:table-cell">
                                    Departamento
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 hidden md:table-cell">
                                    Código
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Estado
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabla-ciudades" class="bg-white divide-y divide-gray-200">
                            @foreach($ciudades as $ciudad)
                            <tr class="hover:bg-gray-50 ciudad-row"
                                data-id="{{ $ciudad->id }}"
                                data-name="{{ strtolower($ciudad->nombre) }}"
                                data-departamento-id="{{ $ciudad->departamento_id }}"
                                data-departamento-name="{{ strtolower($ciudad->departamento->nombre) }}"
                                data-active="{{ $ciudad->activo ? 'true' : 'false' }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $ciudad->nombre }}">
                                                {{ $ciudad->nombre }}
                                            </div>
                                            <div class="text-xs text-gray-500 md:hidden truncate">
                                                {{ $ciudad->departamento->nombre }} • {{ $ciudad->codigo ?? 'Sin código' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ $ciudad->departamento->nombre }}</div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ $ciudad->codigo ?? 'N/A' }}</div>
                                </td>
                                <td class="px-3 py-4">
                                    @if($ciudad->activo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-ciudad-{{ $ciudad->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-ciudad-{{ $ciudad->id }}"
                                             class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                             style="z-index: 9999;"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button class="btn-editar w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                                        data-id="{{ $ciudad->id }}"
                                                        data-nombre="{{ $ciudad->nombre }}"
                                                        data-codigo="{{ $ciudad->codigo }}"
                                                        data-departamento-id="{{ $ciudad->departamento_id }}"
                                                        data-activo="{{ $ciudad->activo ? 'true' : 'false' }}">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Activar/Desactivar -->
                                                <button class="btn-toggle-estado w-full text-left px-4 py-2 text-sm {{ $ciudad->activo ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center"
                                                        data-id="{{ $ciudad->id }}">
                                                    @if($ciudad->activo)
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
                                                <button class="btn-eliminar w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center"
                                                        data-id="{{ $ciudad->id }}"
                                                        data-nombre="{{ $ciudad->nombre }}">
                                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $ciudades->links() }}
                </div>

                <!-- Botón Agregar -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <button id="btn-crear-ciudad"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        Nueva Ciudad
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar ciudad -->
    <div id="modal-ciudad" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
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
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="btn-cancelar" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-guardar" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Guardar Ciudad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación Personalizado -->
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
            <div class="p-4">
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

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3">
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

    @push('scripts')
    @vite('resources/js/admin-ciudades.js')
    @endpush

    <script>
        // Función para manejar los menús desplegables
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-ciudad-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                    // Resetear estilos
                    d.style.top = '';
                    d.style.bottom = '';
                    d.classList.remove('origin-bottom-right', 'mb-2');
                    d.classList.add('origin-top-right', 'mt-2');
                }
            });

            if (isHidden) {
                // Mostrar dropdown
                dropdown.classList.remove('hidden');

                // Calcular posición
                const button = dropdown.previousElementSibling;
                const rect = button.getBoundingClientRect();
                const dropdownRect = dropdown.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                // Verificar si hay espacio suficiente abajo
                const spaceBelow = viewportHeight - rect.bottom;
                const spaceAbove = rect.top;

                if (spaceBelow < 200 && spaceAbove > spaceBelow) {
                    // Mostrar arriba
                    dropdown.style.bottom = (viewportHeight - rect.top) + 'px';
                    dropdown.style.top = 'auto';
                    dropdown.classList.remove('origin-top-right', 'mt-2');
                    dropdown.classList.add('origin-bottom-right', 'mb-2');
                } else {
                    // Mostrar abajo (comportamiento por defecto)
                    dropdown.style.top = rect.bottom + 'px';
                    dropdown.style.bottom = 'auto';
                    dropdown.classList.remove('origin-bottom-right', 'mb-2');
                    dropdown.classList.add('origin-top-right', 'mt-2');
                }

                // Posición horizontal
                dropdown.style.left = (rect.left - dropdown.offsetWidth + button.offsetWidth) + 'px';
            } else {
                // Ocultar dropdown
                dropdown.classList.add('hidden');
            }
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[data-dropdown-menu]') && !e.target.closest('button[onclick*="toggleDropdown"]')) {
                document.querySelectorAll('[id^="dropdown-ciudad-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });
    </script>
</x-app-layout>
