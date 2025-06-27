<x-app-layout>
    <div data-page="admin-trds"></div>
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
                    <div class="flex items-center space-x-2">
                        <h2 class="font-light text-xl text-gray-800 leading-tight">
                            TRD Tradicional
                        </h2>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Sistema Legacy
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 font-light mt-1">
                        Sistema anterior de Tabla de Retención Documental
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button data-action="create-trd"
                        class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                    Nuevo TRD
                </button>
                <x-hospital-brand />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Alerta del nuevo sistema -->
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Sistema TRD Jerárquico Disponible
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>
                                Recomendamos usar el nuevo <strong>Sistema TRD Jerárquico</strong> que ofrece mejor organización
                                con Unidades Administrativas → Series → Subseries. Este sistema tradicional se mantiene
                                para compatibilidad con radicados existentes.
                            </p>
                        </div>
                        <div class="mt-4">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.unidades-administrativas.index') }}"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Usar Sistema Jerárquico
                                </a>
                                <a href="{{ route('gestion.index') }}"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Ver Gestión TRD
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de TRD -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total TRD</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $trds->total() }}</p>
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
                            <p class="text-lg font-semibold text-green-600">{{ $trds->where('activo', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Con Radicados</p>
                            <p class="text-lg font-semibold text-yellow-600">{{ $trds->where('radicados_count', '>', 0)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Series Únicas</p>
                            <p class="text-lg font-semibold text-purple-600">{{ $trds->unique('serie')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de TRD -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de TRD</h3>
                        <div class="flex space-x-3">
                            <input type="text"
                                   id="buscar-trds"
                                   placeholder="Buscar TRDs..."
                                   class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <select id="filtro-estado"
                                    class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activos</option>
                                <option value="inactivo">Inactivos</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-visible table-container">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Código
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                    Serie / Subserie
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Asunto
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 hidden lg:table-cell">
                                    Retención
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Estado
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 hidden xl:table-cell">
                                    Radicados
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="trds-table-body" class="bg-white divide-y divide-gray-200">
                            @foreach($trds as $trd)
                            <tr class="hover:bg-gray-50">
                                <!-- Código -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 font-mono">{{ $trd->codigo }}</div>
                                </td>

                                <!-- Serie / Subserie -->
                                <td class="px-3 py-4 max-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate" title="{{ $trd->serie }}">
                                        {{ $trd->serie }}
                                    </div>
                                    @if($trd->subserie)
                                        <div class="text-xs text-gray-500 truncate" title="{{ $trd->subserie }}">
                                            {{ $trd->subserie }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Asunto -->
                                <td class="px-3 py-4 max-w-0">
                                    <div class="text-sm text-gray-900 truncate" title="{{ $trd->asunto }}">
                                        {{ $trd->asunto }}
                                    </div>
                                    <!-- Mostrar retención en móvil -->
                                    <div class="text-xs text-gray-500 mt-1 lg:hidden">
                                        AG: {{ $trd->retencion_archivo_gestion }}a | AC: {{ $trd->retencion_archivo_central }}a
                                    </div>
                                    <!-- Mostrar radicados en móvil -->
                                    <div class="text-xs text-gray-500 mt-1 xl:hidden">
                                        {{ number_format($trd->radicados_count) }} radicado(s)
                                    </div>
                                </td>

                                <!-- Retención (oculto en móvil) -->
                                <td class="px-3 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="text-xs text-gray-900">
                                        <span class="font-medium">AG:</span> {{ $trd->retencion_archivo_gestion }}a
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <span class="font-medium">AC:</span> {{ $trd->retencion_archivo_central }}a
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-3 py-4 whitespace-nowrap">
                                    @if($trd->activo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                            <span class="hidden sm:inline">Activo</span>
                                            <span class="sm:hidden">✓</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                            <span class="hidden sm:inline">Inactivo</span>
                                            <span class="sm:hidden">✗</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Radicados (oculto en móvil) -->
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 hidden xl:table-cell">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ number_format($trd->radicados_count) }}</span>
                                        @if($trd->radicados_count > 0)
                                            <span class="ml-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                                        @endif
                                    </div>
                                </td>
                                <!-- Acciones -->
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
                                                onclick="toggleDropdown('dropdown-trd-{{ $trd->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-trd-{{ $trd->id }}"
                                             class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                             style="z-index: 9999;"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button data-action="edit-trd"
                                                        data-trd-id="{{ $trd->id }}"
                                                        data-trd-codigo="{{ $trd->codigo }}"
                                                        data-trd-serie="{{ $trd->serie }}"
                                                        data-trd-subserie="{{ $trd->subserie }}"
                                                        data-trd-asunto="{{ $trd->asunto }}"
                                                        data-trd-retencion-gestion="{{ $trd->retencion_archivo_gestion }}"
                                                        data-trd-retencion-central="{{ $trd->retencion_archivo_central }}"
                                                        data-trd-disposicion="{{ $trd->disposicion_final }}"
                                                        data-trd-dias-respuesta="{{ $trd->dias_respuesta }}"
                                                        data-trd-observaciones="{{ $trd->observaciones }}"
                                                        data-trd-activo="{{ $trd->activo ? 'true' : 'false' }}"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Activar/Desactivar -->
                                                <form action="{{ route('admin.trds.toggle-status', $trd->id) }}"
                                                      method="POST"
                                                      class="w-full"
                                                      id="toggle-trd-form-{{ $trd->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button"
                                                            class="w-full text-left px-4 py-2 text-sm {{ $trd->activo ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center"
                                                            data-trd-name="{{ $trd->codigo }}"
                                                            data-trd-active="{{ $trd->activo ? 'true' : 'false' }}"
                                                            data-form-id="toggle-trd-form-{{ $trd->id }}">
                                                        @if($trd->activo)
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
                                                </form>

                                                <!-- Separador -->
                                                <div class="border-t border-gray-100"></div>

                                                <!-- Eliminar -->
                                                @if($trd->radicados_count > 0)
                                                    <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                                         title="No se puede eliminar: tiene {{ $trd->radicados_count }} radicado(s) asociado(s)">
                                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Eliminar
                                                    </div>
                                                @else
                                                    <form action="{{ route('admin.trds.eliminar', $trd->id) }}"
                                                          method="POST"
                                                          class="w-full"
                                                          id="delete-trd-form-{{ $trd->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center"
                                                                data-trd-name="{{ $trd->codigo }}"
                                                                data-form-id="delete-trd-form-{{ $trd->id }}">
                                                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Eliminar
                                                        </button>
                                                    </form>
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

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $trds->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear TRD -->
    <div id="createTrdModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="createModalTitle" class="text-lg font-medium text-gray-900">Nuevo TRD</h3>
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
                <form id="createTrdForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text"
                                   id="create_codigo"
                                   name="codigo"
                                   required
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="create_serie" class="block text-sm font-medium text-gray-700 mb-1">Serie *</label>
                            <input type="text"
                                   id="create_serie"
                                   name="serie"
                                   required
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="create_subserie" class="block text-sm font-medium text-gray-700 mb-1">Subserie</label>
                        <input type="text"
                               id="create_subserie"
                               name="subserie"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="create_asunto" class="block text-sm font-medium text-gray-700 mb-1">Asunto *</label>
                        <textarea id="create_asunto"
                                  name="asunto"
                                  required
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_retencion_gestion" class="block text-sm font-medium text-gray-700 mb-1">Retención AG (años) *</label>
                            <input type="number"
                                   id="create_retencion_gestion"
                                   name="retencion_archivo_gestion"
                                   required
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="create_retencion_central" class="block text-sm font-medium text-gray-700 mb-1">Retención AC (años) *</label>
                            <input type="number"
                                   id="create_retencion_central"
                                   name="retencion_archivo_central"
                                   required
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_disposicion_final" class="block text-sm font-medium text-gray-700 mb-1">Disposición Final *</label>
                            <select id="create_disposicion_final"
                                    name="disposicion_final"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="conservacion_total">Conservación Total</option>
                                <option value="eliminacion">Eliminación</option>
                                <option value="seleccion">Selección</option>
                                <option value="microfilmacion">Microfilmación</option>
                            </select>
                        </div>
                        <div>
                            <label for="create_dias_respuesta" class="block text-sm font-medium text-gray-700 mb-1">Días de Respuesta</label>
                            <input type="number"
                                   id="create_dias_respuesta"
                                   name="dias_respuesta"
                                   min="1"
                                   max="365"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                   placeholder="Ej: 15">
                            <p class="mt-1 text-xs text-gray-500">Días límite para respuesta según TRD o ley</p>
                        </div>
                    </div>

                    <div>
                        <label for="create_observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea id="create_observaciones"
                                  name="observaciones"
                                  rows="2"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="create_activo"
                                   name="activo"
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">TRD activo</span>
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
                            form="createTrdForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Crear TRD
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar TRD -->
    <div id="editTrdModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="editModalTitle" class="text-lg font-medium text-gray-900">Editar TRD</h3>
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
                <form id="editTrdForm" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_trd_id" name="trd_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text"
                                   id="edit_codigo"
                                   name="codigo"
                                   required
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="edit_serie" class="block text-sm font-medium text-gray-700 mb-1">Serie *</label>
                            <input type="text"
                                   id="edit_serie"
                                   name="serie"
                                   required
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="edit_subserie" class="block text-sm font-medium text-gray-700 mb-1">Subserie</label>
                        <input type="text"
                               id="edit_subserie"
                               name="subserie"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="edit_asunto" class="block text-sm font-medium text-gray-700 mb-1">Asunto *</label>
                        <textarea id="edit_asunto"
                                  name="asunto"
                                  required
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_retencion_gestion" class="block text-sm font-medium text-gray-700 mb-1">Retención AG (años) *</label>
                            <input type="number"
                                   id="edit_retencion_gestion"
                                   name="retencion_archivo_gestion"
                                   required
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="edit_retencion_central" class="block text-sm font-medium text-gray-700 mb-1">Retención AC (años) *</label>
                            <input type="number"
                                   id="edit_retencion_central"
                                   name="retencion_archivo_central"
                                   required
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_disposicion_final" class="block text-sm font-medium text-gray-700 mb-1">Disposición Final *</label>
                            <select id="edit_disposicion_final"
                                    name="disposicion_final"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="conservacion_total">Conservación Total</option>
                                <option value="eliminacion">Eliminación</option>
                                <option value="seleccion">Selección</option>
                                <option value="microfilmacion">Microfilmación</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_dias_respuesta" class="block text-sm font-medium text-gray-700 mb-1">Días de Respuesta</label>
                            <input type="number"
                                   id="edit_dias_respuesta"
                                   name="dias_respuesta"
                                   min="1"
                                   max="365"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                   placeholder="Ej: 15">
                            <p class="mt-1 text-xs text-gray-500">Días límite para respuesta según TRD o ley</p>
                        </div>
                    </div>

                    <div>
                        <label for="edit_observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea id="edit_observaciones"
                                  name="observaciones"
                                  rows="2"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="edit_activo"
                                   name="activo"
                                   value="1"
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">TRD activo</span>
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
                            form="editTrdForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Actualizar TRD
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
            document.querySelectorAll('[id^="dropdown-trd-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                    // Resetear estilos completamente
                    d.style.position = '';
                    d.style.top = '';
                    d.style.bottom = '';
                    d.style.left = '';
                    d.style.right = '';
                    d.style.transform = '';
                    d.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
                }
            });

            // Toggle del dropdown actual
            if (isHidden) {
                dropdown.classList.remove('hidden');
                // Ajustar posición del menú según el espacio disponible
                adjustDropdownPosition(dropdown);
            } else {
                dropdown.classList.add('hidden');
                // Resetear estilos al cerrar
                dropdown.style.position = '';
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.style.left = '';
                dropdown.style.right = '';
                dropdown.style.transform = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
            }
        }

        // Función para ajustar la posición del dropdown
        function adjustDropdownPosition(dropdown) {
            // Encontrar el botón que activa este dropdown
            const button = dropdown.parentElement.querySelector('button[onclick*="toggleDropdown"]');
            if (!button) return;

            // Esperar un frame para que el dropdown se renderice completamente
            requestAnimationFrame(() => {
                const buttonRect = button.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const viewportWidth = window.innerWidth;
                const dropdownWidth = 192; // w-48 = 12rem = 192px

                // Resetear estilos de posicionamiento
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.style.left = '';
                dropdown.style.right = '';
                dropdown.style.transform = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');

                // Calcular posición horizontal (alineado a la derecha del botón)
                let leftPosition = buttonRect.right - dropdownWidth;

                // Asegurar que no se salga del viewport por la izquierda
                if (leftPosition < 10) {
                    leftPosition = buttonRect.left;
                }

                // Asegurar que no se salga del viewport por la derecha
                if (leftPosition + dropdownWidth > viewportWidth - 10) {
                    leftPosition = viewportWidth - dropdownWidth - 10;
                }

                // Calcular posición vertical
                const dropdownHeight = dropdown.offsetHeight || 200;
                const spaceBelow = viewportHeight - buttonRect.bottom;
                const spaceAbove = buttonRect.top;

                let topPosition;
                if (spaceBelow >= dropdownHeight + 10) {
                    // Hay espacio suficiente abajo
                    topPosition = buttonRect.bottom + 8;
                    dropdown.classList.add('origin-top-right');
                } else if (spaceAbove >= dropdownHeight + 10) {
                    // No hay espacio abajo pero sí arriba
                    topPosition = buttonRect.top - dropdownHeight - 8;
                    dropdown.classList.add('origin-bottom-right');
                } else {
                    // No hay espacio suficiente ni arriba ni abajo, priorizar abajo
                    topPosition = buttonRect.bottom + 8;
                    dropdown.classList.add('origin-top-right');
                }

                // Asegurar que no se salga del viewport por arriba
                if (topPosition < 10) {
                    topPosition = 10;
                }

                // Asegurar que no se salga del viewport por abajo
                if (topPosition + dropdownHeight > viewportHeight - 10) {
                    topPosition = viewportHeight - dropdownHeight - 10;
                }

                // Aplicar posicionamiento fijo
                dropdown.style.position = 'fixed';
                dropdown.style.left = leftPosition + 'px';
                dropdown.style.top = topPosition + 'px';
                dropdown.style.zIndex = '9999';
            });
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]') && !event.target.closest('[id^="dropdown-trd-"]')) {
                document.querySelectorAll('[id^="dropdown-trd-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Cerrar dropdown después de hacer clic en una acción
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="dropdown-trd-"] button')) {
                // Pequeño delay para permitir que la acción se ejecute
                setTimeout(() => {
                    document.querySelectorAll('[id^="dropdown-trd-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                        // Resetear estilos
                        dropdown.style.position = '';
                        dropdown.style.top = '';
                        dropdown.style.bottom = '';
                        dropdown.style.left = '';
                        dropdown.style.right = '';
                        dropdown.style.transform = '';
                        dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
                    });
                }, 100);
            }
        });

        // Reposicionar dropdowns al redimensionar la ventana
        window.addEventListener('resize', function() {
            document.querySelectorAll('[id^="dropdown-trd-"]:not(.hidden)').forEach(dropdown => {
                adjustDropdownPosition(dropdown);
            });
        });

        // Cerrar dropdowns al hacer scroll
        window.addEventListener('scroll', function() {
            document.querySelectorAll('[id^="dropdown-trd-"]:not(.hidden)').forEach(dropdown => {
                dropdown.classList.add('hidden');
                // Resetear estilos
                dropdown.style.position = '';
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.style.left = '';
                dropdown.style.right = '';
                dropdown.style.transform = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
            });
        });
    </script>

    <style>
        /* Optimizaciones para tabla responsive */
        .table-container {
            min-width: 0;
        }

        /* Asegurar que las celdas con max-w-0 se comporten correctamente */
        td.max-w-0 {
            width: 1px;
            max-width: 0;
        }

        /* Mejorar el truncado de texto */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Responsive breakpoints personalizados */
        @media (max-width: 1024px) {
            /* En tablets, hacer las columnas más compactas */
            .table-container table {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            /* En móviles, reducir padding */
            .table-container td,
            .table-container th {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            /* Hacer el botón de acciones más pequeño */
            .table-container button {
                width: 2rem;
                height: 2rem;
            }
        }

        /* Asegurar que el contenido no se desborde */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Pero preferir que no haya scroll horizontal */
        @media (min-width: 640px) {
            .table-container {
                overflow-x: visible;
            }
        }
    </style>

</x-app-layout>
