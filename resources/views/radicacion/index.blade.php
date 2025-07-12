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
                                            <button onclick="verDetallesRadicado({{ $radicado->id }})"
                                                    class="hover:text-blue-800 hover:underline cursor-pointer">
                                                {{ $radicado->numero_radicado }}
                                            </button>
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
                                        <div class="relative">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                    onclick="toggleDropdown('dropdown-radicado-{{ $radicado->id }}')">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                </svg>
                                            </button>

                                            <div id="dropdown-radicado-{{ $radicado->id }}"
                                                 class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                                 data-dropdown-menu>
                                                <div class="py-1" role="menu">
                                                    <!-- Ver Detalles -->
                                                    <button onclick="verDetallesRadicado({{ $radicado->id }})"
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Ver Detalles
                                                    </button>

                                                    <!-- Editar -->
                                                    @php
                                                        $puedeEditar = auth()->user()->isAdmin() ||
                                                                      (auth()->user()->isVentanilla() &&
                                                                       auth()->user()->id === $radicado->usuario_radica_id &&
                                                                       in_array($radicado->estado, ['pendiente', 'en_proceso']));
                                                    @endphp

                                                    <button onclick="editarRadicado({{ $radicado->id }})"
                                                            class="w-full text-left px-4 py-2 text-sm {{ $puedeEditar ? 'text-gray-700 hover:bg-gray-100' : 'text-gray-400 cursor-not-allowed' }} flex items-center">
                                                        <svg class="w-4 h-4 mr-3 {{ $puedeEditar ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar
                                                        @if(!$puedeEditar && !auth()->user()->isAdmin())
                                                            <span class="ml-auto text-xs text-gray-400">(No disponible)</span>
                                                        @endif
                                                    </button>

                                                    @if(auth()->user()->hasRole('admin'))
                                                        <div class="border-t border-gray-100"></div>
                                                        <!-- Eliminar -->
                                                        <button onclick="eliminarRadicado({{ $radicado->id }}, '{{ $radicado->numero_radicado }}')"
                                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                        <h3 class="text-lg font-medium text-gray-800">Mis Radicados</h3>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($radicadosRecientes as $radicado)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-uniradical-blue">
                                        <button onclick="verDetallesRadicado({{ $radicado->id }})"
                                                class="hover:text-opacity-80 hover:underline cursor-pointer">
                                            {{ $radicado->numero_radicado }}
                                        </button>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-radicado-{{ $radicado->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-radicado-{{ $radicado->id }}"
                                             class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Ver Detalles -->
                                                <button onclick="verDetallesRadicado({{ $radicado->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Ver Detalles
                                                </button>

                                                <!-- Editar -->
                                                @php
                                                    $puedeEditar = auth()->user()->isAdmin() ||
                                                                  (auth()->user()->isVentanilla() &&
                                                                   auth()->user()->id === $radicado->usuario_radica_id &&
                                                                   in_array($radicado->estado, ['pendiente', 'en_proceso']));
                                                @endphp

                                                <button onclick="editarRadicado({{ $radicado->id }})"
                                                        class="w-full text-left px-4 py-2 text-sm {{ $puedeEditar ? 'text-gray-700 hover:bg-gray-100' : 'text-gray-400 cursor-not-allowed' }} flex items-center">
                                                    <svg class="w-4 h-4 mr-3 {{ $puedeEditar ? 'text-green-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                    @if(!$puedeEditar && !auth()->user()->isAdmin())
                                                        <span class="ml-auto text-xs text-gray-400">(No disponible)</span>
                                                    @endif
                                                </button>

                                                @if(auth()->user()->hasRole('admin'))
                                                    <div class="border-t border-gray-100"></div>
                                                    <!-- Eliminar -->
                                                    <button onclick="eliminarRadicado({{ $radicado->id }}, '{{ $radicado->numero_radicado }}')"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
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

                <!-- Paginación -->
                @if($radicadosRecientes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $radicadosRecientes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Detalles del Radicado -->
    <div id="modalDetallesRadicado" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative mx-auto mt-4 mb-4 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out max-h-[95vh] overflow-hidden flex flex-col">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-white flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-800">Detalles del Radicado</h3>
                <div class="flex items-center space-x-3">
                    <button id="btn-imprimir-modal" class="inline-flex items-center px-3 py-1.5 bg-uniradical-blue text-white text-sm font-medium rounded-md hover:bg-opacity-90 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir
                    </button>
                    <button onclick="cerrarModalDetalles()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div id="contenidoDetallesRadicado" class="flex-1 overflow-y-auto p-4">
                <!-- El contenido se cargará dinámicamente aquí -->
            </div>
        </div>
    </div>

    <!-- Modal de Edición del Radicado -->
    <div id="modalEditarRadicado" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative mx-auto mt-4 mb-4 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out max-h-[95vh] overflow-hidden flex flex-col">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-white flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-800">Editar Radicado</h3>
                <div class="flex items-center space-x-3">
                    <button onclick="cerrarModalEditar()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div id="contenidoEditarRadicado" class="flex-1 overflow-y-auto p-4">
                <!-- El contenido se cargará dinámicamente aquí -->
            </div>

            <!-- Footer del Modal -->
            <div class="flex justify-end space-x-3 p-4 border-t border-gray-200 bg-white flex-shrink-0">
                <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" id="btn-guardar-edicion" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript para el Modal y Consulta -->
    <script>
        // Variables globales
        window.userRole = '{{ auth()->user()->role }}';

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
                    console.log('Botón clickeado, tipo:', tipo);
                    cargarFormulario(tipo);
                });
            });

            // Función para cargar formulario via AJAX
            function cargarFormulario(tipo) {
                console.log('Cargando formulario:', tipo);
                mostrarLoading();

                console.log('Cargando formulario:', tipo);

                // Obtener token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const token = csrfToken ? csrfToken.getAttribute('content') : '';

                console.log('Token CSRF encontrado:', token ? 'Sí' : 'No');

                fetch(`/radicacion/form/${tipo}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response ok:', response.ok);

                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Error response:', text);
                                console.error('Error response length:', text.length);
                                throw new Error(`Error ${response.status}: ${text.substring(0, 200)}...`);
                            });
                        }
                        return response.text();
                    })
                    .then(html => {
                        console.log('HTML recibido, longitud:', html.length);

                        if (html.length === 0) {
                            throw new Error('Respuesta vacía del servidor');
                        }

                        document.getElementById('formulario-dinamico').innerHTML = html;
                        mostrarFormulario(tipo);

                        // Inicializar funcionalidades del formulario
                        inicializarFormulario(tipo);
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        console.error('Error stack:', error.stack);
                        console.error('Error message:', error.message);
                        alert('Error al cargar el formulario. Por favor, intenta de nuevo.');
                        mostrarSeleccionTipo();
                    });
            }

            // Función para inicializar funcionalidades específicas del formulario
            function inicializarFormulario(tipo) {
                console.log('Inicializando formulario:', tipo);

                try {
                    // Botón volver a selección
                    const btnVolver = document.getElementById('btn-volver-seleccion');
                    if (btnVolver) {
                        btnVolver.addEventListener('click', mostrarSeleccionTipo);
                    }

                // Inicializar carga de archivos para modales
                if (window.modalFileUpload) {
                    window.modalFileUpload.initializeAll();
                }

                // Disparar evento personalizado para notificar que el modal se abrió
                document.dispatchEvent(new CustomEvent('modalOpened', { detail: { tipo: tipo } }));

                // Funcionalidades específicas según el tipo
                if (tipo === 'entrada') {
                    inicializarFormularioEntrada();
                } else if (tipo === 'interno') {
                    inicializarFormularioInterno();
                } else if (tipo === 'salida') {
                    inicializarFormularioSalida();
                }

                console.log('Formulario inicializado exitosamente:', tipo);

                } catch (error) {
                    console.error('Error al inicializar formulario:', error);
                    throw error;
                }
            }

            // Funcionalidades específicas del formulario de entrada
            function inicializarFormularioEntrada() {
                console.log('Inicializando formulario de entrada...');

                try {
                    // Verificar que las funciones del TRD selector estén disponibles
                    if (typeof window.loadSeriesForRadicacion === 'function') {
                        console.log('Funciones TRD selector disponibles');
                    } else {
                        console.warn('Funciones TRD selector NO disponibles');
                    }

                    // Inicializar tipo de remitente por defecto
                    const tipoRemitenteAnonimo = document.querySelector('input[name="tipo_remitente"][value="anonimo"]');
                    if (tipoRemitenteAnonimo && !document.querySelector('input[name="tipo_remitente"]:checked')) {
                        tipoRemitenteAnonimo.checked = true;
                        console.log('Tipo remitente anónimo seleccionado por defecto');
                    }

                    // Inicializar event listeners para tipo de remitente
                    const tipoRemitenteRadios = document.querySelectorAll('input[name="tipo_remitente"]');
                    tipoRemitenteRadios.forEach(radio => {
                        radio.addEventListener('change', function() {
                            console.log('Tipo remitente cambiado a:', this.value);
                            toggleTipoRemitenteModal();
                        });
                    });

                    // Ejecutar toggle inicial
                    toggleTipoRemitenteModal();

                    // Inicializar cascada departamento-ciudad
                    initDepartamentoCiudadCascade();

                // Botón previsualizar
                const btnPreview = document.getElementById('btn-preview');
                if (btnPreview) {
                    btnPreview.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.RadicacionEntrada && window.RadicacionEntrada.mostrarPreview) {
                            window.RadicacionEntrada.mostrarPreview();
                        }
                    });
                }

                // Event listener para envío del formulario
                const formEntrada = document.getElementById('radicacionEntradaForm');
                if (formEntrada) {
                    formEntrada.addEventListener('submit', function(e) {
                        console.log('Formulario de entrada enviado');

                        // Verificar campos requeridos
                        const tipoRemitente = document.querySelector('input[name="tipo_remitente"]:checked');
                        const nombreCompleto = document.getElementById('nombre_completo').value;
                        const medioRecepcion = document.getElementById('medio_recepcion').value;
                        const tipoComunicacion = document.getElementById('tipo_comunicacion').value;
                        const numeroFolios = document.getElementById('numero_folios').value;
                        const trdId = document.getElementById('trd_id').value;
                        const dependenciaDestino = document.getElementById('dependencia_destino_id').value;
                        const medioRespuesta = document.getElementById('medio_respuesta').value;
                        const tipoAnexo = document.getElementById('tipo_anexo').value;
                        const documento = document.getElementById('documento_modal').files.length;

                        console.log('Validación de campos:', {
                            tipoRemitente: tipoRemitente ? tipoRemitente.value : 'NO SELECCIONADO',
                            nombreCompleto: nombreCompleto || 'VACÍO',
                            medioRecepcion: medioRecepcion || 'VACÍO',
                            tipoComunicacion: tipoComunicacion || 'VACÍO',
                            numeroFolios: numeroFolios || 'VACÍO',
                            trdId: trdId || 'VACÍO',
                            dependenciaDestino: dependenciaDestino || 'VACÍO',
                            medioRespuesta: medioRespuesta || 'VACÍO',
                            tipoAnexo: tipoAnexo || 'VACÍO',
                            documento: documento > 0 ? 'ARCHIVO SELECCIONADO' : 'NO HAY ARCHIVO'
                        });

                        // Permitir envío normal del formulario
                        return true;
                    });
                }

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

                console.log('Formulario de entrada inicializado exitosamente');

                } catch (error) {
                    console.error('Error al inicializar formulario de entrada:', error);
                    throw error;
                }
            }

            // Función para buscar remitente existente
            function buscarRemitente(numeroDocumento) {
                // Esta función se puede implementar más adelante para buscar remitentes existentes
                console.log('Buscando remitente:', numeroDocumento);
            }

            // Funcionalidades específicas del formulario interno
            function inicializarFormularioInterno() {
                // Inicializar RadicacionInterna
                if (window.RadicacionInterna) {
                    window.RadicacionInterna.init();
                }

                // Botón previsualizar
                const btnPreview = document.getElementById('btn-preview');
                if (btnPreview) {
                    btnPreview.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.RadicacionInterna && window.RadicacionInterna.mostrarPreview) {
                            window.RadicacionInterna.mostrarPreview();
                        }
                    });
                }

                // Event listener para envío del formulario - ELIMINADO para evitar conflictos
                // El formulario interno maneja su propia validación en interno.blade.php

                // Mostrar/ocultar fecha límite según requiere respuesta
                const requiereRespuesta = document.getElementById('requiere_respuesta');
                const fechaLimiteContainer = document.getElementById('fecha-limite-container');

                if (requiereRespuesta && fechaLimiteContainer) {
                    requiereRespuesta.addEventListener('change', function() {
                        if (this.value === 'si') {
                            fechaLimiteContainer.classList.remove('hidden');
                            // NO AGREGAR REQUIRED - se valida en el servidor
                        } else {
                            fechaLimiteContainer.classList.add('hidden');
                            document.getElementById('fecha_limite_respuesta').value = '';
                        }
                    });

                    // Verificar estado inicial
                    if (requiereRespuesta.value === 'si') {
                        fechaLimiteContainer.classList.remove('hidden');
                        // NO AGREGAR REQUIRED - se valida en el servidor
                    }
                }
            }

            // Funcionalidades específicas del formulario de salida
            function inicializarFormularioSalida() {
                // Inicializar RadicacionSalida
                if (window.RadicacionSalida) {
                    window.RadicacionSalida.init();
                }

                // Botón previsualizar
                const btnPreview = document.getElementById('btn-preview');
                if (btnPreview) {
                    btnPreview.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.RadicacionSalida && window.RadicacionSalida.mostrarPreview) {
                            window.RadicacionSalida.mostrarPreview();
                        }
                    });
                }

                // Event listener para envío del formulario
                const formSalida = document.getElementById('radicacionSalidaForm');
                if (formSalida) {
                    formSalida.addEventListener('submit', function(e) {
                        console.log('Formulario de salida enviado');
                        // Permitir envío normal del formulario
                        return true;
                    });
                }

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

        // Función para editar radicado
        function editarRadicado(id) {
            // Mostrar loading en el modal
            const modal = document.getElementById('modalEditarRadicado');
            const contenido = document.getElementById('contenidoEditarRadicado');

            contenido.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <svg class="animate-spin h-8 w-8 text-uniradical-blue" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600">Cargando formulario de edición...</span>
                </div>
            `;

            // Mostrar modal con animación
            mostrarModalEditar();

            // Cargar datos del radicado para edición
            fetch(`/radicacion/${id}/editar`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Error al cargar los datos del radicado');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Guardar datos globalmente para uso en funciones auxiliares
                    window.unidadesAdministrativasData = data.unidades_administrativas;

                    contenido.innerHTML = generarFormularioEdicion(data);

                    // Configurar eventos del formulario
                    configurarFormularioEdicion(id, data);
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Determinar el tipo de error y mostrar el mensaje apropiado
                    const isPermissionError = error.message.includes('No se puede editar');
                    const iconColor = isPermissionError ? 'text-yellow-400' : 'text-red-400';
                    const iconPath = isPermissionError
                        ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z'
                        : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z';

                    contenido.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                ${isPermissionError ? 'Edición no permitida' : 'Error al cargar formulario'}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-md mx-auto">${error.message}</p>
                            ${isPermissionError ? `
                                <div class="mt-4">
                                    <button onclick="cerrarModalEditar()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                                        Entendido
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
        }

        // Función para eliminar radicado (solo administradores)
        function eliminarRadicado(id, numeroRadicado) {
            if (confirm(`¿Está seguro de que desea eliminar el radicado ${numeroRadicado}?\n\nEsta acción no se puede deshacer.`)) {
                // Mostrar loading
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;
                button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                button.disabled = true;

                // Realizar petición AJAX
                fetch(`/radicacion/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Error al eliminar el radicado');
                })
                .then(data => {
                    // Mostrar mensaje de éxito
                    alert('Radicado eliminado exitosamente');
                    // Recargar la página para actualizar la lista
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el radicado. Por favor, inténtelo de nuevo.');
                    // Restaurar botón
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
            }
        }

        // Función para manejar los menús desplegables con posicionamiento absoluto
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) {
                console.error('No se encontró el dropdown con ID:', dropdownId);
                return;
            }

            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-radicado-"]').forEach(d => {
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

                    // Si se sale por abajo, cambiar a apertura hacia arriba
                    if (rect.bottom > window.innerHeight - 10) {
                        dropdown.classList.remove('top-full', 'mt-1');
                        dropdown.classList.add('bottom-full', 'mb-1');
                    }
                }, 10);
            } else {
                // Ocultar dropdown
                dropdown.classList.add('hidden');
            }
        }

        // Función para resetear estilos de dropdown
        function resetDropdownStyles(dropdown) {
            dropdown.style.top = '';
            dropdown.style.bottom = '';
            dropdown.style.left = '';
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[id^="dropdown-radicado-"]') && !event.target.closest('button[onclick*="toggleDropdown"]')) {
                document.querySelectorAll('[id^="dropdown-radicado-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                    resetDropdownStyles(dropdown);
                });
            }
        });

        // Cerrar dropdown después de hacer clic en una acción
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="dropdown-radicado-"] button') || event.target.closest('[id^="dropdown-radicado-"] a')) {
                // Pequeño delay para permitir que la acción se ejecute
                setTimeout(() => {
                    document.querySelectorAll('[id^="dropdown-radicado-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                        resetDropdownStyles(dropdown);
                    });
                }, 100);
            }
        });

        // Función para ver detalles del radicado en modal
        function verDetallesRadicado(id) {
            // Mostrar loading en el modal
            const modal = document.getElementById('modalDetallesRadicado');
            const contenido = document.getElementById('contenidoDetallesRadicado');

            contenido.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <svg class="animate-spin h-8 w-8 text-uniradical-blue" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600">Cargando detalles...</span>
                </div>
            `;

            // Mostrar modal con animación
            mostrarModalDetalles();

            // Cargar detalles del radicado
            fetch(`/radicacion/${id}/detalles`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los detalles');
                    }
                    return response.json();
                })
                .then(data => {
                    contenido.innerHTML = generarContenidoDetalles(data);

                    // Configurar botón de imprimir
                    const btnImprimir = document.getElementById('btn-imprimir-modal');
                    btnImprimir.onclick = () => imprimirDetalles(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    contenido.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Error al cargar detalles</h3>
                            <p class="mt-1 text-sm text-gray-500">No se pudieron cargar los detalles del radicado.</p>
                        </div>
                    `;
                });
        }

        function mostrarModalDetalles() {
            const modal = document.getElementById('modalDetallesRadicado');
            const modalContent = modal.querySelector('.relative');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animación de entrada
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
            }, 10);
        }

        function cerrarModalDetalles() {
            const modal = document.getElementById('modalDetallesRadicado');
            const modalContent = modal.querySelector('.relative');

            // Animación de salida
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        function generarContenidoDetalles(radicado) {
            const estadoClasses = {
                'pendiente': 'bg-yellow-100 text-yellow-800',
                'en_proceso': 'bg-blue-100 text-blue-800',
                'respondido': 'bg-green-100 text-green-800',
                'archivado': 'bg-gray-100 text-gray-800'
            };

            return `
                <!-- Información del Radicado -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-medium text-gray-800">Información del Radicado</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${estadoClasses[radicado.estado] || 'bg-gray-100 text-gray-800'}">
                            ${radicado.estado.charAt(0).toUpperCase() + radicado.estado.slice(1).replace('_', ' ')}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <h4 class="font-medium text-blue-800 mb-1 text-sm">Número de Radicado</h4>
                            <p class="text-xl font-bold text-blue-900">${radicado.numero_radicado}</p>
                        </div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <h4 class="font-medium text-gray-800 mb-1 text-sm">Fecha y Hora</h4>
                            <p class="text-sm text-gray-600">${radicado.fecha_radicado} - ${radicado.hora_radicado}</p>
                        </div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <h4 class="font-medium text-gray-800 mb-1 text-sm">Radicado por</h4>
                            <p class="text-sm text-gray-600">${radicado.usuario_radica}</p>
                        </div>
                    </div>
                </div>

                <!-- Información del Remitente -->
                <div class="mb-5">
                    <h3 class="text-base font-medium text-gray-800 mb-3 border-b border-gray-200 pb-1">
                        Información del Remitente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                            <p class="text-sm text-gray-900">${radicado.remitente.tipo === 'anonimo' ? 'Anónimo' : 'Registrado'}</p>
                        </div>
                        ${radicado.remitente.tipo === 'registrado' && radicado.remitente.identificacion_completa ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Identificación</label>
                            <p class="text-sm text-gray-900">${radicado.remitente.identificacion_completa}</p>
                        </div>
                        ` : ''}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                            <p class="text-sm text-gray-900">${radicado.remitente.nombre_completo}</p>
                        </div>
                        ${radicado.remitente.contacto_completo ? `
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contacto</label>
                            <p class="text-sm text-gray-900">${radicado.remitente.contacto_completo}</p>
                        </div>
                        ` : ''}
                        ${radicado.remitente.direccion ? `
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <p class="text-sm text-gray-900">${radicado.remitente.direccion}</p>
                        </div>
                        ` : ''}
                        ${radicado.remitente.entidad ? `
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Entidad</label>
                            <p class="text-sm text-gray-900">${radicado.remitente.entidad}</p>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Información del Documento -->
                <div class="mb-5">
                    <h3 class="text-base font-medium text-gray-800 mb-3 border-b border-gray-200 pb-1">
                        Información del Documento
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medio de Recepción</label>
                            <p class="text-sm text-gray-900">${radicado.medio_recepcion.charAt(0).toUpperCase() + radicado.medio_recepcion.slice(1)}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Comunicación</label>
                            <p class="text-sm text-gray-900">${radicado.tipo_comunicacion.charAt(0).toUpperCase() + radicado.tipo_comunicacion.slice(1)}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Folios</label>
                            <p class="text-sm text-gray-900">${radicado.numero_folios}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Anexo</label>
                            <p class="text-sm text-gray-900">${radicado.tipo_anexo.charAt(0).toUpperCase() + radicado.tipo_anexo.slice(1)}</p>
                        </div>
                        ${radicado.observaciones ? `
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <p class="text-sm text-gray-900">${radicado.observaciones}</p>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- TRD -->
                <div class="mb-5">
                    <h3 class="text-base font-medium text-gray-800 mb-3 border-b border-gray-200 pb-1">
                        Tabla de Retención Documental (TRD)
                    </h3>
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Código</label>
                                <p class="text-sm text-blue-900">${radicado.trd.codigo || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Serie</label>
                                <p class="text-sm text-blue-900">${radicado.trd.serie || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Subserie</label>
                                <p class="text-sm text-blue-900">${radicado.trd.subserie || 'N/A'}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="block text-sm font-medium text-blue-700 mb-1">Descripción</label>
                            <p class="text-sm text-blue-900">${radicado.trd.descripcion || 'Sin descripción'}</p>
                        </div>
                    </div>
                </div>

                <!-- Destino -->
                <div class="mb-5">
                    <h3 class="text-base font-medium text-gray-800 mb-3 border-b border-gray-200 pb-1">
                        Destino del Documento
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dependencia Destino</label>
                            <p class="text-sm text-gray-900">${radicado.dependencia_destino}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medio de Respuesta</label>
                            <p class="text-sm text-gray-900">${radicado.medio_respuesta.charAt(0).toUpperCase() + radicado.medio_respuesta.slice(1).replace('_', ' ')}</p>
                        </div>
                        ${radicado.fecha_limite_respuesta ? `
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Límite de Respuesta</label>
                            <p class="text-sm text-gray-900 ${radicado.esta_vencido ? 'text-red-600 font-medium' : ''}">
                                ${radicado.fecha_limite_respuesta}
                                ${radicado.esta_vencido ? '<span class="text-red-600">(VENCIDO)</span>' : ''}
                                ${radicado.dias_restantes !== null && !radicado.esta_vencido ? `<span class="text-gray-500">(${radicado.dias_restantes} días restantes)</span>` : ''}
                            </p>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Documentos Adjuntos -->
                ${radicado.documentos && radicado.documentos.length > 0 ? `
                <div class="mb-4">
                    <h3 class="text-base font-medium text-gray-800 mb-3 border-b border-gray-200 pb-1">
                        Documentos Adjuntos
                    </h3>
                    <div class="space-y-2">
                        ${radicado.documentos.map(doc => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <div class="flex items-center space-x-3">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">${doc.nombre_archivo}</p>
                                    <p class="text-xs text-gray-500">
                                        ${doc.tipo_archivo} - ${doc.tamaño_legible}
                                        ${doc.es_principal ? '<span class="text-blue-600">(Principal)</span>' : ''}
                                    </p>
                                </div>
                            </div>
                            <a href="${doc.url_descarga}" class="text-uniradical-blue hover:text-opacity-80 text-sm font-medium">
                                Descargar
                            </a>
                        </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            `;
        }

        function imprimirDetalles(radicado) {
            // Crear ventana de impresión con el contenido del modal
            const ventanaImpresion = window.open('', '_blank');
            ventanaImpresion.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Radicado ${radicado.numero_radicado}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .section { margin-bottom: 20px; }
                        .section h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
                        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
                        .field { margin-bottom: 10px; }
                        .label { font-weight: bold; color: #333; }
                        .value { margin-top: 2px; }
                        @media print { body { margin: 0; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Detalles del Radicado</h1>
                        <h2>${radicado.numero_radicado}</h2>
                    </div>
                    ${generarContenidoImpresion(radicado)}
                </body>
                </html>
            `);
            ventanaImpresion.document.close();
            ventanaImpresion.print();
        }

        function generarContenidoImpresion(radicado) {
            // Versión simplificada para impresión
            return `
                <div class="section">
                    <h3>Información del Radicado</h3>
                    <div class="grid">
                        <div class="field">
                            <div class="label">Número de Radicado:</div>
                            <div class="value">${radicado.numero_radicado}</div>
                        </div>
                        <div class="field">
                            <div class="label">Fecha y Hora:</div>
                            <div class="value">${radicado.fecha_radicado} - ${radicado.hora_radicado}</div>
                        </div>
                        <div class="field">
                            <div class="label">Estado:</div>
                            <div class="value">${radicado.estado.charAt(0).toUpperCase() + radicado.estado.slice(1).replace('_', ' ')}</div>
                        </div>
                        <div class="field">
                            <div class="label">Radicado por:</div>
                            <div class="value">${radicado.usuario_radica}</div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>Información del Remitente</h3>
                    <div class="grid">
                        <div class="field">
                            <div class="label">Tipo:</div>
                            <div class="value">${radicado.remitente.tipo === 'anonimo' ? 'Anónimo' : 'Registrado'}</div>
                        </div>
                        <div class="field">
                            <div class="label">Nombre Completo:</div>
                            <div class="value">${radicado.remitente.nombre_completo}</div>
                        </div>
                        ${radicado.remitente.identificacion_completa ? `
                        <div class="field">
                            <div class="label">Identificación:</div>
                            <div class="value">${radicado.remitente.identificacion_completa}</div>
                        </div>
                        ` : ''}
                        ${radicado.remitente.contacto_completo ? `
                        <div class="field">
                            <div class="label">Contacto:</div>
                            <div class="value">${radicado.remitente.contacto_completo}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>

                <div class="section">
                    <h3>TRD</h3>
                    <div class="grid">
                        <div class="field">
                            <div class="label">Código:</div>
                            <div class="value">${radicado.trd.codigo || 'N/A'}</div>
                        </div>
                        <div class="field">
                            <div class="label">Serie:</div>
                            <div class="value">${radicado.trd.serie || 'N/A'}</div>
                        </div>
                        <div class="field">
                            <div class="label">Subserie:</div>
                            <div class="value">${radicado.trd.subserie || 'N/A'}</div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h3>Destino</h3>
                    <div class="grid">
                        <div class="field">
                            <div class="label">Dependencia Destino:</div>
                            <div class="value">${radicado.dependencia_destino}</div>
                        </div>
                        <div class="field">
                            <div class="label">Medio de Respuesta:</div>
                            <div class="value">${radicado.medio_respuesta.charAt(0).toUpperCase() + radicado.medio_respuesta.slice(1).replace('_', ' ')}</div>
                        </div>
                        ${radicado.fecha_limite_respuesta ? `
                        <div class="field">
                            <div class="label">Fecha Límite:</div>
                            <div class="value">${radicado.fecha_limite_respuesta}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        // Cerrar modal al hacer clic fuera
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modalDetallesRadicado');
            if (event.target === modal) {
                cerrarModalDetalles();
            }
        });

        // Funciones para el modal de edición
        function mostrarModalEditar() {
            const modal = document.getElementById('modalEditarRadicado');
            const modalContent = modal.querySelector('.relative');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animación de entrada
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
            }, 10);
        }

        function cerrarModalEditar() {
            const modal = document.getElementById('modalEditarRadicado');
            const modalContent = modal.querySelector('.relative');

            // Animación de salida
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        function generarFormularioEdicion(data) {
            const radicado = data.radicado;
            const remitente = data.remitente;

            return `
                <form id="formEditarRadicado" class="space-y-6">
                    <input type="hidden" id="edit_radicado_id" value="${radicado.id}">

                    <!-- Información del Radicado -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-lg font-medium text-blue-800">Información del Radicado</h4>
                            ${window.userRole === 'administrador' ? `
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Administrador: Puede editar cualquier estado
                                </span>
                            ` : ''}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Radicado</label>
                                <input type="text" value="${radicado.numero_radicado}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                <input type="text" value="${radicado.tipo.charAt(0).toUpperCase() + radicado.tipo.slice(1)}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Remitente -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-3 border-b border-gray-200 pb-2">Información del Remitente</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Remitente</label>
                                <select id="edit_tipo_remitente" name="tipo_remitente" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" onchange="toggleTipoRemitenteEdit()">
                                    <option value="anonimo" ${remitente.tipo === 'anonimo' ? 'selected' : ''}>Anónimo</option>
                                    <option value="registrado" ${remitente.tipo === 'registrado' ? 'selected' : ''}>Registrado</option>
                                </select>
                            </div>
                        </div>

                        <div id="edit_datos_registrado" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" style="display: ${remitente.tipo === 'registrado' ? 'grid' : 'none'}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento</label>
                                <select id="edit_tipo_documento" name="tipo_documento" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                                    <option value="">Seleccionar...</option>
                                    <option value="CC" ${remitente.tipo_documento === 'CC' ? 'selected' : ''}>Cédula de Ciudadanía</option>
                                    <option value="CE" ${remitente.tipo_documento === 'CE' ? 'selected' : ''}>Cédula de Extranjería</option>
                                    <option value="TI" ${remitente.tipo_documento === 'TI' ? 'selected' : ''}>Tarjeta de Identidad</option>
                                    <option value="PP" ${remitente.tipo_documento === 'PP' ? 'selected' : ''}>Pasaporte</option>
                                    <option value="NIT" ${remitente.tipo_documento === 'NIT' ? 'selected' : ''}>NIT</option>
                                    <option value="OTRO" ${remitente.tipo_documento === 'OTRO' ? 'selected' : ''}>Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Documento</label>
                                <input type="text" id="edit_numero_documento" name="numero_documento" value="${remitente.numero_documento || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                                <input type="text" id="edit_nombre_completo" name="nombre_completo" value="${remitente.nombre_completo}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input type="text" id="edit_telefono" name="telefono" value="${remitente.telefono || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="edit_email" name="email" value="${remitente.email || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                <input type="text" id="edit_direccion" name="direccion" value="${remitente.direccion || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                                <input type="text" id="edit_ciudad" name="ciudad" value="${remitente.ciudad || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                                <input type="text" id="edit_departamento" name="departamento" value="${remitente.departamento || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Entidad</label>
                                <input type="text" id="edit_entidad" name="entidad" value="${remitente.entidad || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                        </div>
                    </div>

                    <!-- Información del Documento -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-3 border-b border-gray-200 pb-2">Información del Documento</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medio de Recepción *</label>
                                <select id="edit_medio_recepcion" name="medio_recepcion" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                                    <option value="fisico" ${radicado.medio_recepcion === 'fisico' ? 'selected' : ''}>Físico</option>
                                    <option value="email" ${radicado.medio_recepcion === 'email' ? 'selected' : ''}>Email</option>
                                    <option value="web" ${radicado.medio_recepcion === 'web' ? 'selected' : ''}>Web</option>
                                    <option value="telefono" ${radicado.medio_recepcion === 'telefono' ? 'selected' : ''}>Teléfono</option>
                                    <option value="fax" ${radicado.medio_recepcion === 'fax' ? 'selected' : ''}>Fax</option>
                                    <option value="otro" ${radicado.medio_recepcion === 'otro' ? 'selected' : ''}>Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Comunicación *</label>
                                <select id="edit_tipo_comunicacion" name="tipo_comunicacion" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                                    ${data.tipos_solicitud.map(tipo =>
                                        `<option value="${tipo.codigo}" ${radicado.tipo_comunicacion === tipo.codigo ? 'selected' : ''}>${tipo.nombre}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Folios *</label>
                                <input type="number" id="edit_numero_folios" name="numero_folios" value="${radicado.numero_folios}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Anexos</label>
                                <input type="number" id="edit_numero_anexos" name="numero_anexos" value="${radicado.numero_anexos || 0}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Anexo *</label>
                                <select id="edit_tipo_anexo" name="tipo_anexo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                                    <option value="original" ${radicado.tipo_anexo === 'original' ? 'selected' : ''}>Original</option>
                                    <option value="copia" ${radicado.tipo_anexo === 'copia' ? 'selected' : ''}>Copia</option>
                                    <option value="ninguno" ${radicado.tipo_anexo === 'ninguno' ? 'selected' : ''}>Ninguno</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                            <textarea id="edit_observaciones" name="observaciones" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">${radicado.observaciones || ''}</textarea>
                        </div>
                    </div>

                    <!-- TRD -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-3 border-b border-gray-200 pb-2">Tabla de Retención Documental (TRD)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unidad Administrativa</label>
                                <select id="edit_unidad_administrativa" name="unidad_administrativa_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" onchange="cargarSeriesEdit()">
                                    <option value="">Seleccionar...</option>
                                    ${data.unidades_administrativas.map(unidad =>
                                        `<option value="${unidad.id}" ${radicado.unidad_administrativa_id == unidad.id ? 'selected' : ''}>${unidad.codigo} - ${unidad.nombre}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Serie</label>
                                <select id="edit_serie" name="serie_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" onchange="cargarSubseriesEdit()">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subserie</label>
                                <select id="edit_trd_id" name="trd_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Destino -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-3 border-b border-gray-200 pb-2">Destino del Documento</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            ${radicado.tipo !== 'entrada' ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dependencia Origen *</label>
                                <select id="edit_dependencia_origen_id" name="dependencia_origen_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                                    <option value="">Seleccionar...</option>
                                    ${data.dependencias.map(dep =>
                                        `<option value="${dep.id}" ${radicado.dependencia_origen_id == dep.id ? 'selected' : ''}>${dep.codigo} - ${dep.nombre}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            ` : ''}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dependencia Destino *</label>
                                <select id="edit_dependencia_destino_id" name="dependencia_destino_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                                    <option value="">Seleccionar...</option>
                                    ${data.dependencias.map(dep =>
                                        `<option value="${dep.id}" ${radicado.dependencia_destino_id == dep.id ? 'selected' : ''}>${dep.codigo} - ${dep.nombre}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medio de Respuesta *</label>
                                <select id="edit_medio_respuesta" name="medio_respuesta" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue" required>
                                    <option value="fisico" ${radicado.medio_respuesta === 'fisico' ? 'selected' : ''}>Físico</option>
                                    <option value="email" ${radicado.medio_respuesta === 'email' ? 'selected' : ''}>Email</option>
                                    <option value="telefono" ${radicado.medio_respuesta === 'telefono' ? 'selected' : ''}>Teléfono</option>
                                    <option value="presencial" ${radicado.medio_respuesta === 'presencial' ? 'selected' : ''}>Presencial</option>
                                    <option value="no_requiere" ${radicado.medio_respuesta === 'no_requiere' ? 'selected' : ''}>No Requiere</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Límite de Respuesta</label>
                                <input type="date" id="edit_fecha_limite_respuesta" name="fecha_limite_respuesta" value="${radicado.fecha_limite_respuesta || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-uniradical-blue">
                            </div>
                        </div>
                    </div>
                </form>
            `;
        }

        // Funciones auxiliares para el formulario de edición
        function configurarFormularioEdicion(radicadoId, data) {
            // Configurar el botón de guardar
            const btnGuardar = document.getElementById('btn-guardar-edicion');
            btnGuardar.onclick = () => guardarEdicionRadicado(radicadoId);

            // Agregar datos actuales como atributos para preselección
            if (data.radicado.serie_id) {
                const serieMarker = document.createElement('div');
                serieMarker.setAttribute('data-serie-actual', data.radicado.serie_id);
                serieMarker.style.display = 'none';
                document.body.appendChild(serieMarker);
            }

            if (data.radicado.subserie_id) {
                const subserieMarker = document.createElement('div');
                subserieMarker.setAttribute('data-subserie-actual', data.radicado.subserie_id);
                subserieMarker.style.display = 'none';
                document.body.appendChild(subserieMarker);
            }

            // Cargar series y subseries iniciales
            setTimeout(() => {
                cargarSeriesEdit();
            }, 100);
        }

        function toggleTipoRemitenteEdit() {
            const tipoRemitente = document.getElementById('edit_tipo_remitente').value;
            const datosRegistrado = document.getElementById('edit_datos_registrado');

            if (tipoRemitente === 'registrado') {
                datosRegistrado.style.display = 'grid';
                document.getElementById('edit_tipo_documento').required = true;
                document.getElementById('edit_numero_documento').required = true;
            } else {
                datosRegistrado.style.display = 'none';
                document.getElementById('edit_tipo_documento').required = false;
                document.getElementById('edit_numero_documento').required = false;
            }
        }

        function cargarSeriesEdit() {
            const unidadId = document.getElementById('edit_unidad_administrativa').value;
            const serieSelect = document.getElementById('edit_serie');
            const subserieSelect = document.getElementById('edit_trd_id');

            // Limpiar selects
            serieSelect.innerHTML = '<option value="">Seleccionar...</option>';
            subserieSelect.innerHTML = '<option value="">Seleccionar...</option>';

            if (!unidadId) return;

            // Buscar la unidad administrativa en los datos cargados
            const unidadesData = window.unidadesAdministrativasData;
            if (!unidadesData) return;

            const unidad = unidadesData.find(u => u.id == unidadId);
            if (unidad && unidad.series) {
                unidad.series.forEach(serie => {
                    const option = document.createElement('option');
                    option.value = serie.id;
                    option.textContent = `${serie.numero_serie} - ${serie.nombre}`;
                    serieSelect.appendChild(option);
                });

                // Si hay una serie preseleccionada, seleccionarla y cargar sus subseries
                const serieActual = document.querySelector('[data-serie-actual]');
                if (serieActual) {
                    const serieId = serieActual.dataset.serieActual;
                    serieSelect.value = serieId;
                    console.log('Preseleccionando serie ID:', serieId);
                    cargarSubseriesEdit();
                }
            }
        }

        function cargarSubseriesEdit() {
            const serieId = document.getElementById('edit_serie').value;
            const subserieSelect = document.getElementById('edit_trd_id');

            // Limpiar select
            subserieSelect.innerHTML = '<option value="">Seleccionar...</option>';

            if (!serieId) return;

            // Buscar la serie en los datos cargados
            const unidadesData = window.unidadesAdministrativasData;
            if (!unidadesData) return;

            for (const unidad of unidadesData) {
                const serie = unidad.series?.find(s => s.id == serieId);
                if (serie && serie.subseries) {
                    serie.subseries.forEach(subserie => {
                        const option = document.createElement('option');
                        option.value = subserie.id;
                        option.textContent = `${subserie.numero_subserie} - ${subserie.nombre}`;
                        subserieSelect.appendChild(option);
                    });

                    // Si hay una subserie preseleccionada, usar el ID de la subserie
                    const subserieActual = document.querySelector('[data-subserie-actual]');
                    if (subserieActual) {
                        const subserieId = subserieActual.dataset.subserieActual;
                        subserieSelect.value = subserieId;
                        console.log('Preseleccionando subserie ID:', subserieId);
                    }
                    break;
                }
            }
        }

        function guardarEdicionRadicado(radicadoId) {
            const form = document.getElementById('formEditarRadicado');
            const formData = new FormData(form);
            const btnGuardar = document.getElementById('btn-guardar-edicion');

            // Deshabilitar botón y mostrar loading
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Guardando...
            `;

            // Enviar datos
            fetch(`/radicacion/${radicadoId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    mostrarMensaje('Radicado actualizado exitosamente', 'success');

                    // Cerrar modal
                    cerrarModalEditar();

                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Mostrar errores
                    mostrarErroresFormulario(data.errors || { general: [data.error || 'Error al actualizar el radicado'] });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al actualizar el radicado', 'error');
            })
            .finally(() => {
                // Restaurar botón
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = 'Guardar Cambios';
            });
        }

        function mostrarErroresFormulario(errors) {
            // Limpiar errores anteriores
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
                el.classList.add('border-gray-300');
            });

            // Mostrar nuevos errores
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.remove('border-gray-300');
                    input.classList.add('border-red-500');

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                    errorDiv.textContent = errors[field][0];
                    input.parentNode.appendChild(errorDiv);
                }
            });
        }

        // Función para manejar el toggle de tipo remitente en el modal
        function toggleTipoRemitenteModal() {
            const tipoRemitenteChecked = document.querySelector('input[name="tipo_remitente"]:checked');
            const tipoRemitente = tipoRemitenteChecked ? tipoRemitenteChecked.value : '';
            const camposRegistrado = document.getElementById('campos-registrado');

            console.log('toggleTipoRemitenteModal - Tipo:', tipoRemitente);

            if (!camposRegistrado) {
                console.warn('Elemento campos-registrado no encontrado en el modal');
                return;
            }

            if (tipoRemitente === 'registrado') {
                console.log('Mostrando campos de remitente registrado');
                camposRegistrado.style.display = 'block';

                // Hacer campos requeridos
                const tipoDocumento = document.getElementById('tipo_documento');
                const numeroDocumento = document.getElementById('numero_documento');
                if (tipoDocumento) tipoDocumento.required = true;
                if (numeroDocumento) numeroDocumento.required = true;
            } else {
                console.log('Ocultando campos de remitente registrado');
                camposRegistrado.style.display = 'none';

                // Remover required
                const tipoDocumento = document.getElementById('tipo_documento');
                const numeroDocumento = document.getElementById('numero_documento');
                if (tipoDocumento) {
                    tipoDocumento.required = false;
                    tipoDocumento.value = '';
                }
                if (numeroDocumento) {
                    numeroDocumento.required = false;
                    numeroDocumento.value = '';
                }
            }
        }

        function mostrarMensaje(mensaje, tipo) {
            // Crear elemento de mensaje
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg ${
                tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            messageDiv.textContent = mensaje;

            document.body.appendChild(messageDiv);

            // Remover después de 3 segundos
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        // Cerrar modal de edición al hacer clic fuera
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modalEditarRadicado');
            if (event.target === modal) {
                cerrarModalEditar();
            }
        });

        // Función para inicializar cascada departamento-ciudad
        function initDepartamentoCiudadCascade() {
            console.log('Inicializando cascada departamento-ciudad...');

            const departamentoSelect = document.getElementById('departamento_id');
            const ciudadSelect = document.getElementById('ciudad_id');
            const departamentoNombreInput = document.getElementById('departamento_nombre');
            const ciudadNombreInput = document.getElementById('ciudad_nombre');

            console.log('Elementos encontrados:', {
                departamento: !!departamentoSelect,
                ciudad: !!ciudadSelect,
                deptoNombre: !!departamentoNombreInput,
                ciudadNombre: !!ciudadNombreInput
            });

            if (!departamentoSelect || !ciudadSelect) {
                console.error('Elementos de departamento/ciudad no encontrados');
                return;
            }

            // Manejar cambio de departamento
            departamentoSelect.addEventListener('change', function() {
                const departamentoId = this.value;
                const departamentoOption = this.options[this.selectedIndex];

                console.log('Departamento seleccionado:', departamentoId);

                // Actualizar campo oculto con el nombre del departamento
                if (departamentoNombreInput) {
                    departamentoNombreInput.value = departamentoOption.dataset.nombre || '';
                }

                // Limpiar ciudad
                ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
                ciudadSelect.disabled = true;

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

            // Función para cargar ciudades por departamento
            function cargarCiudadesPorDepartamento(departamentoId) {
                console.log('Cargando ciudades para departamento:', departamentoId);

                // Mostrar estado de carga
                ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
                ciudadSelect.disabled = true;

                // Realizar petición AJAX
                fetch(`/api/ciudades/por-departamento?departamento_id=${departamentoId}`)
                    .then(response => {
                        console.log('Respuesta recibida:', response.status);
                        if (!response.ok) {
                            throw new Error('Error al cargar ciudades');
                        }
                        return response.json();
                    })
                    .then(ciudades => {
                        console.log('Ciudades recibidas:', ciudades);

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

                        console.log('Ciudades cargadas exitosamente');
                    })
                    .catch(error => {
                        console.error('Error al cargar ciudades:', error);
                        ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
                        ciudadSelect.disabled = true;

                        // Mostrar alerta al usuario
                        alert('Error al cargar las ciudades. Por favor, recargue la página.');
                    });
            }

            // Cargar ciudades iniciales si hay un departamento preseleccionado
            if (departamentoSelect.value) {
                console.log('Departamento preseleccionado:', departamentoSelect.value);
                cargarCiudadesPorDepartamento(departamentoSelect.value);
            }
        }
    </script>

    @push('styles')
    <style>
        /* Estilos para dropdowns con posicionamiento absoluto inteligente */
        [id^="dropdown-radicado-"] {
            z-index: 50;
            min-width: 12rem;
            transform-origin: top right;
            transition: opacity 0.15s ease-out, transform 0.15s ease-out;
        }

        /* Animaciones suaves para apertura normal (hacia abajo) */
        [id^="dropdown-radicado-"]:not(.hidden) {
            opacity: 1;
            transform: scale(1);
        }

        [id^="dropdown-radicado-"].hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        /* Estilos específicos para dropdowns que abren hacia arriba */
        [id^="dropdown-radicado-"].bottom-full {
            transform-origin: bottom right;
        }

        /* Asegurar que los contenedores de la tabla permitan overflow */
        .overflow-x-auto {
            overflow: visible !important;
        }

        /* Asegurar que las celdas de la tabla permitan overflow para dropdowns */
        .table-cell-actions {
            position: relative;
            overflow: visible;
        }

        /* Mejorar el posicionamiento de los dropdowns en tablas */
        .table-actions-container {
            position: relative;
            display: inline-block;
        }
    </style>
    @endpush
</x-app-layout>
