<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso No Autorizado - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col justify-center items-center bg-white">
        <!-- Logo/Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-light text-uniradical-blue mb-2">UniRadic</h1>
            <p class="text-gray-600 text-sm font-light">Sistema de Gestión Documental</p>
        </div>

        <!-- Error Content -->
        <div class="w-full max-w-md px-8">
            <div class="bg-white border border-gray-100 p-8 text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-uniradical-blue mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <h2 class="text-2xl font-light text-gray-800 mb-2">Acceso Restringido</h2>
                    <p class="text-gray-600 text-sm">
                        Necesitas iniciar sesión para acceder a esta página.
                    </p>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('login') }}" 
                       class="w-full bg-uniradical-blue text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 inline-block text-center">
                        Iniciar Sesión
                    </a>
                    
                    <a href="{{ url('/') }}" 
                       class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition-colors duration-200 inline-block text-center">
                        Ir al Inicio
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} Hospital Universitario - Sistema UniRadic</p>
        </div>
    </div>
</body>
</html>
