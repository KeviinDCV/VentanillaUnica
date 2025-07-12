<!-- Formulario de Radicación de Entrada para Modal -->
<form action="{{ route('radicacion.entrada.store') }}" method="POST" enctype="multipart/form-data" id="radicacionEntradaForm">
    @csrf

    <!-- Sección 1: Información del Remitente -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            1. Información del Remitente
        </h3>

        <!-- Tipo de Remitente -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tipo de Remitente <span class="text-red-500">*</span>
            </label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="tipo_remitente" value="anonimo"
                           class="form-radio text-uniradical-blue"
                           {{ old('tipo_remitente', 'anonimo') === 'anonimo' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Anónimo</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="tipo_remitente" value="registrado"
                           class="form-radio text-uniradical-blue"
                           {{ old('tipo_remitente') === 'registrado' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Registrado</span>
                </label>
            </div>
        </div>

        <!-- Campos para remitente registrado -->
        <div id="campos-registrado" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" style="display: none;">
            <!-- Tipo de Documento -->
            <div>
                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select name="tipo_documento" id="tipo_documento"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                    <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                    <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                    <option value="PP" {{ old('tipo_documento') == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                    <option value="NIT" {{ old('tipo_documento') == 'NIT' ? 'selected' : '' }}>NIT</option>
                    <option value="OTRO" {{ old('tipo_documento') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <!-- Número de Documento -->
            <div>
                <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <input type="text" name="numero_documento" id="numero_documento"
                       value="{{ old('numero_documento') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Ingrese el número de documento">
                <button type="button" id="btn-buscar-remitente"
                        class="mt-2 text-sm text-uniradical-blue hover:text-opacity-80">
                    Buscar remitente existente
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Nombre Completo -->
            <div>
                <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre Completo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre_completo" id="nombre_completo" required
                       value="{{ old('nombre_completo') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre completo del remitente">
            </div>

            <!-- Espacio vacío para mantener el grid -->
            <div></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                </label>
                <input type="tel" name="telefono" id="telefono"
                       value="{{ old('telefono') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Número de teléfono">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo Electrónico
                </label>
                <input type="email" name="email" id="email"
                       value="{{ old('email') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <!-- Dirección -->
        <div class="mb-4">
            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                Dirección
            </label>
            <input type="text" name="direccion" id="direccion"
                   value="{{ old('direccion') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Dirección completa">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Departamento -->
            <div>
                <label for="departamento_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Departamento
                </label>
                <select name="departamento_id" id="departamento_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar departamento...</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}"
                                data-nombre="{{ $departamento->nombre }}"
                                {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                            {{ $departamento->nombre }}
                        </option>
                    @endforeach
                </select>
                <!-- Campo oculto para guardar el nombre del departamento -->
                <input type="hidden" name="departamento" id="departamento_nombre" value="{{ old('departamento') }}">
            </div>

            <!-- Ciudad -->
            <div>
                <label for="ciudad_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Ciudad
                </label>
                <select name="ciudad_id" id="ciudad_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                        disabled>
                    <option value="">Primero seleccione un departamento...</option>
                </select>
                <!-- Campo oculto para guardar el nombre de la ciudad -->
                <input type="hidden" name="ciudad" id="ciudad_nombre" value="{{ old('ciudad') }}">
            </div>
        </div>

        <!-- Entidad -->
        <div class="mb-4">
            <label for="entidad" class="block text-sm font-medium text-gray-700 mb-2">
                Entidad/Empresa
            </label>
            <input type="text" name="entidad" id="entidad"
                   value="{{ old('entidad') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Nombre de la entidad o empresa">
        </div>
    </div>

    <!-- Sección 2: Información del Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            2. Información del Documento
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Medio de Recepción -->
            <div>
                <label for="medio_recepcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Medio de Recepción <span class="text-red-500">*</span>
                </label>
                <select name="medio_recepcion" id="medio_recepcion" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="fisico" {{ old('medio_recepcion') == 'fisico' ? 'selected' : '' }}>Físico</option>
                    <option value="email" {{ old('medio_recepcion') == 'email' ? 'selected' : '' }}>Correo Electrónico</option>
                    <option value="web" {{ old('medio_recepcion') == 'web' ? 'selected' : '' }}>Página Web</option>
                    <option value="telefono" {{ old('medio_recepcion') == 'telefono' ? 'selected' : '' }}>Teléfono</option>
                    <option value="fax" {{ old('medio_recepcion') == 'fax' ? 'selected' : '' }}>Fax</option>
                    <option value="otro" {{ old('medio_recepcion') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <!-- Tipo de Solicitud -->
            <div>
                <label for="tipo_comunicacion" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Solicitud <span class="text-red-500">*</span>
                </label>
                <select name="tipo_comunicacion" id="tipo_comunicacion" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    @foreach($tiposSolicitud as $tipo)
                        <option value="{{ $tipo->codigo }}"
                                {{ old('tipo_comunicacion') == $tipo->codigo ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Número de Folios -->
            <div>
                <label for="numero_folios" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Folios <span class="text-red-500">*</span>
                </label>
                <input type="number" name="numero_folios" id="numero_folios" required min="1"
                       value="{{ old('numero_folios', 1) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Cantidad de folios">
            </div>

            <!-- Número de Anexos -->
            <div>
                <label for="numero_anexos" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Anexos
                </label>
                <input type="number" name="numero_anexos" id="numero_anexos" min="0"
                       value="{{ old('numero_anexos', 0) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Cantidad de anexos">
            </div>
        </div>

        <!-- Observaciones -->
        <div class="mb-4">
            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                Observaciones
            </label>
            <textarea name="observaciones" id="observaciones" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                      placeholder="Observaciones adicionales">{{ old('observaciones') }}</textarea>
        </div>
    </div>

    <!-- Sección 3: TRD -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            3. Clasificación Documental (TRD)
        </h3>

        <x-trd-selector :unidadesAdministrativas="$unidadesAdministrativas" :optional="true" />
    </div>

    <!-- Sección 4: Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Adjuntar Documento
        </h3>

        <div>
            <label for="documento_modal" class="block text-sm font-medium text-gray-700 mb-2">
                Documento <span class="text-red-500">*</span>
            </label>

            <!-- Zona de arrastrar y soltar -->
            <div id="drop-zone-modal" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-uniradical-blue transition-colors duration-200">
                <div id="drop-zone-content-modal">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="documento_modal" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium text-gray-900">
                                Arrastra y suelta tu archivo aquí, o
                                <span class="text-uniradical-blue hover:text-uniradical-blue-dark">haz clic para seleccionar</span>
                            </span>
                        </label>
                        <input type="file" name="documento" id="documento_modal" required
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="sr-only">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        PDF, Word, JPG, PNG hasta 10MB
                    </p>
                </div>

                <!-- Vista previa del archivo -->
                <div id="file-preview-modal" class="hidden">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <p id="file-name-modal" class="text-sm font-medium text-gray-900"></p>
                                <p id="file-size-modal" class="text-xs text-gray-500"></p>
                            </div>
                        </div>
                        <button type="button" id="remove-file-modal" class="text-red-600 hover:text-red-800">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección 4: Destino y Respuesta -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Destino y Respuesta
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Dependencia Destino -->
            <div>
                <label for="dependencia_destino_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Dependencia Destino <span class="text-red-500">*</span>
                </label>
                <select name="dependencia_destino_id" id="dependencia_destino_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar dependencia...</option>
                    @foreach($dependencias as $dependencia)
                        <option value="{{ $dependencia->id }}"
                                {{ old('dependencia_destino_id') == $dependencia->id ? 'selected' : '' }}>
                            {{ $dependencia->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Medio de Respuesta -->
            <div>
                <label for="medio_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                    Medio de Respuesta <span class="text-red-500">*</span>
                </label>
                <select name="medio_respuesta" id="medio_respuesta" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="fisico" {{ old('medio_respuesta') == 'fisico' ? 'selected' : '' }}>Físico</option>
                    <option value="email" {{ old('medio_respuesta') == 'email' ? 'selected' : '' }}>Correo Electrónico</option>
                    <option value="telefono" {{ old('medio_respuesta') == 'telefono' ? 'selected' : '' }}>Teléfono</option>
                    <option value="presencial" {{ old('medio_respuesta') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                    <option value="no_requiere" {{ old('medio_respuesta') == 'no_requiere' ? 'selected' : '' }}>No Requiere Respuesta</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Tipo de Anexo -->
            <div>
                <label for="tipo_anexo" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Anexo <span class="text-red-500">*</span>
                </label>
                <select name="tipo_anexo" id="tipo_anexo" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="original" {{ old('tipo_anexo') == 'original' ? 'selected' : '' }}>Original</option>
                    <option value="copia" {{ old('tipo_anexo') == 'copia' ? 'selected' : '' }}>Copia</option>
                    <option value="ninguno" {{ old('tipo_anexo') == 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                </select>
            </div>

            <!-- Fecha Límite de Respuesta -->
            <div>
                <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Límite de Respuesta
                </label>
                <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta"
                       value="{{ old('fecha_limite_respuesta') }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="flex justify-between pt-6 border-t border-gray-200">
        <button type="button" id="btn-volver-seleccion" class="cancel-button">
            Volver a Selección
        </button>
        <div class="flex space-x-3">
            <button type="button" id="btn-preview" class="btn-secondary">
                Previsualizar
            </button>
            <button type="submit" class="create-button">
                Crear Radicado
            </button>
        </div>
    </div>
</form>

@push('scripts')
    @vite(['resources/js/trd-selector.js'])
@endpush
