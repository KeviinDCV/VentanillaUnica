<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Radicado {{ $radicado->numero_radicado }}
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Documento de entrada radicado exitosamente
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('radicacion.entrada.index') }}"
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
                                {{ $radicado->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $radicado->estado === 'en_proceso' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $radicado->estado === 'respondido' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $radicado->estado === 'archivado' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $radicado->estado)) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <h4 class="font-medium text-blue-800 mb-2">Número de Radicado</h4>
                                <p class="text-2xl font-bold text-blue-900">{{ $radicado->numero_radicado }}</p>
                            </div>
                            
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <h4 class="font-medium text-gray-800 mb-2">Fecha y Hora</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $radicado->fecha_radicado->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($radicado->hora_radicado)->format('H:i:s') }}
                                </p>
                            </div>
                            
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <h4 class="font-medium text-gray-800 mb-2">Radicado por</h4>
                                <p class="text-sm text-gray-600">{{ $radicado->usuarioRadica->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Remitente -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Información del Remitente
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                <p class="text-sm text-gray-900">
                                    {{ $radicado->remitente->tipo === 'anonimo' ? 'Anónimo' : 'Registrado' }}
                                </p>
                            </div>
                            
                            @if($radicado->remitente->tipo === 'registrado')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Identificación</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->identificacion_completa }}</p>
                            </div>
                            @endif
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->nombre_completo }}</p>
                            </div>
                            
                            @if($radicado->remitente->telefono || $radicado->remitente->email)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contacto</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->contacto_completo }}</p>
                            </div>
                            @endif
                            
                            @if($radicado->remitente->direccion)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->direccion }}</p>
                            </div>
                            @endif
                            
                            @if($radicado->remitente->entidad)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Entidad</label>
                                <p class="text-sm text-gray-900">{{ $radicado->remitente->entidad }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información del Documento -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Información del Documento
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medio de Recepción</label>
                                <p class="text-sm text-gray-900">{{ ucfirst($radicado->medio_recepcion) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Comunicación</label>
                                <p class="text-sm text-gray-900">{{ ucfirst($radicado->tipo_comunicacion) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Folios</label>
                                <p class="text-sm text-gray-900">{{ $radicado->numero_folios }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Anexo</label>
                                <p class="text-sm text-gray-900">{{ ucfirst($radicado->tipo_anexo) }}</p>
                            </div>
                            
                            @if($radicado->observaciones)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                                <p class="text-sm text-gray-900">{{ $radicado->observaciones }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- TRD -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Tabla de Retención Documental (TRD)
                        </h3>
                        
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 mb-1">Código</label>
                                    <p class="text-sm text-blue-900">{{ $radicado->trd->codigo }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 mb-1">Serie</label>
                                    <p class="text-sm text-blue-900">{{ $radicado->trd->serie }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-700 mb-1">Subserie</label>
                                    <p class="text-sm text-blue-900">{{ $radicado->trd->subserie ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-blue-700 mb-1">Asunto</label>
                                <p class="text-sm text-blue-900">{{ $radicado->trd->asunto }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Destino -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Destino del Documento
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dependencia Destino</label>
                                <p class="text-sm text-gray-900">{{ $radicado->dependenciaDestino->nombre_completo }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medio de Respuesta</label>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $radicado->medio_respuesta)) }}</p>
                            </div>
                            
                            @if($radicado->fecha_limite_respuesta)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Límite de Respuesta</label>
                                <p class="text-sm text-gray-900 
                                    {{ $radicado->estaVencido() ? 'text-red-600 font-medium' : '' }}">
                                    {{ $radicado->fecha_limite_respuesta->format('d/m/Y') }}
                                    @if($radicado->estaVencido())
                                        <span class="text-red-600">(VENCIDO)</span>
                                    @elseif($radicado->dias_restantes !== null)
                                        <span class="text-gray-500">({{ $radicado->dias_restantes }} días restantes)</span>
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documentos Adjuntos -->
                    @if($radicado->documentos->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Documentos Adjuntos
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($radicado->documentos as $documento)
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $documento->nombre_archivo }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $documento->tipo_archivo }} - {{ $documento->tamaño_legible }}
                                            @if($documento->es_principal)
                                                <span class="text-blue-600">(Principal)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    @if($documento->archivoExiste())
                                        <a href="{{ $documento->url_archivo }}" target="_blank"
                                           class="px-3 py-1 text-xs bg-uniradical-blue text-white rounded hover:bg-opacity-90 transition duration-200">
                                            Ver
                                        </a>
                                        <a href="{{ $documento->url_archivo }}" download
                                           class="px-3 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-200">
                                            Descargar
                                        </a>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded">
                                            Archivo no encontrado
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Acciones -->
                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                            Volver al Dashboard
                        </a>
                        <div class="flex space-x-3">
                            <a href="{{ route('radicacion.entrada.index') }}" 
                               class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200">
                                Nuevo Radicado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .card-minimal {
                box-shadow: none !important;
                border: 1px solid #000 !important;
            }
        }
    </style>

    <script nonce="{{ session('csp_nonce', 'default-nonce') }}">
        document.addEventListener('DOMContentLoaded', function() {
            const printButton = document.getElementById('btn-print');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    window.print();
                });
            }
        });
    </script>
</x-app-layout>
