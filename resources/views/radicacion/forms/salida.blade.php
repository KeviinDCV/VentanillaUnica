<!-- Formulario de Radicación de Salida para Modal -->
<form action="{{ route('radicacion.salida.store') }}" method="POST" enctype="multipart/form-data" id="radicacionSalidaForm" data-protect="true">
    @csrf
    
    <!-- Sección 1: Información del Destinatario -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            1. Información del Destinatario
        </h3>
        
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Nombres -->
            <div>
                <label for="nombres_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombres <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombres_destinatario" id="nombres_destinatario" required
                       value="{{ old('nombres_destinatario') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombres del destinatario">
            </div>

            <!-- Apellidos -->
            <div>
                <label for="apellidos_destinatario" class="block text-sm font-medium text-gray-700 mb-2">
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input type="text" name="apellidos_destinatario" id="apellidos_destinatario" required
                       value="{{ old('apellidos_destinatario') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Apellidos del destinatario">
            </div>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
    </div>

    <!-- Sección 4: Información de Envío -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Información de Envío
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Dependencia Remitente -->
            <div>
                <label for="dependencia_remitente_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Dependencia Remitente <span class="text-red-500">*</span>
                </label>
                <select name="dependencia_remitente_id" id="dependencia_remitente_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar dependencia...</option>
                    @foreach($dependencias as $dependencia)
                        <option value="{{ $dependencia->id }}" 
                                {{ old('dependencia_remitente_id') == $dependencia->id ? 'selected' : '' }}>
                            {{ $dependencia->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Funcionario Responsable -->
            <div>
                <label for="funcionario_responsable" class="block text-sm font-medium text-gray-700 mb-2">
                    Funcionario Responsable <span class="text-red-500">*</span>
                </label>
                <input type="text" name="funcionario_responsable" id="funcionario_responsable" required
                       value="{{ old('funcionario_responsable') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre del funcionario responsable">
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
