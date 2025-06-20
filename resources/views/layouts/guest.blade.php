<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Sistema de Ventanilla Única</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpg" href="{{ asset('images/LogoHospital.jpg') }}">
        <link rel="shortcut icon" type="image/jpg" href="{{ asset('images/LogoHospital.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        <div class="min-h-screen flex flex-col justify-center items-center bg-white">
            <!-- Logo/Header minimalista -->
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-light text-uniradical-blue mb-2">UniRadic</h1>
                <p class="text-gray-600 text-sm font-light">Sistema de Gestión Documental</p>
            </div>

            <!-- Contenedor del formulario minimalista -->
            <div class="w-full max-w-md px-8">
                <div class="bg-white border border-gray-100 p-8">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer minimalista -->
            <div class="mt-12 text-center">
                <p class="text-xs text-gray-400 font-light">&copy; {{ date('Y') }} UniRadic - Sistema de Gestión Documental</p>
            </div>
        </div>
    </body>
</html>
