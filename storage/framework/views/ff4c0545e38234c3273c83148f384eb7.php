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
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('gestion.index')); ?>"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </a>
                <div>
                    <h2 class="font-light text-xl text-gray-800 leading-tight">
                        Panel de Administración
                    </h2>
                    <p class="text-sm text-gray-600 font-light mt-1">
                        Dashboard administrativo del sistema UniRadic
                    </p>
                </div>
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

            <!-- Estadísticas por Tipo y Estado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Estadísticas por Tipo -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Radicados por Tipo</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Entrada</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasTipo['entrada'])); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Interno</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasTipo['interno'])); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Salida</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasTipo['salida'])); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas por Estado -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Radicados por Estado</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Pendiente</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasEstado['pendiente'])); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">En Proceso</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasEstado['en_proceso'])); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Respondido</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasEstado['respondido'])); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Archivado</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900"><?php echo e(number_format($estadisticasEstado['archivado'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión Administrativa -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                <!-- 1. Usuarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Usuarios</h3>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_usuarios'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Usuarios registrados</p>
                    <a href="<?php echo e(route('admin.usuarios')); ?>"
                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                        Gestionar usuarios
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 2. Dependencias -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Dependencias</h3>
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_dependencias'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Dependencias activas</p>
                    <a href="<?php echo e(route('admin.dependencias')); ?>"
                       class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                        Gestionar dependencias
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>


                <!-- 5. Comunicaciones -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Comunicaciones</h3>
                        <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_comunicaciones'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Tipos de comunicación</p>
                    <a href="<?php echo e(route('admin.comunicaciones.index')); ?>"
                       class="inline-flex items-center text-sm font-medium text-cyan-600 hover:text-cyan-500">
                        Gestionar comunicaciones
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 6. Tipos de Solicitud -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Tipos de Solicitud</h3>
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_tipos_solicitud'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Tipos parametrizados</p>
                    <a href="<?php echo e(route('admin.tipos-solicitud.index')); ?>"
                       class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Gestionar tipos
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>



                <!-- 4. Departamentos -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Departamentos</h3>
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_departamentos'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Departamentos activos</p>
                    <a href="<?php echo e(route('admin.departamentos.index')); ?>"
                       class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-500">
                        Gestionar departamentos
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 5. Ciudad -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Ciudad</h3>
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_ciudades'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Ciudades registradas</p>
                    <a href="<?php echo e(route('admin.ciudades.index')); ?>"
                       class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-500">
                        Gestionar ciudades
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 6. Cliente y proveedores -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Cliente y Proveedores</h3>
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2"><?php echo e(number_format($estadisticas['total_remitentes'])); ?></p>
                    <p class="text-sm text-gray-500 mb-4">Remitentes registrados</p>
                    <a href="<?php echo e(route('admin.remitentes.index')); ?>"
                       class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-500">
                        Gestionar remitentes
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Actividad Reciente</h3>
                        <button onclick="abrirModalActividad()"
                                class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            Ver todos los logs
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <?php if($actividadReciente->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $actividadReciente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $actividad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-<?php echo e($actividad['color']); ?>-100 rounded-full flex items-center justify-center">
                                        <?php if($actividad['icono'] == 'user'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'office-building'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'chat'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'clipboard'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'map'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'location'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'user-plus'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                        <?php elseif($actividad['icono'] == 'login'): ?>
                                            <svg class="w-4 h-4 text-<?php echo e($actividad['color']); ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo e($actividad['descripcion']); ?>

                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Por: <?php echo e($actividad['usuario_responsable']); ?>

                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-sm text-gray-500">
                                    <?php echo e($actividad['fecha']->diffForHumans()); ?>

                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No hay actividad reciente en los últimos 7 días</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Actividad Completa -->
    <div id="modalActividad" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actividad Reciente del Sistema</h3>
                <button onclick="cerrarModalActividad()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="p-4">
                <!-- Buscador -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text"
                               id="busquedaActividad"
                               placeholder="Buscar en la actividad..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Lista de Actividad -->
                <div class="max-h-96 overflow-y-auto">
                    <div id="contenidoActividad" class="space-y-3">
                        <!-- El contenido se carga dinámicamente -->
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="text-gray-500 mt-2">Cargando actividad...</p>
                        </div>
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="flex justify-end pt-4 border-t border-gray-200 mt-4">
                    <button onclick="cerrarModalActividad()"
                            class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let timeoutBusqueda;

        function abrirModalActividad() {
            const modal = document.getElementById('modalActividad');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animación de entrada
            const modalContent = modal.querySelector('.relative');
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalContent.style.opacity = '1';
                modalContent.style.transform = 'scale(1) translateY(0)';
            }, 10);

            cargarActividad();
        }

        function cerrarModalActividad() {
            const modal = document.getElementById('modalActividad');
            const modalContent = modal.querySelector('.relative');

            // Animación de salida
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.getElementById('busquedaActividad').value = '';
            }, 200);
        }

        function cargarActividad(busqueda = '') {
            fetch(`<?php echo e(route('admin.actividad.modal')); ?>?busqueda=${encodeURIComponent(busqueda)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarActividades(data.actividades);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('contenidoActividad').innerHTML =
                        '<p class="text-red-500 text-center py-4">Error al cargar la actividad</p>';
                });
        }

        function mostrarActividades(actividades) {
            const contenido = document.getElementById('contenidoActividad');

            if (actividades.length === 0) {
                contenido.innerHTML = '<p class="text-gray-500 text-center py-4">No se encontró actividad</p>';
                return;
            }

            let html = '';
            actividades.forEach(actividad => {
                const icono = obtenerIconoSVG(actividad.icono, actividad.color);
                const fecha = new Date(actividad.fecha).toLocaleString('es-ES');

                html += `
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-${actividad.color}-100 rounded-full flex items-center justify-center">
                                ${icono}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">${actividad.descripcion}</p>
                            <p class="text-sm text-gray-500">Por: ${actividad.usuario_responsable}</p>
                        </div>
                        <div class="flex-shrink-0 text-sm text-gray-500">
                            ${fecha}
                        </div>
                    </div>
                `;
            });

            contenido.innerHTML = html;
        }

        function obtenerIconoSVG(icono, color) {
            const iconos = {
                'user': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>`,
                'office-building': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>`,
                'chat': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>`,
                'clipboard': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>`,
                'map': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>`,
                'location': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>`,
                'user-plus': `<svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>`
            };
            return iconos[icono] || iconos['user'];
        }

        // Búsqueda en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const busquedaInput = document.getElementById('busquedaActividad');

            busquedaInput.addEventListener('input', function() {
                clearTimeout(timeoutBusqueda);
                timeoutBusqueda = setTimeout(() => {
                    cargarActividad(this.value);
                }, 300);
            });
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalActividad').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalActividad();
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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/admin/index.blade.php ENDPATH**/ ?>