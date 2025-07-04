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
    <div data-page="admin-remitentes"></div>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Remitentes
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de remitentes del sistema UniRadic
                </p>
            </div>
            <div class="flex items-center space-x-4">
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
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Mensajes de éxito y error -->
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800"><?php echo e(session('error')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Estadísticas de Remitentes -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Remitentes</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo e($remitentes->total()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Registrados</p>
                            <p class="text-lg font-semibold text-green-600"><?php echo e($remitentes->where('tipo', 'registrado')->count()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Anónimos</p>
                            <p class="text-lg font-semibold text-orange-600"><?php echo e($remitentes->where('tipo', 'anonimo')->count()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Con Email</p>
                            <p class="text-lg font-semibold text-purple-600"><?php echo e($remitentes->whereNotNull('email')->count()); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Remitentes -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de Remitentes</h3>
                        <div class="flex-1 max-w-md ml-6">
                            <div class="relative">
                                <input type="text"
                                       id="buscar-remitente"
                                       placeholder="Buscar remitentes..."
                                       class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex space-x-3">
                            <select id="filtro-tipo" class="border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos los tipos</option>
                                <option value="registrado">Registrados</option>
                                <option value="anonimo">Anónimos</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-visible">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Remitente
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden md:table-cell">
                                    Tipo
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden md:table-cell">
                                    Documento
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden lg:table-cell">
                                    Contacto
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Radicados
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tabla-remitentes" class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $remitentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remitente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 remitente-row"
                                data-id="<?php echo e($remitente->id); ?>"
                                data-name="<?php echo e(strtolower($remitente->nombre_completo)); ?>"
                                data-tipo="<?php echo e($remitente->tipo); ?>"
                                data-email="<?php echo e(strtolower($remitente->email ?? '')); ?>"
                                data-documento="<?php echo e($remitente->numero_documento ?? ''); ?>">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full <?php echo e($remitente->tipo === 'anonimo' ? 'bg-gray-100' : 'bg-blue-100'); ?> flex items-center justify-center">
                                                <svg class="w-4 h-4 <?php echo e($remitente->tipo === 'anonimo' ? 'text-gray-600' : 'text-blue-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="<?php echo e($remitente->nombre_completo); ?>">
                                                <?php echo e($remitente->nombre_completo); ?>

                                            </div>
                                            <?php if($remitente->entidad): ?>
                                            <div class="text-xs text-gray-500 truncate" title="<?php echo e($remitente->entidad); ?>">
                                                <?php echo e($remitente->entidad); ?>

                                            </div>
                                            <?php endif; ?>
                                            <div class="text-xs text-gray-500 md:hidden">
                                                <?php echo e($remitente->tipo === 'anonimo' ? 'Anónimo' : 'Registrado'); ?>

                                                <?php if($remitente->numero_documento): ?>
                                                    • <?php echo e($remitente->tipo_documento); ?>: <?php echo e($remitente->numero_documento); ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($remitente->tipo === 'anonimo' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800'); ?>">
                                        <span class="w-1.5 h-1.5 mr-1.5 <?php echo e($remitente->tipo === 'anonimo' ? 'bg-gray-400' : 'bg-blue-400'); ?> rounded-full"></span>
                                        <?php echo e($remitente->tipo === 'anonimo' ? 'Anónimo' : 'Registrado'); ?>

                                    </span>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">
                                        <?php if($remitente->tipo_documento && $remitente->numero_documento): ?>
                                            <div class="font-medium"><?php echo e($remitente->tipo_documento); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($remitente->numero_documento); ?></div>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs">Sin documento</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden lg:table-cell">
                                    <div class="text-sm text-gray-900">
                                        <?php if($remitente->email): ?>
                                            <div class="text-xs truncate" title="<?php echo e($remitente->email); ?>"><?php echo e($remitente->email); ?></div>
                                        <?php endif; ?>
                                        <?php if($remitente->telefono): ?>
                                            <div class="text-xs text-gray-500"><?php echo e($remitente->telefono); ?></div>
                                        <?php endif; ?>
                                        <?php if(!$remitente->email && !$remitente->telefono): ?>
                                            <span class="text-gray-400 text-xs">Sin contacto</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($remitente->radicados_count ?? 0); ?></div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-<?php echo e($remitente->id); ?>')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-<?php echo e($remitente->id); ?>"
                                             class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button data-action="edit-remitente"
                                                        data-remitente-id="<?php echo e($remitente->id); ?>"
                                                        data-remitente-nombre="<?php echo e($remitente->nombre_completo); ?>"
                                                        data-remitente-tipo="<?php echo e($remitente->tipo); ?>"
                                                        data-remitente-tipo-documento="<?php echo e($remitente->tipo_documento); ?>"
                                                        data-remitente-numero-documento="<?php echo e($remitente->numero_documento); ?>"
                                                        data-remitente-email="<?php echo e($remitente->email); ?>"
                                                        data-remitente-telefono="<?php echo e($remitente->telefono); ?>"
                                                        data-remitente-ciudad="<?php echo e($remitente->ciudad); ?>"
                                                        data-remitente-departamento="<?php echo e($remitente->departamento); ?>"
                                                        data-remitente-entidad="<?php echo e($remitente->entidad); ?>"
                                                        data-remitente-direccion="<?php echo e($remitente->direccion); ?>"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Separador -->
                                                <div class="border-t border-gray-100"></div>

                                                <!-- Eliminar -->
                                                <button data-action="delete-remitente"
                                                        data-remitente-id="<?php echo e($remitente->id); ?>"
                                                        data-remitente-name="<?php echo e($remitente->nombre_completo); ?>"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($remitentes->links()); ?>

                </div>

                <!-- Botón Agregar -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <button data-action="create-remitente"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        Nuevo Remitente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar remitente -->
    <div id="modal-remitente" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-titulo">Nuevo Remitente</h3>
                    <button id="btn-cerrar-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="form-remitente">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="remitente-id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="remitente-nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nombre_completo"
                                   name="nombre_completo"
                                   required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Nombre completo del remitente">
                        </div>

                        <div>
                            <label for="remitente-tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo"
                                    name="tipo"
                                    required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar tipo...</option>
                                <option value="registrado">Registrado</option>
                                <option value="anonimo">Anónimo</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="remitente-tipo-documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Documento
                            </label>
                            <select id="tipo_documento"
                                    name="tipo_documento"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar...</option>
                                <option value="CC">Cédula de Ciudadanía</option>
                                <option value="CE">Cédula de Extranjería</option>
                                <option value="NIT">NIT</option>
                                <option value="TI">Tarjeta de Identidad</option>
                                <option value="PP">Pasaporte</option>
                            </select>
                        </div>

                        <div>
                            <label for="remitente-numero-documento" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Documento
                            </label>
                            <input type="text"
                                   id="numero_documento"
                                   name="numero_documento"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Número de documento">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="remitente-email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="correo@ejemplo.com">
                        </div>

                        <div>
                            <label for="remitente-telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text"
                                   id="telefono"
                                   name="telefono"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Número de teléfono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-2">
                                Ciudad
                            </label>
                            <input type="text"
                                   id="ciudad"
                                   name="ciudad"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Ciudad">
                        </div>

                        <div>
                            <label for="departamento" class="block text-sm font-medium text-gray-700 mb-2">
                                Departamento
                            </label>
                            <input type="text"
                                   id="departamento"
                                   name="departamento"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                   placeholder="Departamento">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="entidad" class="block text-sm font-medium text-gray-700 mb-2">
                            Entidad/Organización
                        </label>
                        <input type="text"
                               id="entidad"
                               name="entidad"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                               placeholder="Nombre de la entidad u organización">
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea id="direccion"
                                  name="direccion"
                                  rows="2"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                  placeholder="Dirección completa"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="btn-cancelar" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </button>
                        <button type="submit" id="btn-guardar" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Guardar Remitente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <!-- Modal de Confirmación Personalizado -->
    <div id="confirmStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="confirmModalTitle" class="text-lg font-medium text-gray-900">Confirmar Acción</h3>
                <button data-action="close-confirm-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <div id="confirmModalIcon" class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full">
                        <!-- Icono se agregará dinámicamente -->
                    </div>
                    <div class="ml-4 flex-1">
                        <p id="confirmModalMessage" class="text-sm text-gray-600">
                            <!-- Mensaje se agregará dinámicamente -->
                        </p>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3">
                    <button type="button"
                            data-action="close-confirm-modal"
                            class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="button"
                            id="confirmModalAction"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors min-w-[100px]">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    <script>
        // Función simple para manejar los menús desplegables con posicionamiento absoluto
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) {
                console.error('No se encontró el dropdown con ID:', dropdownId);
                return;
            }

            // Cerrar todos los otros dropdowns
            document.querySelectorAll('[id^="dropdown-"]:not(#' + dropdownId + ')').forEach(otherDropdown => {
                resetDropdown(otherDropdown);
            });

            // Toggle del dropdown actual
            if (dropdown.classList.contains('hidden')) {
                // Mostrar dropdown
                dropdown.classList.remove('hidden');

                // Calcular posición inteligente
                const button = dropdown.previousElementSibling;
                const rect = button.getBoundingClientRect();
                const dropdownRect = dropdown.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const viewportWidth = window.innerWidth;

                // Verificar si hay espacio suficiente abajo
                const spaceBelow = viewportHeight - rect.bottom;
                const spaceAbove = rect.top;

                // Verificar si hay espacio suficiente a la derecha
                const spaceRight = viewportWidth - rect.right;

                // Posicionamiento vertical
                if (spaceBelow < 200 && spaceAbove > spaceBelow) {
                    // Mostrar arriba
                    dropdown.classList.remove('top-full', 'mt-1');
                    dropdown.classList.add('bottom-full', 'mb-1');
                    dropdown.style.transformOrigin = 'bottom right';
                } else {
                    // Mostrar abajo (comportamiento por defecto)
                    dropdown.classList.remove('bottom-full', 'mb-1');
                    dropdown.classList.add('top-full', 'mt-1');
                    dropdown.style.transformOrigin = 'top right';
                }

                // Posicionamiento horizontal
                if (spaceRight < 200) {
                    // Mostrar a la izquierda
                    dropdown.classList.remove('right-0');
                    dropdown.classList.add('left-0');
                    dropdown.style.transformOrigin = dropdown.style.transformOrigin.replace('right', 'left');
                } else {
                    // Mostrar a la derecha (comportamiento por defecto)
                    dropdown.classList.remove('left-0');
                    dropdown.classList.add('right-0');
                    dropdown.style.transformOrigin = 'top right';
                }
            } else {
                // Ocultar dropdown
                resetDropdown(dropdown);
            }
        }

        // Función para resetear dropdown a estado inicial
        function resetDropdown(dropdown) {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('left-0', 'bottom-full', 'mb-1');
            dropdown.classList.add('right-0', 'top-full', 'mt-1');
            dropdown.style.transformOrigin = 'top right';
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]') && !event.target.closest('[id^="dropdown-"]')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    resetDropdown(dropdown);
                });
            }
        });

        // Cerrar dropdown después de hacer clic en una acción
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="dropdown-"] button') || event.target.closest('[id^="dropdown-"] a')) {
                setTimeout(() => {
                    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                        resetDropdown(dropdown);
                    });
                }, 100);
            }
        });

        // Cerrar dropdowns al hacer scroll (opcional, para mejor UX)
        window.addEventListener('scroll', function() {
            document.querySelectorAll('[id^="dropdown-"]:not(.hidden)').forEach(dropdown => {
                resetDropdown(dropdown);
            });
        });
    </script>

    <?php $__env->startPush('styles'); ?>
    <style>
        /* Estilos para dropdowns con posicionamiento absoluto inteligente */
        [id^="dropdown-"] {
            transition: opacity 0.1s ease-out, transform 0.1s ease-out;
        }

        [id^="dropdown-"]:not(.hidden) {
            opacity: 1;
            transform: scale(1);
        }

        [id^="dropdown-"].hidden {
            opacity: 0;
            transform: scale(0.95);
        }

        /* Asegurar que los contenedores de la tabla permitan overflow */
        .overflow-x-auto {
            overflow: visible !important;
        }

        /* Mejorar el contenedor de acciones para posicionamiento relativo */
        .relative.inline-block {
            position: relative;
        }

        /* Asegurar que la tabla no interfiera */
        table {
            position: relative;
        }

        /* Asegurar z-index correcto para dropdowns */
        [id^="dropdown-"] {
            z-index: 60;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin-remitentes.js']); ?>
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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/admin/remitentes/index.blade.php ENDPATH**/ ?>