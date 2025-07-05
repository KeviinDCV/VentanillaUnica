<x-app-layout>
    <div data-page="admin-tipos-solicitud"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Tipos de Solicitud
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de tipos de solicitud del sistema
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

            <!-- Estadísticas de Tipos de Solicitud -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Tipos</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $tiposSolicitud->total() }}</p>
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
                            <p class="text-sm font-medium text-gray-500">Activos</p>
                            <p class="text-lg font-semibold text-green-600">{{ $tiposSolicitud->where('activo', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Inactivos</p>
                            <p class="text-lg font-semibold text-red-600">{{ $tiposSolicitud->where('activo', false)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">En Uso</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $tiposSolicitud->sum('radicados_count') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Tipos de Solicitud -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de Tipos de Solicitud</h3>
                        <div class="flex-1 max-w-md ml-6">
                            <div class="relative">
                                <input type="text"
                                       id="buscar-tipo"
                                       placeholder="Buscar tipos de solicitud..."
                                       class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <select id="filtro-estado" class="border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activos</option>
                                <option value="inactivo">Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-visible">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                    Tipo de Solicitud
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 hidden md:table-cell">
                                    Código
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3 hidden lg:table-cell">
                                    Descripción
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 hidden md:table-cell">
                                    Uso
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Estado
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabla-tipos" class="bg-white divide-y divide-gray-200">
                            @foreach($tiposSolicitud as $tipo)
                            <tr class="hover:bg-gray-50 tipo-row"
                                data-id="{{ $tipo->id }}"
                                data-name="{{ strtolower($tipo->nombre) }}"
                                data-codigo="{{ strtolower($tipo->codigo) }}"
                                data-active="{{ $tipo->activo ? 'true' : 'false' }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $tipo->nombre }}">
                                                {{ $tipo->nombre }}
                                            </div>
                                            <div class="text-xs text-gray-500 md:hidden truncate">
                                                {{ $tipo->codigo }} • {{ $tipo->radicados_count }} radicados
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900 font-mono">{{ $tipo->codigo }}</div>
                                </td>
                                <td class="px-3 py-4 hidden lg:table-cell">
                                    <div class="text-sm text-gray-900 truncate" title="{{ $tipo->descripcion }}">
                                        {{ $tipo->descripcion ?: 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ $tipo->radicados_count }}</div>
                                </td>
                                <td class="px-3 py-4">
                                    @if($tipo->activo)
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
                                                onclick="toggleDropdown('dropdown-tipo-{{ $tipo->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-tipo-{{ $tipo->id }}"
                                             class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button class="btn-editar w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                                        data-id="{{ $tipo->id }}"
                                                        data-nombre="{{ $tipo->nombre }}"
                                                        data-codigo="{{ $tipo->codigo }}"
                                                        data-descripcion="{{ $tipo->descripcion }}"
                                                        data-activo="{{ $tipo->activo ? 'true' : 'false' }}">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Activar/Desactivar -->
                                                <button class="btn-toggle-estado w-full text-left px-4 py-2 text-sm {{ $tipo->activo ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center"
                                                        data-id="{{ $tipo->id }}">
                                                    @if($tipo->activo)
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
                                                        data-id="{{ $tipo->id }}"
                                                        data-nombre="{{ $tipo->nombre }}"
                                                        data-uso="{{ $tipo->radicados_count }}">
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
                    {{ $tiposSolicitud->links() }}
                </div>

                <!-- Botón Agregar -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <button id="btn-crear-tipo"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        Nuevo Tipo de Solicitud
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar tipo de solicitud -->
    <div id="modal-tipo" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">Nuevo Tipo de Solicitud</h3>
                    <button id="btn-cerrar-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="form-tipo">
                    <input type="hidden" id="tipo-id">

                    <div class="mb-4">
                        <label for="tipo-nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Tipo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="tipo-nombre"
                               name="nombre"
                               required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Ej: Físico">
                    </div>

                    <div class="mb-4">
                        <label for="tipo-codigo" class="block text-sm font-medium text-gray-700 mb-2">
                            Código <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="tipo-codigo"
                               name="codigo"
                               required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Ej: fisico">
                        <p class="text-xs text-gray-500 mt-1">El código debe ser único y se usará internamente en el sistema</p>
                    </div>

                    <div class="mb-4">
                        <label for="tipo-descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción (Opcional)
                        </label>
                        <textarea id="tipo-descripcion"
                                  name="descripcion"
                                  rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                  placeholder="Descripción del tipo de solicitud..."></textarea>
                    </div>

                    <div class="mb-6" id="campo-activo" style="display: none;">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="tipo-activo"
                                   name="activo"
                                   class="rounded border-gray-300 text-uniradical-blue shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Tipo de solicitud activo</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="btn-cancelar" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-guardar" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Guardar Tipo
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
    @vite('resources/js/admin-tipos-solicitud.js')
    @endpush

    <script>
        // Función simple para manejar los menús desplegables con posicionamiento absoluto
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) {
                console.error('No se encontró el dropdown con ID:', dropdownId);
                return;
            }

            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-tipo-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                }
            });

            if (isHidden) {
                // Mostrar dropdown
                dropdown.classList.remove('hidden');

                // Verificar posicionamiento y ajustar automáticamente
                setTimeout(() => {
                    const rect = dropdown.getBoundingClientRect();
                    const container = dropdown.closest('.relative');

                    // Si se sale por la derecha, cambiar a alineación izquierda
                    if (rect.right > window.innerWidth - 10) {
                        dropdown.classList.remove('right-0');
                        dropdown.classList.add('left-0');
                    }

                    // Detectar si está en las últimas 2 filas de la tabla
                    const row = container.closest('tr');
                    const tbody = row.closest('tbody');
                    const allRows = tbody.querySelectorAll('tr');
                    const rowIndex = Array.from(allRows).indexOf(row);
                    const totalRows = allRows.length;

                    // Si está en las últimas 2 filas, abrir hacia arriba
                    if (rowIndex >= totalRows - 2) {
                        dropdown.classList.remove('top-full', 'mt-1');
                        dropdown.classList.add('bottom-full', 'mb-1');
                        dropdown.style.transformOrigin = 'bottom right';
                    } else {
                        // Filas normales, abrir hacia abajo
                        dropdown.classList.remove('bottom-full', 'mb-1');
                        dropdown.classList.add('top-full', 'mt-1');
                        dropdown.style.transformOrigin = 'top right';
                    }
                }, 10);
            } else {
                // Ocultar dropdown y resetear clases
                dropdown.classList.add('hidden');
                dropdown.classList.remove('left-0', 'bottom-full', 'mb-1');
                dropdown.classList.add('right-0', 'top-full', 'mt-1');
                dropdown.style.transformOrigin = 'top right';
            }
        }

        // Función para resetear dropdown a estado inicial
        function resetDropdown(dropdown) {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('left-0', 'bottom-full', 'mb-1');
            dropdown.classList.add('right-0', 'top-full', 'mt-1');
            dropdown.style.transformOrigin = 'top right';
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]') && !event.target.closest('[id^="dropdown-tipo-"]')) {
                document.querySelectorAll('[id^="dropdown-tipo-"]').forEach(dropdown => {
                    resetDropdown(dropdown);
                });
            }
        });

        // Cerrar dropdown después de hacer clic en una acción
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="dropdown-tipo-"] button') || event.target.closest('[id^="dropdown-tipo-"] a')) {
                setTimeout(() => {
                    document.querySelectorAll('[id^="dropdown-tipo-"]').forEach(dropdown => {
                        resetDropdown(dropdown);
                    });
                }, 100);
            }
        });

        // Cerrar dropdowns al hacer scroll
        window.addEventListener('scroll', function() {
            document.querySelectorAll('[id^="dropdown-tipo-"]:not(.hidden)').forEach(dropdown => {
                resetDropdown(dropdown);
            });
        });
    </script>

    @push('styles')
    <style>
        /* Estilos para dropdowns con posicionamiento absoluto inteligente */
        [id^="dropdown-tipo-"] {
            z-index: 50;
            min-width: 12rem;
            transform-origin: top right;
            transition: opacity 0.15s ease-out, transform 0.15s ease-out;
        }

        /* Animaciones suaves para apertura normal (hacia abajo) */
        [id^="dropdown-tipo-"]:not(.hidden) {
            opacity: 1;
            transform: scale(1);
        }

        [id^="dropdown-tipo-"].hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        /* Estilos específicos para dropdowns que abren hacia arriba */
        [id^="dropdown-tipo-"].bottom-full {
            transform-origin: bottom right;
        }

        /* Asegurar que los contenedores de la tabla permitan overflow */
        .overflow-x-auto {
            overflow: visible !important;
        }

        /* Mejorar el contenedor de acciones para posicionamiento relativo */
        .relative.inline-block {
            position: relative;
        }

        /* Asegurar que la tabla no interfiera */
        table {
            position: relative;
            z-index: 1;
        }

        /* Mejorar la visibilidad en las últimas filas */
        tbody tr:nth-last-child(-n+2) [id^="dropdown-tipo-"] {
            /* Asegurar que los dropdowns de las últimas 2 filas tengan prioridad */
            z-index: 60;
        }

        /* Animaciones para modales */
        #modal-tipo .relative,
        #confirmStatusModal .relative {
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }

        /* Backdrop blur para modales */
        #modal-tipo,
        #confirmStatusModal {
            backdrop-filter: blur(4px);
        }
    </style>
    @endpush
</x-app-layout>
