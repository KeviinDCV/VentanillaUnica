<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Suspender Sistema
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Suspensión temporal del sistema UniRadic
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Advertencia -->
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            ¡Atención! Acción Crítica del Sistema
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Al suspender el sistema:</p>
                            <ul class="list-disc list-inside mt-1">
                                <li>Todos los usuarios serán desconectados inmediatamente</li>
                                <li>No se podrá acceder al sistema hasta la reactivación</li>
                                <li>Los procesos en curso se interrumpirán</li>
                                <li>Solo podrá reactivarse con la contraseña configurada o al vencer el tiempo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Suspensión -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Configurar Suspensión del Sistema</h3>
                    <p class="text-sm text-gray-600 mt-1">Define los parámetros para la suspensión temporal</p>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Errores en el formulario:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.suspender.procesar') }}" id="suspenderForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Duración de la Suspensión -->
                            <div>
                                <label for="minutos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Duración de la Suspensión <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="minutos" id="minutos" 
                                           value="{{ old('minutos', 30) }}" min="1" max="1440" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">minutos</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Mínimo: 1 minuto, Máximo: 1440 minutos (24 horas)
                                </p>
                                
                                <!-- Botones de tiempo rápido -->
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <button type="button" data-minutos="15"
                                            class="btn-tiempo px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        15 min
                                    </button>
                                    <button type="button" data-minutos="30"
                                            class="btn-tiempo px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        30 min
                                    </button>
                                    <button type="button" data-minutos="60"
                                            class="btn-tiempo px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        1 hora
                                    </button>
                                    <button type="button" data-minutos="120"
                                            class="btn-tiempo px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        2 horas
                                    </button>
                                    <button type="button" data-minutos="480"
                                            class="btn-tiempo px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        8 horas
                                    </button>
                                </div>
                            </div>

                            <!-- Contraseña de Reactivación -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña de Reactivación (Opcional)
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" 
                                           value="{{ old('password') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Dejar vacío para reactivación automática">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" id="toggle-password" class="text-gray-400 hover:text-gray-600">
                                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Si se configura, será necesaria para reactivar antes del tiempo límite
                                </p>
                            </div>

                            <!-- Motivo de la Suspensión -->
                            <div class="md:col-span-2">
                                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Motivo de la Suspensión
                                </label>
                                <textarea name="motivo" id="motivo" rows="3"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                          placeholder="Describe el motivo de la suspensión (mantenimiento, actualización, etc.)">{{ old('motivo') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    Este motivo se registrará en los logs del sistema
                                </p>
                            </div>
                        </div>

                        <!-- Información de la Suspensión -->
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">Información de la Suspensión</h4>
                            <div class="text-sm text-yellow-700 space-y-1">
                                <p id="tiempo-info">• Duración: 30 minutos</p>
                                <p id="reactivacion-info">• Reactivación automática: <span id="fecha-reactivacion"></span></p>
                                <p>• Usuario que suspende: {{ auth()->user()->name }}</p>
                                <p>• Fecha de suspensión: {{ now()->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <!-- Confirmación -->
                        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="confirmar" required
                                           class="form-checkbox text-red-600 border-red-300 focus:ring-red-500">
                                </div>
                                <div class="ml-3">
                                    <label for="confirmar" class="text-sm font-medium text-red-800">
                                        Confirmo que entiendo las consecuencias de suspender el sistema
                                    </label>
                                    <p class="text-sm text-red-700 mt-1">
                                        Al marcar esta casilla, confirmo que he leído y entiendo que todos los usuarios serán desconectados inmediatamente y no podrán acceder al sistema hasta su reactivación.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-6 flex justify-between">
                            <a href="{{ route('admin.index') }}" 
                               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Cancelar
                            </a>
                            <button type="submit" id="btn-suspender"
                                    class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                Suspender Sistema
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ session('csp_nonce', 'default-nonce') }}">
        function setMinutos(minutos) {
            document.getElementById('minutos').value = minutos;
            actualizarInfo();
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        function actualizarInfo() {
            const minutos = parseInt(document.getElementById('minutos').value) || 30;
            const ahora = new Date();
            const reactivacion = new Date(ahora.getTime() + (minutos * 60000));
            
            // Actualizar información de tiempo
            let tiempoTexto = '';
            if (minutos < 60) {
                tiempoTexto = `${minutos} minutos`;
            } else {
                const horas = Math.floor(minutos / 60);
                const minutosRestantes = minutos % 60;
                tiempoTexto = `${horas} hora${horas > 1 ? 's' : ''}`;
                if (minutosRestantes > 0) {
                    tiempoTexto += ` y ${minutosRestantes} minutos`;
                }
            }
            
            document.getElementById('tiempo-info').textContent = `• Duración: ${tiempoTexto}`;
            document.getElementById('fecha-reactivacion').textContent = reactivacion.toLocaleString('es-ES');
        }

        function confirmarSuspension() {
            const minutos = document.getElementById('minutos').value;
            const motivo = document.getElementById('motivo').value || 'No especificado';
            
            return confirm(
                `¿Estás seguro de que deseas suspender el sistema por ${minutos} minutos?\n\n` +
                `Motivo: ${motivo}\n\n` +
                `Esta acción desconectará a todos los usuarios inmediatamente.`
            );
        }

        // Actualizar información al cargar y al cambiar los minutos
        document.addEventListener('DOMContentLoaded', function() {
            actualizarInfo();
            document.getElementById('minutos').addEventListener('input', actualizarInfo);

            // Event listeners para botones de tiempo
            document.querySelectorAll('.btn-tiempo').forEach(button => {
                button.addEventListener('click', function() {
                    const minutos = parseInt(this.dataset.minutos);
                    setMinutos(minutos);
                });
            });

            // Event listener para toggle password
            const togglePasswordBtn = document.getElementById('toggle-password');
            if (togglePasswordBtn) {
                togglePasswordBtn.addEventListener('click', togglePassword);
            }

            // Event listener para el formulario de suspensión
            const btnSuspender = document.getElementById('btn-suspender');
            if (btnSuspender) {
                btnSuspender.addEventListener('click', function(e) {
                    if (!confirmarSuspension()) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</x-app-layout>
