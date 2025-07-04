<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Radicado Interno <?php echo e($radicado->numero_radicado); ?>

                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Documento interno radicado exitosamente
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('radicacion.index')); ?>"
                   class="create-button">
                    Nuevo Radicado Interno
                </a>
                <button id="btn-print"
                        class="btn-institutional">
                    Imprimir
                </button>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="container-minimal">
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                <?php echo e(session('success')); ?>

                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-minimal">
                <div class="p-8">
                    <!-- Información del Radicado -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800">Información del Radicado Interno</h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                <?php echo e($radicado->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($radicado->estado === 'en_proceso' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($radicado->estado === 'respondido' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($radicado->estado === 'archivado' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                <?php echo e(ucfirst($radicado->estado)); ?>

                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Radicado</label>
                                <p class="text-lg font-semibold text-uniradical-blue"><?php echo e($radicado->numero_radicado); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Radicación</label>
                                <p class="text-sm text-gray-900"><?php echo e($radicado->fecha_radicado->format('d/m/Y')); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hora de Radicación</label>
                                <p class="text-sm text-gray-900"><?php echo e($radicado->hora_radicado); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Dependencia Origen -->
                            <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                                <h4 class="font-medium text-green-800 mb-3">Dependencia de Origen</h4>
                                <?php if($dependenciaOrigen): ?>
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-green-700">Dependencia:</span>
                                            <span class="text-sm text-green-900"><?php echo e($dependenciaOrigen->nombre_completo); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-green-700">Funcionario:</span>
                                            <span class="text-sm text-green-900"><?php echo e($radicado->remitente->nombre_completo); ?></span>
                                        </div>
                                        <?php if($radicado->remitente->telefono): ?>
                                        <div>
                                            <span class="text-sm font-medium text-green-700">Teléfono:</span>
                                            <span class="text-sm text-green-900"><?php echo e($radicado->remitente->telefono); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($radicado->remitente->email): ?>
                                        <div>
                                            <span class="text-sm font-medium text-green-700">Email:</span>
                                            <span class="text-sm text-green-900"><?php echo e($radicado->remitente->email); ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-sm text-green-900"><?php echo e($radicado->remitente->entidad); ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Dependencia Destino -->
                            <div class="p-4 bg-orange-50 border border-orange-200 rounded-md">
                                <h4 class="font-medium text-orange-800 mb-3">Dependencia Destinataria</h4>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-sm font-medium text-orange-700">Dependencia:</span>
                                        <span class="text-sm text-orange-900"><?php echo e($radicado->dependenciaDestino->nombre_completo); ?></span>
                                    </div>
                                    <?php if($radicado->dependenciaDestino->responsable): ?>
                                    <div>
                                        <span class="text-sm font-medium text-orange-700">Responsable:</span>
                                        <span class="text-sm text-orange-900"><?php echo e($radicado->dependenciaDestino->responsable); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($radicado->dependenciaDestino->telefono): ?>
                                    <div>
                                        <span class="text-sm font-medium text-orange-700">Teléfono:</span>
                                        <span class="text-sm text-orange-900"><?php echo e($radicado->dependenciaDestino->telefono); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Documento -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Información del Documento
                        </h3>

                        <!-- Extraer información de observaciones -->
                        <?php
                            $observaciones = explode(' | ', $radicado->observaciones);
                            $asunto = '';
                            $tipo = '';
                            $prioridad = '';
                            $cargo = '';
                            $observacionesAdicionales = '';

                            foreach ($observaciones as $obs) {
                                if (str_starts_with($obs, 'Asunto:')) {
                                    $asunto = trim(str_replace('Asunto:', '', $obs));
                                } elseif (str_starts_with($obs, 'Tipo:')) {
                                    $tipo = trim(str_replace('Tipo:', '', $obs));
                                } elseif (str_starts_with($obs, 'Prioridad:')) {
                                    $prioridad = trim(str_replace('Prioridad:', '', $obs));
                                } elseif (str_starts_with($obs, 'Cargo:')) {
                                    $cargo = trim(str_replace('Cargo:', '', $obs));
                                } elseif (str_starts_with($obs, 'Observaciones:')) {
                                    $observacionesAdicionales = trim(str_replace('Observaciones:', '', $obs));
                                }
                            }
                        ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php if($asunto): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                                <p class="text-sm text-gray-900 p-3 bg-gray-50 rounded-md"><?php echo e($asunto); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($tipo): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Comunicación</label>
                                <p class="text-sm text-gray-900"><?php echo e($tipo); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($prioridad): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    <?php echo e($prioridad === 'Urgente' ? 'bg-red-100 text-red-800' : ''); ?>

                                    <?php echo e($prioridad === 'Alta' ? 'bg-orange-100 text-orange-800' : ''); ?>

                                    <?php echo e($prioridad === 'Normal' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                    <?php echo e($prioridad === 'Baja' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                    <?php echo e($prioridad); ?>

                                </span>
                            </div>
                            <?php endif; ?>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Folios</label>
                                <p class="text-sm text-gray-900"><?php echo e($radicado->numero_folios); ?></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Anexo</label>
                                <p class="text-sm text-gray-900"><?php echo e(ucfirst($radicado->tipo_anexo)); ?></p>
                            </div>

                            <?php if($cargo): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cargo del Funcionario</label>
                                <p class="text-sm text-gray-900"><?php echo e($cargo); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if($observacionesAdicionales): ?>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones Adicionales</label>
                                <p class="text-sm text-gray-900 p-3 bg-gray-50 rounded-md"><?php echo e($observacionesAdicionales); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- TRD -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Clasificación TRD
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <?php if($radicado->subserie && $radicado->subserie->serie && $radicado->subserie->serie->unidadAdministrativa): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unidad Administrativa</label>
                                <p class="text-sm text-gray-900">
                                    <?php echo e($radicado->subserie->serie->unidadAdministrativa->codigo); ?> - 
                                    <?php echo e($radicado->subserie->serie->unidadAdministrativa->nombre); ?>

                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Serie</label>
                                <p class="text-sm text-gray-900">
                                    <?php echo e($radicado->subserie->serie->codigo); ?> - <?php echo e($radicado->subserie->serie->nombre); ?>

                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subserie</label>
                                <p class="text-sm text-gray-900">
                                    <?php echo e($radicado->subserie->codigo); ?> - <?php echo e($radicado->subserie->nombre); ?>

                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Información de Respuesta -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Información de Respuesta
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Requiere Respuesta</label>
                                <p class="text-sm text-gray-900">
                                    <?php echo e($radicado->medio_respuesta === 'no_requiere' ? 'No' : 'Sí'); ?>

                                </p>
                            </div>

                            <?php if($radicado->fecha_limite_respuesta): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Límite de Respuesta</label>
                                <p class="text-sm text-gray-900
                                    <?php echo e($radicado->estaVencido() ? 'text-red-600 font-medium' : ''); ?>">
                                    <?php echo e($radicado->fecha_limite_respuesta->format('d/m/Y')); ?>

                                    <?php if($radicado->estaVencido()): ?>
                                        <span class="text-red-600">(VENCIDO)</span>
                                    <?php elseif($radicado->dias_restantes !== null): ?>
                                        <span class="text-gray-500">(<?php echo e($radicado->dias_restantes); ?> días restantes)</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Documentos Adjuntos -->
                    <?php if($radicado->documentos->count() > 0): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                            Documentos Adjuntos
                        </h3>

                        <div class="space-y-3">
                            <?php $__currentLoopData = $radicado->documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $documento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800"><?php echo e($documento->nombre_archivo); ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?php echo e($documento->tipo_archivo); ?> - <?php echo e($documento->tamaño_legible); ?>

                                            <?php if($documento->es_principal): ?>
                                                <span class="text-blue-600">(Principal)</span>
                                            <?php endif; ?>
                                        </p>
                                        <?php if($documento->descripcion): ?>
                                            <p class="text-xs text-gray-600 mt-1"><?php echo e($documento->descripcion); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <?php if($documento->archivoExiste()): ?>
                                        <a href="<?php echo e($documento->url_archivo); ?>" target="_blank"
                                           class="px-3 py-1 text-xs bg-uniradical-blue text-white rounded hover:bg-opacity-90 transition duration-200">
                                            Ver
                                        </a>
                                        <a href="<?php echo e($documento->url_archivo); ?>" download
                                           class="px-3 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-200">
                                            Descargar
                                        </a>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded">
                                            Archivo no encontrado
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Acciones -->
                    <div class="flex justify-between pt-6 border-t border-gray-200">
                        <a href="<?php echo e(route('dashboard')); ?>"
                           class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                            Volver al Dashboard
                        </a>
                        <div class="flex space-x-3">
                            <a href="<?php echo e(route('radicacion.index')); ?>"
                               class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200">
                                Nuevo Radicado Interno
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

    <script nonce="<?php echo e(session('csp_nonce', 'default-nonce')); ?>">
        document.addEventListener('DOMContentLoaded', function() {
            const printButton = document.getElementById('btn-print');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    window.print();
                });
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/radicacion/interna/show.blade.php ENDPATH**/ ?>