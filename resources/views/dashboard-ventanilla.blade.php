<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Panel de Ventanilla
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Bienvenido, {{ $user->name }}
                </p>
            </div>
            <div class="text-sm text-gray-500">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas Personales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Mis Radicados</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticasPersonales['mis_radicados'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Este Mes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticasPersonales['mis_radicados_mes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pendientes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticasPersonales['mis_pendientes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Hoy</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $actividadHoy['radicados_creados'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Accesos Rápidos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($accesosRapidos as $acceso)
                    <a href="{{ $acceso['url'] }}" class="group">
                        <div class="quick-access-card group-hover:border-{{ $acceso['color'] }}-300">
                            <div class="card-content">
                                <div class="card-icon bg-{{ $acceso['color'] }}-100 group-hover:bg-{{ $acceso['color'] }}-200">
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $acceso['icono'] }}"/>
                                    </svg>
                                </div>
                                <div class="card-text">
                                    <h4 class="card-title group-hover:text-{{ $acceso['color'] }}-700">{{ $acceso['titulo'] }}</h4>
                                    <p class="card-description">{{ $acceso['descripcion'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Mis Radicados Recientes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Mis Radicados Recientes</h3>
                        <p class="text-sm text-gray-600 mt-1">Últimos radicados que has creado</p>
                    </div>
                    <div class="p-6">
                        @if($misRadicadosRecientes->count() > 0)
                            <div class="space-y-4">
                                @foreach($misRadicadosRecientes as $radicado)
                                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $radicado->numero_radicado }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $radicado->asunto ?? 'Sin asunto' }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $radicado->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($radicado->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif($radicado->estado === 'en_proceso') bg-blue-100 text-blue-800
                                            @elseif($radicado->estado === 'respondido') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $radicado->estado)) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('radicacion.index') }}"
                                   class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                    Ver todos mis radicados
                                </a>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay radicados</h3>
                                <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer radicado.</p>
                                <div class="mt-6">
                                    <a href="{{ route('radicacion.entrada.index') }}"
                                       class="create-button inline-flex items-center shadow-sm text-sm">
                                        Crear Radicado
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actividad del Día -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Actividad del Día</h3>
                        <p class="text-sm text-gray-600 mt-1">Resumen de actividad de hoy</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600">Radicados creados</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $actividadHoy['radicados_creados'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600">Radicados respondidos</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $actividadHoy['radicados_respondidos'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600">Usuarios activos</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $actividadHoy['usuarios_activos'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
