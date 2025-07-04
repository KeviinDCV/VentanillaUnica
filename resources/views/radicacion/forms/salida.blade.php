<!-- Formulario de Radicación de Salida COMPLETAMENTE NUEVO -->
<form action="{{ route('radicacion.salida.store') }}" method="POST" enctype="multipart/form-data" id="radicacionSalidaForm" data-protect="true">
    @csrf

    <!-- Sección 1: Información del Destinatario -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            1. Información del Destinatario
        </h3>

        <!-- Tipo de Destinatario -->
        <div class="mb-4">
            <label for="tipo_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                Tipo de Destinatario <span class="text-red-500">*</span>
            </label>
            <select name="tipo_destinatario" id="tipo_destinatario" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                <option value="">Seleccionar tipo...</option>
                <option value="persona_natural" {{ old('tipo_destinatario') == 'persona_natural' ? 'selected' : '' }}>Persona Natural</option>
                <option value="persona_juridica" {{ old('tipo_destinatario') == 'persona_juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                <option value="entidad_publica" {{ old('tipo_destinatario') == 'entidad_publica' ? 'selected' : '' }}>Entidad Pública</option>
            </select>
        </div>

        <!-- Campos para Persona Natural - SIEMPRE VISIBLES -->
        <div id="campos-persona-natural">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="tipo_documento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento <span id="tipo_doc_required" class="text-red-500 hidden">*</span>
                    </label>
                    <select name="tipo_documento_destinatario" id="tipo_documento_destinatario"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        <option value="">Seleccionar...</option>
                        <option value="CC" {{ old('tipo_documento_destinatario') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                        <option value="CE" {{ old('tipo_documento_destinatario') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                        <option value="TI" {{ old('tipo_documento_destinatario') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                        <option value="PP" {{ old('tipo_documento_destinatario') == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                        <option value="OTRO" {{ old('tipo_documento_destinatario') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div>
                    <label for="numero_documento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Documento <span id="num_doc_required" class="text-red-500 hidden">*</span>
                    </label>
                    <input type="text" name="numero_documento_destinatario" id="numero_documento_destinatario"
                           value="{{ old('numero_documento_destinatario') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                           placeholder="Ingrese el número de documento">
                </div>
            </div>
        </div>

        <!-- Campos para Persona Jurídica/Entidad Pública - SIEMPRE VISIBLES -->
        <div id="campos-juridica">
            <div class="mb-4">
                <label for="nit_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    NIT <span id="nit_required" class="text-red-500 hidden">*</span>
                </label>
                <input type="text" name="nit_destinatario" id="nit_destinatario"
                       value="{{ old('nit_destinatario') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Ingrese el NIT">
            </div>
        </div>

        <!-- Nombre Completo del Destinatario -->
        <div class="mb-4">
            <label for="nombre_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                Nombre Completo del Destinatario <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre_destinatario" id="nombre_destinatario" required
                   value="{{ old('nombre_destinatario') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Nombre completo o razón social del destinatario">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Teléfono -->
            <div>
                <label for="telefono_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                </label>
                <input type="tel" name="telefono_destinatario" id="telefono_destinatario"
                       value="{{ old('telefono_destinatario') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Número de teléfono">
            </div>

            <!-- Email -->
            <div>
                <label for="email_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo Electrónico
                </label>
                <input type="email" name="email_destinatario" id="email_destinatario"
                       value="{{ old('email_destinatario') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <!-- Dirección -->
        <div class="mb-4">
            <label for="direccion_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                Dirección <span class="text-red-500">*</span>
            </label>
            <input type="text" name="direccion_destinatario" id="direccion_destinatario" required
                   value="{{ old('direccion_destinatario') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Dirección completa">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Departamento -->
            <div>
                <label for="departamento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Departamento <span class="text-red-500">*</span>
                </label>
                <select name="departamento_destinatario" id="departamento_destinatario" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar departamento...</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->nombre }}"
                                {{ old('departamento_destinatario') == $departamento->nombre ? 'selected' : '' }}>
                            {{ $departamento->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ciudad -->
            <div>
                <label for="ciudad_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Ciudad <span class="text-red-500">*</span>
                </label>
                <select name="ciudad_destinatario" id="ciudad_destinatario" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar ciudad...</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->nombre }}"
                                data-departamento="{{ $ciudad->departamento->nombre }}"
                                {{ old('ciudad_destinatario') == $ciudad->nombre ? 'selected' : '' }}>
                            {{ $ciudad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Entidad -->
        <div class="mb-4">
            <label for="entidad_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                Entidad/Empresa
            </label>
            <input type="text" name="entidad_destinatario" id="entidad_destinatario"
                   value="{{ old('entidad_destinatario') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Nombre de la entidad o empresa">
        </div>
    </div>

    <!-- Sección 2: Información del Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            2. Información del Documento
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Tipo de Comunicación -->
            <div>
                <label for="tipo_comunicacion" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Comunicación <span class="text-red-500">*</span>
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

            <!-- Medio de Envío -->
            <div>
                <label for="medio_envio" class="block text-sm font-medium text-gray-700 mb-2">
                    Medio de Envío <span class="text-red-500">*</span>
                </label>
                <select name="medio_envio" id="medio_envio" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="correo_fisico" {{ old('medio_envio') == 'correo_fisico' ? 'selected' : '' }}>Correo Físico</option>
                    <option value="correo_electronico" {{ old('medio_envio') == 'correo_electronico' ? 'selected' : '' }}>Correo Electrónico</option>
                    <option value="mensajeria" {{ old('medio_envio') == 'mensajeria' ? 'selected' : '' }}>Mensajería</option>
                    <option value="entrega_personal" {{ old('medio_envio') == 'entrega_personal' ? 'selected' : '' }}>Entrega Personal</option>
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
                    <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="normal" {{ old('prioridad') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ old('prioridad') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>
        </div>

        <!-- Asunto -->
        <div class="mb-4">
            <label for="asunto" class="block text-sm font-medium text-gray-700 mb-2">
                Asunto <span class="text-red-500">*</span>
            </label>
            <input type="text" name="asunto" id="asunto" required
                   value="{{ old('asunto') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Asunto del documento">
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

        <x-trd-selector :unidadesAdministrativas="$unidadesAdministrativas" />
    </div>

    <!-- Sección 4: Adjuntar Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Adjuntar Documento
        </h3>

        <div>
            <label for="documento_modal_salida" class="block text-sm font-medium text-gray-700 mb-2">
                Documento <span class="text-red-500">*</span>
            </label>

            <!-- Zona de arrastrar y soltar -->
            <div id="drop-zone-modal-salida" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-uniradical-blue transition-colors duration-200">
                <div id="drop-zone-content-modal-salida">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="documento_modal_salida" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium text-gray-900">
                                Arrastra y suelta tu archivo aquí, o
                                <span class="text-uniradical-blue hover:text-uniradical-blue-dark">haz clic para seleccionar</span>
                            </span>
                        </label>
                        <input type="file" name="documento" id="documento_modal_salida" required
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="sr-only">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        PDF, Word, JPG, PNG hasta 10MB
                    </p>
                </div>

                <!-- Vista previa del archivo -->
                <div id="file-preview-modal-salida" class="hidden">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <p id="file-name-modal-salida" class="text-sm font-medium text-gray-900"></p>
                                <p id="file-size-modal-salida" class="text-xs text-gray-500"></p>
                            </div>
                        </div>
                        <button type="button" id="remove-file-modal-salida" class="text-red-600 hover:text-red-800">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección 5: Información de Envío -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            5. Información de Envío
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Dependencia Origen -->
            <div>
                <label for="dependencia_origen_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Dependencia de Origen <span class="text-red-500">*</span>
                </label>
                <select name="dependencia_origen_id" id="dependencia_origen_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar dependencia...</option>
                    @foreach($dependencias as $dependencia)
                        <option value="{{ $dependencia->id }}"
                                {{ old('dependencia_origen_id') == $dependencia->id ? 'selected' : '' }}>
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
                <input type="text" name="funcionario_remitente" id="funcionario_remitente" required
                       value="{{ old('funcionario_remitente') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre del funcionario remitente">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

            <!-- Fecha de Envío -->
            <div>
                <label for="fecha_envio" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Envío
                </label>
                <input type="date" name="fecha_envio" id="fecha_envio"
                       value="{{ old('fecha_envio', date('Y-m-d')) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
            </div>
        </div>

        <!-- Tipo de Anexo -->
        <div class="mb-4">
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

        <!-- Requiere Acuse de Recibo -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ¿Requiere Acuse de Recibo? <span class="text-red-500">*</span>
            </label>
            <div class="flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="requiere_acuse_recibo" value="1" required
                           {{ old('requiere_acuse_recibo') == '1' ? 'checked' : '' }}
                           class="form-radio text-uniradical-blue">
                    <span class="ml-2">Sí</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="requiere_acuse_recibo" value="0" required
                           {{ old('requiere_acuse_recibo') == '0' ? 'checked' : '' }}
                           class="form-radio text-uniradical-blue">
                    <span class="ml-2">No</span>
                </label>
            </div>
        </div>

        <!-- Fecha Límite de Respuesta -->
        <div class="mb-4" id="fecha-limite-container" style="display: none;">
            <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                Fecha Límite de Respuesta
            </label>
            <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta"
                   value="{{ old('fecha_limite_respuesta') }}"
                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
        </div>

        <!-- Instrucciones de Envío -->
        <div class="mb-4">
            <label for="instrucciones_envio" class="block text-sm font-medium text-gray-700 mb-2">
                Instrucciones de Envío
            </label>
            <textarea name="instrucciones_envio" id="instrucciones_envio" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                      placeholder="Instrucciones especiales para el envío">{{ old('instrucciones_envio') }}</textarea>
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
                Crear Radicado de Salida
            </button>
        </div>
    </div>
</form>

<script>
// Función global para el componente TRD (compatible con trd-selector.js)
window.updateTrdId = function(subserieId) {
    const trdIdInput = document.getElementById('trd_id');
    if (trdIdInput) {
        // Si se pasa un parámetro, usarlo; si no, obtener del select
        const valorTrd = subserieId || document.getElementById('subserie_id')?.value || '';
        trdIdInput.value = valorTrd;
        console.log('TRD ID actualizado:', valorTrd);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Manejo del tipo de destinatario
    const tipoDestinatario = document.getElementById('tipo_destinatario');

    tipoDestinatario.addEventListener('change', function() {
        const valor = this.value;

        // Elementos de campos requeridos
        const tipoDocRequired = document.getElementById('tipo_doc_required');
        const numDocRequired = document.getElementById('num_doc_required');
        const nitRequired = document.getElementById('nit_required');

        // Resetear todos los campos como no requeridos
        document.getElementById('tipo_documento_destinatario').required = false;
        document.getElementById('numero_documento_destinatario').required = false;
        document.getElementById('nit_destinatario').required = false;

        // Ocultar todos los asteriscos
        tipoDocRequired.classList.add('hidden');
        numDocRequired.classList.add('hidden');
        nitRequired.classList.add('hidden');

        // Configurar según el tipo seleccionado
        if (valor === 'persona_natural') {
            document.getElementById('tipo_documento_destinatario').required = true;
            document.getElementById('numero_documento_destinatario').required = true;
            tipoDocRequired.classList.remove('hidden');
            numDocRequired.classList.remove('hidden');
            // Limpiar campo NIT
            document.getElementById('nit_destinatario').value = '';
        } else if (valor === 'persona_juridica' || valor === 'entidad_publica') {
            document.getElementById('nit_destinatario').required = true;
            nitRequired.classList.remove('hidden');
            // Limpiar campos de persona natural
            document.getElementById('tipo_documento_destinatario').value = '';
            document.getElementById('numero_documento_destinatario').value = '';
        } else {
            // Si no hay selección, limpiar todos los campos
            document.getElementById('tipo_documento_destinatario').value = '';
            document.getElementById('numero_documento_destinatario').value = '';
            document.getElementById('nit_destinatario').value = '';
        }
    });
    });

    // Filtro de ciudades por departamento
    const departamentoSelect = document.getElementById('departamento_destinatario');
    const ciudadSelect = document.getElementById('ciudad_destinatario');
    const todasLasCiudades = Array.from(ciudadSelect.options);

    departamentoSelect.addEventListener('change', function() {
        const departamentoSeleccionado = this.value;

        // Limpiar ciudades
        ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';

        if (departamentoSeleccionado) {
            // Filtrar ciudades por departamento
            const ciudadesFiltradas = todasLasCiudades.filter(option =>
                option.dataset.departamento === departamentoSeleccionado
            );

            ciudadesFiltradas.forEach(option => {
                ciudadSelect.appendChild(option.cloneNode(true));
            });
        }
    });

    // Manejo de acuse de recibo
    const acuseRadios = document.querySelectorAll('input[name="requiere_acuse_recibo"]');
    const fechaLimiteContainer = document.getElementById('fecha-limite-container');

    acuseRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                fechaLimiteContainer.style.display = 'block';
                document.getElementById('fecha_limite_respuesta').required = true;
            } else {
                fechaLimiteContainer.style.display = 'none';
                document.getElementById('fecha_limite_respuesta').required = false;
            }
        });
    });

    // Manejo de archivos - Drag and Drop
    const dropZone = document.getElementById('drop-zone-modal-salida');
    const fileInput = document.getElementById('documento_modal_salida');
    const dropZoneContent = document.getElementById('drop-zone-content-modal-salida');
    const filePreview = document.getElementById('file-preview-modal-salida');
    const fileName = document.getElementById('file-name-modal-salida');
    const fileSize = document.getElementById('file-size-modal-salida');
    const removeFileBtn = document.getElementById('remove-file-modal-salida');

    // Prevenir comportamiento por defecto
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Resaltar zona de drop
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-uniradical-blue', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-uniradical-blue', 'bg-blue-50');
    }

    // Manejar archivos soltados
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    // Manejar selección de archivos
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];

            // Validar tipo de archivo
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipo de archivo no permitido. Solo se permiten archivos PDF, Word e imágenes.');
                return;
            }

            // Validar tamaño (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('El archivo es demasiado grande. El tamaño máximo es 10MB.');
                return;
            }

            // Mostrar vista previa
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            dropZoneContent.classList.add('hidden');
            filePreview.classList.remove('hidden');

            // Asignar archivo al input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }
    }

    // Remover archivo
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        dropZoneContent.classList.remove('hidden');
        filePreview.classList.add('hidden');
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Botón volver
    document.getElementById('btn-volver-seleccion').addEventListener('click', function() {
        window.location.href = '{{ route("radicacion.index") }}';
    });

    // Botón preview
    document.getElementById('btn-preview').addEventListener('click', function() {
        // Validar campos requeridos antes de mostrar preview
        const form = document.getElementById('radicacionSalidaForm');
        if (form.checkValidity()) {
            // Aquí se puede implementar la lógica de preview
            alert('Funcionalidad de preview en desarrollo');
        } else {
            form.reportValidity();
        }
    });

    // Inicializar estado del formulario
    if (tipoDestinatario.value) {
        tipoDestinatario.dispatchEvent(new Event('change'));
    }

    const acuseChecked = document.querySelector('input[name="requiere_acuse_recibo"]:checked');
    if (acuseChecked) {
        acuseChecked.dispatchEvent(new Event('change'));
    }

    // Inicializar TRD selector - Las funciones principales están en trd-selector.js
    // Solo necesitamos asegurar que updateTrdId esté disponible
    console.log('Formulario de salida inicializado correctamente');
});
</script>
