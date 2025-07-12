<!-- Formulario de Radicación Interna para Modal -->
<form action="<?php echo e(route('radicacion.interna.store')); ?>" method="POST" enctype="multipart/form-data" id="radicacionInternaForm" data-protect="true">
    <?php echo csrf_field(); ?>

    <!-- Sección 1: Información del Remitente Interno -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            1. Información del Remitente
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Dependencia Remitente -->
            <div>
                <label for="dependencia_origen_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Dependencia Remitente <span class="text-red-500">*</span>
                </label>
                <select name="dependencia_origen_id" id="dependencia_origen_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar dependencia...</option>
                    <?php $__currentLoopData = $dependencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dependencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dependencia->id); ?>"
                                <?php echo e(old('dependencia_origen_id') == $dependencia->id ? 'selected' : ''); ?>>
                            <?php echo e($dependencia->nombre_completo); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Funcionario Remitente -->
            <div>
                <label for="funcionario_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Funcionario Remitente <span class="text-red-500">*</span>
                </label>
                <input type="text" name="funcionario_remitente" id="funcionario_remitente" required
                       value="<?php echo e(old('funcionario_remitente')); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre del funcionario">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Cargo -->
            <div>
                <label for="cargo_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo
                </label>
                <input type="text" name="cargo_remitente" id="cargo_remitente"
                       value="<?php echo e(old('cargo_remitente')); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Cargo del funcionario">
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                </label>
                <input type="tel" name="telefono_remitente" id="telefono_remitente"
                       value="<?php echo e(old('telefono_remitente')); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Número de teléfono">
            </div>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                Correo Electrónico
            </label>
            <input type="email" name="email_remitente" id="email_remitente"
                   value="<?php echo e(old('email_remitente')); ?>"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="correo@hospital.gov.co">
        </div>
    </div>

    <!-- Sección 2: Información del Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            2. Información del Documento
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Tipo de Solicitud -->
            <div>
                <label for="tipo_comunicacion" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Solicitud <span class="text-red-500">*</span>
                </label>
                <select name="tipo_comunicacion" id="tipo_comunicacion" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <?php $__currentLoopData = $tiposSolicitud; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tipo->codigo); ?>"
                                <?php echo e(old('tipo_comunicacion') == $tipo->codigo ? 'selected' : ''); ?>>
                            <?php echo e($tipo->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Prioridad -->
            <div>
                <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-2">
                    Prioridad <span class="text-red-500">*</span>
                </label>
                <select name="prioridad" id="prioridad" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="baja" <?php echo e(old('prioridad') == 'baja' ? 'selected' : ''); ?>>Baja</option>
                    <option value="normal" <?php echo e(old('prioridad') == 'normal' ? 'selected' : ''); ?>>Normal</option>
                    <option value="alta" <?php echo e(old('prioridad') == 'alta' ? 'selected' : ''); ?>>Alta</option>
                    <option value="urgente" <?php echo e(old('prioridad') == 'urgente' ? 'selected' : ''); ?>>Urgente</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Asunto -->
            <div class="md:col-span-2">
                <label for="asunto" class="block text-sm font-medium text-gray-700 mb-2">
                    Asunto <span class="text-red-500">*</span>
                </label>
                <input type="text" name="asunto" id="asunto" required
                       value="<?php echo e(old('asunto')); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Asunto del documento">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Número de Folios -->
            <div>
                <label for="numero_folios" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Folios <span class="text-red-500">*</span>
                </label>
                <input type="number" name="numero_folios" id="numero_folios" required min="1"
                       value="<?php echo e(old('numero_folios', 1)); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Cantidad de folios">
            </div>

            <!-- Número de Anexos -->
            <div>
                <label for="numero_anexos" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Anexos
                </label>
                <input type="number" name="numero_anexos" id="numero_anexos" min="0"
                       value="<?php echo e(old('numero_anexos', 0)); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Cantidad de anexos">
            </div>
        </div>

        <!-- Observaciones -->
        <div class="mb-4">
            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                Observaciones
            </label>
            <textarea name="observaciones" id="observaciones" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                      placeholder="Observaciones adicionales"><?php echo e(old('observaciones')); ?></textarea>
        </div>
    </div>

    <!-- Sección 3: TRD -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            3. Clasificación Documental (TRD)
        </h3>

        <?php if (isset($component)) { $__componentOriginal99a46d8246fd80cd14c21c1aea288fca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99a46d8246fd80cd14c21c1aea288fca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.trd-selector','data' => ['unidadesAdministrativas' => $unidadesAdministrativas,'optional' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('trd-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['unidadesAdministrativas' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($unidadesAdministrativas),'optional' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99a46d8246fd80cd14c21c1aea288fca)): ?>
<?php $attributes = $__attributesOriginal99a46d8246fd80cd14c21c1aea288fca; ?>
<?php unset($__attributesOriginal99a46d8246fd80cd14c21c1aea288fca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99a46d8246fd80cd14c21c1aea288fca)): ?>
<?php $component = $__componentOriginal99a46d8246fd80cd14c21c1aea288fca; ?>
<?php unset($__componentOriginal99a46d8246fd80cd14c21c1aea288fca); ?>
<?php endif; ?>
    </div>

    <!-- Sección 4: Destino -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Información de Destino
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Dependencia Destino -->
            <div>
                <label for="dependencia_destino_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Dependencia Destino <span class="text-red-500">*</span>
                </label>
                <select name="dependencia_destino_id" id="dependencia_destino_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar dependencia...</option>
                    <?php $__currentLoopData = $dependencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dependencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dependencia->id); ?>"
                                <?php echo e(old('dependencia_destino_id') == $dependencia->id ? 'selected' : ''); ?>>
                            <?php echo e($dependencia->nombre_completo); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Funcionario Destino -->
            <div>
                <label for="funcionario_destino" class="block text-sm font-medium text-gray-700 mb-2">
                    Funcionario Destino
                </label>
                <input type="text" name="funcionario_destino" id="funcionario_destino"
                       value="<?php echo e(old('funcionario_destino')); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre del funcionario destino">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Requiere Respuesta -->
            <div>
                <label for="requiere_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                    Requiere Respuesta <span class="text-red-500">*</span>
                </label>
                <select name="requiere_respuesta" id="requiere_respuesta" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="si" <?php echo e(old('requiere_respuesta') == 'si' ? 'selected' : ''); ?>>Sí</option>
                    <option value="no" <?php echo e(old('requiere_respuesta') == 'no' ? 'selected' : ''); ?>>No</option>
                </select>
            </div>

            <!-- Fecha Límite de Respuesta -->
            <div id="fecha-limite-container" style="display: none;">
                <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Límite de Respuesta <span class="text-red-500">*</span>
                </label>
                <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta"
                       value="<?php echo e(old('fecha_limite_respuesta')); ?>"
                       min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                <p class="text-sm text-gray-500 mt-1">Solo requerido si se selecciona "Requiere respuesta: Sí"</p>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="mb-4">
            <label for="instrucciones" class="block text-sm font-medium text-gray-700 mb-2">
                Instrucciones Especiales
            </label>
            <textarea name="instrucciones" id="instrucciones" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                      placeholder="Instrucciones especiales para el destinatario"><?php echo e(old('instrucciones')); ?></textarea>
        </div>

        <!-- Tipo de Anexo -->
        <div class="mb-4">
            <label for="tipo_anexo" class="block text-sm font-medium text-gray-700 mb-2">
                Tipo de Anexo <span class="text-red-500">*</span>
            </label>
            <select name="tipo_anexo" id="tipo_anexo" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                <option value="">Seleccionar...</option>
                <option value="original" <?php echo e(old('tipo_anexo') == 'original' ? 'selected' : ''); ?>>Original</option>
                <option value="copia" <?php echo e(old('tipo_anexo') == 'copia' ? 'selected' : ''); ?>>Copia</option>
                <option value="ninguno" <?php echo e(old('tipo_anexo') == 'ninguno' ? 'selected' : ''); ?>>Ninguno</option>
            </select>
        </div>
    </div>

    <!-- Sección 5: Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            5. Adjuntar Documento
        </h3>

        <div>
            <label for="documento_modal_interno" class="block text-sm font-medium text-gray-700 mb-2">
                Documento <span class="text-red-500">*</span>
            </label>

            <!-- Zona de arrastrar y soltar -->
            <div id="drop-zone-modal-interno" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-uniradical-blue transition-colors duration-200">
                <div id="drop-zone-content-modal-interno">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="documento_modal_interno" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium text-gray-900">
                                Arrastra y suelta tu archivo aquí, o
                                <span class="text-uniradical-blue hover:text-uniradical-blue-dark">haz clic para seleccionar</span>
                            </span>
                        </label>
                        <input type="file" name="documento" id="documento_modal_interno"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="sr-only">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        PDF, Word, JPG, PNG hasta 10MB
                    </p>
                </div>

                <!-- Vista previa del archivo -->
                <div id="file-preview-modal-interno" class="hidden">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <p id="file-name-modal-interno" class="text-sm font-medium text-gray-900"></p>
                                <p id="file-size-modal-interno" class="text-xs text-gray-500"></p>
                            </div>
                        </div>
                        <button type="button" id="remove-file-modal-interno" class="text-red-600 hover:text-red-800">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="flex justify-between pt-6 border-t border-gray-200">
        <button type="button" id="btn-volver-seleccion" class="cancel-button">
            Volver a Selección
        </button>
        <div class="flex space-x-3">
            <button type="button" id="btn-preview" class="btn-secondary">
                Previsualizar
            </button>
            <button type="submit" class="create-button">
                Crear Radicado Interno
            </button>
        </div>
    </div>
</form>

<!-- JavaScript movido a radicacion.js para funcionar correctamente en modales -->
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/radicacion/forms/interno.blade.php ENDPATH**/ ?>