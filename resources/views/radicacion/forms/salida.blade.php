<!-- Formulario de Radicación de Salida para Modal -->
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Tipo de Documento -->
            <div>
                <label for="tipo_documento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select name="tipo_documento_destinatario" id="tipo_documento_destinatario" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="CC" {{ old('tipo_documento_destinatario') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                    <option value="CE" {{ old('tipo_documento_destinatario') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                    <option value="NIT" {{ old('tipo_documento_destinatario') == 'NIT' ? 'selected' : '' }}>NIT</option>
                    <option value="PASAPORTE" {{ old('tipo_documento_destinatario') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    <option value="OTRO" {{ old('tipo_documento_destinatario') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <!-- Número de Documento -->
            <div>
                <label for="numero_documento_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <input type="text" name="numero_documento_destinatario" id="numero_documento_destinatario" required
                       value="{{ old('numero_documento_destinatario') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Ingrese el número de documento">
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
                <label for="tipo_comunicacion_salida" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Comunicación <span class="text-red-500">*</span>
                </label>
                <select name="tipo_comunicacion_salida" id="tipo_comunicacion_salida" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    @foreach($tiposSolicitud as $tipo)
                        <option value="{{ $tipo->codigo }}"
                                {{ old('tipo_comunicacion_salida') == $tipo->codigo ? 'selected' : '' }}>
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
                    <option value="fisico" {{ old('medio_envio') == 'fisico' ? 'selected' : '' }}>Físico</option>
                    <option value="email" {{ old('medio_envio') == 'email' ? 'selected' : '' }}>Correo Electrónico</option>
                    <option value="correo_certificado" {{ old('medio_envio') == 'correo_certificado' ? 'selected' : '' }}>Correo Certificado</option>
                    <option value="mensajeria" {{ old('medio_envio') == 'mensajeria' ? 'selected' : '' }}>Mensajería</option>
                    <option value="fax" {{ old('medio_envio') == 'fax' ? 'selected' : '' }}>Fax</option>
                    <option value="otro" {{ old('medio_envio') == 'otro' ? 'selected' : '' }}>Otro</option>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Asunto -->
            <div class="md:col-span-2">
                <label for="asunto_salida" class="block text-sm font-medium text-gray-700 mb-2">
                    Asunto <span class="text-red-500">*</span>
                </label>
                <input type="text" name="asunto_salida" id="asunto_salida" required
                       value="{{ old('asunto_salida') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Asunto del documento">
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

        <x-trd-selector :unidadesAdministrativas="$unidadesAdministrativas" />
    </div>

    <!-- Sección 4: Documento -->
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

    <!-- Sección 4: Información de Envío -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Información de Envío
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
                <label for="cargo_responsable" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo
                </label>
                <input type="text" name="cargo_responsable" id="cargo_responsable"
                       value="{{ old('cargo_responsable') }}"
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
// Funcionalidad específica del formulario de salida
document.addEventListener('DOMContentLoaded', function() {
    // Cascada de departamento-ciudad para destinatario
    const departamentoSelect = document.getElementById('departamento_destinatario');
    const ciudadSelect = document.getElementById('ciudad_destinatario');

    if (departamentoSelect && ciudadSelect) {
        departamentoSelect.addEventListener('change', function() {
            const departamentoSeleccionado = this.value;
            const opciones = ciudadSelect.querySelectorAll('option');

            opciones.forEach(opcion => {
                if (opcion.value === '') {
                    opcion.style.display = 'block';
                } else {
                    const departamentoOpcion = opcion.getAttribute('data-departamento');
                    opcion.style.display = departamentoOpcion === departamentoSeleccionado ? 'block' : 'none';
                }
            });

            ciudadSelect.value = '';
        });
    }
});
</script>
