{{-- Selector TRD Jerárquico --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Unidad Administrativa -->
    <div>
        <label for="unidad_administrativa_id" class="block text-sm font-medium text-gray-700 mb-2">
            Unidad Administrativa <span class="text-red-500">*</span>
        </label>
        <select name="unidad_administrativa_id" id="unidad_administrativa_id" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                onchange="loadSeriesForRadicacion(this.value)">
            <option value="">Seleccionar unidad...</option>
            @if(isset($unidadesAdministrativas) && count($unidadesAdministrativas) > 0)
                @foreach($unidadesAdministrativas as $unidad)
                    <option value="{{ $unidad->id ?? '' }}" {{ old('unidad_administrativa_id') == ($unidad->id ?? '') ? 'selected' : '' }}>
                        {{ ($unidad->codigo ?? 'Sin código') }} - {{ ($unidad->nombre ?? 'Sin nombre') }}
                    </option>
                @endforeach
            @else
                <option value="">No hay unidades disponibles</option>
            @endif
        </select>
    </div>

    <!-- Serie -->
    <div>
        <label for="serie_id" class="block text-sm font-medium text-gray-700 mb-2">
            Serie <span class="text-red-500">*</span>
        </label>
        <select name="serie_id" id="serie_id" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                onchange="loadSubseriesForRadicacion(this.value)">
            <option value="">Seleccionar serie...</option>
        </select>
    </div>

    <!-- Subserie -->
    <div>
        <label for="subserie_id" class="block text-sm font-medium text-gray-700 mb-2">
            Subserie <span class="text-red-500">*</span>
        </label>
        <select name="subserie_id" id="subserie_id" required
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue"
                onchange="updateTrdId(this.value)">
            <option value="">Seleccionar subserie...</option>
        </select>
        <!-- Campo oculto para compatibilidad con controladores que esperan trd_id -->
        <input type="hidden" name="trd_id" id="trd_id" value="{{ old('trd_id') }}">
    </div>
</div>

{{-- Las funciones JavaScript están en resources/js/trd-selector.js --}}
