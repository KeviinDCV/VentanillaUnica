<x-app-layout>
    <div data-page="admin-remitentes"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Remitentes
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de remitentes del sistema UniRadic
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

            <!-- Estadísticas de Remitentes -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Remitentes</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $remitentes->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Registrados</p>
                            <p class="text-lg font-semibold text-green-600">{{ $remitentes->where('tipo', 'registrado')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Anónimos</p>
                            <p class="text-lg font-semibold text-orange-600">{{ $remitentes->where('tipo', 'anonimo')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Con Email</p>
                            <p class="text-lg font-semibold text-purple-600">{{ $remitentes->whereNotNull('email')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Remitentes -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm table-container">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Lista de Remitentes</h3>
                            <p id="contador-resultados" class="text-sm text-gray-500 mt-1">Mostrando {{ $remitentes->count() }} de {{ $remitentes->total() }} remitentes</p>
                        </div>
                        <div class="flex-1 max-w-md ml-6">
                            <div class="relative">
                                <input type="text"
                                       id="buscar-remitente"
                                       value="{{ request('buscar') }}"
                                       placeholder="Buscar remitentes..."
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

                <div class="overflow-visible">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Remitente
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden md:table-cell">
                                    Tipo
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden md:table-cell">
                                    Documento
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden lg:table-cell">
                                    Contacto
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Radicados
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabla-remitentes" class="bg-white divide-y divide-gray-200">
                            @foreach($remitentes as $remitente)
                            <tr class="hover:bg-gray-50 remitente-row"
                                data-id="{{ $remitente->id }}"
                                data-name="{{ strtolower($remitente->nombre_completo) }}"
                                data-tipo="{{ $remitente->tipo }}"
                                data-email="{{ strtolower($remitente->email ?? '') }}"
                                data-documento="{{ $remitente->numero_documento ?? '' }}"
                                data-entidad="{{ strtolower($remitente->entidad ?? '') }}"
                                data-telefono="{{ $remitente->telefono ?? '' }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full {{ $remitente->tipo === 'anonimo' ? 'bg-gray-100' : 'bg-blue-100' }} flex items-center justify-center">
                                                <svg class="w-4 h-4 {{ $remitente->tipo === 'anonimo' ? 'text-gray-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $remitente->nombre_completo }}">
                                                {{ $remitente->nombre_completo }}
                                            </div>
                                            @if($remitente->entidad)
                                            <div class="text-xs text-gray-500 truncate" title="{{ $remitente->entidad }}">
                                                {{ $remitente->entidad }}
                                            </div>
                                            @endif
                                            <div class="text-xs text-gray-500 md:hidden">
                                                {{ $remitente->tipo === 'anonimo' ? 'Anónimo' : 'Registrado' }}
                                                @if($remitente->numero_documento)
                                                    • {{ $remitente->tipo_documento }}: {{ $remitente->numero_documento }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $remitente->tipo === 'anonimo' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                        <span class="w-1.5 h-1.5 mr-1.5 {{ $remitente->tipo === 'anonimo' ? 'bg-gray-400' : 'bg-blue-400' }} rounded-full"></span>
                                        {{ $remitente->tipo === 'anonimo' ? 'Anónimo' : 'Registrado' }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">
                                        @if($remitente->tipo_documento && $remitente->numero_documento)
                                            <div class="font-medium">{{ $remitente->tipo_documento }}</div>
                                            <div class="text-xs text-gray-500">{{ $remitente->numero_documento }}</div>
                                        @else
                                            <span class="text-gray-400 text-xs">Sin documento</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden lg:table-cell">
                                    <div class="text-sm text-gray-900">
                                        @if($remitente->email)
                                            <div class="text-xs truncate" title="{{ $remitente->email }}">{{ $remitente->email }}</div>
                                        @endif
                                        @if($remitente->telefono)
                                            <div class="text-xs text-gray-500">{{ $remitente->telefono }}</div>
                                        @endif
                                        @if(!$remitente->email && !$remitente->telefono)
                                            <span class="text-gray-400 text-xs">Sin contacto</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $remitente->radicados_count ?? 0 }}</div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-{{ $remitente->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-{{ $remitente->id }}"
                                             class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button class="btn-editar w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                                        data-id="{{ $remitente->id }}"
                                                        data-nombre-completo="{{ $remitente->nombre_completo }}"
                                                        data-tipo="{{ $remitente->tipo }}"
                                                        data-tipo-documento="{{ $remitente->tipo_documento }}"
                                                        data-numero-documento="{{ $remitente->numero_documento }}"
                                                        data-email="{{ $remitente->email }}"
                                                        data-telefono="{{ $remitente->telefono }}"
                                                        data-ciudad="{{ $remitente->ciudad }}"
                                                        data-departamento="{{ $remitente->departamento }}"
                                                        data-entidad="{{ $remitente->entidad }}"
                                                        data-direccion="{{ $remitente->direccion }}">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Separador -->
                                                <div class="border-t border-gray-100"></div>

                                                <!-- Eliminar -->
                                                <button class="btn-eliminar w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center"
                                                        data-id="{{ $remitente->id }}"
                                                        data-nombre="{{ $remitente->nombre_completo }}">
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
                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $remitentes->links() }}
                </div>

                <!-- Botón Agregar -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <button data-action="create-remitente"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        Nuevo Remitente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar remitente -->
    <div id="modal-remitente" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-titulo">Nuevo Remitente</h3>
                    <button id="btn-cerrar-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="form-remitente">
                    @csrf
                    <input type="hidden" id="remitente-id">

                    <!-- Campo oculto para tipo (siempre registrado) -->
                    <input type="hidden" id="tipo" name="tipo" value="registrado">

                    <div class="mb-4">
                        <label for="remitente-nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nombre_completo"
                               name="nombre_completo"
                               required
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Nombre completo del remitente">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="remitente-tipo-documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Documento
                            </label>
                            <select id="tipo_documento"
                                    name="tipo_documento"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar...</option>
                                <option value="CC">Cédula de Ciudadanía</option>
                                <option value="CE">Cédula de Extranjería</option>
                                <option value="NIT">NIT</option>
                                <option value="TI">Tarjeta de Identidad</option>
                                <option value="PP">Pasaporte</option>
                            </select>
                        </div>

                        <div>
                            <label for="remitente-numero-documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Documento
                            </label>
                            <input type="text"
                                   id="numero_documento"
                                   name="numero_documento"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Número de documento">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="remitente-email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="correo@ejemplo.com">
                        </div>

                        <div>
                            <label for="remitente-telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text"
                                   id="telefono"
                                   name="telefono"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Número de teléfono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="departamento_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Departamento
                            </label>
                            <select id="departamento_id"
                                    name="departamento_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar departamento...</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" data-nombre="{{ $departamento->nombre }}">
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <!-- Campo oculto para el nombre del departamento -->
                            <input type="hidden" id="departamento_nombre" name="departamento">
                        </div>

                        <div>
                            <label for="ciudad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Ciudad
                            </label>
                            <select id="ciudad_id"
                                    name="ciudad_id"
                                    disabled
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue bg-gray-100">
                                <option value="">Primero seleccione un departamento...</option>
                            </select>
                            <!-- Campo oculto para el nombre de la ciudad -->
                            <input type="hidden" id="ciudad_nombre" name="ciudad">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="entidad" class="block text-sm font-medium text-gray-700 mb-2">
                            Entidad/Organización
                        </label>
                        <input type="text"
                               id="entidad"
                               name="entidad"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Nombre de la entidad u organización">
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea id="direccion"
                                  name="direccion"
                                  rows="2"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                  placeholder="Dirección completa"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="btn-cancelar" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-guardar" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Guardar Remitente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <!-- Modal de Confirmación Personalizado -->
    <div id="confirmStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[60] backdrop-blur-sm">
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

    @push('styles')
    <style>
        /* Estilos para dropdowns con posicionamiento absoluto */
        [id^="dropdown-"] {
            z-index: 50;
            min-width: 12rem;
            transform-origin: top right;
            transition: opacity 0.15s ease-out, transform 0.15s ease-out;
        }

        /* Animaciones suaves para apertura */
        [id^="dropdown-"]:not(.hidden) {
            opacity: 1;
            transform: scale(1);
        }

        [id^="dropdown-"].hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        /* Asegurar que los contenedores de la tabla permitan overflow, pero no afecten modales */
        .overflow-x-auto:not(#confirmStatusModal):not(#confirmStatusModal *) {
            overflow: visible !important;
        }

        .table-container:not(#confirmStatusModal):not(#confirmStatusModal *) {
            overflow: visible !important;
        }

        /* Asegurar que el modal tenga prioridad sobre otros estilos */
        #confirmStatusModal {
            overflow-y: auto !important;
        }
    </style>
    @endpush

    @push('scripts')
    @vite(['resources/js/admin-remitentes.js'])
    @endpush

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
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Funcionalidad para cascada departamento-ciudad
        document.addEventListener('DOMContentLoaded', function() {
            initDepartamentoCiudadCascade();
        });

        function initDepartamentoCiudadCascade() {
            const departamentoSelect = document.getElementById('departamento_id');
            const ciudadSelect = document.getElementById('ciudad_id');
            const departamentoNombreInput = document.getElementById('departamento_nombre');
            const ciudadNombreInput = document.getElementById('ciudad_nombre');

            if (!departamentoSelect || !ciudadSelect) {
                return;
            }

            // Manejar cambio de departamento
            departamentoSelect.addEventListener('change', function() {
                const departamentoId = this.value;
                const departamentoOption = this.options[this.selectedIndex];

                // Actualizar campo oculto con el nombre del departamento
                if (departamentoNombreInput) {
                    departamentoNombreInput.value = departamentoOption.dataset.nombre || '';
                }

                // Limpiar ciudad
                ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
                ciudadSelect.disabled = true;
                ciudadSelect.classList.add('bg-gray-100');

                if (ciudadNombreInput) {
                    ciudadNombreInput.value = '';
                }

                if (departamentoId) {
                    // Cargar ciudades del departamento seleccionado
                    cargarCiudadesPorDepartamento(departamentoId);
                } else {
                    // No hay departamento seleccionado
                    ciudadSelect.innerHTML = '<option value="">Primero seleccione un departamento...</option>';
                    ciudadSelect.disabled = true;
                    ciudadSelect.classList.add('bg-gray-100');
                }
            });

            // Manejar cambio de ciudad
            ciudadSelect.addEventListener('change', function() {
                const ciudadOption = this.options[this.selectedIndex];

                // Actualizar campo oculto con el nombre de la ciudad
                if (ciudadNombreInput) {
                    ciudadNombreInput.value = ciudadOption.dataset.nombre || '';
                }
            });
        }

        function cargarCiudadesPorDepartamento(departamentoId) {
            const ciudadSelect = document.getElementById('ciudad_id');
            const ciudadNombreInput = document.getElementById('ciudad_nombre');

            if (!ciudadSelect) return;

            // Realizar petición AJAX
            fetch(`/api/ciudades/por-departamento?departamento_id=${departamentoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar ciudades');
                    }
                    return response.json();
                })
                .then(ciudades => {
                    // Limpiar select
                    ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';

                    // Agregar ciudades
                    ciudades.forEach(ciudad => {
                        const option = document.createElement('option');
                        option.value = ciudad.id;
                        option.textContent = ciudad.nombre;
                        option.dataset.nombre = ciudad.nombre;
                        ciudadSelect.appendChild(option);
                    });

                    // Habilitar select
                    ciudadSelect.disabled = false;
                    ciudadSelect.classList.remove('bg-gray-100');
                })
                .catch(error => {
                    console.error('Error al cargar ciudades:', error);
                    ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
                    ciudadSelect.disabled = true;
                    ciudadSelect.classList.add('bg-gray-100');
                });
        }
    </script>
</x-app-layout>
