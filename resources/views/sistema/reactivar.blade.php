<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reactivar Sistema - {{ config('app.name', 'UniRadic') }}</title>

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
                <div class="mx-auto h-20 w-20 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Reactivar Sistema
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Ingresa la contraseña para reactivar UniRadic
                </p>
            </div>

            <!-- Formulario de Reactivación -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Error en la reactivación
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('sistema.reactivar.procesar') }}">
                    @csrf
                    
                    <!-- Estado Actual -->
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Sistema Suspendido
                                </h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    @if($estado['tiempo_restante'] && $estado['tiempo_restante'] > 0)
                                        Tiempo restante: {{ $estado['tiempo_restante'] }} minutos
                                    @else
                                        El tiempo de suspensión ha expirado
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($estado['requiere_password'])
                    <!-- Campo de Contraseña -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña de Administrador <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-uniradical-blue focus:border-uniradical-blue"
                                   placeholder="Ingresa la contraseña de reactivación">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Se requiere la contraseña configurada durante la suspensión
                        </p>
                    </div>
                    @else
                    <!-- Sin contraseña requerida -->
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    Reactivación Disponible
                                </h3>
                                <div class="mt-1 text-sm text-green-700">
                                    No se requiere contraseña para reactivar el sistema
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Información Adicional -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Información de Reactivación</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p>• Al reactivar el sistema, todos los usuarios podrán acceder nuevamente</p>
                            <p>• Se registrará la reactivación en los logs del sistema</p>
                            <p>• La sesión actual se mantendrá activa</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Reactivar Sistema
                        </button>
                        
                        <a href="{{ route('sistema.suspendido') }}" 
                           class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                            Volver
                        </a>
                    </div>
                </form>
            </div>

            <!-- Información de Contacto -->
            <div class="text-center">
                <div class="text-sm text-gray-500">
                    <p>¿Problemas con la reactivación?</p>
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

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        // Auto-focus en el campo de contraseña si existe
        @if($estado['requiere_password'])
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('password').focus();
        });
        @endif
    </script>
</body>
</html>
