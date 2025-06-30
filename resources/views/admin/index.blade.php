<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('gestion.index') }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </a>
                <div>
                    <h2 class="font-light text-xl text-gray-800 leading-tight">
                        Panel de Administración
                    </h2>
                    <p class="text-sm text-gray-600 font-light mt-1">
                        Dashboard administrativo del sistema UniRadic
                    </p>
                </div>
            </div>
            <x-hospital-brand />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">

            <!-- Estadísticas por Tipo y Estado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Estadísticas por Tipo -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Radicados por Tipo</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Entrada</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasTipo['entrada']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Interno</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasTipo['interno']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Salida</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasTipo['salida']) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas por Estado -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Radicados por Estado</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Pendiente</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasEstado['pendiente']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">En Proceso</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasEstado['en_proceso']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Respondido</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasEstado['respondido']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                <span class="text-sm text-gray-700">Archivado</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($estadisticasEstado['archivado']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión Administrativa -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                <!-- 1. Usuarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Usuarios</h3>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($estadisticas['total_usuarios']) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Usuarios registrados</p>
                    <a href="{{ route('admin.usuarios') }}"
                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                        Gestionar usuarios
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 2. Dependencias -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Dependencias</h3>
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($estadisticas['total_dependencias']) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Dependencias activas</p>
                    <a href="{{ route('admin.dependencias') }}"
                       class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                        Gestionar dependencias
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>


                <!-- 5. Comunicaciones -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Comunicaciones</h3>
                        <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($estadisticas['total_comunicaciones']) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Tipos de comunicación</p>
                    <a href="#"
                       class="inline-flex items-center text-sm font-medium text-cyan-600 hover:text-cyan-500">
                        Gestionar comunicaciones
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 6. Tipos de Solicitud -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Tipos de Solicitud</h3>
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($estadisticas['total_tipos_solicitud']) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Tipos parametrizados</p>
                    <a href="{{ route('admin.tipos-solicitud.index') }}"
                       class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Gestionar tipos
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>



                <!-- 4. Departamentos -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Departamentos</h3>
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($estadisticas['total_departamentos']) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Departamentos activos</p>
                    <a href="{{ route('admin.departamentos.index') }}"
                       class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-500">
                        Gestionar departamentos
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 5. Ciudad -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Ciudad</h3>
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($estadisticas['total_ciudades']) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Ciudades registradas</p>
                    <a href="{{ route('admin.ciudades.index') }}"
                       class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-500">
                        Gestionar ciudades
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>

                <!-- 6. Cliente y proveedores -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Cliente y Proveedores</h3>
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-2">0</p>
                    <p class="text-sm text-gray-500 mb-4">Entidades externas</p>
                    <a href="#"
                       class="inline-flex items-center text-sm font-medium text-teal-600 hover:text-teal-500">
                        Gestionar entidades
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Actividad Reciente</h3>
                        <a href="{{ route('admin.logs') }}"
                           class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            Ver todos los logs
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($radicadosRecientes->count() > 0)
                        <div class="space-y-4">
                            @foreach($radicadosRecientes->take(5) as $radicado)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $radicado->numero_radicado }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $radicado->remitente->nombre_completo }} → {{ $radicado->dependenciaDestino->nombre }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-sm text-gray-500">
                                    {{ $radicado->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay actividad reciente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
