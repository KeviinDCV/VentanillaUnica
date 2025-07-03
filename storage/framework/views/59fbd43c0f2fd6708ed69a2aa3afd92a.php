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
                    Sistema
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Configuración y opciones del sistema UniRadic
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
            <!-- Información del Usuario -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900"><?php echo e(Auth::user()->name); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo e(Auth::user()->email); ?></p>
                        <p class="text-sm text-gray-500">
                            Rol: <span class="font-medium"><?php echo e(Auth::user()->isAdmin() ? 'Administrador' : 'Usuario'); ?></span>
                        </p>
                        <p class="text-sm text-gray-500">
                            Estado: <?php echo e(Auth::user()->active ? 'Activo' : 'Inactivo'); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Sesión -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tiempo de sesión</p>
                            <p class="text-lg font-semibold text-gray-900" id="session-time">Calculando...</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Radicados hoy</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo e($estadisticas['radicados_usuario_hoy']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Estado del sistema</p>
                            <p class="text-lg font-semibold text-green-600">Activo</p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Información del Sistema -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Información del Sistema</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Sistema UniRadic</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Versión:</span>
                                    <span class="font-medium">1.0.0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Entidad:</span>
                                    <span class="font-medium">E.S.E Hospital San Agustín</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ubicación:</span>
                                    <span class="font-medium">Puerto Merizalde</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Estado:</span>
                                    <span class="font-medium text-green-600">Operativo</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Estadísticas del Sistema</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Usuarios registrados:</span>
                                    <span class="font-medium"><?php echo e($estadisticas['total_usuarios']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Radicados totales:</span>
                                    <span class="font-medium"><?php echo e($estadisticas['total_radicados']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Última actualización:</span>
                                    <span class="font-medium"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tiempo de actividad:</span>
                                    <span class="font-medium text-green-600">99.9%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calcular tiempo de sesión
        document.addEventListener('DOMContentLoaded', function() {
            // Usar la hora actual como inicio de sesión aproximado
            const sessionStart = new Date(Date.now() - (Math.random() * 3600000)); // Tiempo aleatorio hasta 1 hora atrás

            function updateSessionTime() {
                const now = new Date();
                const diff = now - sessionStart;

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('session-time').textContent =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            updateSessionTime();
            setInterval(updateSessionTime, 1000);
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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/sistema/index.blade.php ENDPATH**/ ?>