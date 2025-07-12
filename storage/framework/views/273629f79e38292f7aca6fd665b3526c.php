
<?php
    $isOptional = isset($optional) && $optional;
    $requiredAttr = $isOptional ? '' : 'required';
    $asterisk = $isOptional ? '' : '<span class="text-red-500">*</span>';
    $optionalText = $isOptional ? ' (Opcional)' : '';
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Unidad Administrativa -->
    <div>
        <label for="unidad_administrativa_id" class="block text-sm font-medium text-gray-700 mb-2">
            Unidad Administrativa<?php echo $optionalText; ?> <?php echo $asterisk; ?>

        </label>
        <select name="unidad_administrativa_id" id="unidad_administrativa_id" <?php echo e($requiredAttr); ?>

                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                onchange="loadSeriesForRadicacion(this.value)">
            <option value="">Seleccionar unidad...</option>
            <?php if(isset($unidadesAdministrativas) && count($unidadesAdministrativas) > 0): ?>
                <?php $__currentLoopData = $unidadesAdministrativas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($unidad->id ?? ''); ?>" <?php echo e(old('unidad_administrativa_id') == ($unidad->id ?? '') ? 'selected' : ''); ?>>
                        <?php echo e(($unidad->codigo ?? 'Sin código')); ?> - <?php echo e(($unidad->nombre ?? 'Sin nombre')); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <option value="">No hay unidades disponibles</option>
            <?php endif; ?>
        </select>
    </div>

    <!-- Serie -->
    <div>
        <label for="serie_id" class="block text-sm font-medium text-gray-700 mb-2">
            Serie<?php echo $optionalText; ?> <?php echo $asterisk; ?>

        </label>
        <select name="serie_id" id="serie_id" <?php echo e($requiredAttr); ?>

                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                onchange="loadSubseriesForRadicacion(this.value)">
            <option value="">Seleccionar serie...</option>
        </select>
    </div>

    <!-- Subserie -->
    <div>
        <label for="subserie_id" class="block text-sm font-medium text-gray-700 mb-2">
            Subserie<?php echo $optionalText; ?> <?php echo $asterisk; ?>

        </label>
        <select name="subserie_id" id="subserie_id" <?php echo e($requiredAttr); ?>

                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                onchange="updateTrdId(this.value)">
            <option value="">Seleccionar subserie...</option>
        </select>
        <!-- Campo oculto para compatibilidad con controladores que esperan trd_id -->
        <input type="hidden" name="trd_id" id="trd_id" value="<?php echo e(old('trd_id')); ?>">
    </div>
</div>


<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/components/trd-selector.blade.php ENDPATH**/ ?>