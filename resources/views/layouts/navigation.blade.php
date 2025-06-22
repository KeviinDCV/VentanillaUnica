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
                    <img src="{{ asset('images/LogoHospital.jpg') }}"
                         alt="Logo Hospital"
                         class="h-8 w-8 object-contain flex-shrink-0 sidebar-logo-img">
                    <div class="hidden items-center justify-center h-8 w-8 bg-uniradical-blue rounded sidebar-logo-fallback">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
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
                <div :class="{ 'collapsed': !sidebarOpen && window.innerWidth >= 1024 }" class="sidebar-user">
                    <div class="w-8 h-8 bg-uniradical-blue rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="sidebar-user-info">
                        <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-uniradical-blue">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </div>
            @endauth
        </div>
    </aside>

    <!-- Script para aplicar estado inicial inmediatamente -->
    <script nonce="{{ session('csp_nonce', 'default-nonce') }}">
        (function() {
            try {
                var sidebar = document.getElementById('main-sidebar');
                if (!sidebar) return;

                // Solo desactivar transiciones temporalmente para evitar animaciones
                var allElements = sidebar.querySelectorAll('*');
                allElements.forEach(function(el) {
                    el.style.transition = 'none';
                    el.style.animation = 'none';
                });

                // Programar restauración de transiciones después de que Alpine.js se inicialice
                setTimeout(function() {
                    sidebar.classList.remove('sidebar-no-animations');

                    allElements.forEach(function(el) {
                        el.style.transition = '';
                        el.style.animation = '';
                    });
                }, 200);

            } catch(e) {
                // Silenciar errores
            }
        })();
    </script>

    <!-- Botón hamburguesa para móviles -->
    <button @click="toggleMobile()"
            class="fixed top-4 left-4 z-50 lg:hidden bg-white border border-gray-200 rounded-lg p-2 shadow-md">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
</div>
