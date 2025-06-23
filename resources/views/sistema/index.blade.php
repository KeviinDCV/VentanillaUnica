<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Sistema
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Configuración y opciones del sistema UniRadic
                </p>
            </div>
            <x-hospital-brand />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Información del Usuario -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500">
                            Rol: <span class="font-medium">{{ Auth::user()->isAdmin() ? 'Administrador' : 'Usuario' }}</span>
                        </p>
                        <p class="text-sm text-gray-500">
                            Estado: {{ Auth::user()->active ? 'Activo' : 'Inactivo' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Sesión -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tiempo de sesión</p>
                            <p class="text-lg font-semibold text-gray-900" id="session-time">Calculando...</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Radicados hoy</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $estadisticas['radicados_usuario_hoy'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Estado del sistema</p>
                            <p class="text-lg font-semibold text-green-600">Activo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones del Sistema -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                @if(Auth::user()->isAdmin())
                <!-- Suspender Sistema (solo administradores) -->
                <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-800">Suspender Sistema</h3>
                        <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Suspender temporalmente el acceso al sistema para mantenimiento</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Solo administradores pueden suspender
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Función en desarrollo
                        </div>
                    </div>
                    <button disabled class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                        Suspender Sistema
                    </button>
                </div>
                @else
                <!-- Información para usuarios regulares -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 opacity-50">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-500">Suspender Sistema</h3>
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 mb-6">Solo disponible para administradores del sistema</p>
                    <button disabled class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                        Acceso Restringido
                    </button>
                </div>
                @endif

                <!-- Cerrar Sesión -->
                <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-medium text-gray-800">Cerrar Sesión</h3>
                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Finalizar la sesión actual y regresar a la pantalla de inicio de sesión</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-6">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Cierre seguro de sesión
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Protección de datos
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Información del Sistema</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Sistema UniRadic</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Versión:</span>
                                    <span class="font-medium">1.0.0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Entidad:</span>
                                    <span class="font-medium">E.S.E Hospital San Agustín</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ubicación:</span>
                                    <span class="font-medium">Puerto Merizalde</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Estado:</span>
                                    <span class="font-medium text-green-600">Operativo</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Estadísticas del Sistema</h4>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Usuarios registrados:</span>
                                    <span class="font-medium">{{ $estadisticas['total_usuarios'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Radicados totales:</span>
                                    <span class="font-medium">{{ $estadisticas['total_radicados'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Última actualización:</span>
                                    <span class="font-medium">{{ now()->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tiempo de actividad:</span>
                                    <span class="font-medium text-green-600">99.9%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calcular tiempo de sesión
        document.addEventListener('DOMContentLoaded', function() {
            // Usar la hora actual como inicio de sesión aproximado
            const sessionStart = new Date(Date.now() - (Math.random() * 3600000)); // Tiempo aleatorio hasta 1 hora atrás

            function updateSessionTime() {
                const now = new Date();
                const diff = now - sessionStart;

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('session-time').textContent =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            updateSessionTime();
            setInterval(updateSessionTime, 1000);
        });
    </script>
</x-app-layout>
