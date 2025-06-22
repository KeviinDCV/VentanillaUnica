<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Dependencias
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de dependencias del hospital
                </p>
            </div>
            <div class="flex space-x-3">
                <button data-action="create-dependencia"
                        class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                    Nueva Dependencia
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Estadísticas de Dependencias -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Dependencias</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $dependencias->total() }}</p>
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
                            <p class="text-lg font-semibold text-green-600">{{ $dependencias->where('activa', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Con Radicados</p>
                            <p class="text-lg font-semibold text-yellow-600">{{ $dependencias->where('radicados_destino_count', '>', 0)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Inactivas</p>
                            <p class="text-lg font-semibold text-red-600">{{ $dependencias->where('activa', false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Dependencias -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de Dependencias</h3>
                        <div class="flex space-x-3">
                            <input type="text"
                                   id="buscar-dependencias"
                                   placeholder="Buscar dependencias..."
                                   class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <select id="filtro-estado"
                                    class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Todos los estados</option>
                                <option value="activa">Activas</option>
                                <option value="inactiva">Inactivas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Dependencia
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Código
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Responsable
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Estado
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Radicados
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="dependencias-table-body" class="bg-white divide-y divide-gray-200">
                            @foreach($dependencias as $dependencia)
                            <tr class="hover:bg-gray-50 dependencia-row"
                                data-name="{{ strtolower($dependencia->nombre) }}"
                                data-codigo="{{ strtolower($dependencia->codigo) }}"
                                data-responsable="{{ strtolower($dependencia->responsable ?? '') }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $dependencia->nombre }}">
                                                {{ $dependencia->nombre }}
                                            </div>
                                            @if($dependencia->descripcion)
                                                <div class="text-xs text-gray-500 truncate" title="{{ $dependencia->descripcion }}">
                                                    {{ $dependencia->descripcion }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">{{ $dependencia->codigo }}</div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm text-gray-900 truncate max-w-32" title="{{ $dependencia->responsable ?: 'No asignado' }}">
                                        {{ $dependencia->responsable ?: 'No asignado' }}
                                    </div>
                                    @if($dependencia->telefono)
                                        <div class="text-xs text-gray-500">{{ $dependencia->telefono }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    @if($dependencia->activa)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                            Inactiva
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ number_format($dependencia->radicados_destino_count) }}</span>
                                        <span class="text-xs text-gray-500">dest.</span>
                                    </div>
                                    @if($dependencia->radicados_origen_count > 0)
                                        <div class="text-xs text-gray-500">
                                            {{ number_format($dependencia->radicados_origen_count) }} orig.
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-{{ $dependencia->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-{{ $dependencia->id }}"
                                             class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button data-action="edit-dependencia"
                                                        data-dependencia-id="{{ $dependencia->id }}"
                                                        data-dependencia-codigo="{{ $dependencia->codigo }}"
                                                        data-dependencia-nombre="{{ $dependencia->nombre }}"
                                                        data-dependencia-sigla="{{ $dependencia->sigla }}"
                                                        data-dependencia-descripcion="{{ $dependencia->descripcion }}"
                                                        data-dependencia-responsable="{{ $dependencia->responsable }}"
                                                        data-dependencia-telefono="{{ $dependencia->telefono }}"
                                                        data-dependencia-email="{{ $dependencia->email }}"
                                                        data-dependencia-activa="{{ $dependencia->activa ? 'true' : 'false' }}"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Activar/Desactivar -->
                                                <form action="{{ route('admin.dependencias.toggle-status', $dependencia->id) }}"
                                                      method="POST"
                                                      class="w-full"
                                                      id="toggle-form-{{ $dependencia->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button"
                                                            class="w-full text-left px-4 py-2 text-sm {{ $dependencia->activa ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center"
                                                            data-dependencia-name="{{ $dependencia->nombre }}"
                                                            data-dependencia-active="{{ $dependencia->activa ? 'true' : 'false' }}"
                                                            data-form-id="toggle-form-{{ $dependencia->id }}">
                                                        @if($dependencia->activa)
                                                            <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"/>
                                                            </svg>
                                                            Desactivar
                                                        @else
                                                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            Activar
                                                        @endif
                                                    </button>
                                                </form>

                                                <!-- Separador -->
                                                <div class="border-t border-gray-100"></div>

                                                <!-- Eliminar -->
                                                <button data-action="delete-dependencia"
                                                        data-dependencia-id="{{ $dependencia->id }}"
                                                        data-dependencia-name="{{ $dependencia->nombre }}"
                                                        data-radicados-count="{{ $dependencia->radicados_destino_count + $dependencia->radicados_origen_count }}"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
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
                    {{ $dependencias->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Dependencia -->
    <div id="createDependenciaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="createModalTitle" class="text-lg font-medium text-gray-900">Nueva Dependencia</h3>
                <button data-action="close-create-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
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
                <form id="createDependenciaForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text"
                                   id="create_codigo"
                                   name="codigo"
                                   required
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="create_sigla" class="block text-sm font-medium text-gray-700 mb-1">Sigla</label>
                            <input type="text"
                                   id="create_sigla"
                                   name="sigla"
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="create_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text"
                               id="create_nombre"
                               name="nombre"
                               required
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="create_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="create_descripcion"
                                  name="descripcion"
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                            <input type="text"
                                   id="create_responsable"
                                   name="responsable"
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="create_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text"
                                   id="create_telefono"
                                   name="telefono"
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="create_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email"
                               id="create_email"
                               name="email"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="create_activa"
                                   name="activa"
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Dependencia activa</span>
                        </label>
                    </div>
                </form>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button"
                            data-action="close-create-modal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            form="createDependenciaForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Crear Dependencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Dependencia -->
    <div id="editDependenciaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="editModalTitle" class="text-lg font-medium text-gray-900">Editar Dependencia</h3>
                <button data-action="close-edit-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-4">
                <!-- Errores -->
                <div id="editModalErrors" class="mb-4 hidden">
                    <div class="bg-red-50 border border-red-200 rounded-md p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                                <ul id="editErrorsList" class="mt-2 text-sm text-red-700 list-disc list-inside"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="editDependenciaForm" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_dependencia_id" name="dependencia_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text"
                                   id="edit_codigo"
                                   name="codigo"
                                   required
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="edit_sigla" class="block text-sm font-medium text-gray-700 mb-1">Sigla</label>
                            <input type="text"
                                   id="edit_sigla"
                                   name="sigla"
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="edit_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text"
                               id="edit_nombre"
                               name="nombre"
                               required
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="edit_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="edit_descripcion"
                                  name="descripcion"
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                            <input type="text"
                                   id="edit_responsable"
                                   name="responsable"
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="edit_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text"
                                   id="edit_telefono"
                                   name="telefono"
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email"
                               id="edit_email"
                               name="email"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="edit_activa"
                                   name="activa"
                                   value="1"
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Dependencia activa</span>
                        </label>
                    </div>
                </form>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button"
                            data-action="close-edit-modal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            form="editDependenciaForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Actualizar Dependencia
                    </button>
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
            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                    // Resetear estilos
                    d.style.top = '';
                    d.style.bottom = '';
                    d.classList.remove('origin-bottom-right', 'mb-2');
                    d.classList.add('origin-top-right', 'mt-2');
                }
            });

            // Toggle del dropdown actual
            if (isHidden) {
                // Resetear posición antes de mostrar
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2');
                dropdown.classList.add('origin-top-right', 'mt-2');

                dropdown.classList.remove('hidden');

                // Ajustar posición del menú según el espacio disponible
                adjustDropdownPosition(dropdown);
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Función para ajustar la posición del dropdown
        function adjustDropdownPosition(dropdown) {
            // Encontrar el botón que activa este dropdown
            const button = dropdown.parentElement.querySelector('button[onclick*="toggleDropdown"]');
            if (!button) return;

            const buttonRect = button.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const dropdownHeight = 200; // Altura estimada del menú (ajustar si es necesario)

            // Calcular espacio disponible debajo del botón
            const spaceBelow = viewportHeight - buttonRect.bottom;

            // Si no hay suficiente espacio abajo (con margen de seguridad), abrir hacia arriba
            if (spaceBelow < dropdownHeight + 50) {
                dropdown.classList.remove('origin-top-right', 'mt-2');
                dropdown.classList.add('origin-bottom-right', 'mb-2');
                dropdown.style.bottom = '100%';
                dropdown.style.top = 'auto';
            } else {
                dropdown.classList.remove('origin-bottom-right', 'mb-2');
                dropdown.classList.add('origin-top-right', 'mt-2');
                dropdown.style.top = '100%';
                dropdown.style.bottom = 'auto';
            }
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]') && !event.target.closest('[id^="dropdown-"]')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Cerrar dropdown después de hacer clic en una acción
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="dropdown-"] button')) {
                // Pequeño delay para permitir que la acción se ejecute
                setTimeout(() => {
                    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }, 100);
            }
        });
    </script>


</x-app-layout>
