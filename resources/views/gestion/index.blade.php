<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Herramientas de administración y reportes del sistema
                </p>
            </div>
            <x-hospital-brand />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Unidades Administrativas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['unidades_administrativas'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0a2 2 0 01-2 2h-4a2 2 0 01-2-2v0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Series Documentales</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['series'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Subseries Documentales</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['subseries'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión TRD Jerárquico -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Gestión TRD Jerárquico</h3>
                    </div>
                    <p class="text-gray-600 mb-6">
                        Sistema completo de Tablas de Retención Documental con estructura jerárquica:
                        Unidades Administrativas → Series → Subseries
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.unidades-administrativas.index') }}"
                           class="group bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 hover:border-blue-300">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <span class="text-blue-600 font-bold text-sm">1</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-800 group-hover:text-blue-600 mb-1">Unidades Administrativas</h4>
                            <p class="text-sm text-gray-600">Crear códigos de unidades administrativas</p>
                        </a>

                        <a href="{{ route('gestion.series.index') }}"
                           class="group bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 hover:border-blue-300">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <span class="text-green-600 font-bold text-sm">2</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-800 group-hover:text-green-600 mb-1">Series</h4>
                            <p class="text-sm text-gray-600">Agregar series a las unidades</p>
                        </a>

                        <a href="{{ route('admin.subseries.index') }}"
                           class="group bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 hover:border-blue-300">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                    <span class="text-purple-600 font-bold text-sm">3</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-800 group-hover:text-purple-600 mb-1">Subseries</h4>
                            <p class="text-sm text-gray-600">Crear subseries para las series</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Acciones Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Reportes -->
                <a href="{{ route('admin.reportes') }}"
                   class="group bg-white border border-gray-200 rounded-lg p-8 shadow-sm hover:shadow-md transition-all duration-200 hover:border-blue-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-800 group-hover:text-blue-600">Reportes</h3>
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Generar reportes estadísticos y análisis de radicados del sistema</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Reportes por fechas
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Estadísticas por dependencia
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Exportación a Excel/PDF
                        </div>
                    </div>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        Generar reportes
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- Administración -->
                <a href="{{ route('admin.index') }}"
                   class="group bg-white border border-gray-200 rounded-lg p-8 shadow-sm hover:shadow-md transition-all duration-200 hover:border-green-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-800 group-hover:text-green-600">Administración</h3>
                        <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Configuración y gestión de parámetros del sistema UniRadic</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Gestión de usuarios
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Configuración de dependencias
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Parámetros del sistema
                        </div>
                    </div>
                    <div class="flex items-center text-green-600 text-sm font-medium">
                        Acceder al panel
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
