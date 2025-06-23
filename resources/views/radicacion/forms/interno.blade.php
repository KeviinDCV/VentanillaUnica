<!-- Formulario de Radicación Interna para Modal -->
<form action="{{ route('radicacion.interna.store') }}" method="POST" enctype="multipart/form-data" id="radicacionInternaForm" data-protect="true">
    @csrf
    
    <!-- Sección 1: Información del Remitente Interno -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            1. Información del Remitente
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

            <!-- Funcionario Remitente -->
            <div>
                <label for="funcionario_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Funcionario Remitente <span class="text-red-500">*</span>
                </label>
                <input type="text" name="funcionario_remitente" id="funcionario_remitente" required
                       value="{{ old('funcionario_remitente') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre del funcionario">
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

            <!-- Teléfono -->
            <div>
                <label for="telefono_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                </label>
                <input type="tel" name="telefono_remitente" id="telefono_remitente"
                       value="{{ old('telefono_remitente') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Número de teléfono">
            </div>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                Correo Electrónico
            </label>
            <input type="email" name="email_remitente" id="email_remitente"
                   value="{{ old('email_remitente') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="correo@hospital.gov.co">
        </div>
    </div>

    <!-- Sección 2: Información del Documento -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            2. Información del Documento
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Tipo de Comunicación Interna -->
            <div>
                <label for="tipo_comunicacion_interna" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Comunicación <span class="text-red-500">*</span>
                </label>
                <select name="tipo_comunicacion_interna" id="tipo_comunicacion_interna" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="memorando" {{ old('tipo_comunicacion_interna') == 'memorando' ? 'selected' : '' }}>Memorando</option>
                    <option value="circular" {{ old('tipo_comunicacion_interna') == 'circular' ? 'selected' : '' }}>Circular</option>
                    <option value="informe" {{ old('tipo_comunicacion_interna') == 'informe' ? 'selected' : '' }}>Informe</option>
                    <option value="acta" {{ old('tipo_comunicacion_interna') == 'acta' ? 'selected' : '' }}>Acta</option>
                    <option value="resolucion" {{ old('tipo_comunicacion_interna') == 'resolucion' ? 'selected' : '' }}>Resolución</option>
                    <option value="oficio" {{ old('tipo_comunicacion_interna') == 'oficio' ? 'selected' : '' }}>Oficio</option>
                    <option value="otro" {{ old('tipo_comunicacion_interna') == 'otro' ? 'selected' : '' }}>Otro</option>
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
                <label for="asunto" class="block text-sm font-medium text-gray-700 mb-2">
                    Asunto <span class="text-red-500">*</span>
                </label>
                <input type="text" name="asunto" id="asunto" required
                       value="{{ old('asunto') }}"
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

    <!-- Sección 4: Destino -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            4. Información de Destino
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

            <!-- Funcionario Destino -->
            <div>
                <label for="funcionario_destino" class="block text-sm font-medium text-gray-700 mb-2">
                    Funcionario Destino
                </label>
                <input type="text" name="funcionario_destino" id="funcionario_destino"
                       value="{{ old('funcionario_destino') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombre del funcionario destino">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Requiere Respuesta -->
            <div>
                <label for="requiere_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                    Requiere Respuesta <span class="text-red-500">*</span>
                </label>
                <select name="requiere_respuesta" id="requiere_respuesta" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="si" {{ old('requiere_respuesta') == 'si' ? 'selected' : '' }}>Sí</option>
                    <option value="no" {{ old('requiere_respuesta') == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <!-- Fecha Límite de Respuesta -->
            <div id="fecha-limite-container" class="hidden">
                <label for="fecha_limite_respuesta" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha Límite de Respuesta
                </label>
                <input type="date" name="fecha_limite_respuesta" id="fecha_limite_respuesta"
                       value="{{ old('fecha_limite_respuesta') }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="mb-4">
            <label for="instrucciones" class="block text-sm font-medium text-gray-700 mb-2">
                Instrucciones Especiales
            </label>
            <textarea name="instrucciones" id="instrucciones" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                      placeholder="Instrucciones especiales para el destinatario">{{ old('instrucciones') }}</textarea>
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
                Crear Radicado Interno
            </button>
        </div>
    </div>
</form>

<script>
// Funcionalidad específica del formulario interno
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar fecha límite según requiere respuesta
    const requiereRespuesta = document.getElementById('requiere_respuesta');
    const fechaLimiteContainer = document.getElementById('fecha-limite-container');
    
    if (requiereRespuesta && fechaLimiteContainer) {
        requiereRespuesta.addEventListener('change', function() {
            if (this.value === 'si') {
                fechaLimiteContainer.classList.remove('hidden');
                document.getElementById('fecha_limite_respuesta').required = true;
            } else {
                fechaLimiteContainer.classList.add('hidden');
                document.getElementById('fecha_limite_respuesta').required = false;
                document.getElementById('fecha_limite_respuesta').value = '';
            }
        });
        
        // Verificar estado inicial
        if (requiereRespuesta.value === 'si') {
            fechaLimiteContainer.classList.remove('hidden');
            document.getElementById('fecha_limite_respuesta').required = true;
        }
    }
});
</script>
