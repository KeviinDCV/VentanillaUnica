<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            Panel de Ventanilla
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <div class="card-minimal">
                <div class="p-8 text-gray-900">
                    <div class="mb-8">
                        <h3 class="text-lg font-light text-gray-900 mb-2">
                            Bienvenido, {{ $user->name }}
                        </h3>
                        <p class="text-sm text-gray-600 font-light">
                            Rol: <span class="font-medium text-uniradical-blue">{{ ucfirst($user->role) }}</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Módulo de Entrada -->
                        <div class="card-minimal p-6 border-l-4 border-uniradical-blue">
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Entrada</h4>
                            <p class="text-sm text-gray-600 mb-4 font-light">Radicación de documentos externos</p>
                            <button class="w-full bg-uniradical-blue text-white py-2 px-4 font-light hover:bg-opacity-90 transition duration-200" disabled>
                                Próximamente
                            </button>
                        </div>

                        <!-- Módulo de Consultar Radicado -->
                        <div class="card-minimal p-6 border-l-4 border-gray-300">
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Consultar Radicado</h4>
                            <p class="text-sm text-gray-600 mb-4 font-light">Búsqueda de documentos radicados</p>
                            <button class="w-full bg-gray-600 text-white py-2 px-4 font-light hover:bg-opacity-90 transition duration-200" disabled>
                                Próximamente
                            </button>
                        </div>

                        <!-- Módulo Interno -->
                        <div class="card-minimal p-6 border-l-4 border-uniradical-blue">
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Interno</h4>
                            <p class="text-sm text-gray-600 mb-4 font-light">Documentos entre dependencias</p>
                            <button class="w-full bg-uniradical-blue text-white py-2 px-4 font-light hover:bg-opacity-90 transition duration-200" disabled>
                                Próximamente
                            </button>
                        </div>

                        <!-- Módulo de Salida -->
                        <div class="card-minimal p-6 border-l-4 border-gray-300">
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Salida</h4>
                            <p class="text-sm text-gray-600 mb-4 font-light">Documentos hacia entidades externas</p>
                            <button class="w-full bg-gray-600 text-white py-2 px-4 font-light hover:bg-opacity-90 transition duration-200" disabled>
                                Próximamente
                            </button>
                        </div>
                    </div>

                    <div class="mt-12 p-6 bg-gray-50 border-l-4 border-gray-300">
                        <h4 class="text-md font-medium text-gray-800 mb-4">Información de Sesión</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div>
                                <span class="text-gray-600 font-light">Sesión iniciada:</span>
                                <span class="font-medium">{{ \App\Helpers\DateHelper::currentDateTimeForDashboard() }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-light">Tiempo de sesión:</span>
                                <span class="font-medium">{{ config('session.lifetime') }} minutos</span>
                            </div>
                        </div>
                        <div class="mt-6 p-4 bg-gray-100 border-l-4 border-uniradical-blue">
                            <p class="text-sm text-gray-700 font-light">
                                <strong>Nota:</strong> Su sesión se suspenderá automáticamente después de {{ config('session.lifetime') }} minutos de inactividad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
