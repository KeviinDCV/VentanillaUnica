/**
 * UniRadic - Selector TRD Jerárquico
 * Funcionalidad para cargar series y subseries dinámicamente
 */

// Funciones para cargar series y subseries en formularios de radicación
window.loadSeriesForRadicacion = function(unidadId) {
    const serieSelect = document.getElementById('serie_id');
    const subserieSelect = document.getElementById('subserie_id');

    if (!serieSelect || !subserieSelect) {
        console.warn('Elementos serie_id o subserie_id no encontrados');
        return;
    }

    // Limpiar selects
    serieSelect.innerHTML = '<option value="">Seleccionar serie...</option>';
    subserieSelect.innerHTML = '<option value="">Seleccionar subserie...</option>';

    if (!unidadId) return;

    // Mostrar loading
    serieSelect.innerHTML = '<option value="">Cargando series...</option>';

    // Obtener token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const token = csrfToken ? csrfToken.getAttribute('content') : '';

    fetch(`/admin/subseries/series-por-unidad/${unidadId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            // Limpiar loading
            serieSelect.innerHTML = '<option value="">Seleccionar serie...</option>';

            if (data.success) {
                data.series.forEach(serie => {
                    const option = document.createElement('option');
                    option.value = serie.id;
                    option.textContent = `${serie.numero_serie} - ${serie.nombre}`;
                    serieSelect.appendChild(option);
                });
            } else {
                console.error('Error en respuesta:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading series:', error);
            serieSelect.innerHTML = '<option value="">Error al cargar series</option>';
        });
};

window.loadSubseriesForRadicacion = function(serieId) {
    const subserieSelect = document.getElementById('subserie_id');

    if (!subserieSelect) {
        console.warn('Elemento subserie_id no encontrado');
        return;
    }

    // Limpiar select
    subserieSelect.innerHTML = '<option value="">Seleccionar subserie...</option>';

    if (!serieId) return;

    // Mostrar loading
    subserieSelect.innerHTML = '<option value="">Cargando subseries...</option>';

    // Obtener token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const token = csrfToken ? csrfToken.getAttribute('content') : '';

    fetch(`/admin/subseries/por-serie/${serieId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            // Limpiar loading
            subserieSelect.innerHTML = '<option value="">Seleccionar subserie...</option>';

            if (data.success) {
                data.subseries.forEach(subserie => {
                    const option = document.createElement('option');
                    option.value = subserie.id;
                    option.textContent = `${subserie.numero_subserie} - ${subserie.nombre}`;
                    subserieSelect.appendChild(option);
                });
            } else {
                console.error('Error en respuesta:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading subseries:', error);
            subserieSelect.innerHTML = '<option value="">Error al cargar subseries</option>';
        });
};

// Función para actualizar el campo oculto trd_id cuando se selecciona una subserie
window.updateTrdId = function(subserieId) {
    const trdIdInput = document.getElementById('trd_id');
    if (trdIdInput) {
        trdIdInput.value = subserieId;
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('TRD Selector loaded');
});

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        loadSeriesForRadicacion: window.loadSeriesForRadicacion,
        loadSubseriesForRadicacion: window.loadSubseriesForRadicacion
    };
}
