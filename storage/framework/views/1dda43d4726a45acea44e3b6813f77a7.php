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
                    Radicación de Documentos de Entrada
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Formulario para radicar documentos externos recibidos
                </p>
            </div>
            <?php if (isset($component)) { $__componentOriginal891e6c0b8a48d6de15606ccc6221404b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal891e6c0b8a48d6de15606ccc6221404b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.hospital-brand','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('hospital-brand'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal891e6c0b8a48d6de15606ccc6221404b)): ?>
<?php $attributes = $__attributesOriginal891e6c0b8a48d6de15606ccc6221404b; ?>
<?php unset($__attributesOriginal891e6c0b8a48d6de15606ccc6221404b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal891e6c0b8a48d6de15606ccc6221404b)): ?>
<?php $component = $__componentOriginal891e6c0b8a48d6de15606ccc6221404b; ?>
<?php unset($__componentOriginal891e6c0b8a48d6de15606ccc6221404b); ?>
<?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="container-minimal">
            <div class="card-minimal">
                <div class="p-8">
                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Se encontraron errores en el formulario:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('radicacion.entrada.store')); ?>" method="POST" enctype="multipart/form-data" id="radicacionEntradaForm" data-protect="true">
                        <?php echo csrf_field(); ?>

                        <!-- Sección 1: Información del Remitente -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                1. Información del Remitente
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tipo de Remitente -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Remitente <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="tipo_remitente" value="anonimo"
                                                   class="form-radio text-uniradical-blue"
                                                   <?php echo e(old('tipo_remitente') === 'anonimo' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-sm text-gray-700">Anónimo</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="tipo_remitente" value="registrado"
                                                   class="form-radio text-uniradical-blue"
                                                   <?php echo e(old('tipo_remitente') === 'registrado' ? 'checked' : ''); ?>>
                                            <span class="ml-2 text-sm text-gray-700">Registrado</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Campos para remitente registrado -->
                                <div id="campos-registrado" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                                    <div>
                                        <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tipo de Documento <span class="text-red-500">*</span>
                                        </label>
                                        <select name="tipo_documento" id="tipo_documento"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                            <option value="">Seleccionar...</option>
                                            <option value="CC" <?php echo e(old('tipo_documento') === 'CC' ? 'selected' : ''); ?>>Cédula de Ciudadanía</option>
                                            <option value="CE" <?php echo e(old('tipo_documento') === 'CE' ? 'selected' : ''); ?>>Cédula de Extranjería</option>
                                            <option value="TI" <?php echo e(old('tipo_documento') === 'TI' ? 'selected' : ''); ?>>Tarjeta de Identidad</option>
                                            <option value="PP" <?php echo e(old('tipo_documento') === 'PP' ? 'selected' : ''); ?>>Pasaporte</option>
                                            <option value="NIT" <?php echo e(old('tipo_documento') === 'NIT' ? 'selected' : ''); ?>>NIT</option>
                                            <option value="OTRO" <?php echo e(old('tipo_documento') === 'OTRO' ? 'selected' : ''); ?>>Otro</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Documento <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="numero_documento" id="numero_documento"
                                               value="<?php echo e(old('numero_documento')); ?>"
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                               placeholder="Número de documento">
                                    </div>
                                </div>

                                <!-- Nombre Completo -->
                                <div class="md:col-span-2">
                                    <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre Completo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombre_completo" id="nombre_completo"
                                           value="<?php echo e(old('nombre_completo')); ?>"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Nombre completo del remitente" required>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono
                                    </label>
                                    <input type="text" name="telefono" id="telefono"
                                           value="<?php echo e(old('telefono')); ?>"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Número de teléfono">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="email"
                                           value="<?php echo e(old('email')); ?>"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Correo electrónico">
                                </div>

                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dirección
                                    </label>
                                    <textarea name="direccion" id="direccion" rows="2"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                              placeholder="Dirección completa"><?php echo e(old('direccion')); ?></textarea>
                                </div>

                                <!-- Departamento -->
                                <div>
                                    <label for="departamento_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Departamento <span class="text-red-500">*</span>
                                    </label>
                                    <select name="departamento_id" id="departamento_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar departamento...</option>
                                        <?php $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $departamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($departamento->id); ?>" <?php echo e(old('departamento_id') == $departamento->id ? 'selected' : ''); ?>>
                                                <?php echo e($departamento->nombre); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Primero seleccione el departamento para ver las ciudades disponibles</p>
                                </div>

                                <!-- Ciudad -->
                                <div>
                                    <label for="ciudad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ciudad <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ciudad_id" id="ciudad_id" required disabled
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue disabled:bg-gray-100 disabled:cursor-not-allowed">
                                        <option value="">Primero seleccione un departamento...</option>
                                        <?php $__currentLoopData = $ciudades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ciudad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($ciudad->id); ?>"
                                                    data-departamento="<?php echo e($ciudad->departamento_id); ?>"
                                                    <?php echo e(old('ciudad_id') == $ciudad->id ? 'selected' : ''); ?>

                                                    style="display: none;">
                                                <?php echo e($ciudad->nombre); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Las ciudades se mostrarán según el departamento seleccionado</p>
                                </div>

                                <!-- Entidad -->
                                <div class="md:col-span-2">
                                    <label for="entidad" class="block text-sm font-medium text-gray-700 mb-2">
                                        Entidad que Representa
                                    </label>
                                    <input type="text" name="entidad" id="entidad"
                                           value="<?php echo e(old('entidad')); ?>"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Entidad o empresa que representa (si aplica)">
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Datos del Radicado -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                2. Datos del Radicado
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Medio de Recepción -->
                                <div>
                                    <label for="medio_recepcion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Medio de Recepción <span class="text-red-500">*</span>
                                    </label>
                                    <select name="medio_recepcion" id="medio_recepcion" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="fisico" <?php echo e(old('medio_recepcion') === 'fisico' ? 'selected' : ''); ?>>Físico</option>
                                        <option value="email" <?php echo e(old('medio_recepcion') === 'email' ? 'selected' : ''); ?>>Email</option>
                                        <option value="web" <?php echo e(old('medio_recepcion') === 'web' ? 'selected' : ''); ?>>Página Web</option>
                                        <option value="telefono" <?php echo e(old('medio_recepcion') === 'telefono' ? 'selected' : ''); ?>>Teléfono</option>
                                        <option value="fax" <?php echo e(old('medio_recepcion') === 'fax' ? 'selected' : ''); ?>>Fax</option>
                                        <option value="otro" <?php echo e(old('medio_recepcion') === 'otro' ? 'selected' : ''); ?>>Otro</option>
                                    </select>
                                </div>

                                <!-- Tipo de solicitud -->
                                <div>
                                    <label for="tipo_comunicacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de solicitud <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo_comunicacion" id="tipo_comunicacion" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <?php $__currentLoopData = $tiposSolicitud; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($tipo->codigo); ?>" <?php echo e(old('tipo_comunicacion') === $tipo->codigo ? 'selected' : ''); ?>>
                                                <?php echo e($tipo->nombre); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!-- Número de Folios -->
                                <div>
                                    <label for="numero_folios" class="block text-sm font-medium text-gray-700 mb-2">
                                        Número de Folios <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="numero_folios" id="numero_folios"
                                           value="<?php echo e(old('numero_folios', 1)); ?>" min="1" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                </div>

                                <!-- Observaciones -->
                                <div class="md:col-span-2">
                                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                        Observaciones
                                    </label>
                                    <textarea name="observaciones" id="observaciones" rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                              placeholder="Observaciones adicionales sobre el documento"><?php echo e(old('observaciones')); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: TRD (Tablas de Retención Documental) -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                3. Tablas de Retención Documental (TRD)
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- TRD Jerárquico -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Clasificación Documental (TRD)</h4>
                                    <?php if (isset($component)) { $__componentOriginal99a46d8246fd80cd14c21c1aea288fca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99a46d8246fd80cd14c21c1aea288fca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.trd-selector','data' => ['unidadesAdministrativas' => $unidadesAdministrativas]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('trd-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['unidadesAdministrativas' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($unidadesAdministrativas)]); ?>
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

                                <!-- Información del TRD seleccionado -->
                                <div id="trd-info" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-md">
                                    <h4 class="font-medium text-blue-800 mb-2">Información del TRD:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-blue-700">Código:</span>
                                            <span id="trd-codigo" class="text-blue-600"></span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-700">Serie:</span>
                                            <span id="trd-serie" class="text-blue-600"></span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-700">Subserie:</span>
                                            <span id="trd-subserie" class="text-blue-600"></span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="font-medium text-blue-700">Asunto:</span>
                                        <span id="trd-asunto" class="text-blue-600"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Destino del Documento -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                4. Destino del Documento
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Dependencia Destino -->
                                <div class="md:col-span-2">
                                    <label for="dependencia_destino_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Oficina Destinataria <span class="text-red-500">*</span>
                                    </label>
                                    <select name="dependencia_destino_id" id="dependencia_destino_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar dependencia...</option>
                                        <?php $__currentLoopData = $dependencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dependencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($dependencia->id); ?>" <?php echo e(old('dependencia_destino_id') == $dependencia->id ? 'selected' : ''); ?>>
                                                <?php echo e($dependencia->nombre_completo); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <!-- Medio de Respuesta -->
                                <div>
                                    <label for="medio_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                                        Medio de Respuesta <span class="text-red-500">*</span>
                                    </label>
                                    <select name="medio_respuesta" id="medio_respuesta" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="fisico" <?php echo e(old('medio_respuesta') === 'fisico' ? 'selected' : ''); ?>>Físico</option>
                                        <option value="email" <?php echo e(old('medio_respuesta') === 'email' ? 'selected' : ''); ?>>Email</option>
                                        <option value="telefono" <?php echo e(old('medio_respuesta') === 'telefono' ? 'selected' : ''); ?>>Teléfono</option>
                                        <option value="presencial" <?php echo e(old('medio_respuesta') === 'presencial' ? 'selected' : ''); ?>>Presencial</option>
                                        <option value="no_requiere" <?php echo e(old('medio_respuesta') === 'no_requiere' ? 'selected' : ''); ?>>No Requiere</option>
                                    </select>
                                </div>

                                <!-- Tipo de Anexo -->
                                <div>
                                    <label for="tipo_anexo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Anexo <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo_anexo" id="tipo_anexo" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="original" <?php echo e(old('tipo_anexo') === 'original' ? 'selected' : ''); ?>>Original</option>
                                        <option value="copia" <?php echo e(old('tipo_anexo') === 'copia' ? 'selected' : ''); ?>>Copia</option>
                                        <option value="ninguno" <?php echo e(old('tipo_anexo') === 'ninguno' ? 'selected' : ''); ?>>Ninguno</option>
                                    </select>
                                </div>

                                <!-- Fecha Límite de Respuesta -->
                                <div class="md:col-span-2">
                                    <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Límite de Respuesta
                                    </label>
                                    <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta"
                                           value="<?php echo e(old('fecha_limite_respuesta')); ?>"
                                           min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <p class="text-xs text-gray-500 mt-1">Opcional. Si no se especifica, no habrá fecha límite.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 5: Documento Digitalizado -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                5. Documento Digitalizado
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Archivo -->
                                <div>
                                    <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">
                                        Adjuntar Documento <span class="text-red-500">*</span>
                                    </label>

                                    <!-- Zona de arrastrar y soltar -->
                                    <div id="drop-zone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-uniradical-blue transition-colors duration-200">
                                        <div id="drop-zone-content">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-4">
                                                <label for="documento" class="cursor-pointer">
                                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                                        Arrastra y suelta tu archivo aquí, o
                                                        <span class="text-uniradical-blue hover:text-uniradical-blue-dark">haz clic para seleccionar</span>
                                                    </span>
                                                </label>
                                                <input type="file" name="documento" id="documento" required
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                       class="sr-only">
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">
                                                PDF, Word, JPG, PNG hasta 10MB
                                            </p>
                                        </div>

                                        <!-- Indicador de carga -->
                                        <div id="upload-progress" class="hidden">
                                            <div class="flex items-center justify-center">
                                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-uniradical-blue"></div>
                                                <span class="ml-2 text-sm text-gray-600">Procesando archivo...</span>
                                            </div>
                                            <div class="mt-2 bg-gray-200 rounded-full h-2">
                                                <div id="progress-bar" class="bg-uniradical-blue h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vista previa del archivo -->
                                    <div id="file-preview" class="hidden mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div id="file-icon" class="flex-shrink-0">
                                                    <!-- Icono se insertará dinámicamente -->
                                                </div>
                                                <div>
                                                    <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                                                    <p id="file-size" class="text-xs text-gray-500"></p>
                                                    <p id="file-type" class="text-xs text-gray-500"></p>
                                                </div>
                                            </div>
                                            <button type="button" id="remove-file" class="text-red-600 hover:text-red-800 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Vista previa de imagen -->
                                        <div id="image-preview" class="hidden mt-3">
                                            <img id="preview-image" src="" alt="Vista previa" class="max-w-full h-48 object-contain border border-gray-200 rounded">
                                        </div>
                                    </div>
                                </div>

                                <!-- Vista previa del archivo -->
                                <div id="file-preview" class="hidden p-4 bg-gray-50 border border-gray-200 rounded-md">
                                    <h4 class="font-medium text-gray-800 mb-2">Archivo seleccionado:</h4>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p id="file-name" class="text-sm font-medium text-gray-800"></p>
                                            <p id="file-size" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-between">
                            <a href="<?php echo e(route('dashboard')); ?>"
                               class="cancel-button">
                                Cancelar
                            </a>
                            <div class="flex space-x-3">
                                <button type="button"
                                        id="btn-preview"
                                        class="btn-secondary">
                                    Previsualizar
                                </button>
                                <button type="submit"
                                        class="create-button">
                                    Crear Radicado
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/ciudad-departamento.js'); ?>
    <?php $__env->stopPush(); ?>

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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/radicacion/entrada/index.blade.php ENDPATH**/ ?>