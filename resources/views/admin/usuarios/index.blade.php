<x-app-layout>
    <div data-page="admin-usuarios"></div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Gestión de Usuarios
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Administración de usuarios del sistema UniRadic
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <button data-action="create-user" class="create-button">
                    Nuevo Usuario
                </button>
                <x-hospital-brand />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <!-- Mensajes de éxito y error -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Estadísticas de Usuarios -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Usuarios</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $usuarios->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Activos</p>
                            <p class="text-lg font-semibold text-green-600">{{ $usuarios->where('active', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Administradores</p>
                            <p class="text-lg font-semibold text-yellow-600">{{ $usuarios->where('role', 'administrador')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Ventanilla</p>
                            <p class="text-lg font-semibold text-purple-600">{{ $usuarios->where('role', 'ventanilla')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">Lista de Usuarios</h3>
                        <div class="flex-1 max-w-md ml-6">
                            <div class="relative">
                                <input type="text"
                                       id="buscar-usuarios"
                                       placeholder="Buscar por nombre, email o rol..."
                                       class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md text-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-visible">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Usuario
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 hidden md:table-cell">
                                    Email
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Rol
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Estado
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 hidden lg:table-cell">
                                    Radicados
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 hidden xl:table-cell">
                                    Registro
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody id="usuarios-table-body" class="bg-white divide-y divide-gray-200">
                            @foreach($usuarios as $usuario)
                            <tr class="hover:bg-gray-50 usuario-row"
                                data-name="{{ strtolower($usuario->name) }}"
                                data-email="{{ strtolower($usuario->email) }}"
                                data-role="{{ strtolower($usuario->getRoleDisplayName()) }}">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ strtoupper(substr($usuario->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate" title="{{ $usuario->name }}">
                                                {{ $usuario->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 md:hidden truncate" title="{{ $usuario->email }}">
                                                {{ $usuario->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-900 truncate max-w-32" title="{{ $usuario->email }}">{{ $usuario->email }}</div>
                                </td>
                                <td class="px-3 py-4">
                                    @if($usuario->role === 'administrador')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @elseif($usuario->role === 'ventanilla')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Ventanilla
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Sin rol
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    @if($usuario->active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-900 hidden lg:table-cell">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ number_format($usuario->radicados_count) }}</span>
                                        @if($usuario->radicados_count > 0)
                                            <span class="ml-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-xs text-gray-500 hidden xl:table-cell">
                                    {{ $usuario->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-4 text-sm font-medium">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                                                onclick="toggleDropdown('dropdown-user-{{ $usuario->id }}')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>

                                        <div id="dropdown-user-{{ $usuario->id }}"
                                             class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                             style="z-index: 9999;"
                                             data-dropdown-menu>
                                            <div class="py-1" role="menu">
                                                <!-- Editar -->
                                                <button data-action="edit-user"
                                                        data-user-id="{{ $usuario->id }}"
                                                        data-user-name="{{ $usuario->name }}"
                                                        data-user-email="{{ $usuario->email }}"
                                                        data-user-role="{{ $usuario->role }}"
                                                        data-user-active="{{ $usuario->active ? 'true' : 'false' }}"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Activar/Desactivar -->
                                                @if($usuario->id === auth()->id())
                                                    <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                                         title="No puedes desactivar tu propia cuenta">
                                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                        {{ $usuario->active ? 'Desactivar' : 'Activar' }}
                                                    </div>
                                                @else
                                                    <form action="{{ route('admin.usuarios.toggle-status', $usuario->id) }}"
                                                          method="POST"
                                                          class="w-full"
                                                          id="toggle-form-{{ $usuario->id }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button"
                                                                class="w-full text-left px-4 py-2 text-sm {{ $usuario->active ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} flex items-center"
                                                                data-user-name="{{ $usuario->name }}"
                                                                data-user-active="{{ $usuario->active ? 'true' : 'false' }}"
                                                                data-form-id="toggle-form-{{ $usuario->id }}">
                                                            @if($usuario->active)
                                                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                </svg>
                                                                Desactivar
                                                            @else
                                                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Activar
                                                            @endif
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Separador -->
                                                <div class="border-t border-gray-100"></div>

                                                <!-- Eliminar -->
                                                @if($usuario->id === auth()->id())
                                                    <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                                         title="No puedes eliminar tu propia cuenta">
                                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Eliminar
                                                    </div>
                                                @elseif($usuario->radicados_count > 0)
                                                    <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                                         title="No se puede eliminar: tiene {{ $usuario->radicados_count }} radicado(s) asociado(s)">
                                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Eliminar
                                                    </div>
                                                @else
                                                    <form action="{{ route('admin.usuarios.eliminar', $usuario->id) }}"
                                                          method="POST"
                                                          class="w-full"
                                                          id="delete-form-{{ $usuario->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center"
                                                                data-user-name="{{ $usuario->name }}"
                                                                data-form-id="delete-form-{{ $usuario->id }}">
                                                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $usuarios->links() }}
                </div>
            </div>

            <!-- Navegación -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('gestion.index') }}" class="back-button">
                    ← Volver a Gestión
                </a>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Usuario -->
    <div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-800">Editar Usuario</h3>
                <button data-action="close-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="mt-4">
                <!-- Errores de validación -->
                <div id="modalErrors" class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4 hidden">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                            <ul id="errorsList" class="mt-2 text-sm text-red-700 list-disc list-inside"></ul>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="edit_name"
                                   name="name"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="edit_email"
                                   name="email"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input type="password"
                                   id="edit_password"
                                   name="password"
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                            <p class="text-xs text-gray-500 mt-1">Dejar en blanco para mantener la contraseña actual</p>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password"
                                   id="edit_password_confirmation"
                                   name="password_confirmation"
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Rol -->
                        <div>
                            <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_role"
                                    name="role"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar rol...</option>
                                <option value="administrador">Administrador</option>
                                <option value="ventanilla">Ventanilla</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="edit_active"
                                       name="active"
                                       value="1"
                                       class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                                <label for="edit_active" class="ml-2 text-sm text-gray-700">
                                    Usuario activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button"
                                data-action="close-modal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Usuario -->
    <div id="createUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-800">Crear Nuevo Usuario</h3>
                <button data-action="close-create-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="mt-4">
                <!-- Errores de validación -->
                <div id="createModalErrors" class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4 hidden">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Errores en el formulario:</h3>
                            <ul id="createErrorsList" class="mt-2 text-sm text-red-700 list-disc list-inside"></ul>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="createUserForm" method="POST" action="{{ route('admin.usuarios.guardar') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="create_name"
                                   name="name"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="create_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="create_email"
                                   name="email"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label for="create_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   id="create_password"
                                   name="password"
                                   required
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="create_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   id="create_password_confirmation"
                                   name="password_confirmation"
                                   required
                                   minlength="8"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        </div>

                        <!-- Rol -->
                        <div>
                            <label for="create_role" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select id="create_role"
                                    name="role"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                <option value="">Seleccionar rol...</option>
                                <option value="administrador">Administrador</option>
                                <option value="ventanilla">Ventanilla</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="create_active"
                                       name="active"
                                       value="1"
                                       checked
                                       class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                                <label for="create_active" class="ml-2 text-sm text-gray-700">
                                    Usuario activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button"
                                data-action="close-create-modal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Cambiar Estado -->
    <div id="confirmStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <h3 id="confirmModalTitle" class="text-lg font-medium text-gray-800">Confirmar Acción</h3>
                <button data-action="close-confirm-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="mt-4">
                <div class="flex items-center mb-4">
                    <div id="confirmModalIcon" class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full">
                        <!-- Icono se agregará dinámicamente -->
                    </div>
                    <div class="ml-4 flex-1">
                        <p id="confirmModalMessage" class="text-sm text-gray-600">
                            <!-- Mensaje se agregará dinámicamente -->
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button"
                            data-action="close-confirm-modal"
                            class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="button"
                            id="confirmModalAction"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors min-w-[100px]">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para manejar los menús desplegables
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const isHidden = dropdown.classList.contains('hidden');

            // Cerrar todos los dropdowns abiertos
            document.querySelectorAll('[id^="dropdown-user-"]').forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                    // Resetear estilos completamente
                    d.style.position = '';
                    d.style.top = '';
                    d.style.bottom = '';
                    d.style.left = '';
                    d.style.right = '';
                    d.style.transform = '';
                    d.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
                }
            });

            // Toggle del dropdown actual
            if (isHidden) {
                dropdown.classList.remove('hidden');
                // Ajustar posición del menú según el espacio disponible
                adjustDropdownPosition(dropdown);
            } else {
                dropdown.classList.add('hidden');
                // Resetear estilos al cerrar
                dropdown.style.position = '';
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.style.left = '';
                dropdown.style.right = '';
                dropdown.style.transform = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
            }
        }

        // Función para ajustar la posición del dropdown
        function adjustDropdownPosition(dropdown) {
            // Encontrar el botón que activa este dropdown
            const button = dropdown.parentElement.querySelector('button[onclick*="toggleDropdown"]');
            if (!button) return;

            // Esperar un frame para que el dropdown se renderice completamente
            requestAnimationFrame(() => {
                const buttonRect = button.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const viewportWidth = window.innerWidth;
                const dropdownWidth = 192; // w-48 = 12rem = 192px

                // Resetear estilos de posicionamiento
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.style.left = '';
                dropdown.style.right = '';
                dropdown.style.transform = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');

                // Calcular posición horizontal (alineado a la derecha del botón)
                let leftPosition = buttonRect.right - dropdownWidth;

                // Asegurar que no se salga del viewport por la izquierda
                if (leftPosition < 10) {
                    leftPosition = buttonRect.left;
                }

                // Asegurar que no se salga del viewport por la derecha
                if (leftPosition + dropdownWidth > viewportWidth - 10) {
                    leftPosition = viewportWidth - dropdownWidth - 10;
                }

                // Calcular posición vertical
                const dropdownHeight = dropdown.offsetHeight || 200;
                const spaceBelow = viewportHeight - buttonRect.bottom;
                const spaceAbove = buttonRect.top;

                let topPosition;
                if (spaceBelow >= dropdownHeight + 10) {
                    // Hay espacio suficiente abajo
                    topPosition = buttonRect.bottom + 8;
                    dropdown.classList.add('origin-top-right');
                } else if (spaceAbove >= dropdownHeight + 10) {
                    // No hay espacio abajo pero sí arriba
                    topPosition = buttonRect.top - dropdownHeight - 8;
                    dropdown.classList.add('origin-bottom-right');
                } else {
                    // No hay espacio suficiente ni arriba ni abajo, priorizar abajo
                    topPosition = buttonRect.bottom + 8;
                    dropdown.classList.add('origin-top-right');
                }

                // Asegurar que no se salga del viewport por arriba
                if (topPosition < 10) {
                    topPosition = 10;
                }

                // Asegurar que no se salga del viewport por abajo
                if (topPosition + dropdownHeight > viewportHeight - 10) {
                    topPosition = viewportHeight - dropdownHeight - 10;
                }

                // Aplicar posicionamiento fijo
                dropdown.style.position = 'fixed';
                dropdown.style.left = leftPosition + 'px';
                dropdown.style.top = topPosition + 'px';
                dropdown.style.zIndex = '9999';
            });
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]') && !event.target.closest('[id^="dropdown-user-"]')) {
                document.querySelectorAll('[id^="dropdown-user-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Cerrar dropdown después de hacer clic en una acción
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="dropdown-user-"] button')) {
                // Pequeño delay para permitir que la acción se ejecute
                setTimeout(() => {
                    document.querySelectorAll('[id^="dropdown-user-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                        // Resetear estilos
                        dropdown.style.position = '';
                        dropdown.style.top = '';
                        dropdown.style.bottom = '';
                        dropdown.style.left = '';
                        dropdown.style.right = '';
                        dropdown.style.transform = '';
                        dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
                    });
                }, 100);
            }
        });

        // Reposicionar dropdowns al redimensionar la ventana
        window.addEventListener('resize', function() {
            document.querySelectorAll('[id^="dropdown-user-"]:not(.hidden)').forEach(dropdown => {
                adjustDropdownPosition(dropdown);
            });
        });

        // Cerrar dropdowns al hacer scroll
        window.addEventListener('scroll', function() {
            document.querySelectorAll('[id^="dropdown-user-"]:not(.hidden)').forEach(dropdown => {
                dropdown.classList.add('hidden');
                // Resetear estilos
                dropdown.style.position = '';
                dropdown.style.top = '';
                dropdown.style.bottom = '';
                dropdown.style.left = '';
                dropdown.style.right = '';
                dropdown.style.transform = '';
                dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
            });
        });
    </script>


</x-app-layout>
