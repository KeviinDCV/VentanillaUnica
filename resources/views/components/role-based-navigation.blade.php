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

<!-- Sección Radicación (disponible para todos) -->
<x-sidebar-section title="Radicación" :collapsible="true" :defaultOpen="true" storageKey="sidebar_radicacion">
    <!-- Entrada -->
    <x-sidebar-link :href="route('radicacion.entrada.index')" :active="request()->routeIs('radicacion.entrada.*')">
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
            </svg>
        </x-slot>
        Entrada
    </x-sidebar-link>

    <!-- Consultar Radicado -->
    <x-sidebar-link :href="route('consultar.index')" :active="request()->routeIs('consultar.*')">
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </x-slot>
        Consultar Radicado
    </x-sidebar-link>

    <!-- Interno -->
    <x-sidebar-link :href="route('radicacion.interna.index')" :active="request()->routeIs('radicacion.interna.*')">
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
        </x-slot>
        Interno
    </x-sidebar-link>

    @if($user->isAdmin())
    <!-- Salida (solo para administradores) -->
    <x-sidebar-link :href="route('radicacion.salida.index')" :active="request()->routeIs('radicacion.salida.*')">
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </x-slot>
        Salida
    </x-sidebar-link>
    @endif
</x-sidebar-section>

@if($user->isAdmin())
<!-- Sección Gestión (solo para administradores) -->
<x-sidebar-section title="Gestión" :collapsible="true" :defaultOpen="true" storageKey="sidebar_gestion">
    <!-- Reportes -->
    <x-sidebar-link :href="route('admin.reportes')" :active="request()->routeIs('admin.reportes')" :disabled="false">
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </x-slot>
        Reportes
    </x-sidebar-link>

    <!-- Administración -->
    <x-sidebar-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </x-slot>
        Administración
    </x-sidebar-link>
</x-sidebar-section>
@endif

<!-- Separador -->
<x-sidebar-section title="Sistema" />

@if($user->isAdmin())
<!-- Suspender (solo para administradores) -->
<x-sidebar-link href="#" :disabled="true">
    <x-slot name="icon">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </x-slot>
    Suspender
</x-sidebar-link>
@endif

<!-- Cerrar Sesión (disponible para todos) -->
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="sidebar-link w-full text-left">
        <div class="sidebar-link-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
        </div>
        <span class="sidebar-link-text">Cerrar Sesión</span>
    </button>
</form>
