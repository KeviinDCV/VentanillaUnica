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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/session-manager.js'])
    </head>
    <body class="font-sans antialiased bg-white" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || false }">
        <div class="min-h-screen bg-white">
            @include('layouts.navigation')

            <!-- Contenido principal con margen para la sidebar -->
            <div :class="{ 'sidebar-collapsed': !sidebarOpen }" class="main-layout">
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
    </body>
</html>
