@props(['user'])

<!-- Panel Principal (disponible para todos) -->
<x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    <x-slot name="icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
        </svg>
    </x-slot>
    Panel Principal
</x-sidebar-link>

<!-- Radicación (disponible para todos) -->
<x-sidebar-link :href="route('radicacion.index')" :active="request()->routeIs('radicacion.*')">
    <x-slot name="icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </x-slot>
    Radicación
</x-sidebar-link>

@if($user->isAdmin())
<!-- Gestión (solo para administradores) -->
<x-sidebar-link :href="route('gestion.index')" :active="request()->routeIs('gestion.*')">
    <x-slot name="icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </x-slot>
    Gestión
</x-sidebar-link>
@endif

<!-- Sistema -->
<x-sidebar-link :href="route('sistema.index')" :active="request()->routeIs('sistema.*')">
    <x-slot name="icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </x-slot>
    Sistema
</x-sidebar-link>
