<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Crear Nuevo Usuario
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Agregar un nuevo usuario al sistema UniRadic
                </p>
            </div>
            <div>
                <a href="{{ route('admin.usuarios') }}" class="back-button">
                    Volver a Usuarios
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Errores de validación -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Información del Usuario</h3>
                    <p class="text-sm text-gray-600 mt-1">Complete todos los campos requeridos para crear el usuario.</p>
                </div>

                <form action="{{ route('admin.usuarios.guardar') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Rol -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select id="role" 
                                    name="role" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar rol...</option>
                                <option value="administrador" {{ old('role') === 'administrador' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="ventanilla" {{ old('role') === 'ventanilla' ? 'selected' : '' }}>
                                    Ventanilla
                                </option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="active" 
                                       name="active" 
                                       value="1"
                                       {{ old('active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                                <label for="active" class="ml-2 text-sm text-gray-700">
                                    Usuario activo
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Los usuarios inactivos no pueden acceder al sistema</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.usuarios') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
