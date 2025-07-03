<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-light text-gray-800">
                    Panel Principal UniRadic
                </h1>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Bienvenido, {{ $user->name }} - {{ ucfirst($user->role) }} | {{ \App\Helpers\DateHelper::formatForDashboard() }}
                </p>
            </div>
            <x-hospital-brand />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container-minimal">
            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Radicados -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Radicados</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasGenerales['total_radicados']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Radicados Hoy -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Radicados Hoy</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasGenerales['radicados_hoy']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Radicados del Mes -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Este Mes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasGenerales['radicados_mes']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pendientes -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pendientes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticasGenerales['pendientes']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Personales y por Tipo -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Mis Estadísticas -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Estadísticas del Sistema</h3>
                        <p class="text-sm text-gray-600 mt-1">Resumen general de radicados en el sistema</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total radicados</span>
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($estadisticasPersonales['mis_radicados']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Radicados este mes</span>
                                <span class="text-lg font-semibold text-uniradical-blue">{{ number_format($estadisticasPersonales['mis_radicados_mes']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pendientes</span>
                                <span class="text-lg font-semibold text-yellow-600">{{ number_format($estadisticasPersonales['mis_pendientes']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas por Tipo -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Radicados por Tipo</h3>
                        <p class="text-sm text-gray-600 mt-1">Distribución de documentos en el sistema</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600">Entrada</span>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($estadisticasTipo['entrada']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600">Interno</span>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($estadisticasTipo['interno']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-sm text-gray-600">Salida</span>
                                </div>
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($estadisticasTipo['salida']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Actividad Reciente -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Mis Radicados Recientes -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Radicados Recientes</h3>
                        <p class="text-sm text-gray-600 mt-1">Últimos documentos radicados en el sistema</p>
                    </div>
                    <div class="p-6">
                        @if($misRadicadosRecientes->count() > 0)
                            <div class="space-y-4">
                                @foreach($misRadicadosRecientes as $radicado)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($radicado->tipo === 'entrada') bg-blue-100 text-blue-800
                                                @elseif($radicado->tipo === 'interno') bg-green-100 text-green-800
                                                @else bg-purple-100 text-purple-800 @endif">
                                                {{ ucfirst($radicado->tipo) }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">{{ $radicado->numero_radicado }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ $radicado->remitente ? $radicado->remitente->nombre : 'Sin remitente' }} →
                                            {{ $radicado->dependenciaDestino ? $radicado->dependenciaDestino->nombre : 'Sin destino' }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $radicado->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                                <a href="{{ route('radicacion.index') }}" class="text-sm text-uniradical-blue hover:text-opacity-80">
                                    Ver todos mis radicados →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500">No has radicado documentos aún</p>
                                <a href="{{ route('radicacion.index') }}" class="text-sm text-uniradical-blue hover:text-opacity-80 mt-2 inline-block">
                                    Crear tu primer radicado →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actividad del Sistema -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Actividad del Sistema</h3>
                        <p class="text-sm text-gray-600 mt-1">Resumen de actividad de hoy</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Actividad de Hoy -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Actividad de Hoy</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Radicados creados</span>
                                        <span class="text-sm font-semibold text-green-600">{{ number_format($actividadHoy['radicados_creados']) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Radicados respondidos</span>
                                        <span class="text-sm font-semibold text-blue-600">{{ number_format($actividadHoy['radicados_respondidos']) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Usuarios activos</span>
                                        <span class="text-sm font-semibold text-purple-600">{{ number_format($actividadHoy['usuarios_activos']) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Últimos Radicados del Sistema -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Últimos Radicados del Sistema</h4>
                                <div class="space-y-2">
                                    @foreach($radicadosRecientes->take(3) as $radicado)
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-2 h-2 bg-uniradical-blue rounded-full"></span>
                                            <span class="font-medium">{{ $radicado->numero_radicado }}</span>
                                        </div>
                                        <span class="text-gray-500">{{ $radicado->created_at->diffForHumans() }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('radicacion.index') }}" class="text-xs text-uniradical-blue hover:text-opacity-80">
                                        Ver todos →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Información del Sistema</h3>
                    <p class="text-sm text-gray-600 mt-1">Estado y configuración actual</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-semibold text-green-600 mb-1">●</div>
                            <p class="text-sm font-medium text-gray-900">Sistema Activo</p>
                            <p class="text-xs text-gray-500">Funcionando correctamente</p>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900 mb-1" data-colombia-time>{{ \App\Helpers\DateHelper::currentTimeForDashboard() }}</div>
                            <p class="text-sm font-medium text-gray-900">Hora Actual (Colombia)</p>
                            <p class="text-xs text-gray-500" data-colombia-date>{{ \App\Helpers\DateHelper::formatDate() }}</p>
                            <p class="text-xs text-gray-400" data-timezone-info>Hora de Colombia (UTC-5)</p>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900 mb-1">v1.0.0</div>
                            <p class="text-sm font-medium text-gray-900">Versión UniRadic</p>
                            <p class="text-xs text-gray-500">Sistema actualizado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
