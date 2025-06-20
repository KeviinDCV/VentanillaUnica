<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 leading-tight">
            Radicación de Documentos Internos
        </h2>
        <p class="text-sm text-gray-600 font-light mt-1">
            Formulario para radicar comunicaciones entre dependencias del hospital
        </p>
    </x-slot>

    <div class="py-12">
        <div class="container-minimal">
            <div class="card-minimal">
                <div class="p-8">
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
                                        Se encontraron errores en el formulario:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('radicacion.interna.store') }}" method="POST" enctype="multipart/form-data" id="radicacionInternaForm">
                        @csrf
                        
                        <!-- Sección 1: Información del Remitente Interno -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                1. Información del Remitente (Dependencia Origen)
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Dependencia Origen -->
                                <div class="md:col-span-2">
                                    <label for="dependencia_origen_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dependencia de Origen <span class="text-red-500">*</span>
                                    </label>
                                    <select name="dependencia_origen_id" id="dependencia_origen_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar dependencia de origen...</option>
                                        @foreach($dependencias as $dependencia)
                                            <option value="{{ $dependencia->id }}" {{ old('dependencia_origen_id') == $dependencia->id ? 'selected' : '' }}>
                                                {{ $dependencia->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Funcionario Remitente -->
                                <div>
                                    <label for="funcionario_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                        Funcionario Remitente <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="funcionario_remitente" id="funcionario_remitente" 
                                           value="{{ old('funcionario_remitente') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Nombre completo del funcionario" required>
                                </div>

                                <!-- Cargo -->
                                <div>
                                    <label for="cargo_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cargo
                                    </label>
                                    <input type="text" name="cargo_remitente" id="cargo_remitente" 
                                           value="{{ old('cargo_remitente') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Cargo del funcionario">
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono
                                    </label>
                                    <input type="text" name="telefono_remitente" id="telefono_remitente" 
                                           value="{{ old('telefono_remitente') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Número de teléfono">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" name="email_remitente" id="email_remitente" 
                                           value="{{ old('email_remitente') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Correo electrónico">
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Información del Documento -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                2. Información del Documento
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Asunto -->
                                <div class="md:col-span-2">
                                    <label for="asunto" class="block text-sm font-medium text-gray-700 mb-2">
                                        Asunto del Documento <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="asunto" id="asunto" rows="3" required
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                              placeholder="Descripción clara y concisa del asunto del documento">{{ old('asunto') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                                </div>

                                <!-- Tipo de Comunicación -->
                                <div>
                                    <label for="tipo_comunicacion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Comunicación <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo_comunicacion" id="tipo_comunicacion" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="memorando" {{ old('tipo_comunicacion') === 'memorando' ? 'selected' : '' }}>Memorando</option>
                                        <option value="circular" {{ old('tipo_comunicacion') === 'circular' ? 'selected' : '' }}>Circular</option>
                                        <option value="oficio" {{ old('tipo_comunicacion') === 'oficio' ? 'selected' : '' }}>Oficio</option>
                                        <option value="informe" {{ old('tipo_comunicacion') === 'informe' ? 'selected' : '' }}>Informe</option>
                                        <option value="acta" {{ old('tipo_comunicacion') === 'acta' ? 'selected' : '' }}>Acta</option>
                                        <option value="otro" {{ old('tipo_comunicacion') === 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>

                                <!-- Prioridad -->
                                <div>
                                    <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-2">
                                        Prioridad <span class="text-red-500">*</span>
                                    </label>
                                    <select name="prioridad" id="prioridad" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="baja" {{ old('prioridad') === 'baja' ? 'selected' : '' }}>Baja</option>
                                        <option value="normal" {{ old('prioridad') === 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="alta" {{ old('prioridad') === 'alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="urgente" {{ old('prioridad') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                                    </select>
                                </div>

                                <!-- Número de Folios -->
                                <div>
                                    <label for="numero_folios" class="block text-sm font-medium text-gray-700 mb-2">
                                        Número de Folios <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="numero_folios" id="numero_folios" 
                                           value="{{ old('numero_folios', 1) }}" min="1" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                </div>

                                <!-- Observaciones -->
                                <div class="md:col-span-2">
                                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                        Observaciones
                                    </label>
                                    <textarea name="observaciones" id="observaciones" rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                              placeholder="Observaciones adicionales sobre el documento">{{ old('observaciones') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: TRD -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                3. Tabla de Retención Documental (TRD)
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- TRD -->
                                <div>
                                    <label for="trd_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Seleccionar TRD <span class="text-red-500">*</span>
                                    </label>
                                    <select name="trd_id" id="trd_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar TRD...</option>
                                        @foreach($trds as $trd)
                                            <option value="{{ $trd->id }}" 
                                                    data-codigo="{{ $trd->codigo }}"
                                                    data-serie="{{ $trd->serie }}"
                                                    data-subserie="{{ $trd->subserie }}"
                                                    data-asunto="{{ $trd->asunto }}"
                                                    {{ old('trd_id') == $trd->id ? 'selected' : '' }}>
                                                {{ $trd->descripcion_completa }} - {{ $trd->asunto }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Información del TRD seleccionado -->
                                <div id="trd-info" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-md">
                                    <h4 class="font-medium text-blue-800 mb-2">Información del TRD:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-blue-700">Código:</span>
                                            <span id="trd-codigo" class="text-blue-600"></span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-700">Serie:</span>
                                            <span id="trd-serie" class="text-blue-600"></span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-700">Subserie:</span>
                                            <span id="trd-subserie" class="text-blue-600"></span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="font-medium text-blue-700">Asunto:</span>
                                        <span id="trd-asunto" class="text-blue-600"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Destino del Documento -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                4. Destino del Documento
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Dependencia Destino -->
                                <div class="md:col-span-2">
                                    <label for="dependencia_destino_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dependencia Destinataria <span class="text-red-500">*</span>
                                    </label>
                                    <select name="dependencia_destino_id" id="dependencia_destino_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar dependencia destino...</option>
                                        @foreach($dependencias as $dependencia)
                                            <option value="{{ $dependencia->id }}" {{ old('dependencia_destino_id') == $dependencia->id ? 'selected' : '' }}>
                                                {{ $dependencia->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Requiere Respuesta -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ¿Requiere Respuesta? <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="requiere_respuesta" value="1"
                                                   class="form-radio text-uniradical-blue"
                                                   {{ old('requiere_respuesta') === '1' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Sí</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="requiere_respuesta" value="0"
                                                   class="form-radio text-uniradical-blue"
                                                   {{ old('requiere_respuesta') === '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">No</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Tipo de Anexo -->
                                <div>
                                    <label for="tipo_anexo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Anexo <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo_anexo" id="tipo_anexo" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="original" {{ old('tipo_anexo') === 'original' ? 'selected' : '' }}>Original</option>
                                        <option value="copia" {{ old('tipo_anexo') === 'copia' ? 'selected' : '' }}>Copia</option>
                                        <option value="ninguno" {{ old('tipo_anexo') === 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                                    </select>
                                </div>

                                <!-- Fecha Límite de Respuesta -->
                                <div id="fecha-limite-container" class="md:col-span-2" style="display: none;">
                                    <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Límite de Respuesta <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta" 
                                           value="{{ old('fecha_limite_respuesta') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <p class="text-xs text-gray-500 mt-1">Obligatorio cuando se requiere respuesta.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 5: Documento -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                5. Documento
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Archivo -->
                                <div>
                                    <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">
                                        Adjuntar Documento <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="documento" id="documento" required
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Formatos permitidos: PDF, Word, JPG, PNG. Tamaño máximo: 10MB
                                    </p>
                                </div>

                                <!-- Vista previa del archivo -->
                                <div id="file-preview" class="hidden p-4 bg-gray-50 border border-gray-200 rounded-md">
                                    <h4 class="font-medium text-gray-800 mb-2">Archivo seleccionado:</h4>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p id="file-name" class="text-sm font-medium text-gray-800"></p>
                                            <p id="file-size" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-between">
                            <a href="{{ route('dashboard') }}"
                               class="cancel-button">
                                Cancelar
                            </a>
                            <div class="flex space-x-3">
                                <button type="button"
                                        id="btn-preview-interno"
                                        class="btn-secondary">
                                    Previsualizar
                                </button>
                                <button type="submit"
                                        class="create-button">
                                    Crear Radicado Interno
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/radicacion.js'])
    @endpush
</x-app-layout>
