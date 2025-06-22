<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Previsualización - Radicado Interno {{ $datosPreview['numero_radicado'] }}</title>
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 20px;
            }

            #documento-preview {
                border: none !important;
                box-shadow: none !important;
                min-height: auto !important;
            }
        }

        .dragging {
            opacity: 0.7;
            transform: rotate(5deg);
        }

        #sello-radicado {
            user-select: none;
            transition: all 0.2s ease;
        }

        #sello-radicado:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <!-- Header con botones -->
                    <div class="flex justify-between items-center mb-6 no-print">
                        <h3 class="text-lg font-medium text-gray-800">
                            Previsualización del Radicado Interno
                        </h3>
                        <div class="flex space-x-3">
                            <button data-action="print" class="btn-institutional">
                                Imprimir
                            </button>
                            <button data-action="close" class="cancel-button">
                                Cerrar
                            </button>
                        </div>
                    </div>

                    <!-- Documento con sello movible -->
                    <div id="documento-preview" class="relative bg-white border-2 border-gray-300 min-h-[800px] p-8">
                        <!-- Sello de radicado movible -->
                        <div id="sello-radicado" draggable="true"
                             class="absolute top-4 right-4 bg-red-600 text-white p-3 rounded-lg shadow-lg cursor-move z-10">
                            <div class="text-center">
                                <div class="text-xs font-bold">RADICADO INTERNO</div>
                                <div class="text-lg font-bold">{{ $datosPreview['numero_radicado'] }}</div>
                                <div class="text-xs">{{ $datosPreview['fecha_radicado'] }}</div>
                                <div class="text-xs">{{ $datosPreview['hora_radicado'] }}</div>
                            </div>
                        </div>

                        <!-- Contenido del documento -->
                        <div class="space-y-6">
                            <!-- Encabezado institucional -->
                            <div class="text-center border-b-2 border-gray-300 pb-4">
                                <h1 class="text-xl font-bold text-gray-800">SISTEMA UNIRADICAL</h1>
                                <h2 class="text-lg font-semibold text-gray-700">SISTEMA DE RADICACIÓN</h2>
                                <h3 class="text-md font-medium text-gray-600">DOCUMENTO INTERNO</h3>
                            </div>

                            <!-- Información del documento -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">ORIGEN:</h4>
                                    <p><strong>Dependencia:</strong> {{ $datosPreview['dependencia_origen']->nombre_completo }}</p>
                                    <p><strong>Funcionario:</strong> {{ $datosPreview['funcionario_remitente'] }}</p>
                                    @if($datosPreview['cargo_remitente'])
                                        <p><strong>Cargo:</strong> {{ $datosPreview['cargo_remitente'] }}</p>
                                    @endif
                                    @if($datosPreview['telefono_remitente'])
                                        <p><strong>Teléfono:</strong> {{ $datosPreview['telefono_remitente'] }}</p>
                                    @endif
                                    @if($datosPreview['email_remitente'])
                                        <p><strong>Email:</strong> {{ $datosPreview['email_remitente'] }}</p>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">DESTINO:</h4>
                                    <p><strong>Dependencia:</strong> {{ $datosPreview['dependencia_destino']->nombre_completo }}</p>
                                    @if($datosPreview['dependencia_destino']->responsable)
                                        <p><strong>Responsable:</strong> {{ $datosPreview['dependencia_destino']->responsable }}</p>
                                    @endif
                                    @if($datosPreview['dependencia_destino']->telefono)
                                        <p><strong>Teléfono:</strong> {{ $datosPreview['dependencia_destino']->telefono }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Información del documento -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">INFORMACIÓN DEL DOCUMENTO:</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <p><strong>Tipo:</strong> {{ ucfirst($datosPreview['tipo_comunicacion']) }}</p>
                                    <p><strong>Prioridad:</strong>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $datosPreview['prioridad'] === 'urgente' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $datosPreview['prioridad'] === 'alta' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $datosPreview['prioridad'] === 'normal' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $datosPreview['prioridad'] === 'baja' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst($datosPreview['prioridad']) }}
                                        </span>
                                    </p>
                                    <p><strong>Folios:</strong> {{ $datosPreview['numero_folios'] }}</p>
                                    @if($datosPreview['tipo_anexo'])
                                        <p><strong>Anexo:</strong> {{ ucfirst($datosPreview['tipo_anexo']) }}</p>
                                    @endif
                                </div>
                                <div class="mt-3">
                                    <p><strong>Asunto:</strong></p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-md border">
                                        {{ $datosPreview['asunto'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- TRD -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">TABLA DE RETENCIÓN DOCUMENTAL (TRD):</h4>
                                <div class="p-3 bg-blue-50 rounded-md border border-blue-200">
                                    <div class="grid grid-cols-3 gap-4">
                                        <p><strong>Código:</strong> {{ $datosPreview['trd']->codigo }}</p>
                                        <p><strong>Serie:</strong> {{ $datosPreview['trd']->serie }}</p>
                                        <p><strong>Subserie:</strong> {{ $datosPreview['trd']->subserie ?: 'N/A' }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p><strong>Asunto TRD:</strong> {{ $datosPreview['trd']->asunto }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de respuesta -->
                            @if($datosPreview['requiere_respuesta'])
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">INFORMACIÓN DE RESPUESTA:</h4>
                                <div class="p-3 bg-yellow-50 rounded-md border border-yellow-200">
                                    <p><strong>Requiere respuesta:</strong> Sí</p>
                                    @if($datosPreview['fecha_limite_respuesta'])
                                        <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($datosPreview['fecha_limite_respuesta'])->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Observaciones -->
                            @if($datosPreview['observaciones'])
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">OBSERVACIONES:</h4>
                                <div class="p-3 bg-gray-50 rounded-md border">
                                    {{ $datosPreview['observaciones'] }}
                                </div>
                            </div>
                            @endif

                            <!-- Pie de página -->
                            <div class="text-center text-sm text-gray-600 border-t-2 border-gray-300 pt-4 mt-8">
                                <p><strong>Radicado por:</strong> {{ $datosPreview['usuario_radica'] }}</p>
                                <p><strong>Fecha y hora:</strong> {{ $datosPreview['fecha_radicado'] }} - {{ $datosPreview['hora_radicado'] }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <em>Esta es una previsualización. El documento será radicado oficialmente al confirmar la creación.</em>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
