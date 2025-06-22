<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cerrar Sesión - {{ config('app.name', 'UniRadic') }}</title>

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
                <div class="mx-auto h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-uniradical-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Cerrar Sesión
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    ¿Estás seguro de que deseas cerrar tu sesión en UniRadic?
                </p>
            </div>

            <!-- Información de la Sesión -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                @if(Auth::check())
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Sesión Activa
                            </h3>
                            <div class="mt-1 text-sm text-blue-700">
                                <p><strong>Usuario:</strong> {{ Auth::user()->name }}</p>
                                <p><strong>Rol:</strong> {{ ucfirst(Auth::user()->role) }}</p>
                                <p><strong>Último acceso:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Formulario de Logout -->
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Información de Seguridad -->
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">Información de Seguridad</h4>
                            <div class="text-sm text-yellow-700 space-y-1">
                                <p>• Al cerrar sesión, todos los datos no guardados se perderán</p>
                                <p>• Deberás iniciar sesión nuevamente para acceder al sistema</p>
                                <p>• Se recomienda cerrar sesión al finalizar tu trabajo</p>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex space-x-4">
                            <button type="submit" 
                                    class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Cerrar Sesión
                            </button>
                            
                            <a href="{{ route('dashboard') }}" 
                               class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Información de Contacto -->
            <div class="text-center">
                <div class="text-sm text-gray-500">
                    <p>¿Problemas con tu sesión?</p>
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
        // Manejar el envío del formulario
        document.getElementById('logoutForm').addEventListener('submit', function(e) {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Cerrando sesión...
            `;
        });

        // Auto-refresh del token CSRF cada 10 minutos para evitar expiración
        setInterval(function() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                    document.querySelector('input[name="_token"]').value = data.token;
                })
                .catch(error => {
                    console.log('Error refreshing CSRF token:', error);
                });
        }, 600000); // 10 minutos
    </script>
</body>
</html>
