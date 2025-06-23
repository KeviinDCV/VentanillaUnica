<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-light text-xl text-gray-800 leading-tight">
                    Radicación de Documentos de Salida
                </h2>
                <p class="text-sm text-gray-600 font-light mt-1">
                    Formulario para radicar documentos hacia entidades externas
                </p>
            </div>
            <x-hospital-brand />
        </div>
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

                    <form action="{{ route('radicacion.salida.store') }}" method="POST" enctype="multipart/form-data" id="radicacionSalidaForm">
                        @csrf

                        <!-- Sección 1: Información del Destinatario Externo -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                1. Información del Destinatario Externo
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tipo de Destinatario -->
                                <div class="md:col-span-2">
                                    <label for="tipo_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Destinatario <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipo_destinatario" id="tipo_destinatario" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar tipo...</option>
                                        <option value="persona_natural" {{ old('tipo_destinatario') === 'persona_natural' ? 'selected' : '' }}>Persona Natural</option>
                                        <option value="persona_juridica" {{ old('tipo_destinatario') === 'persona_juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                                        <option value="entidad_publica" {{ old('tipo_destinatario') === 'entidad_publica' ? 'selected' : '' }}>Entidad Pública</option>
                                    </select>
                                </div>

                                <!-- Campos para Persona Natural -->
                                <div id="campos-persona-natural" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                                    <div>
                                        <label for="tipo_documento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tipo de Documento <span class="text-red-500">*</span>
                                        </label>
                                        <select name="tipo_documento_destinatario" id="tipo_documento_destinatario"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                            <option value="">Seleccionar...</option>
                                            <option value="CC" {{ old('tipo_documento_destinatario') === 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                            <option value="CE" {{ old('tipo_documento_destinatario') === 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                            <option value="TI" {{ old('tipo_documento_destinatario') === 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                            <option value="PP" {{ old('tipo_documento_destinatario') === 'PP' ? 'selected' : '' }}>Pasaporte</option>
                                            <option value="OTRO" {{ old('tipo_documento_destinatario') === 'OTRO' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="numero_documento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Documento <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="numero_documento_destinatario" id="numero_documento_destinatario"
                                               value="{{ old('numero_documento_destinatario') }}"
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                               placeholder="Número de documento">
                                    </div>
                                </div>

                                <!-- Campos para Persona Jurídica/Entidad Pública -->
                                <div id="campos-juridica" class="md:col-span-2" style="display: none;">
                                    <label for="nit_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                        NIT <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nit_destinatario" id="nit_destinatario"
                                           value="{{ old('nit_destinatario') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="NIT (ej: 900123456-1)">
                                </div>

                                <!-- Nombre del Destinatario -->
                                <div class="md:col-span-2">
                                    <label for="nombre_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre Completo del Destinatario <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombre_destinatario" id="nombre_destinatario"
                                           value="{{ old('nombre_destinatario') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Nombre completo o razón social" required>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono
                                    </label>
                                    <input type="text" name="telefono_destinatario" id="telefono_destinatario"
                                           value="{{ old('telefono_destinatario') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Número de teléfono">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" name="email_destinatario" id="email_destinatario"
                                           value="{{ old('email_destinatario') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Correo electrónico">
                                </div>

                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <label for="direccion_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dirección <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="direccion_destinatario" id="direccion_destinatario"
                                           value="{{ old('direccion_destinatario') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                                           placeholder="Dirección completa" required>
                                </div>

                                <!-- Departamento -->
                                <div>
                                    <label for="departamento_destinatario_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Departamento <span class="text-red-500">*</span>
                                    </label>
                                    <select name="departamento_destinatario_id" id="departamento_destinatario_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar departamento...</option>
                                        @foreach($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}" {{ old('departamento_destinatario_id') == $departamento->id ? 'selected' : '' }}>
                                                {{ $departamento->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Primero seleccione el departamento para ver las ciudades disponibles</p>
                                </div>

                                <!-- Ciudad -->
                                <div>
                                    <label for="ciudad_destinatario_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ciudad <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ciudad_destinatario_id" id="ciudad_destinatario_id" required disabled
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue disabled:bg-gray-100 disabled:cursor-not-allowed">
                                        <option value="">Primero seleccione un departamento...</option>
                                        @foreach($ciudades as $ciudad)
                                            <option value="{{ $ciudad->id }}"
                                                    data-departamento="{{ $ciudad->departamento_id }}"
                                                    {{ old('ciudad_destinatario_id') == $ciudad->id ? 'selected' : '' }}
                                                    style="display: none;">
                                                {{ $ciudad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Las ciudades se mostrarán según el departamento seleccionado</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Información del Remitente Interno -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                2. Información del Remitente (Dependencia Origen)
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
                            </div>
                        </div>

                        <!-- Sección 3: Información del Documento -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                3. Información del Documento
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
                                        @foreach($tiposSolicitud as $tipo)
                                            <option value="{{ $tipo->codigo }}" {{ old('tipo_comunicacion') === $tipo->codigo ? 'selected' : '' }}>
                                                {{ $tipo->nombre }}
                                            </option>
                                        @endforeach
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

                        <!-- Sección 4: TRD -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                4. Tabla de Retención Documental (TRD)
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

                        <!-- Sección 5: Información de Envío -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                5. Información de Envío
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Medio de Envío -->
                                <div>
                                    <label for="medio_envio" class="block text-sm font-medium text-gray-700 mb-2">
                                        Medio de Envío <span class="text-red-500">*</span>
                                    </label>
                                    <select name="medio_envio" id="medio_envio" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                        <option value="">Seleccionar...</option>
                                        <option value="correo_fisico" {{ old('medio_envio') === 'correo_fisico' ? 'selected' : '' }}>Correo Físico</option>
                                        <option value="correo_electronico" {{ old('medio_envio') === 'correo_electronico' ? 'selected' : '' }}>Correo Electrónico</option>
                                        <option value="mensajeria" {{ old('medio_envio') === 'mensajeria' ? 'selected' : '' }}>Mensajería</option>
                                        <option value="entrega_personal" {{ old('medio_envio') === 'entrega_personal' ? 'selected' : '' }}>Entrega Personal</option>
                                    </select>
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

                                <!-- Requiere Acuse de Recibo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ¿Requiere Acuse de Recibo? <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="requiere_acuse_recibo" value="1"
                                                   class="form-radio text-uniradical-blue"
                                                   {{ old('requiere_acuse_recibo') === '1' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">Sí</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="requiere_acuse_recibo" value="0"
                                                   class="form-radio text-uniradical-blue"
                                                   {{ old('requiere_acuse_recibo') === '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700">No</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Fecha Límite de Respuesta -->
                                <div>
                                    <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Límite de Respuesta (Opcional)
                                    </label>
                                    <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta"
                                           value="{{ old('fecha_limite_respuesta') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                                    <p class="text-xs text-gray-500 mt-1">Solo si se espera respuesta del destinatario.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 6: Documento -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
                                6. Documento
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
                               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                                Crear Radicado de Salida
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/ciudad-departamento.js')
    @endpush

</x-app-layout>