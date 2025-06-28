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
                                        <td class="px-3 py-4 text-sm font-medium">
                                            <div class="relative inline-block text-left">
                                                <button type="button"
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                        onclick="toggleDropdown('dropdown-unidad-{{ $unidad->id }}')">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                    </svg>
                                                </button>

                                                <div id="dropdown-unidad-{{ $unidad->id }}"
                                                     class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                                     style="z-index: 9999;"
                                                     data-dropdown-menu>
                                                    <div class="py-1" role="menu">
                                                        <!-- Editar -->
                                                        <button data-action="edit-unidad"
                                                                data-unidad-id="{{ $unidad->id }}"
                                                                data-unidad-codigo="{{ $unidad->codigo }}"
                                                                data-unidad-nombre="{{ $unidad->nombre }}"
                                                                data-unidad-descripcion="{{ $unidad->descripcion }}"
                                                                data-unidad-activa="{{ $unidad->activa ? 'true' : 'false' }}"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Editar
                                                        </button>

                                                        <!-- Activar/Desactivar -->
                                                        <button data-action="toggle-status"
                                                                data-unidad-id="{{ $unidad->id }}"
                                                                data-unidad-nombre="{{ $unidad->nombre }}"
                                                                data-unidad-activa="{{ $unidad->activa ? 'true' : 'false' }}"
                                                                class="w-full text-left px-4 py-2 text-sm {{ $unidad->activa ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center">
                                                            @if($unidad->activa)
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
                                                        @if($unidad->series_count > 0)
                                                            <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                                                 title="No se puede eliminar: tiene {{ $unidad->series_count }} serie(s) asociada(s)">
                                                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Eliminar
                                                            </div>
                                                        @else
                                                            <button data-action="delete-unidad"
                                                                    data-unidad-id="{{ $unidad->id }}"
                                                                    data-unidad-nombre="{{ $unidad->nombre }}"
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

    <!-- Modal de Confirmación para Cambiar Estado -->
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
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] bg-red-600 hover:bg-red-700">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Unidad Administrativa -->
    <div id="createUnidadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="createModalTitle" class="text-lg font-medium text-gray-900">Nueva Unidad Administrativa</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-4">
                <!-- Errores -->
                <div id="createModalErrors" class="mb-4 hidden">
                    <div class="bg-red-50 border border-red-200 rounded-md p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                                <ul id="createErrorsList" class="mt-2 text-sm text-red-700 list-disc list-inside"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="createUnidadForm" action="{{ route('admin.unidades-administrativas.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                        <input type="text"
                               id="codigo"
                               name="codigo"
                               required
                               maxlength="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text"
                               id="nombre"
                               name="nombre"
                               required
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="activa"
                                   name="activa"
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Unidad activa</span>
                        </label>
                    </div>
                </form>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button"
                            onclick="closeCreateModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            form="createUnidadForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Crear Unidad Administrativa
                    </button>
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

    <script>
        // Función para manejar los menús desplegables
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) return;

            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-unidad-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                }
            });

            if (isHidden) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Función para cerrar todos los dropdowns
        function closeAllDropdowns() {
            document.querySelectorAll('[id^="dropdown-unidad-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }

        // Función para abrir modal de crear
        function openCreateModal() {
            const modal = document.getElementById('createUnidadModal');
            const form = document.getElementById('createUnidadForm');

            // Limpiar formulario
            form.reset();
            document.getElementById('activa').checked = true;

            // Ocultar errores
            document.getElementById('createModalErrors').classList.add('hidden');

            // Mostrar modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animación de entrada
            const modalContent = modal.querySelector('.relative');
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
                // Focus en el primer campo
                document.getElementById('codigo').focus();
            }, 10);
        }

        // Función para cerrar modal de crear/editar
        function closeCreateModal() {
            const modal = document.getElementById('createUnidadModal');
            const modalContent = modal.querySelector('.relative');

            // Animación de salida
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                // Resetear modal para crear
                resetModalToCreate();
            }, 200);
        }

        // Función para resetear modal a modo crear
        function resetModalToCreate() {
            // Cambiar título
            document.getElementById('createModalTitle').textContent = 'Nueva Unidad Administrativa';

            // Resetear formulario
            const form = document.getElementById('createUnidadForm');
            form.action = '{{ route('admin.unidades-administrativas.store') }}';

            // Remover método PUT si existe
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            // Cambiar texto del botón
            const submitButton = form.parentElement.querySelector('button[type="submit"]');
            submitButton.textContent = 'Crear Unidad Administrativa';
        }

        // Event listeners cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Botón crear unidad administrativa
            const createButton = document.querySelector('[data-action="create-unidad"]');
            if (createButton) {
                createButton.addEventListener('click', openCreateModal);
            }

            // Cerrar modal al hacer clic fuera
            document.getElementById('createUnidadModal').addEventListener('click', function(e) {
                if (e.target === this) closeCreateModal();
            });

            // Cerrar modal con Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('createUnidadModal');
                    if (!modal.classList.contains('hidden')) {
                        closeCreateModal();
                    }
                }
            });

            // Event listeners para las acciones del menú
            document.addEventListener('click', function(e) {
                // Botón Editar
                if (e.target.closest('[data-action="edit-unidad"]')) {
                    const button = e.target.closest('[data-action="edit-unidad"]');
                    const unidadId = button.dataset.unidadId;
                    const codigo = button.dataset.unidadCodigo;
                    const nombre = button.dataset.unidadNombre;
                    const descripcion = button.dataset.unidadDescripcion;
                    const activa = button.dataset.unidadActiva === 'true';

                    // Cerrar dropdown antes de abrir modal
                    closeAllDropdowns();

                    openEditModal(unidadId, codigo, nombre, descripcion, activa);
                }

                // Botón Cambiar Estado
                if (e.target.closest('[data-action="toggle-status"]')) {
                    const button = e.target.closest('[data-action="toggle-status"]');
                    const unidadId = button.dataset.unidadId;
                    const nombre = button.dataset.unidadNombre;
                    const activa = button.dataset.unidadActiva === 'true';

                    // Cerrar dropdown antes de ejecutar acción
                    closeAllDropdowns();

                    toggleUnidadStatus(unidadId, nombre, activa);
                }

                // Botón Eliminar
                if (e.target.closest('[data-action="delete-unidad"]')) {
                    const button = e.target.closest('[data-action="delete-unidad"]');
                    const unidadId = button.dataset.unidadId;
                    const nombre = button.dataset.unidadNombre;

                    // Cerrar dropdown antes de ejecutar acción
                    closeAllDropdowns();

                    deleteUnidad(unidadId, nombre);
                }
            });

            // Cerrar dropdowns al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.relative')) {
                    closeAllDropdowns();
                }
            });
        });

        // Función para abrir modal de editar
        function openEditModal(unidadId, codigo, nombre, descripcion, activa) {
            // Cambiar título del modal
            document.getElementById('createModalTitle').textContent = 'Editar Unidad Administrativa';

            // Llenar formulario con datos existentes
            document.getElementById('codigo').value = codigo;
            document.getElementById('nombre').value = nombre;
            document.getElementById('descripcion').value = descripcion || '';
            document.getElementById('activa').checked = activa;

            // Cambiar action del formulario para editar
            const form = document.getElementById('createUnidadForm');
            form.action = `/admin/unidades-administrativas/${unidadId}`;

            // Agregar método PUT
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            // Cambiar texto del botón
            const submitButton = form.parentElement.querySelector('button[type="submit"]');
            submitButton.textContent = 'Actualizar Unidad Administrativa';

            // Mostrar modal
            const modal = document.getElementById('createUnidadModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Ocultar errores
            document.getElementById('createModalErrors').classList.add('hidden');

            // Animación de entrada
            const modalContent = modal.querySelector('.relative');
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
                document.getElementById('codigo').focus();
            }, 10);
        }

        // Función para cambiar estado de unidad
        function toggleUnidadStatus(unidadId, nombre, activa) {
            // La lógica correcta: si activa=true, queremos desactivar; si activa=false, queremos activar
            const accion = activa ? 'desactivar' : 'activar';
            const accionCapital = activa ? 'Desactivar' : 'Activar';
            const mensaje = `¿Está seguro que desea ${accion} la unidad administrativa "${nombre}"?`;

            showConfirmModal({
                title: `${accionCapital} Unidad Administrativa`,
                message: mensaje,
                actionText: accionCapital,
                // Si está activa (true) y queremos desactivar → naranja
                // Si está inactiva (false) y queremos activar → verde
                actionClass: activa ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
                iconClass: activa ? 'bg-orange-100' : 'bg-green-100',
                iconColor: activa ? 'text-orange-600' : 'text-green-600',
                onConfirm: function() {
                    executeToggleUnidadStatus(unidadId);
                }
            });
        }

        // Función para ejecutar el cambio de estado
        function executeToggleUnidadStatus(unidadId) {
            // Usar fetch para hacer la petición AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/unidades-administrativas/${unidadId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito y recargar la página
                    showSuccessMessage(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorMessage(data.message || 'Error al cambiar el estado');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error al procesar la solicitud');
            });
        }

        // Función para eliminar unidad
        function deleteUnidad(unidadId, nombre) {
            const mensaje = `¿Está seguro que desea eliminar la unidad administrativa "${nombre}"?\n\nEsta acción no se puede deshacer.`;

            showConfirmModal({
                title: 'Eliminar Unidad Administrativa',
                message: mensaje,
                actionText: 'Eliminar',
                actionClass: 'bg-red-600 hover:bg-red-700',
                iconClass: 'bg-red-100',
                iconColor: 'text-red-600',
                onConfirm: function() {
                    executeDeleteUnidad(unidadId);
                }
            });
        }

        // Función para ejecutar la eliminación
        function executeDeleteUnidad(unidadId) {
            // Usar fetch para hacer la petición AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/unidades-administrativas/${unidadId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito y recargar la página
                    showSuccessMessage(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorMessage(data.message || 'Error al eliminar la unidad');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error al procesar la solicitud');
            });
        }

        // Variable global para almacenar la función de confirmación
        let currentConfirmAction = null;

        // Función para mostrar modal de confirmación
        function showConfirmModal(options) {
            const modal = document.getElementById('confirmStatusModal');
            const title = document.getElementById('confirmModalTitle');
            const message = document.getElementById('confirmModalMessage');
            const actionButton = document.getElementById('confirmModalAction');
            const iconContainer = document.getElementById('confirmModalIcon');

            // Configurar contenido del modal
            title.textContent = options.title;
            message.textContent = options.message;
            actionButton.textContent = options.actionText;

            // Aplicar clases CSS para el botón
            actionButton.className = `px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}`;

            // Configurar icono
            iconContainer.className = `flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full ${options.iconClass}`;
            iconContainer.innerHTML = `
                <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            `;

            // Guardar la función de confirmación
            currentConfirmAction = options.onConfirm;

            // Mostrar modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animación de entrada
            const modalContent = modal.querySelector('.relative');
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
            }, 10);
        }

        // Función para cerrar modal de confirmación
        function closeConfirmModal() {
            const modal = document.getElementById('confirmStatusModal');
            const modalContent = modal.querySelector('.relative');

            // Animación de salida
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentConfirmAction = null;
            }, 200);
        }

        // Event listeners para el modal de confirmación
        document.addEventListener('DOMContentLoaded', function() {
            // Botones de cerrar modal de confirmación
            document.querySelectorAll('[data-action="close-confirm-modal"]').forEach(button => {
                button.addEventListener('click', closeConfirmModal);
            });

            // Botón de confirmación
            document.getElementById('confirmModalAction').addEventListener('click', function() {
                if (currentConfirmAction) {
                    currentConfirmAction();
                }
                closeConfirmModal();
            });

            // Cerrar modal con Escape
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('confirmStatusModal');
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeConfirmModal();
                }
            });

            // Cerrar modal al hacer clic fuera
            document.getElementById('confirmStatusModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeConfirmModal();
                }
            });
        });

        // Funciones para mostrar mensajes
        function showSuccessMessage(message) {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ease-in-out';
            notification.textContent = message;

            // Agregar al DOM
            document.body.appendChild(notification);

            // Animación de entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);

            // Remover después de 3 segundos
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        function showErrorMessage(message) {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ease-in-out';
            notification.textContent = message;

            // Agregar al DOM
            document.body.appendChild(notification);

            // Animación de entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);

            // Remover después de 4 segundos
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // Hacer funciones disponibles globalmente
        window.toggleDropdown = toggleDropdown;
        window.openCreateModal = openCreateModal;
        window.closeCreateModal = closeCreateModal;
        window.openEditModal = openEditModal;
        window.toggleUnidadStatus = toggleUnidadStatus;
        window.deleteUnidad = deleteUnidad;
        window.showConfirmModal = showConfirmModal;
        window.closeConfirmModal = closeConfirmModal;
        window.showSuccessMessage = showSuccessMessage;
        window.showErrorMessage = showErrorMessage;
    </script>


</x-app-layout>
