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
            @foreach($unidadesAdministrativas as $unidad)
                <option value="{{ $unidad->id }}" {{ old('unidad_administrativa_id') == $unidad->id ? 'selected' : '' }}>
                    {{ $unidad->codigo }} - {{ $unidad->nombre }}
                </option>
            @endforeach
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
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
            <option value="">Seleccionar subserie...</option>
        </select>
    </div>
</div>

<script>
// Funciones para cargar series y subseries en formularios de radicación
function loadSeriesForRadicacion(unidadId) {
    const serieSelect = document.getElementById('serie_id');
    const subserieSelect = document.getElementById('subserie_id');
    
    // Limpiar selects
    serieSelect.innerHTML = '<option value="">Seleccionar serie...</option>';
    subserieSelect.innerHTML = '<option value="">Seleccionar subserie...</option>';
    
    if (!unidadId) return;
    
    fetch(`/admin/subseries/series-por-unidad/${unidadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.series.forEach(serie => {
                    const option = document.createElement('option');
                    option.value = serie.id;
                    option.textContent = `${serie.numero_serie} - ${serie.nombre}`;
                    serieSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading series:', error);
        });
}

function loadSubseriesForRadicacion(serieId) {
    const subserieSelect = document.getElementById('subserie_id');
    
    // Limpiar select
    subserieSelect.innerHTML = '<option value="">Seleccionar subserie...</option>';
    
    if (!serieId) return;
    
    fetch(`/admin/subseries/por-serie/${serieId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.subseries.forEach(subserie => {
                    const option = document.createElement('option');
                    option.value = subserie.id;
                    option.textContent = `${subserie.numero_subserie} - ${subserie.nombre}`;
                    subserieSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading subseries:', error);
        });
}
</script>
