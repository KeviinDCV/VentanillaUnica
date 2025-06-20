<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Consultar Radicados
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Búsqueda y consulta de documentos radicados
                </p>
            </div>
            <div class="flex space-x-3">
                @if(count($filtros) > 0)
                    <a href="{{ route('consultar.exportar', request()->query()) }}"
                       class="export-button">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar
                    </a>
                @endif
                <a href="{{ route('radicacion.entrada.index') }}"
                   class="create-button">
                    Nuevo Radicado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
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

                <div class="bg-white border border-gray-200 rounded-lg p-4">
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
                            <p class="text-lg font-semibold text-yellow-600">{{ number_format($estadisticas['pendientes']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">En Proceso</p>
                            <p class="text-lg font-semibold text-blue-600">{{ number_format($estadisticas['en_proceso']) }}</p>
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
                            <p class="text-sm font-medium text-gray-500">Respondidos</p>
                            <p class="text-lg font-semibold text-green-600">{{ number_format($estadisticas['respondidos']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Vencidos</p>
                            <p class="text-lg font-semibold text-red-600">{{ number_format($estadisticas['vencidos']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Búsqueda -->
            <div class="card-minimal mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Filtros de Búsqueda</h3>
                    
                    <form method="GET" action="{{ route('consultar.index') }}" id="filtros-form">
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
                                       placeholder="Número de documento">
                            </div>

                            <!-- Nombre del Remitente -->
                            <div>
                                <label for="nombre_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Remitente
                                </label>
                                <input type="text" name="nombre_remitente" id="nombre_remitente" 
                                       value="{{ request('nombre_remitente') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                       placeholder="Nombre completo">
                            </div>

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
                                            {{ $dependencia->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- TRD -->
                            <div>
                                <label for="trd_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    TRD
                                </label>
                                <select name="trd_id" id="trd_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todos los TRD</option>
                                    @foreach($trds as $trd)
                                        <option value="{{ $trd->id }}" 
                                                {{ request('trd_id') == $trd->id ? 'selected' : '' }}>
                                            {{ $trd->descripcion_completa }}
                                        </option>
                                    @endforeach
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
                                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="respondido" {{ request('estado') === 'respondido' ? 'selected' : '' }}>Respondido</option>
                                    <option value="archivado" {{ request('estado') === 'archivado' ? 'selected' : '' }}>Archivado</option>
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
                                    <option value="entrada" {{ request('tipo') === 'entrada' ? 'selected' : '' }}>Entrada</option>
                                    <option value="interno" {{ request('tipo') === 'interno' ? 'selected' : '' }}>Interno</option>
                                    <option value="salida" {{ request('tipo') === 'salida' ? 'selected' : '' }}>Salida</option>
                                </select>
                            </div>

                            <!-- Medio de Recepción -->
                            <div>
                                <label for="medio_recepcion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Medio de Recepción
                                </label>
                                <select name="medio_recepcion" id="medio_recepcion"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <option value="">Todos los medios</option>
                                    <option value="fisico" {{ request('medio_recepcion') === 'fisico' ? 'selected' : '' }}>Físico</option>
                                    <option value="email" {{ request('medio_recepcion') === 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="web" {{ request('medio_recepcion') === 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="telefono" {{ request('medio_recepcion') === 'telefono' ? 'selected' : '' }}>Teléfono</option>
                                    <option value="fax" {{ request('medio_recepcion') === 'fax' ? 'selected' : '' }}>Fax</option>
                                    <option value="otro" {{ request('medio_recepcion') === 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtros de Fecha -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Desde
                                </label>
                                <input type="date" name="fecha_desde" id="fecha_desde" 
                                       value="{{ request('fecha_desde') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <div>
                                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Hasta
                                </label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" 
                                       value="{{ request('fecha_hasta') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <div>
                                <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Límite Hasta
                                </label>
                                <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta" 
                                       value="{{ request('fecha_limite_respuesta') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" name="solo_vencidos" value="1" 
                                           {{ request('solo_vencidos') ? 'checked' : '' }}
                                           class="form-checkbox text-uniradical-blue">
                                    <span class="ml-2 text-sm text-gray-700">Solo vencidos</span>
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-between">
                            <a href="{{ route('consultar.index') }}"
                               class="clear-button">
                                Limpiar Filtros
                            </a>
                            <button type="submit"
                                    class="search-button">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            @if($radicados->count() > 0)
                <div class="card-minimal">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800">
                                Resultados ({{ $radicados->total() }} encontrados)
                            </h3>
                            @if(count($filtros) > 0)
                                <div class="text-sm text-gray-600">
                                    Filtros activos: {{ count($filtros) }}
                                </div>
                            @endif
                        </div>

                        <!-- Tabla de Resultados -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Radicado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha/Hora
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Remitente
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Destino
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($radicados as $radicado)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $radicado->numero_radicado }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ ucfirst($radicado->tipo) }} - {{ $radicado->numero_folios }} folios
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $radicado->fecha_radicado->format('d/m/Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($radicado->hora_radicado)->format('H:i:s') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ $radicado->remitente->nombre_completo }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $radicado->remitente->identificacion_completa }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    {{ $radicado->dependenciaDestino->nombre }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $radicado->trd->serie }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                        <div class="mt-6">
                            {{ $radicados->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="card-minimal">
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron radicados</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(count($filtros) > 0)
                                No hay radicados que coincidan con los filtros aplicados.
                            @else
                                No hay radicados registrados en el sistema.
                            @endif
                        </p>
                        <div class="mt-6">
                            @if(count($filtros) > 0)
                                <a href="{{ route('consultar.index') }}"
                                   class="clear-button inline-flex items-center shadow-sm text-sm">
                                    Limpiar Filtros
                                </a>
                            @endif
                            <a href="{{ route('radicacion.entrada.index') }}"
                               class="ml-3 create-button inline-flex items-center shadow-sm text-sm">
                                Crear Primer Radicado
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/radicacion.js'])
    @endpush
</x-app-layout>
