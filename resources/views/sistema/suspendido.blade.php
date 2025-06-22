<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema Suspendido - {{ config('app.name', 'UniRadic') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo y Título -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Sistema Suspendido
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    El sistema UniRadic está temporalmente fuera de servicio
                </p>
            </div>

            <!-- Información de la Suspensión -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="space-y-4">
                    <!-- Estado -->
                    <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Sistema Fuera de Servicio
                                </h3>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-red-800">
                            SUSPENDIDO
                        </div>
                    </div>

                    <!-- Tiempo Restante -->
                    @if($estado['tiempo_restante'] && $estado['tiempo_restante'] > 0)
                    <div class="text-center">
                        <div class="text-sm text-gray-500 mb-2">Tiempo restante:</div>
                        <div id="countdown" class="text-2xl font-bold text-gray-900">
                            {{ $estado['tiempo_restante'] }} minutos
                        </div>
                        @if($estado['tiempo_reactivacion'])
                        <div class="text-sm text-gray-500 mt-2">
                            Reactivación automática: {{ $estado['tiempo_reactivacion'] }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Información Adicional -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="text-sm text-gray-600 space-y-2">
                            <p>
                                <strong>Motivo:</strong> Mantenimiento programado del sistema
                            </p>
                            <p>
                                <strong>Estado:</strong> El sistema se encuentra temporalmente suspendido
                            </p>
                            @if($estado['requiere_password'])
                            <p>
                                <strong>Reactivación:</strong> Se requiere contraseña de administrador
                            </p>
                            @else
                            <p>
                                <strong>Reactivación:</strong> Automática al finalizar el tiempo
                            </p>
                            @endif
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        @if($estado['requiere_password'] || $estado['tiempo_restante'] <= 0)
                        <a href="{{ route('sistema.reactivar') }}" 
                           class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue transition duration-200">
                            Reactivar Sistema
                        </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="text-center">
                <div class="text-sm text-gray-500">
                    <p>¿Necesitas ayuda?</p>
                    <p class="mt-1">
                        Contacta al administrador del sistema:<br>
                        <a href="mailto:admin@hospitaluniversitario.edu.co" class="text-uniradical-blue hover:text-opacity-80">
                            admin@hospitaluniversitario.edu.co
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ session('csp_nonce', 'default-nonce') }}">
        // Actualizar countdown cada minuto
        @if($estado['tiempo_restante'] && $estado['tiempo_restante'] > 0)
        let tiempoRestante = {{ $estado['tiempo_restante'] }};
        
        function actualizarCountdown() {
            const countdownElement = document.getElementById('countdown');
            
            if (tiempoRestante <= 0) {
                // Recargar la página para verificar si el sistema se reactivó
                window.location.reload();
                return;
            }
            
            const horas = Math.floor(tiempoRestante / 60);
            const minutos = tiempoRestante % 60;
            
            if (horas > 0) {
                countdownElement.textContent = `${horas}h ${minutos}m`;
            } else {
                countdownElement.textContent = `${minutos} minutos`;
            }
            
            tiempoRestante--;
        }
        
        // Actualizar inmediatamente
        actualizarCountdown();
        
        // Actualizar cada minuto
        setInterval(actualizarCountdown, 60000);
        
        // Verificar estado del sistema cada 2 minutos
        setInterval(function() {
            fetch('{{ route('sistema.estado') }}')
                .then(response => response.json())
                .then(data => {
                    if (!data.suspendido) {
                        window.location.href = '{{ route('dashboard') }}';
                    }
                })
                .catch(error => {
                    console.log('Error verificando estado del sistema:', error);
                });
        }, 120000);
        @endif
    </script>
</body>
</html>
