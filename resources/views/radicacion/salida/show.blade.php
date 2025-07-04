<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Radicado {{ $radicado->numero_radicado }}
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Documento de salida radicado exitosamente
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('radicacion.index') }}"
                   class="create-button">
                    Nuevo Radicado
                </a>
                <button id="btn-print"
                        class="btn-institutional">
                    Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-minimal">
                <div class="p-8">
                    <!-- Información del Radicado -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800">Información del Radicado</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                @if($radicado->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($radicado->estado === 'en_proceso') bg-blue-100 text-blue-800
                                @elseif($radicado->estado === 'respondido') bg-green-100 text-green-800
                                @elseif($radicado->estado === 'archivado') bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $radicado->estado)) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Número de Radicado</label>
                                <p class="text-lg font-semibold text-uniradical-blue">{{ $radicado->numero_radicado }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo</label>
                                <p class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Salida
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Fecha y Hora</label>
                                <p class="text-sm text-gray-900">
                                    {{ $radicado->fecha_radicado->format('d/m/Y') }} - {{ $radicado->hora_radicado->format('H:i:s') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Destinatario -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Información del Destinatario</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nombre Completo</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->nombre_completo }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Identificación</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->identificacion_completa }}</p>
                            </div>
                            @if($radicado->remitente->telefono)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Teléfono</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->telefono }}</p>
                            </div>
                            @endif
                            @if($radicado->remitente->email)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->email }}</p>
                            </div>
                            @endif
                            @if($radicado->remitente->direccion)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Dirección</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->direccion }}</p>
                            </div>
                            @endif
                            @if($radicado->remitente->ciudad || $radicado->remitente->departamento)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Ubicación</label>
                                <p class="text-sm text-gray-900">
                                    {{ $radicado->remitente->ciudad }}{{ $radicado->remitente->ciudad && $radicado->remitente->departamento ? ', ' : '' }}{{ $radicado->remitente->departamento }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información de la Dependencia Origen -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Dependencia de Origen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Dependencia</label>
                                <p class="text-sm text-gray-900">{{ $radicado->dependenciaDestino->nombre }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Usuario que Radica</label>
                                <p class="text-sm text-gray-900">{{ $radicado->usuarioRadica->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Documento -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Información del Documento</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($radicado->subserie)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">TRD (Serie/Subserie)</label>
                                <p class="text-sm text-gray-900">
                                    {{ $radicado->subserie->serie->unidadAdministrativa->codigo }}.{{ $radicado->subserie->serie->numero_serie }}.{{ $radicado->subserie->numero_subserie }} - 
                                    {{ $radicado->subserie->serie->nombre }} / {{ $radicado->subserie->nombre }}
                                </p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Comunicación</label>
                                <p class="text-sm text-gray-900">{{ $radicado->tipo_comunicacion }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Número de Folios</label>
                                <p class="text-sm text-gray-900">{{ $radicado->numero_folios }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Medio de Respuesta</label>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $radicado->medio_respuesta)) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tipo de Anexo</label>
                                <p class="text-sm text-gray-900">{{ ucfirst($radicado->tipo_anexo) }}</p>
                            </div>
                            @if($radicado->fecha_limite_respuesta)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Fecha Límite de Respuesta</label>
                                <p class="text-sm text-gray-900">{{ $radicado->fecha_limite_respuesta->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($radicado->observaciones)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Observaciones</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $radicado->observaciones }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Documentos Adjuntos -->
                    @if($radicado->documentos->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Documentos Adjuntos</h3>
                        <div class="space-y-3">
                            @foreach($radicado->documentos as $documento)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $documento->nombre_archivo }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($documento->tamaño_archivo / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($documento->ruta_archivo) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Ver
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Script para impresión -->
    <script>
        document.getElementById('btn-print').addEventListener('click', function() {
            window.print();
        });
    </script>
</x-app-layout>
