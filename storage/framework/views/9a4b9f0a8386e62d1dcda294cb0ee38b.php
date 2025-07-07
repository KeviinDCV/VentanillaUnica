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
    <div data-page="admin-dependencias"></div>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Dependencias
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de dependencias del hospital
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
            <!-- Estadísticas de Dependencias -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Dependencias</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo e($dependencias->total()); ?></p>
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
                            <p class="text-sm font-medium text-gray-500">Activas</p>
                            <p class="text-lg font-semibold text-green-600"><?php echo e($dependencias->where('activa', true)->count()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Con Radicados</p>
                            <p class="text-lg font-semibold text-yellow-600"><?php echo e($dependencias->where('radicados_destino_count', '>', 0)->count()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Inactivas</p>
                            <p class="text-lg font-semibold text-red-600"><?php echo e($dependencias->where('activa', false)->count()); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Dependencias -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de Dependencias</h3>
                        <div class="flex space-x-3">
                            <input type="text"
                                   id="buscar-dependencias"
                                   placeholder="Buscar dependencias..."
                                   class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <select id="filtro-estado"
                                    class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Todos los estados</option>
                                <option value="activa">Activas</option>
                                <option value="inactiva">Inactivas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Dependencia
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Código
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Responsable
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Estado
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Radicados
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="dependencias-table-body" class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $dependencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dependencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 dependencia-row"
                                data-name="<?php echo e(strtolower($dependencia->nombre)); ?>"
                                data-codigo="<?php echo e(strtolower($dependencia->codigo)); ?>"
                                data-responsable="<?php echo e(strtolower($dependencia->responsable ?? '')); ?>">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="<?php echo e($dependencia->nombre); ?>">
                                                <?php echo e($dependencia->nombre); ?>

                                            </div>
                                            <?php if($dependencia->descripcion): ?>
                                                <div class="text-xs text-gray-500 truncate" title="<?php echo e($dependencia->descripcion); ?>">
                                                    <?php echo e($dependencia->descripcion); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono"><?php echo e($dependencia->codigo); ?></div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm text-gray-900 truncate max-w-32" title="<?php echo e($dependencia->responsable ?: 'No asignado'); ?>">
                                        <?php echo e($dependencia->responsable ?: 'No asignado'); ?>

                                    </div>
                                    <?php if($dependencia->telefono): ?>
                                        <div class="text-xs text-gray-500"><?php echo e($dependencia->telefono); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <?php if($dependencia->activa): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                            Activa
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                            Inactiva
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium"><?php echo e(number_format($dependencia->radicados_destino_count)); ?></span>
                                        <span class="text-xs text-gray-500">dest.</span>
                                    </div>
                                    <?php if($dependencia->radicados_origen_count > 0): ?>
                                        <div class="text-xs text-gray-500">
                                            <?php echo e(number_format($dependencia->radicados_origen_count)); ?> orig.
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-<?php echo e($dependencia->id); ?>')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-<?php echo e($dependencia->id); ?>"
                                             class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button data-action="edit-dependencia"
                                                        data-dependencia-id="<?php echo e($dependencia->id); ?>"
                                                        data-dependencia-codigo="<?php echo e($dependencia->codigo); ?>"
                                                        data-dependencia-nombre="<?php echo e($dependencia->nombre); ?>"
                                                        data-dependencia-sigla="<?php echo e($dependencia->sigla); ?>"
                                                        data-dependencia-descripcion="<?php echo e($dependencia->descripcion); ?>"
                                                        data-dependencia-responsable="<?php echo e($dependencia->responsable); ?>"
                                                        data-dependencia-telefono="<?php echo e($dependencia->telefono); ?>"
                                                        data-dependencia-email="<?php echo e($dependencia->email); ?>"
                                                        data-dependencia-activa="<?php echo e($dependencia->activa ? 'true' : 'false'); ?>"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Activar/Desactivar -->
                                                <form action="<?php echo e(route('admin.dependencias.toggle-status', $dependencia->id)); ?>"
                                                      method="POST"
                                                      class="w-full"
                                                      id="toggle-form-<?php echo e($dependencia->id); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="button"
                                                            class="w-full text-left px-4 py-2 text-sm <?php echo e($dependencia->activa ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50'); ?> flex items-center"
                                                            data-dependencia-name="<?php echo e($dependencia->nombre); ?>"
                                                            data-dependencia-active="<?php echo e($dependencia->activa ? 'true' : 'false'); ?>"
                                                            data-form-id="toggle-form-<?php echo e($dependencia->id); ?>">
                                                        <?php if($dependencia->activa): ?>
                                                            <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"/>
                                                            </svg>
                                                            Desactivar
                                                        <?php else: ?>
                                                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            Activar
                                                        <?php endif; ?>
                                                    </button>
                                                </form>

                                                <!-- Separador -->
                                                <div class="border-t border-gray-100"></div>

                                                <!-- Eliminar -->
                                                <button data-action="delete-dependencia"
                                                        data-dependencia-id="<?php echo e($dependencia->id); ?>"
                                                        data-dependencia-name="<?php echo e($dependencia->nombre); ?>"
                                                        data-radicados-count="<?php echo e($dependencia->radicados_destino_count + $dependencia->radicados_origen_count); ?>"
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
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($dependencias->links()); ?>

                </div>

                <!-- Botón Agregar -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <button data-action="create-dependencia"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        Nueva Dependencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Dependencia -->
    <div id="createDependenciaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="createModalTitle" class="text-lg font-medium text-gray-900">Nueva Dependencia</h3>
                <button data-action="close-create-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-4">
                <!-- Errores -->
                <div id="createModalErrors" class="mb-4 hidden">
                    <div class="bg-red-50 border border-red-200 rounded-md p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                                <ul id="createErrorsList" class="mt-2 text-sm text-red-700 list-disc list-inside"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="createDependenciaForm" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text"
                                   id="create_codigo"
                                   name="codigo"
                                   required
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="create_sigla" class="block text-sm font-medium text-gray-700 mb-1">Sigla</label>
                            <input type="text"
                                   id="create_sigla"
                                   name="sigla"
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="create_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text"
                               id="create_nombre"
                               name="nombre"
                               required
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="create_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="create_descripcion"
                                  name="descripcion"
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="create_responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                            <input type="text"
                                   id="create_responsable"
                                   name="responsable"
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="create_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text"
                                   id="create_telefono"
                                   name="telefono"
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="create_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email"
                               id="create_email"
                               name="email"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="create_activa"
                                   name="activa"
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Dependencia activa</span>
                        </label>
                    </div>
                </form>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button"
                            data-action="close-create-modal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            form="createDependenciaForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Crear Dependencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Dependencia -->
    <div id="editDependenciaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 id="editModalTitle" class="text-lg font-medium text-gray-900">Editar Dependencia</h3>
                <button data-action="close-edit-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-4">
                <!-- Errores -->
                <div id="editModalErrors" class="mb-4 hidden">
                    <div class="bg-red-50 border border-red-200 rounded-md p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                                <ul id="editErrorsList" class="mt-2 text-sm text-red-700 list-disc list-inside"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="editDependenciaForm" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" id="edit_dependencia_id" name="dependencia_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_codigo" class="block text-sm font-medium text-gray-700 mb-1">Código *</label>
                            <input type="text"
                                   id="edit_codigo"
                                   name="codigo"
                                   required
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="edit_sigla" class="block text-sm font-medium text-gray-700 mb-1">Sigla</label>
                            <input type="text"
                                   id="edit_sigla"
                                   name="sigla"
                                   maxlength="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="edit_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text"
                               id="edit_nombre"
                               name="nombre"
                               required
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label for="edit_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="edit_descripcion"
                                  name="descripcion"
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                            <input type="text"
                                   id="edit_responsable"
                                   name="responsable"
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label for="edit_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text"
                                   id="edit_telefono"
                                   name="telefono"
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email"
                               id="edit_email"
                               name="email"
                               maxlength="255"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   id="edit_activa"
                                   name="activa"
                                   value="1"
                                   class="rounded border-gray-300 text-uniradical-blue">
                            <span class="ml-2 text-sm text-gray-700">Dependencia activa</span>
                        </label>
                    </div>
                </form>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 mt-6">
                    <button type="button"
                            data-action="close-edit-modal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            form="editDependenciaForm"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors">
                        Actualizar Dependencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Cambiar Estado -->
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
            <div class="mt-4">
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

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
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
    </div>

    <script>
        // Función simple para manejar los menús desplegables con posicionamiento absoluto
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            if (!dropdown) {
                console.error('No se encontró el dropdown con ID:', dropdownId);
                return;
            }

            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                }
            });

            if (isHidden) {
                // Mostrar dropdown
                dropdown.classList.remove('hidden');

                // Verificar posicionamiento y ajustar automáticamente
                setTimeout(() => {
                    const rect = dropdown.getBoundingClientRect();
                    const container = dropdown.closest('.relative');

                    // Si se sale por la derecha, cambiar a alineación izquierda
                    if (rect.right > window.innerWidth - 10) {
                        dropdown.classList.remove('right-0');
                        dropdown.classList.add('left-0');
                    }

                    // Detectar si está en las últimas 2 filas de la tabla
                    const row = container.closest('tr');
                    const tbody = row.closest('tbody');
                    const allRows = tbody.querySelectorAll('tr');
                    const rowIndex = Array.from(allRows).indexOf(row);
                    const totalRows = allRows.length;

                    // Si está en las últimas 2 filas, abrir hacia arriba
                    if (rowIndex >= totalRows - 2) {
                        dropdown.classList.remove('top-full', 'mt-1');
                        dropdown.classList.add('bottom-full', 'mb-1');
                        dropdown.style.transformOrigin = 'bottom right';
                    } else {
                        // Filas normales, abrir hacia abajo
                        dropdown.classList.remove('bottom-full', 'mb-1');
                        dropdown.classList.add('top-full', 'mt-1');
                        dropdown.style.transformOrigin = 'top right';
                    }
                }, 10);
            } else {
                // Ocultar dropdown y resetear clases
                dropdown.classList.add('hidden');
                dropdown.classList.remove('left-0', 'bottom-full', 'mb-1');
                dropdown.classList.add('right-0', 'top-full', 'mt-1');
                dropdown.style.transformOrigin = 'top right';
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

        // Cerrar dropdowns al hacer scroll
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
            z-index: 50;
            min-width: 12rem;
            transform-origin: top right;
            transition: opacity 0.15s ease-out, transform 0.15s ease-out;
        }

        /* Animaciones suaves para apertura normal (hacia abajo) */
        [id^="dropdown-"]:not(.hidden) {
            opacity: 1;
            transform: scale(1);
        }

        [id^="dropdown-"].hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        /* Estilos específicos para dropdowns que abren hacia arriba */
        [id^="dropdown-"].bottom-full {
            transform-origin: bottom right;
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
            z-index: 1;
        }

        /* Mejorar la visibilidad en las últimas filas */
        tbody tr:nth-last-child(-n+2) [id^="dropdown-"] {
            /* Asegurar que los dropdowns de las últimas 2 filas tengan prioridad */
            z-index: 60;
        }
    </style>
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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/admin/dependencias/index.blade.php ENDPATH**/ ?>