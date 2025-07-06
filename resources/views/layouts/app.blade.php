<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="session-timeout" content="{{ config('session.lifetime') }}">
        @auth
        <meta name="user-name" content="{{ auth()->user()->name }}">
        <meta name="user-id" content="{{ auth()->user()->id }}">
        @endauth

        <title>{{ config('app.name', 'Laravel') }} - Sistema de Ventanilla Única</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpg" href="{{ asset('images/LogoHospital.jpg') }}">
        <link rel="shortcut icon" type="image/jpg" href="{{ asset('images/LogoHospital.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Fallback CSS para fuentes locales en caso de error de conexión -->
        <style>
            @font-face {
                font-family: 'Figtree-Fallback';
                src: local('Inter'), local('Segoe UI'), local('Roboto'), local('Helvetica Neue'), local('Arial');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }

            body {
                font-family: 'Figtree', 'Figtree-Fallback', 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', sans-serif;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/responsive-improvements.css', 'resources/js/app.js', 'resources/js/radicacion.js', 'resources/js/session-manager.js', 'resources/js/colombia-time.js', 'resources/js/file-upload.js', 'resources/js/modal-file-upload.js'])

        <!-- Script para detectar errores de fuentes y aplicar fallback -->
        <script>
            // Detectar errores de carga de fuentes y aplicar fallback
            document.addEventListener('DOMContentLoaded', function() {
                // Verificar si la fuente Figtree se cargó correctamente
                const testElement = document.createElement('div');
                testElement.style.fontFamily = 'Figtree, monospace';
                testElement.style.position = 'absolute';
                testElement.style.visibility = 'hidden';
                testElement.style.fontSize = '72px';
                testElement.innerHTML = 'mmmmmmmmmmlli';
                document.body.appendChild(testElement);

                const figtreeWidth = testElement.offsetWidth;

                testElement.style.fontFamily = 'monospace';
                const monospaceWidth = testElement.offsetWidth;

                document.body.removeChild(testElement);

                // Si las anchuras son iguales, Figtree no se cargó
                if (figtreeWidth === monospaceWidth) {
                    console.warn('Fuente Figtree no disponible, usando fuentes del sistema');
                    document.documentElement.style.setProperty('--font-family-fallback', 'Inter, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif');
                    document.body.style.fontFamily = 'Inter, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
                }
            });

            // Manejar errores de red para fuentes
            window.addEventListener('error', function(e) {
                if (e.target && e.target.href && e.target.href.includes('fonts.bunny.net')) {
                    console.warn('Error cargando fuente externa:', e.target.href);
                    document.body.style.fontFamily = 'Inter, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
                }
            }, true);
        </script>

        <!-- Script para evitar parpadeo de sidebar -->
        <script>
            // Ejecutar inmediatamente para evitar parpadeo
            (function() {
                try {
                    var stored = localStorage.getItem('sidebarOpen');
                    var shouldBeCollapsed = stored !== null ? stored !== 'true' : false;
                    var isDesktop = window.innerWidth >= 1024;

                    if (shouldBeCollapsed && isDesktop) {
                        document.documentElement.classList.add('sidebar-collapsed-initial');
                    }

                    // Aplicar estilos directamente para evitar cualquier animación
                    document.documentElement.style.setProperty('--sidebar-loading', '1');
                } catch (e) {
                    // Silenciar errores de localStorage en caso de que no esté disponible
                }
            })();
        </script>

        <!-- Estilos inline críticos para evitar animaciones -->
        <style>
            :root[style*="--sidebar-loading"] * {
                transition: none !important;
                animation: none !important;
                transform: none !important;
                box-shadow: none !important;
                border: none !important;
                border-left: none !important;
                border-right: none !important;
                border-top: none !important;
                border-bottom: none !important;
                border-color: transparent !important;
                border-width: 0 !important;
                border-style: none !important;
                outline: none !important;
                outline-color: transparent !important;
                outline-width: 0 !important;
                outline-style: none !important;
                filter: none !important;
            }

            :root[style*="--sidebar-loading"] .sidebar {
                border: none !important;
                border-right: none !important;
                box-shadow: none !important;
                outline: none !important;
            }

            :root[style*="--sidebar-loading"] *::before,
            :root[style*="--sidebar-loading"] *::after {
                display: none !important;
                content: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white loading-initial" id="app-body">
        <div class="min-h-screen bg-white">
            @include('layouts.navigation')

            <!-- Contenido principal con margen para la sidebar -->
            <div x-data="{
                    sidebarCollapsed: false,
                    init() {
                        this.updateLayout();

                        // Escuchar cambios en localStorage
                        window.addEventListener('storage', () => {
                            this.updateLayout();
                        });

                        // Escuchar evento personalizado de toggle
                        document.addEventListener('sidebarToggled', () => {
                            this.updateLayout();
                        });

                        // Escuchar cambios de tamaño de ventana
                        window.addEventListener('resize', () => {
                            this.updateLayout();
                        });

                        // Watcher para cambios en sidebarCollapsed
                        this.$watch('sidebarCollapsed', (value) => {
                            // Aplicar clase inmediatamente
                            if (value) {
                                this.$el.classList.add('sidebar-collapsed');
                            } else {
                                this.$el.classList.remove('sidebar-collapsed');
                            }
                        });

                        // Limpiar clases de carga
                        document.documentElement.classList.remove('sidebar-collapsed-initial');
                        document.body.classList.remove('loading-initial');
                        document.documentElement.style.removeProperty('--sidebar-loading');
                    },
                    updateLayout() {
                        const sidebarOpen = localStorage.getItem('sidebarOpen') === 'true' || (localStorage.getItem('sidebarOpen') === null && window.innerWidth >= 1024);
                        const isDesktop = window.innerWidth >= 1024;
                        const newState = !sidebarOpen && isDesktop;

                        if (this.sidebarCollapsed !== newState) {
                            this.sidebarCollapsed = newState;

                            // Forzar re-render inmediato
                            this.$nextTick(() => {
                                // Disparar evento para otros componentes que puedan necesitar reaccionar
                                window.dispatchEvent(new CustomEvent('layout-updated', {
                                    detail: { sidebarCollapsed: this.sidebarCollapsed }
                                }));
                            });
                        }
                    }
                 }"
                 :class="{ 'sidebar-collapsed': sidebarCollapsed }"
                 class="main-layout"
                 @sidebar-state-changed.window="updateLayout()"
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-gray-100">
                        <div class="container-minimal py-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="bg-white">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Scripts adicionales -->
        @stack('scripts')

        <!-- Inicialización de Alpine.js -->
        <script>
            // Solo inicializar una vez para evitar conflictos con SPA
            if (!window.alpineInitialized) {
                window.alpineInitialized = true;

                document.addEventListener('DOMContentLoaded', function() {
                    // Inicializar Alpine.js data para el body
                    if (window.Alpine) {
                        const appBody = document.getElementById('app-body');
                        if (appBody) {
                            appBody.setAttribute('x-data', '{ sidebarOpen: localStorage.getItem(\'sidebarOpen\') === \'true\' || false }');
                            window.Alpine.initTree(appBody);
                        }
                    }

                    // Remover clases de carga después de un pequeño delay para asegurar que todo esté listo
                    setTimeout(function() {
                        document.documentElement.classList.remove('sidebar-collapsed-initial');
                        document.body.classList.remove('loading-initial');
                        document.documentElement.style.removeProperty('--sidebar-loading');
                    }, 100);
                });
            }
        </script>
    </body>
</html>
