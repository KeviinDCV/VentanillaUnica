<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Configuración del Sistema
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Configuración y información del sistema UniRadic
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Volver al Dashboard
                </a>
                <button class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                    Guardar Configuración
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Información del Sistema -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Información del Sistema</h3>
                    <p class="text-sm text-gray-600 mt-1">Detalles técnicos y versión del sistema</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Versión UniRadic:</span>
                                <span class="text-sm text-gray-900">{{ $infoSistema['version'] }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Versión Laravel:</span>
                                <span class="text-sm text-gray-900">{{ $infoSistema['laravel_version'] }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Versión PHP:</span>
                                <span class="text-sm text-gray-900">{{ $infoSistema['php_version'] }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Base de Datos:</span>
                                <span class="text-sm text-gray-900">{{ ucfirst($infoSistema['base_datos']) }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Zona Horaria:</span>
                                <span class="text-sm text-gray-900">{{ $infoSistema['timezone'] }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Entorno:</span>
                                <span class="text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $infoSistema['environment'] === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($infoSistema['environment']) }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Modo Debug:</span>
                                <span class="text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $infoSistema['debug_mode'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $infoSistema['debug_mode'] ? 'Activado' : 'Desactivado' }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">Fecha Actual:</span>
                                <span class="text-sm text-gray-900">{{ now()->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Almacenamiento -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Estadísticas de Almacenamiento</h3>
                    <p class="text-sm text-gray-600 mt-1">Información sobre documentos y espacio utilizado</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Total Documentos -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-blue-800 mb-1">Total Documentos</h4>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($estadisticasAlmacenamiento['total_documentos']) }}</p>
                        </div>

                        <!-- Tamaño Total -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-green-800 mb-1">Tamaño Total</h4>
                            <p class="text-2xl font-bold text-green-900">
                                @php
                                    $tamaño = $estadisticasAlmacenamiento['tamaño_total'];
                                    if ($tamaño >= 1073741824) {
                                        $tamaño_formateado = number_format($tamaño / 1073741824, 2) . ' GB';
                                    } elseif ($tamaño >= 1048576) {
                                        $tamaño_formateado = number_format($tamaño / 1048576, 2) . ' MB';
                                    } elseif ($tamaño >= 1024) {
                                        $tamaño_formateado = number_format($tamaño / 1024, 2) . ' KB';
                                    } else {
                                        $tamaño_formateado = $tamaño . ' B';
                                    }
                                @endphp
                                {{ $tamaño_formateado }}
                            </p>
                        </div>

                        <!-- Documentos por Tipo -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-yellow-800 mb-3 text-center">Por Tipo</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-yellow-700">Entrada:</span>
                                    <span class="text-sm font-medium text-yellow-900">{{ number_format($estadisticasAlmacenamiento['documentos_entrada']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-yellow-700">Interno:</span>
                                    <span class="text-sm font-medium text-yellow-900">{{ number_format($estadisticasAlmacenamiento['documentos_interno']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-yellow-700">Salida:</span>
                                    <span class="text-sm font-medium text-yellow-900">{{ number_format($estadisticasAlmacenamiento['documentos_salida']) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Espacio Disponible -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-purple-800 mb-1">Estado</h4>
                            <p class="text-lg font-bold text-purple-900">Óptimo</p>
                            <p class="text-xs text-purple-600 mt-1">Sistema funcionando correctamente</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración General -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Configuración General</h3>
                    <p class="text-sm text-gray-600 mt-1">Parámetros generales del sistema</p>
                </div>
                <div class="p-6">
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre del Hospital -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Hospital</label>
                                <input type="text" value="Hospital Universitario San Rafael" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <!-- NIT -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIT</label>
                                <input type="text" value="900.123.456-1" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <!-- Dirección -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                <input type="text" value="Calle 123 #45-67, Bogotá D.C." 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="text" value="(601) 234-5678" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Institucional</label>
                                <input type="email" value="info@hospitaluniversitario.edu.co" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>

                            <!-- Sitio Web -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sitio Web</label>
                                <input type="url" value="https://www.hospitaluniversitario.edu.co" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            </div>
                        </div>

                        <!-- Configuración de Radicación -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-800 mb-4">Configuración de Radicación</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Días Límite por Defecto</label>
                                    <input type="number" value="15" min="1" max="365"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <p class="text-xs text-gray-500 mt-1">Días para respuesta automática</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tamaño Máximo de Archivo</label>
                                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="5">5 MB</option>
                                        <option value="10" selected>10 MB</option>
                                        <option value="20">20 MB</option>
                                        <option value="50">50 MB</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Formatos Permitidos</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" checked class="form-checkbox text-uniradical-blue">
                                            <span class="ml-2 text-sm text-gray-700">PDF</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" checked class="form-checkbox text-uniradical-blue">
                                            <span class="ml-2 text-sm text-gray-700">Word (DOC, DOCX)</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" checked class="form-checkbox text-uniradical-blue">
                                            <span class="ml-2 text-sm text-gray-700">Imágenes (JPG, PNG)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notificaciones -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-800 mb-4">Configuración de Notificaciones</h4>
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="form-checkbox text-uniradical-blue">
                                    <span class="ml-2 text-sm text-gray-700">Notificar por email cuando se cree un nuevo radicado</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="form-checkbox text-uniradical-blue">
                                    <span class="ml-2 text-sm text-gray-700">Alertas de documentos próximos a vencer</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox text-uniradical-blue">
                                    <span class="ml-2 text-sm text-gray-700">Reportes automáticos semanales</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" checked class="form-checkbox text-uniradical-blue">
                                    <span class="ml-2 text-sm text-gray-700">Notificaciones de sistema</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Acciones del Sistema -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Acciones del Sistema</h3>
                    <p class="text-sm text-gray-600 mt-1">Herramientas de mantenimiento y administración</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Backup -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Respaldo de Datos</h4>
                            <p class="text-sm text-gray-600 mb-4">Crear respaldo completo del sistema</p>
                            <button class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                                Crear Respaldo
                            </button>
                        </div>

                        <!-- Limpiar Cache -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Limpiar Caché</h4>
                            <p class="text-sm text-gray-600 mb-4">Limpiar caché del sistema para mejorar rendimiento</p>
                            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                                Limpiar Caché
                            </button>
                        </div>

                        <!-- Suspender Sistema -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Suspender Sistema</h4>
                            <p class="text-sm text-gray-600 mb-4">Suspensión temporal con timeout y contraseña</p>
                            <a href="{{ route('admin.suspender') }}"
                               class="w-full inline-flex justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                                Suspender Sistema
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
