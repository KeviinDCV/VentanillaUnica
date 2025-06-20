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
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
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
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Inactivas</p>
                            <p class="text-lg font-semibold text-purple-600">{{ $dependencias->where('activa', false)->count() }}</p>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dependencia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Responsable
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Radicados
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $dependencia->nombre }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $dependencia->descripcion }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $dependencia->codigo }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $dependencia->responsable ?: 'No asignado' }}</div>
                                    @if($dependencia->telefono)
                                        <div class="text-sm text-gray-500">{{ $dependencia->telefono }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($dependencia->activa)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activa
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactiva
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ number_format($dependencia->radicados_destino_count) }}</span> destino
                                    </div>
                                    @if($dependencia->radicados_origen_count > 0)
                                        <div class="text-sm text-gray-500">
                                            {{ number_format($dependencia->radicados_origen_count) }} origen
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
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
                                                class="text-blue-600 hover:text-blue-900 font-medium text-xs sm:text-sm">
                                            Editar
                                        </button>

                                        <form action="{{ route('admin.dependencias.toggle-status', $dependencia->id) }}"
                                              method="POST"
                                              class="inline"
                                              id="toggle-form-{{ $dependencia->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button"
                                                    class="{{ $dependencia->activa ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }} font-medium text-xs sm:text-sm cursor-pointer"
                                                    data-dependencia-name="{{ $dependencia->nombre }}"
                                                    data-dependencia-active="{{ $dependencia->activa ? 'true' : 'false' }}"
                                                    data-form-id="toggle-form-{{ $dependencia->id }}">
                                                {{ $dependencia->activa ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
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

    <!-- Script para funcionalidad -->
    @vite(['resources/js/admin-dependencias.js'])
</x-app-layout>
