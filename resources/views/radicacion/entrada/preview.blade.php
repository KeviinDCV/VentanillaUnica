<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Previsualización - Radicado {{ $datosPreview['numero_radicado'] }}</title>
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
                            Previsualización del Radicado de Entrada
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
                                <div class="text-xs font-bold">RADICADO ENTRADA</div>
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
                                <h3 class="text-md font-medium text-gray-600">DOCUMENTO DE ENTRADA</h3>
                            </div>

                            <!-- Información del remitente -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">REMITENTE:</h4>
                                    <p><strong>Nombre:</strong> {{ $datosPreview['remitente']['nombre_completo'] }}</p>
                                    @if($datosPreview['remitente']['tipo_documento'])
                                        <p><strong>Documento:</strong> {{ $datosPreview['remitente']['tipo_documento'] }} {{ $datosPreview['remitente']['numero_documento'] }}</p>
                                    @endif
                                    @if($datosPreview['remitente']['telefono'])
                                        <p><strong>Teléfono:</strong> {{ $datosPreview['remitente']['telefono'] }}</p>
                                    @endif
                                    @if($datosPreview['remitente']['email'])
                                        <p><strong>Email:</strong> {{ $datosPreview['remitente']['email'] }}</p>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">DESTINO:</h4>
                                    <p><strong>Dependencia:</strong> {{ $datosPreview['dependencia_destino'] }}</p>
                                    <p><strong>Medio de Recepción:</strong> {{ ucfirst($datosPreview['medio_recepcion']) }}</p>
                                </div>
                            </div>

                            <!-- Información del documento -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">INFORMACIÓN DEL DOCUMENTO:</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <p><strong>Tipo:</strong> {{ ucfirst($datosPreview['tipo_comunicacion']) }}</p>
                                    <p><strong>Folios:</strong> {{ $datosPreview['numero_folios'] }}</p>
                                </div>
                            </div>

                            <!-- TRD -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">TABLA DE RETENCIÓN DOCUMENTAL (TRD):</h4>
                                <div class="p-3 bg-blue-50 rounded-md border border-blue-200">
                                    <div class="grid grid-cols-3 gap-4">
                                        <p><strong>Código:</strong> {{ $datosPreview['trd']['codigo'] }}</p>
                                        <p><strong>Serie:</strong> {{ $datosPreview['trd']['serie'] }}</p>
                                        <p><strong>Subserie:</strong> {{ $datosPreview['trd']['subserie'] ?: 'N/A' }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p><strong>Asunto TRD:</strong> {{ $datosPreview['trd']['asunto'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            @if($datosPreview['observaciones'])
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">OBSERVACIONES:</h4>
                                <div class="p-3 bg-gray-50 rounded-md border">
                                    {!! nl2br(e($datosPreview['observaciones'])) !!}
                                </div>
                            </div>
                            @endif

                            <!-- Pie de página -->
                            <div class="text-center text-sm text-gray-600 border-t-2 border-gray-300 pt-4 mt-8">
                                <p><strong>Radicado por:</strong> {{ auth()->user()->name }}</p>
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
