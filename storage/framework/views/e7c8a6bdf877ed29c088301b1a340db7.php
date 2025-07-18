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
    <div data-page="reportes" style="display: none;"></div>

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
                        Reportes y Estadísticas
                    </h2>
                    <p class="text-sm text-gray-600 font-light mt-1">
                        Análisis detallado del sistema UniRadic
                    </p>
                </div>
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
            <!-- Reporte por Período -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-800">Reporte por Período</h3>
                            <p class="text-sm text-gray-600 mt-1">Comparativo de radicados por períodos de tiempo</p>
                        </div>
                        <button type="button"
                                id="exportReportBtn"
                                class="export-button">
                            Exportar Reporte
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Comparativo Diario -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-3">Comparativo Diario</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-blue-700">Hoy:</span>
                                    <span class="text-sm font-medium text-blue-900"><?php echo e(number_format($reportePeriodo['hoy'])); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-blue-700">Ayer:</span>
                                    <span class="text-sm font-medium text-blue-900"><?php echo e(number_format($reportePeriodo['ayer'])); ?></span>
                                </div>
                                <div class="flex justify-between border-t border-blue-200 pt-2">
                                    <span class="text-sm font-medium text-blue-700">Diferencia:</span>
                                    <?php
                                        $diferenciaDiaria = $reportePeriodo['hoy'] - $reportePeriodo['ayer'];
                                        $porcentajeDiario = $reportePeriodo['ayer'] > 0 ? (($diferenciaDiaria / $reportePeriodo['ayer']) * 100) : 0;
                                    ?>
                                    <span class="text-sm font-medium <?php echo e($diferenciaDiaria >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($diferenciaDiaria >= 0 ? '+' : ''); ?><?php echo e(number_format($diferenciaDiaria)); ?>

                                        (<?php echo e(number_format($porcentajeDiario, 1)); ?>%)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Comparativo Semanal -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-medium text-green-800 mb-3">Comparativo Semanal</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-green-700">Esta semana:</span>
                                    <span class="text-sm font-medium text-green-900"><?php echo e(number_format($reportePeriodo['esta_semana'])); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-green-700">Semana pasada:</span>
                                    <span class="text-sm font-medium text-green-900"><?php echo e(number_format($reportePeriodo['semana_pasada'])); ?></span>
                                </div>
                                <div class="flex justify-between border-t border-green-200 pt-2">
                                    <span class="text-sm font-medium text-green-700">Diferencia:</span>
                                    <?php
                                        $diferenciaSemanal = $reportePeriodo['esta_semana'] - $reportePeriodo['semana_pasada'];
                                        $porcentajeSemanal = $reportePeriodo['semana_pasada'] > 0 ? (($diferenciaSemanal / $reportePeriodo['semana_pasada']) * 100) : 0;
                                    ?>
                                    <span class="text-sm font-medium <?php echo e($diferenciaSemanal >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($diferenciaSemanal >= 0 ? '+' : ''); ?><?php echo e(number_format($diferenciaSemanal)); ?>

                                        (<?php echo e(number_format($porcentajeSemanal, 1)); ?>%)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Comparativo Mensual -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <h4 class="font-medium text-purple-800 mb-3">Comparativo Mensual</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-purple-700">Este mes:</span>
                                    <span class="text-sm font-medium text-purple-900"><?php echo e(number_format($reportePeriodo['este_mes'])); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-purple-700">Mes pasado:</span>
                                    <span class="text-sm font-medium text-purple-900"><?php echo e(number_format($reportePeriodo['mes_pasado'])); ?></span>
                                </div>
                                <div class="flex justify-between border-t border-purple-200 pt-2">
                                    <span class="text-sm font-medium text-purple-700">Diferencia:</span>
                                    <?php
                                        $diferenciaMensual = $reportePeriodo['este_mes'] - $reportePeriodo['mes_pasado'];
                                        $porcentajeMensual = $reportePeriodo['mes_pasado'] > 0 ? (($diferenciaMensual / $reportePeriodo['mes_pasado']) * 100) : 0;
                                    ?>
                                    <span class="text-sm font-medium <?php echo e($diferenciaMensual >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($diferenciaMensual >= 0 ? '+' : ''); ?><?php echo e(number_format($diferenciaMensual)); ?>

                                        (<?php echo e(number_format($porcentajeMensual, 1)); ?>%)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reporte por Dependencias -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Top 10 Dependencias Más Activas</h3>
                    <p class="text-sm text-gray-600 mt-1">Dependencias con mayor número de radicados recibidos</p>
                </div>
                <div class="p-6">
                    <?php if($reporteDependencias->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $reporteDependencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dependencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600"><?php echo e($index + 1); ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900"><?php echo e($dependencia->nombre); ?></h4>
                                        <p class="text-sm text-gray-500"><?php echo e($dependencia->descripcion); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900"><?php echo e(number_format($dependencia->radicados_destino_count)); ?></p>
                                    <p class="text-sm text-gray-500">radicados</p>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">No hay datos de dependencias disponibles</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reporte de Eficiencia -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Reporte de Eficiencia</h3>
                    <p class="text-sm text-gray-600 mt-1">Análisis de tiempos de respuesta del mes actual</p>
                </div>
                <div class="p-6">
                    <?php if($reporteEficiencia && $reporteEficiencia->total_respondidos > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-green-800 mb-2">Tiempo Promedio</h4>
                                <p class="text-3xl font-bold text-green-900"><?php echo e(number_format($reporteEficiencia->promedio_dias, 1)); ?></p>
                                <p class="text-sm text-green-600 mt-1">días de respuesta</p>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-blue-800 mb-2">Total Respondidos</h4>
                                <p class="text-3xl font-bold text-blue-900"><?php echo e(number_format($reporteEficiencia->total_respondidos)); ?></p>
                                <p class="text-sm text-blue-600 mt-1">este mes</p>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-yellow-800 mb-2">Eficiencia</h4>
                                <?php
                                    $eficiencia = $reporteEficiencia->promedio_dias <= 5 ? 'Excelente' : 
                                                 ($reporteEficiencia->promedio_dias <= 10 ? 'Buena' : 
                                                 ($reporteEficiencia->promedio_dias <= 15 ? 'Regular' : 'Mejorable'));
                                    $colorEficiencia = $reporteEficiencia->promedio_dias <= 5 ? 'text-green-900' : 
                                                      ($reporteEficiencia->promedio_dias <= 10 ? 'text-blue-900' : 
                                                      ($reporteEficiencia->promedio_dias <= 15 ? 'text-yellow-900' : 'text-red-900'));
                                ?>
                                <p class="text-3xl font-bold <?php echo e($colorEficiencia); ?>"><?php echo e($eficiencia); ?></p>
                                <p class="text-sm text-yellow-600 mt-1">calificación</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin datos de eficiencia</h3>
                            <p class="mt-1 text-sm text-gray-500">No hay radicados respondidos este mes para calcular la eficiencia</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filtros de Reporte Personalizado -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Generar Reporte Personalizado</h3>
                    <p class="text-sm text-gray-600 mt-1">Configura los parámetros para generar un reporte específico</p>
                </div>
                <div class="p-6">
                    <form id="customReportForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                            <input type="date"
                                   name="fecha_desde"
                                   id="fechaDesde"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                            <input type="date"
                                   name="fecha_hasta"
                                   id="fechaHasta"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Radicado</label>
                            <select name="tipo_radicado"
                                    id="tipoRadicado"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Todos</option>
                                <option value="entrada">Entrada</option>
                                <option value="interno">Interno</option>
                                <option value="salida">Salida</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                    id="generateCustomReportBtn"
                                    class="w-full generate-button">
                                Generar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/admin/reportes/index.blade.php ENDPATH**/ ?>