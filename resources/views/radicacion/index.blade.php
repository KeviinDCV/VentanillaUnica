<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Radicación
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Gestión integral de documentos y radicados
                </p>
            </div>
            <x-hospital-brand />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">

            <!-- Botón Crear Radicado -->
            <div class="mb-8 text-center">
                <button id="btn-crear-radicado"
                        class="inline-flex items-center px-8 py-4 bg-uniradical-blue text-white font-medium rounded-lg shadow-sm hover:bg-opacity-90 transition-colors duration-200">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Radicado
                </button>
            </div>

            <!-- Modal Crear Radicado -->
            <div id="modal-crear-radicado" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[95vh] overflow-y-auto">
                        <!-- Header del Modal -->
                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                            <h3 id="modal-title" class="text-xl font-semibold text-gray-800">Seleccionar Tipo de Radicado</h3>
                            <button id="btn-cerrar-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Contenido del Modal -->
                        <div id="modal-content" class="p-6">
                            <!-- Selección de Tipo de Radicado -->
                            <div id="seleccion-tipo" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Entrada -->
                                <button data-tipo="entrada" class="opcion-radicado group bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-blue-300 hover:shadow-md transition-colors duration-200 text-left w-full">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-800 group-hover:text-blue-600">Entrada</h4>
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Radicar documentos de entrada al hospital</p>
                                    <div class="flex items-center text-blue-600 text-sm font-medium">
                                        Radicar documento
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Interno -->
                                <button data-tipo="interno" class="opcion-radicado group bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-green-300 hover:shadow-md transition-colors duration-200 text-left w-full">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-800 group-hover:text-green-600">Interno</h4>
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Gestionar comunicaciones internas</p>
                                    <div class="flex items-center text-green-600 text-sm font-medium">
                                        Crear interno
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Salida -->
                                @if(Auth::user()->isAdmin())
                                <button data-tipo="salida" class="opcion-radicado group bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-orange-300 hover:shadow-md transition-colors duration-200 text-left w-full">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-800 group-hover:text-orange-600">Salida</h4>
                                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Radicar documentos de salida</p>
                                    <div class="flex items-center text-orange-600 text-sm font-medium">
                                        Radicar salida
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </button>
                                @else
                                <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 opacity-50">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium text-gray-500">Salida</h4>
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-4">Solo disponible para administradores</p>
                                    <div class="flex items-center text-gray-400 text-sm font-medium">
                                        Acceso restringido
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Contenedor para el formulario dinámico -->
                            <div id="formulario-dinamico" class="hidden">
                                <!-- Aquí se cargará el formulario dinámicamente -->
                            </div>

                            <!-- Loading spinner -->
                            <div id="loading-spinner" class="hidden text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-uniradical-blue"></div>
                                <p class="mt-2 text-gray-600">Cargando formulario...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Consulta de Radicados -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <button id="btn-toggle-consulta" class="flex items-center space-x-2 text-lg font-medium text-gray-800 hover:text-uniradical-blue transition-colors">
                            <h3>Consultar Radicados</h3>
                            <svg id="icono-consulta" class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="flex space-x-3">
                            @if(isset($filtros) && count($filtros) > 0)
                                <a href="{{ route('radicacion.exportar', request()->query()) }}"
                                   class="export-button">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Exportar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenido Desplegable -->
                <div id="contenido-consulta" class="overflow-hidden transition-all duration-500 ease-out" style="max-height: 0; opacity: 0; transform: scale(1); transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease-out, transform 0.2s ease-out;">

                <!-- Estadísticas Rápidas -->
                @if(isset($estadisticas))
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Total</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($estadisticas['total']) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Pendientes</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($estadisticas['pendientes']) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">En Proceso</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($estadisticas['en_proceso']) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Respondidos</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($estadisticas['respondidos']) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Vencidos</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($estadisticas['vencidos']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Formulario de Búsqueda -->
                <div class="p-6">
                    <form method="GET" action="{{ route('radicacion.index') }}" id="filtros-form">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Número de Radicado -->
                            <div>
                                <label for="numero_radicado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Radicado
                                </label>
                                <input type="text" name="numero_radicado" id="numero_radicado"
                                       value="{{ request('numero_radicado') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                       placeholder="E-2025-000001">
                            </div>

                            <!-- Documento del Remitente -->
                            <div>
                                <label for="documento_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                    Documento del Remitente
                                </label>
                                <input type="text" name="documento_remitente" id="documento_remitente"
                                       value="{{ request('documento_remitente') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                       placeholder="12345678">
                            </div>

                            <!-- Nombre del Remitente -->
                            <div>
                                <label for="nombre_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Remitente
                                </label>
                                <input type="text" name="nombre_remitente" id="nombre_remitente"
                                       value="{{ request('nombre_remitente') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                       placeholder="Juan Pérez">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <!-- Dependencia Destino -->
                            <div>
                                <label for="dependencia_destino_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dependencia Destino
                                </label>
                                <select name="dependencia_destino_id" id="dependencia_destino_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todas las dependencias</option>
                                    @foreach($dependencias as $dependencia)
                                        <option value="{{ $dependencia->id }}"
                                                {{ request('dependencia_destino_id') == $dependencia->id ? 'selected' : '' }}>
                                            {{ $dependencia->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Radicado
                                </label>
                                <select name="tipo" id="tipo"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todos los tipos</option>
                                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="interno" {{ request('tipo') == 'interno' ? 'selected' : '' }}>Interno</option>
                                    <option value="salida" {{ request('tipo') == 'salida' ? 'selected' : '' }}>Salida</option>
                                </select>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <select name="estado" id="estado"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="respondido" {{ request('estado') == 'respondido' ? 'selected' : '' }}>Respondido</option>
                                    <option value="archivado" {{ request('estado') == 'archivado' ? 'selected' : '' }}>Archivado</option>
                                </select>
                            </div>

                            <!-- Solo Vencidos -->
                            <div>
                                <label for="solo_vencidos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Filtros Especiales
                                </label>
                                <div class="flex items-center">
                                    <input type="checkbox" name="solo_vencidos" id="solo_vencidos" value="1"
                                           {{ request('solo_vencidos') ? 'checked' : '' }}
                                           class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                                    <label for="solo_vencidos" class="ml-2 text-sm text-gray-700">
                                        Solo vencidos
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Fecha Desde -->
                            <div>
                                <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Desde
                                </label>
                                <input type="date" name="fecha_desde" id="fecha_desde"
                                       value="{{ request('fecha_desde') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <!-- Fecha Hasta -->
                            <div>
                                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Hasta
                                </label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta"
                                       value="{{ request('fecha_hasta') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-between">
                            <a href="{{ route('radicacion.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Limpiar Filtros
                            </a>
                            <button type="submit" name="buscar" value="1"
                                    class="search-button">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>
                </div> <!-- Fin contenido desplegable -->
            </div>

            <!-- Resultados de Consulta -->
            @if(isset($radicadosConsulta) && $radicadosConsulta->count() > 0)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-800">
                                Resultados de Búsqueda ({{ $radicadosConsulta->total() }} encontrados)
                            </h3>
                            @if(isset($filtros) && count($filtros) > 0)
                                <div class="text-sm text-gray-600">
                                    Filtros activos: {{ count($filtros) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Número
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Remitente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dependencia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($radicadosConsulta as $radicado)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-blue-600">
                                            {{ $radicado->numero_radicado }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($radicado->tipo === 'entrada') bg-blue-100 text-blue-800
                                            @elseif($radicado->tipo === 'interno') bg-green-100 text-green-800
                                            @elseif($radicado->tipo === 'salida') bg-orange-100 text-orange-800
                                            @endif">
                                            {{ ucfirst($radicado->tipo) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $radicado->remitente->nombre_completo }}">
                                            {{ $radicado->remitente->nombre_completo }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $radicado->remitente->identificacion_completa }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $radicado->dependenciaDestino->nombre }}">
                                            {{ $radicado->dependenciaDestino->nombre }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $radicado->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $radicado->estado === 'en_proceso' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $radicado->estado === 'respondido' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $radicado->estado === 'archivado' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $radicado->estado)) }}
                                        </span>
                                        @if($radicado->estaVencido())
                                            <div class="text-xs text-red-600 mt-1">VENCIDO</div>
                                        @elseif($radicado->fecha_limite_respuesta && $radicado->dias_restantes !== null)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $radicado->dias_restantes }} días
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $radicado->fecha_radicado->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('radicacion.entrada.show', $radicado->id) }}"
                                           class="text-uniradical-blue hover:text-opacity-80">
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($radicadosConsulta->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $radicadosConsulta->links() }}
                        </div>
                    @endif
                </div>
            @elseif(isset($radicadosConsulta) && $radicadosConsulta->count() == 0 && (count($filtros ?? []) > 0 || request()->has('buscar')))
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                    <div class="p-12 text-center">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron resultados</h3>
                            <p class="mt-1 text-sm text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                            <div class="mt-6">
                                <a href="{{ route('radicacion.index') }}"
                                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                    Limpiar Filtros
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Radicados Recientes -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Radicados Recientes</h3>
                        <button onclick="mostrarSeccionConsulta()"
                                class="text-sm font-medium text-uniradical-blue hover:text-opacity-80">
                            Buscar radicados
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Número
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Remitente/Destinatario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dependencia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($radicadosRecientes as $radicado)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-uniradical-blue">
                                        <a href="{{ route('radicacion.index', ['numero_radicado' => $radicado->numero_radicado]) }}"
                                           class="hover:text-opacity-80">
                                            {{ $radicado->numero_radicado }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($radicado->tipo === 'entrada') bg-blue-100 text-blue-800
                                        @elseif($radicado->tipo === 'interno') bg-green-100 text-green-800
                                        @elseif($radicado->tipo === 'salida') bg-orange-100 text-orange-800
                                        @endif">
                                        {{ ucfirst($radicado->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $radicado->remitente->nombre_completo }}">
                                        {{ $radicado->remitente->nombre_completo }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $radicado->dependenciaDestino->nombre }}">
                                        {{ $radicado->dependenciaDestino->nombre }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($radicado->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($radicado->estado === 'en_proceso') bg-blue-100 text-blue-800
                                        @elseif($radicado->estado === 'respondido') bg-green-100 text-green-800
                                        @elseif($radicado->estado === 'archivado') bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $radicado->estado)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $radicado->fecha_radicado->format('d/m/Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay radicados</h3>
                                        <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer radicado.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para el Modal y Consulta -->
    <script>
        // Variables globales para la funcionalidad de consulta
        let estaExpandido = false;
        let contenidoConsulta, iconoConsulta, expandirSeccion, colapsarSeccion;

        document.addEventListener('DOMContentLoaded', function() {
            const btnCrearRadicado = document.getElementById('btn-crear-radicado');
            const modalCrearRadicado = document.getElementById('modal-crear-radicado');
            const btnCerrarModal = document.getElementById('btn-cerrar-modal');

            // Abrir modal
            btnCrearRadicado.addEventListener('click', function() {
                mostrarSeleccionTipo();
                modalCrearRadicado.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevenir scroll del body
            });

            // Cerrar modal con botón X
            btnCerrarModal.addEventListener('click', function() {
                cerrarModal();
            });

            // Cerrar modal al hacer clic fuera del contenido
            modalCrearRadicado.addEventListener('click', function(e) {
                if (e.target === modalCrearRadicado) {
                    cerrarModal();
                }
            });

            // Cerrar modal con tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modalCrearRadicado.classList.contains('hidden')) {
                    cerrarModal();
                }
            });

            function cerrarModal() {
                modalCrearRadicado.classList.add('hidden');
                document.body.style.overflow = ''; // Restaurar scroll del body
                mostrarSeleccionTipo(); // Volver a la selección de tipo
            }

            // Funciones para el modal dinámico
            function mostrarSeleccionTipo() {
                document.getElementById('modal-title').textContent = 'Seleccionar Tipo de Radicado';
                document.getElementById('seleccion-tipo').classList.remove('hidden');
                document.getElementById('formulario-dinamico').classList.add('hidden');
                document.getElementById('loading-spinner').classList.add('hidden');
            }

            function mostrarLoading() {
                document.getElementById('seleccion-tipo').classList.add('hidden');
                document.getElementById('formulario-dinamico').classList.add('hidden');
                document.getElementById('loading-spinner').classList.remove('hidden');
            }

            function mostrarFormulario(tipo) {
                const titulos = {
                    'entrada': 'Crear Radicado de Entrada',
                    'interno': 'Crear Radicado Interno',
                    'salida': 'Crear Radicado de Salida'
                };

                document.getElementById('modal-title').textContent = titulos[tipo] || 'Crear Radicado';
                document.getElementById('seleccion-tipo').classList.add('hidden');
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('formulario-dinamico').classList.remove('hidden');
            }

            // Event listeners para las opciones de radicado
            document.querySelectorAll('.opcion-radicado').forEach(boton => {
                boton.addEventListener('click', function() {
                    const tipo = this.getAttribute('data-tipo');
                    cargarFormulario(tipo);
                });
            });

            // Función para cargar formulario via AJAX
            function cargarFormulario(tipo) {
                mostrarLoading();

                fetch(`/radicacion/form/${tipo}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al cargar el formulario');
                        }
                        return response.text();
                    })
                    .then(html => {
                        document.getElementById('formulario-dinamico').innerHTML = html;
                        mostrarFormulario(tipo);

                        // Inicializar funcionalidades del formulario
                        inicializarFormulario(tipo);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cargar el formulario. Por favor, intenta de nuevo.');
                        mostrarSeleccionTipo();
                    });
            }

            // Función para inicializar funcionalidades específicas del formulario
            function inicializarFormulario(tipo) {
                // Botón volver a selección
                const btnVolver = document.getElementById('btn-volver-seleccion');
                if (btnVolver) {
                    btnVolver.addEventListener('click', mostrarSeleccionTipo);
                }

                // Funcionalidades específicas según el tipo
                if (tipo === 'entrada') {
                    inicializarFormularioEntrada();
                } else if (tipo === 'interno') {
                    inicializarFormularioInterno();
                } else if (tipo === 'salida') {
                    inicializarFormularioSalida();
                }
            }

            // Funcionalidades específicas del formulario de entrada
            function inicializarFormularioEntrada() {
                // Cascada de departamento-ciudad
                const departamentoSelect = document.getElementById('departamento_remitente');
                const ciudadSelect = document.getElementById('ciudad_remitente');

                if (departamentoSelect && ciudadSelect) {
                    departamentoSelect.addEventListener('change', function() {
                        const departamentoSeleccionado = this.value;
                        const opciones = ciudadSelect.querySelectorAll('option');

                        opciones.forEach(opcion => {
                            if (opcion.value === '') {
                                opcion.style.display = 'block';
                            } else {
                                const departamentoOpcion = opcion.getAttribute('data-departamento');
                                opcion.style.display = departamentoOpcion === departamentoSeleccionado ? 'block' : 'none';
                            }
                        });

                        ciudadSelect.value = '';
                    });
                }

                // Buscar remitente existente
                const btnBuscarRemitente = document.getElementById('btn-buscar-remitente');
                if (btnBuscarRemitente) {
                    btnBuscarRemitente.addEventListener('click', function() {
                        const numeroDocumento = document.getElementById('numero_documento').value;
                        if (numeroDocumento) {
                            buscarRemitente(numeroDocumento);
                        } else {
                            alert('Por favor, ingrese un número de documento para buscar.');
                        }
                    });
                }
            }

            // Función para buscar remitente existente
            function buscarRemitente(numeroDocumento) {
                // Esta función se puede implementar más adelante para buscar remitentes existentes
                console.log('Buscando remitente:', numeroDocumento);
            }

            // Funcionalidades específicas del formulario interno
            function inicializarFormularioInterno() {
                // Mostrar/ocultar fecha límite según requiere respuesta
                const requiereRespuesta = document.getElementById('requiere_respuesta');
                const fechaLimiteContainer = document.getElementById('fecha-limite-container');

                if (requiereRespuesta && fechaLimiteContainer) {
                    requiereRespuesta.addEventListener('change', function() {
                        if (this.value === 'si') {
                            fechaLimiteContainer.classList.remove('hidden');
                            document.getElementById('fecha_limite_respuesta').required = true;
                        } else {
                            fechaLimiteContainer.classList.add('hidden');
                            document.getElementById('fecha_limite_respuesta').required = false;
                            document.getElementById('fecha_limite_respuesta').value = '';
                        }
                    });

                    // Verificar estado inicial
                    if (requiereRespuesta.value === 'si') {
                        fechaLimiteContainer.classList.remove('hidden');
                        document.getElementById('fecha_limite_respuesta').required = true;
                    }
                }
            }

            // Funcionalidades específicas del formulario de salida
            function inicializarFormularioSalida() {
                // Cascada de departamento-ciudad para destinatario
                const departamentoSelect = document.getElementById('departamento_destinatario');
                const ciudadSelect = document.getElementById('ciudad_destinatario');

                if (departamentoSelect && ciudadSelect) {
                    departamentoSelect.addEventListener('change', function() {
                        const departamentoSeleccionado = this.value;
                        const opciones = ciudadSelect.querySelectorAll('option');

                        opciones.forEach(opcion => {
                            if (opcion.value === '') {
                                opcion.style.display = 'block';
                            } else {
                                const departamentoOpcion = opcion.getAttribute('data-departamento');
                                opcion.style.display = departamentoOpcion === departamentoSeleccionado ? 'block' : 'none';
                            }
                        });

                        ciudadSelect.value = '';
                    });
                }
            }

            // Funcionalidad de consulta desplegable
            const btnToggleConsulta = document.getElementById('btn-toggle-consulta');
            contenidoConsulta = document.getElementById('contenido-consulta');
            iconoConsulta = document.getElementById('icono-consulta');

            // Funciones para expandir/colapsar
            expandirSeccion = function() {
                estaExpandido = true;
                contenidoConsulta.style.maxHeight = contenidoConsulta.scrollHeight + 'px';
                contenidoConsulta.style.opacity = '1';
                iconoConsulta.style.transform = 'rotate(180deg)';
            };

            colapsarSeccion = function() {
                estaExpandido = false;
                contenidoConsulta.style.maxHeight = '0px';
                contenidoConsulta.style.opacity = '0';
                iconoConsulta.style.transform = 'rotate(0deg)';
                contenidoConsulta.style.transform = 'scale(1)';
            };

            // Estado inicial: expandido si hay filtros activos o resultados
            const hayFiltrosActivos = {{ isset($filtros) && count($filtros) > 0 ? 'true' : 'false' }};
            const hayResultados = {{ isset($radicadosConsulta) ? 'true' : 'false' }};

            if (hayFiltrosActivos || hayResultados) {
                expandirSeccion();
            }

            btnToggleConsulta.addEventListener('click', function() {
                if (estaExpandido) {
                    colapsarSeccion();
                } else {
                    expandirSeccion();
                }
            });

            // Funcionalidad de consulta de radicados
            if (document.getElementById('filtros-form')) {
                // Auto-submit para filtros rápidos
                const filtrosRapidos = document.querySelectorAll('.filtro-rapido');
                filtrosRapidos.forEach(filtro => {
                    filtro.addEventListener('change', () => {
                        document.getElementById('filtros-form').submit();
                    });
                });
            }
        });

        // Función global para mostrar la sección de consulta
        function mostrarSeccionConsulta() {
            // Mostrar la sección si está oculta
            if (!estaExpandido && expandirSeccion) {
                expandirSeccion();
            }

            // Hacer scroll suave hacia la sección con un pequeño delay para la animación
            setTimeout(() => {
                document.getElementById('btn-toggle-consulta').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 200);
        }
    </script>
</x-app-layout>
