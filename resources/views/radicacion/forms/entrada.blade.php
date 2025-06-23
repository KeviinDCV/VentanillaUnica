<!-- Formulario de Radicación de Entrada para Modal -->
<form action="{{ route('radicacion.entrada.store') }}" method="POST" enctype="multipart/form-data" id="radicacionEntradaForm" data-protect="true">
    @csrf
    
    <!-- Sección 1: Información del Remitente -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4 border-b border-gray-200 pb-2">
            1. Información del Remitente
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Tipo de Documento -->
            <div>
                <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select name="tipo_documento" id="tipo_documento" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar...</option>
                    <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                    <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                    <option value="NIT" {{ old('tipo_documento') == 'NIT' ? 'selected' : '' }}>NIT</option>
                    <option value="PASAPORTE" {{ old('tipo_documento') == 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                    <option value="OTRO" {{ old('tipo_documento') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <!-- Número de Documento -->
            <div>
                <label for="numero_documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <input type="text" name="numero_documento" id="numero_documento" required
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
            <!-- Nombres -->
            <div>
                <label for="nombres" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombres <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombres" id="nombres" required
                       value="{{ old('nombres') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Nombres del remitente">
            </div>

            <!-- Apellidos -->
            <div>
                <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input type="text" name="apellidos" id="apellidos" required
                       value="{{ old('apellidos') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                       placeholder="Apellidos del remitente">
            </div>
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
                <label for="departamento_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Departamento
                </label>
                <select name="departamento_remitente" id="departamento_remitente"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar departamento...</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->nombre }}" 
                                {{ old('departamento_remitente') == $departamento->nombre ? 'selected' : '' }}>
                            {{ $departamento->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ciudad -->
            <div>
                <label for="ciudad_remitente" class="block text-sm font-medium text-gray-700 mb-2">
                    Ciudad
                </label>
                <select name="ciudad_remitente" id="ciudad_remitente"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                    <option value="">Seleccionar ciudad...</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->nombre }}" 
                                data-departamento="{{ $ciudad->departamento->nombre }}"
                                {{ old('ciudad_remitente') == $ciudad->nombre ? 'selected' : '' }}>
                            {{ $ciudad->nombre }}
                        </option>
                    @endforeach
                </select>
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
        </div>

        <!-- Número de Folios -->
        <div class="mb-4">
            <label for="numero_folios" class="block text-sm font-medium text-gray-700 mb-2">
                Número de Folios <span class="text-red-500">*</span>
            </label>
            <input type="number" name="numero_folios" id="numero_folios" required min="1"
                   value="{{ old('numero_folios', 1) }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                   placeholder="Cantidad de folios">
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
