@props(['href' => '#', 'active' => false, 'icon' => null, 'disabled' => false, 'badge' => false])

@php
$classes = 'sidebar-link';
if ($active) {
    $classes .= ' active';
}
if ($disabled) {
    $classes .= ' disabled';
}
@endphp

<a href="{{ $disabled ? '#' : $href }}"
   class="{{ $classes }}"
   @if($disabled)
       data-disabled="true"
       aria-disabled="true"
       tabindex="-1"
   @endif
   @if($active) aria-current="page" @endif
   title="{{ $disabled ? 'Funcionalidad no disponible' : $slot }}">
    @if($icon)
        <div class="sidebar-link-icon">
            {!! $icon !!}
        </div>
    @endif
    <span class="sidebar-link-text">{{ $slot }}</span>
    @if($badge)
        <div class="sidebar-notification-badge"></div>
    @endif
</a>
