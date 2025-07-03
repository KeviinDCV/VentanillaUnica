<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href' => '#', 'active' => false, 'icon' => null, 'disabled' => false, 'badge' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['href' => '#', 'active' => false, 'icon' => null, 'disabled' => false, 'badge' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$classes = 'sidebar-link';
if ($active) {
    $classes .= ' active';
}
if ($disabled) {
    $classes .= ' disabled';
}
?>

<a href="<?php echo e($disabled ? '#' : $href); ?>"
   class="<?php echo e($classes); ?>"
   <?php if($disabled): ?>
       data-disabled="true"
       aria-disabled="true"
       tabindex="-1"
   <?php endif; ?>
   <?php if($active): ?> aria-current="page" <?php endif; ?>
   title="<?php echo e($disabled ? 'Funcionalidad no disponible' : $slot); ?>">
    <?php if($icon): ?>
        <div class="sidebar-link-icon">
            <?php echo $icon; ?>

        </div>
    <?php endif; ?>
    <span class="sidebar-link-text"><?php echo e($slot); ?></span>
    <?php if($badge): ?>
        <div class="sidebar-notification-badge"></div>
    <?php endif; ?>
</a>
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/components/sidebar-link.blade.php ENDPATH**/ ?>