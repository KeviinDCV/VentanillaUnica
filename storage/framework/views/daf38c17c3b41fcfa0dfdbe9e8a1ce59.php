<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="session-timeout" content="<?php echo e(config('session.lifetime')); ?>">
        <?php if(auth()->guard()->check()): ?>
        <meta name="user-name" content="<?php echo e(auth()->user()->name); ?>">
        <meta name="user-id" content="<?php echo e(auth()->user()->id); ?>">
        <?php endif; ?>

        <title><?php echo e(config('app.name', 'Laravel')); ?> - Sistema de Ventanilla Única</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpg" href="<?php echo e(asset('images/LogoHospital.jpg')); ?>">
        <link rel="shortcut icon" type="image/jpg" href="<?php echo e(asset('images/LogoHospital.jpg')); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js', 'resources/js/radicacion.js', 'resources/js/session-manager.js', 'resources/js/colombia-time.js', 'resources/js/file-upload.js', 'resources/js/modal-file-upload.js']); ?>

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
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                <?php if(isset($header)): ?>
                    <header class="bg-white border-b border-gray-100">
                        <div class="container-minimal py-8">
                            <?php echo e($header); ?>

                        </div>
                    </header>
                <?php endif; ?>

                <!-- Page Content -->
                <main class="bg-white">
                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>

        <!-- Scripts adicionales -->
        <?php echo $__env->yieldPushContent('scripts'); ?>

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
<?php /**PATH E:\Hospital\Ventanilla\UniRadic\resources\views/layouts/app.blade.php ENDPATH**/ ?>