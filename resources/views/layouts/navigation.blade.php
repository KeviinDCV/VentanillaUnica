<!-- Sidebar Navigation -->
<div x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || (localStorage.getItem('sidebarOpen') === null && window.innerWidth >= 1024),
    mobileOpen: false,
    init() {
        // Inicializar store global para sidebar
        if (!this.$store.sidebar) {
            Alpine.store('sidebar', {
                isOpen: this.sidebarOpen,
                toggle() {
                    this.isOpen = !this.isOpen;
                    localStorage.setItem('sidebarOpen', this.isOpen);
                }
            });
        }

        // Sincronizar con el store
        this.$watch('sidebarOpen', (value) => {
            this.$store.sidebar.isOpen = value;
        });

        this.$watch('$store.sidebar.isOpen', (value) => {
            this.sidebarOpen = value;
        });
    },
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
        if (this.$store.sidebar) {
            this.$store.sidebar.isOpen = this.sidebarOpen;
        }

        // Disparar múltiples eventos para asegurar que todos los componentes reaccionen
        document.dispatchEvent(new CustomEvent('sidebarToggled', {
            detail: { isOpen: this.sidebarOpen }
        }));

        document.dispatchEvent(new CustomEvent('sidebar-state-changed', {
            detail: { isOpen: this.sidebarOpen }
        }));

        // Forzar actualización del layout inmediatamente
        window.dispatchEvent(new Event('resize'));
    },
    toggleMobile() {
        this.mobileOpen = !this.mobileOpen;
    }
}" class="relative">

    <!-- Overlay para móviles -->
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="sidebar-overlay"
         style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="{
        'collapsed': !sidebarOpen && window.innerWidth >= 1024,
        'mobile-open': mobileOpen
    }"
    class="sidebar sidebar-no-animations"
    id="main-sidebar"
    x-init="
        // Remover clase de no-animaciones
        $el.classList.remove('sidebar-no-animations');

        // Limpiar solo las transiciones y animaciones
        var allElements = $el.querySelectorAll('*');
        allElements.forEach(function(el) {
            el.style.transition = '';
            el.style.animation = '';
        });

        // Aplicar estado correcto
        if (!sidebarOpen && window.innerWidth >= 1024) {
            $el.classList.add('collapsed');
        } else {
            $el.classList.remove('collapsed');
        }
    ">

        <!-- Header de la Sidebar -->
        <div class="sidebar-header">
            <div :class="{ 'collapsed': !sidebarOpen && window.innerWidth >= 1024 }" class="sidebar-logo">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="relative">
                        <img src="{{ asset('images/LogoHospital.jpg') }}"
                             alt="Logo Hospital"
                             class="h-10 w-10 object-contain flex-shrink-0 sidebar-logo-img rounded-lg shadow-lg border-2 border-white/20">
                        <div class="hidden items-center justify-center h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg border-2 border-white/20 sidebar-logo-fallback">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <!-- Efecto de brillo en el logo -->
                        <div class="absolute inset-0 rounded-lg bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <span class="sidebar-logo-text">UniRadic</span>
                </a>
            </div>

            <!-- Botón toggle para desktop -->
            <button @click="toggleSidebar()" class="sidebar-toggle hidden lg:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          :d="sidebarOpen ? 'M11 19l-7-7 7-7M18 19l-7-7 7-7' : 'M13 5l7 7-7 7M6 5l7 7-7 7'"></path>
                </svg>
            </button>

            <!-- Botón cerrar para móviles -->
            <button @click="mobileOpen = false" class="sidebar-toggle lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navegación basada en roles -->
        <nav class="sidebar-nav">
            @auth
                <x-role-based-navigation :user="Auth::user()" />
            @endauth
        </nav>

        <!-- Footer de la Sidebar -->
        <div class="sidebar-footer">
            @auth
                <!-- Botón Cerrar Sesión -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="sidebar-link" title="Cerrar Sesión" style="background: rgba(255, 255, 255, 0.12); border: 2px solid rgba(255, 255, 255, 0.25); margin: 2px 8px; margin-bottom: 8px;">
                        <div class="sidebar-link-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="sidebar-link-text">Cerrar Sesión</span>
                    </button>
                </form>

                <!-- Información del Usuario -->
                <div :class="{ 'collapsed': !sidebarOpen && window.innerWidth >= 1024 }" class="sidebar-user">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-full flex items-center justify-center flex-shrink-0 relative overflow-hidden shadow-lg border-2 border-white/20">
                        <span class="text-white text-sm font-bold relative z-10">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        <!-- Efecto de brillo animado -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent transform -skew-x-12 -translate-x-full animate-shimmer"></div>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                        <div class="text-xs opacity-80">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </div>

                <!-- Información del Sistema -->
                <div :class="{ 'collapsed': !sidebarOpen && window.innerWidth >= 1024 }" class="sidebar-system-info">
                    <div class="text-center px-4 py-3 border-t border-white/10">
                        <div class="sidebar-system-title">
                            <div class="text-xs font-semibold text-white/90 leading-tight">Sistema de Gestión Documental</div>
                            <div class="text-xs text-white/70 mt-1 leading-tight">E.S.E Hospital San Agustín Puerto Merizalde</div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </aside>

    <!-- Script para aplicar estado inicial sin animaciones de carga -->
    <script>
        (function() {
            try {
                var sidebar = document.getElementById('main-sidebar');
                if (!sidebar) return;

                // Desactivar transiciones solo temporalmente durante la carga inicial
                var allElements = sidebar.querySelectorAll('*');
                allElements.forEach(function(el) {
                    el.style.transition = 'none';
                    el.style.animation = 'none';
                });

                sidebar.style.transition = 'none';
                sidebar.style.animation = 'none';

                // Restaurar transiciones después de que la página esté completamente cargada
                // Esto permite las animaciones de toggle pero evita animaciones de carga
                setTimeout(function() {
                    sidebar.style.transition = '';
                    sidebar.style.animation = '';

                    allElements.forEach(function(el) {
                        el.style.transition = '';
                        el.style.animation = '';
                    });
                }, 100);

            } catch(e) {
                // Silenciar errores
            }
        })();
    </script>

    <!-- Botón hamburguesa para móviles - Diseño mejorado -->
    <button @click="toggleMobile()"
            class="fixed top-4 left-4 z-50 lg:hidden bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 border border-white/20 rounded-xl p-3 shadow-xl backdrop-blur-sm transition-all duration-300 hover:shadow-2xl hover:scale-105">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <!-- Efecto de brillo -->
        <div class="absolute inset-0 rounded-xl bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
    </button>
</div>
