<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Previsualización - Radicado de Salida <?php echo e($datosPreview['numero_radicado']); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpg" href="<?php echo e(asset('images/LogoHospital.jpg')); ?>">
    <link rel="shortcut icon" type="image/jpg" href="<?php echo e(asset('images/LogoHospital.jpg')); ?>">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                font-size: 12px !important;
                line-height: 1.4 !important;
            }

            .min-h-screen {
                min-height: auto !important;
                height: auto !important;
            }

            .max-w-4xl {
                max-width: none !important;
                margin: 0 !important;
            }

            .mx-auto {
                margin: 0 !important;
            }

            .py-8 {
                padding: 0 !important;
            }

            .bg-white {
                background: white !important;
            }

            .rounded-lg {
                border-radius: 0 !important;
            }

            .shadow-lg {
                box-shadow: none !important;
            }

            .overflow-hidden {
                overflow: visible !important;
            }

            .p-6 {
                padding: 15mm !important;
            }

            /* Asegurar que el contenedor del documento mantenga su estructura */
            #documento-preview {
                position: relative !important;
                margin: 0 !important;
                padding: 32px !important; /* Equivalente a p-8 */
                border: none !important;
                box-shadow: none !important;
                background: white !important;
                overflow: visible !important;
                min-height: auto !important;
                height: auto !important;
            }

            #documento-preview {
                border: none !important;
                box-shadow: none !important;
                min-height: auto !important;
                height: auto !important;
                max-height: none !important;
                padding: 10mm !important;
                margin: 0 !important;
                page-break-inside: avoid;
                overflow: visible !important;
            }

            .space-y-6 > * + * {
                margin-top: 15px !important;
            }

            .space-y-2 > * + * {
                margin-top: 8px !important;
            }

            .grid {
                display: block !important;
            }

            .grid-cols-2 > div {
                display: inline-block !important;
                width: 48% !important;
                vertical-align: top !important;
                margin-right: 2% !important;
            }

            .text-xl {
                font-size: 16px !important;
            }

            .text-lg {
                font-size: 14px !important;
            }

            .text-md {
                font-size: 12px !important;
            }

            .text-sm {
                font-size: 11px !important;
            }

            .text-xs {
                font-size: 10px !important;
            }

            /* Asegurar que el sello se imprima en su posición exacta */
            #sello-radicado {
                position: absolute !important;
                background: #000000 !important;
                color: white !important;
                padding: 8px 12px !important;
                border-radius: 0 !important;
                font-size: 8px !important;
                line-height: 1.2 !important;
                z-index: 1000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
                border: 2px solid #000000 !important;
                width: 120px !important;
                font-family: 'Arial', sans-serif !important;
                font-weight: bold !important;
                text-align: center !important;
                /* Mantener posición exacta del preview usando píxeles */
                left: var(--sticker-left, 20px) !important;
                top: var(--sticker-top, 20px) !important;
                transform: none !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                /* Eliminar cualquier efecto visual no profesional */
                backdrop-filter: none !important;
                filter: none !important;
                transition: none !important;
                /* Asegurar que no se mueva durante la impresión */
                margin: 0 !important;
                padding-top: 8px !important;
                padding-bottom: 8px !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
                /* Forzar posición absoluta sin herencia */
                position: absolute !important;
                /* Eliminar cualquier offset adicional */
                inset: unset !important;
                right: unset !important;
                bottom: unset !important;
            }

            /* Evitar saltos de página innecesarios */
            * {
                page-break-inside: avoid !important;
            }

            h1, h2, h3, h4 {
                page-break-after: avoid !important;
            }

            /* Optimizar espaciado para una sola página */
            .border-b-2 {
                border-bottom: 1px solid #ccc !important;
                padding-bottom: 8px !important;
                margin-bottom: 12px !important;
            }

            .border-t-2 {
                border-top: 1px solid #ccc !important;
                padding-top: 8px !important;
                margin-top: 12px !important;
            }

            .mb-3, .mb-4, .mb-6, .mb-8 {
                margin-bottom: 8px !important;
            }

            .mt-2, .mt-4, .mt-8 {
                margin-top: 8px !important;
            }

            .p-3 {
                padding: 6px !important;
            }

            .bg-gray-50 {
                background: #f8f9fa !important;
            }

            .rounded-md {
                border-radius: 2px !important;
            }
        }

        .dragging {
            opacity: 0.9 !important;
            transform: scale(1.05) !important;
            z-index: 9999 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
            transition: none !important;
        }

        #sello-radicado {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            transition: all 0.2s ease;
            cursor: grab;
            border: 2px solid #000000;
            /* Prevenir drag nativo */
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
            /* Prevenir selección */
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
            /* Estilo profesional y limpio */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            border-radius: 0;
            font-family: 'Arial', sans-serif;
            text-shadow: none;
            background: #000000;
            color: white;
        }

        #sello-radicado * {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            -webkit-user-drag: none !important;
            -khtml-user-drag: none !important;
            -moz-user-drag: none !important;
            -o-user-drag: none !important;
            user-drag: none !important;
            pointer-events: none;
        }

        #sello-radicado:hover {
            transform: scale(1.02);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
            border-color: #333333;
        }

        #sello-radicado:active {
            cursor: grabbing !important;
        }

        /* Mejorar la visibilidad del área de arrastre */
        #documento-preview {
            position: relative !important;
            overflow: hidden;
            /* Debug: agregar borde temporal para ver límites */
            /* border: 2px solid red; */
        }

        /* Prevenir selección de texto durante el arrastre */
        body.dragging {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }

        /* Feedback visual para proximidad a bordes */
        #sello-radicado.near-left-boundary,
        #sello-radicado.near-right-boundary,
        #sello-radicado.near-top-boundary,
        #sello-radicado.near-bottom-boundary {
            border-color: rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6) !important;
        }

        /* Feedback cuando está exactamente en el límite */
        #sello-radicado.at-boundary {
            border-color: rgba(255, 255, 255, 1) !important;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8) !important;
            animation: boundaryPulse 0.3s ease-in-out;
        }

        @keyframes boundaryPulse {
            0%, 100% {
                transform: scale(1.05);
                border-width: 2px;
            }
            50% {
                transform: scale(1.08);
                border-width: 3px;
            }
        }

        /* Indicadores visuales del área de arrastre */
        #documento-preview {
            position: relative;
            overflow: hidden;
        }

        /* Opcional: Mostrar límites visuales sutiles */
        #documento-preview::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 1px dashed rgba(0, 0, 0, 0.1);
            pointer-events: none;
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Mostrar límites cuando se está arrastrando */
        body.dragging #documento-preview::before {
            opacity: 1;
        }



        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
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
                            Previsualización del Radicado de Salida
                        </h3>
                        <div class="flex space-x-3">
                            <button id="btn-imprimir" class="btn-institutional flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Imprimir
                            </button>
                            <button id="btn-digitalizar" class="btn-institutional flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Digitalizar
                            </button>
                            <button id="btn-finalizar" class="btn-institutional flex items-center opacity-50 cursor-not-allowed" disabled>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Finalizar
                            </button>
                            <button id="btn-volver" class="cancel-button flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <!-- Documento con sello movible -->
                    <div id="documento-preview" class="relative bg-white border-2 border-gray-300 min-h-[800px] p-8">
                        <!-- Sello de radicado movible -->
                        <div id="sello-radicado" draggable="false"
                             class="bg-black text-white cursor-grab z-10"
                             style="position: absolute; top: 20px; right: 20px; padding: 8px 12px; font-size: 8px; line-height: 1.2; width: 120px; border: 2px solid #000; font-family: Arial, sans-serif; font-weight: bold;">
                            <div class="text-center">
                                <div class="mb-1" style="font-size: 7px; letter-spacing: 0.5px;">RADICADO</div>
                                <div style="font-size: 9px; margin: 2px 0;"><?php echo e($datosPreview['numero_radicado']); ?></div>
                                <div style="font-size: 6px; margin: 1px 0;"><?php echo e($datosPreview['fecha_radicado']); ?></div>
                                <div style="font-size: 6px;"><?php echo e($datosPreview['hora_radicado']); ?></div>
                            </div>
                        </div>

                        <!-- Contenido del documento -->
                        <div class="space-y-6">
                            <!-- Encabezado institucional -->
                            <div class="text-center border-b-2 border-gray-300 pb-4">
                                <h1 class="text-xl font-bold text-gray-800">SISTEMA UNIRADICAL</h1>
                                <h2 class="text-md font-medium text-gray-600 mt-1">COMUNICACIÓN OFICIAL DE SALIDA</h2>
                                <div class="text-sm text-gray-500 mt-2">
                                    <strong>No. Radicado:</strong> <?php echo e($datosPreview['numero_radicado']); ?>

                                </div>
                            </div>

                            <!-- Información del remitente y destinatario -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-3">INFORMACIÓN DEL REMITENTE:</h4>
                                    <div class="space-y-2 text-sm">
                                        <p><strong>Dependencia:</strong> <?php echo e($datosPreview['dependencia_origen']->nombre_completo); ?></p>
                                        <p><strong>Funcionario:</strong> <?php echo e($datosPreview['funcionario_remitente']); ?></p>
                                        <?php if($datosPreview['cargo_remitente']): ?>
                                            <p><strong>Cargo:</strong> <?php echo e($datosPreview['cargo_remitente']); ?></p>
                                        <?php endif; ?>
                                        <?php if($datosPreview['telefono_remitente']): ?>
                                            <p><strong>Teléfono:</strong> <?php echo e($datosPreview['telefono_remitente']); ?></p>
                                        <?php endif; ?>
                                        <?php if($datosPreview['email_remitente']): ?>
                                            <p><strong>Email:</strong> <?php echo e($datosPreview['email_remitente']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-3">INFORMACIÓN DEL DESTINATARIO:</h4>
                                    <div class="space-y-2 text-sm">
                                        <p><strong>Nombre:</strong> <?php echo e($datosPreview['nombre_destinatario']); ?></p>
                                        <?php if($datosPreview['telefono_destinatario']): ?>
                                            <p><strong>Teléfono:</strong> <?php echo e($datosPreview['telefono_destinatario']); ?></p>
                                        <?php endif; ?>
                                        <?php if($datosPreview['email_destinatario']): ?>
                                            <p><strong>Email:</strong> <?php echo e($datosPreview['email_destinatario']); ?></p>
                                        <?php endif; ?>
                                        <?php if($datosPreview['direccion_destinatario']): ?>
                                            <p><strong>Dirección:</strong> <?php echo e($datosPreview['direccion_destinatario']); ?></p>
                                        <?php endif; ?>
                                        <p><strong>Ciudad:</strong> <?php echo e($datosPreview['ciudad_destinatario']); ?></p>
                                        <p><strong>Departamento:</strong> <?php echo e($datosPreview['departamento_destinatario']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del documento -->
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">DETALLES DE LA COMUNICACIÓN:</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="grid grid-cols-2 gap-4">
                                        <p><strong>Asunto:</strong> <?php echo e($datosPreview['asunto']); ?></p>
                                        <p><strong>Tipo:</strong> <?php echo e(ucfirst($datosPreview['tipo_comunicacion'])); ?></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <p><strong>Folios:</strong> <?php echo e($datosPreview['numero_folios']); ?></p>
                                        <p><strong>Medio de Envío:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $datosPreview['medio_envio']))); ?></p>
                                    </div>
                                    <p><strong>Clasificación TRD:</strong> <?php echo e($datosPreview['trd']['codigo']); ?> - <?php echo e($datosPreview['trd']['serie']); ?> / <?php echo e($datosPreview['trd']['subserie'] ?: 'N/A'); ?></p>
                                    <?php if($datosPreview['requiere_acuse_recibo']): ?>
                                        <p><strong>Requiere Acuse de Recibo:</strong> Sí</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <?php if($datosPreview['observaciones']): ?>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">OBSERVACIONES:</h4>
                                <div class="p-3 bg-gray-50 rounded-md border">
                                    <?php echo nl2br(e($datosPreview['observaciones'])); ?>

                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Pie de página -->
                            <div class="text-center text-sm text-gray-600 border-t border-gray-300 pt-4 mt-8">
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div class="text-left">
                                        <p><strong>Procesado por:</strong> <?php echo e($datosPreview['usuario_radica']); ?></p>
                                        <p><strong>Fecha:</strong> <?php echo e($datosPreview['fecha_radicado']); ?> <?php echo e($datosPreview['hora_radicado']); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-500">
                                            <em>Documento generado por Sistema UniRadical</em>
                                        </p>
                                        <p class="text-gray-400 text-xs mt-1">
                                            Previsualización - Pendiente de radicación oficial
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/preview.js']); ?>

    <script>
        // Configuración específica para radicación de salida
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar información del radicado para finalización
            if (window.previewManager) {
                window.previewManager.radicadoData = {
                    numero_radicado: '<?php echo e($datosPreview['numero_radicado']); ?>',
                    tipo: 'salida',
                    redirect_url: '<?php echo e(route('radicacion.salida.index')); ?>'
                };
            }
        });
    </script>
</body>
</html>
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/radicacion/salida/preview.blade.php ENDPATH**/ ?>