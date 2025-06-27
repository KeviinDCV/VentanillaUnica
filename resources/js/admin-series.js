// Gestión de Series - Admin
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de series
    if (!document.querySelector('[data-page="admin-series"]')) {
        return;
    }

    console.log('DOM loaded, initializing Series management...');

    // Configurar event listeners
    setupEventListeners();
    setupFilters();

    console.log('Series management initialized successfully');
});

function setupEventListeners() {
    // Botón crear serie
    document.querySelector('[data-action="create-serie"]')?.addEventListener('click', openCreateModal);

    // Botones editar
    document.querySelectorAll('[data-action="edit-serie"]').forEach(button => {
        button.addEventListener('click', function() {
            const serieId = this.dataset.serieId;
            const unidadId = this.dataset.serieUnidad;
            const numero = this.dataset.serieNumero;
            const nombre = this.dataset.serieNombre;
            const descripcion = this.dataset.serieDescripcion;
            const dias = this.dataset.serieDias;
            const activa = this.dataset.serieActiva === 'true';

            openEditModal(serieId, unidadId, numero, nombre, descripcion, dias, activa);
        });
    });

    // Botones toggle status
    document.querySelectorAll('[data-action="toggle-status"]').forEach(button => {
        button.addEventListener('click', function() {
            const serieId = this.dataset.serieId;
            const nombre = this.dataset.serieNombre;
            toggleSerieStatus(serieId, nombre);
        });
    });

    // Botones eliminar
    document.querySelectorAll('[data-action="delete-serie"]').forEach(button => {
        button.addEventListener('click', function() {
            const serieId = this.dataset.serieId;
            const nombre = this.dataset.serieNombre;
            showDeleteConfirmModal(serieId, nombre);
        });
    });
}

function setupFilters() {
    const filtroUnidad = document.getElementById('filtro-unidad');
    const buscarSeries = document.getElementById('buscar-series');
    const limpiarFiltros = document.getElementById('limpiar-filtros');

    let searchTimeout;

    // Filtro por unidad administrativa
    filtroUnidad?.addEventListener('change', function() {
        const unidadId = this.value;
        const termino = buscarSeries.value.trim();
        buscarSeriesFunc(termino, unidadId);
    });

    // Búsqueda en tiempo real
    buscarSeries?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();
        const unidadId = filtroUnidad.value;

        searchTimeout = setTimeout(() => {
            if (termino.length >= 2 || termino.length === 0) {
                buscarSeriesFunc(termino, unidadId);
            }
        }, 300);
    });

    // Limpiar filtros
    limpiarFiltros?.addEventListener('click', function() {
        filtroUnidad.value = '';
        buscarSeries.value = '';
        showAllSeries();
    });
}

function openCreateModal() {
    const modal = createSerieModal('create');
    document.body.appendChild(modal);

    // Cargar unidades administrativas
    loadUnidadesAdministrativas('create_unidad_administrativa_id');
}

function openEditModal(serieId, unidadId, numero, nombre, descripcion, dias, activa) {
    const modal = createSerieModal('edit', {
        id: serieId,
        unidad_administrativa_id: unidadId,
        numero_serie: numero,
        nombre: nombre,
        descripcion: descripcion,
        dias_respuesta: dias,
        activa: activa
    });
    document.body.appendChild(modal);

    // Cargar unidades administrativas y seleccionar la actual
    loadUnidadesAdministrativas('edit_unidad_administrativa_id', unidadId);
}

function createSerieModal(mode, data = null) {
    const isEdit = mode === 'edit';
    const title = isEdit ? 'Editar Serie' : 'Nueva Serie';

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.id = 'serie-modal';

    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeSerieModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="serie-form" class="space-y-4">
                    <div>
                        <label for="${mode}_unidad_administrativa_id" class="block text-sm font-medium text-gray-700">Unidad Administrativa *</label>
                        <select id="${mode}_unidad_administrativa_id"
                                name="unidad_administrativa_id"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue">
                            <option value="">Seleccionar unidad administrativa...</option>
                        </select>
                    </div>

                    <div>
                        <label for="${mode}_numero_serie" class="block text-sm font-medium text-gray-700">Número de Serie *</label>
                        <input type="text"
                               id="${mode}_numero_serie"
                               name="numero_serie"
                               value="${data?.numero_serie || ''}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: 11"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Número único de la serie dentro de la unidad administrativa</p>
                    </div>

                    <div>
                        <label for="${mode}_nombre" class="block text-sm font-medium text-gray-700">Nombre de la Serie *</label>
                        <input type="text"
                               id="${mode}_nombre"
                               name="nombre"
                               value="${data?.nombre || ''}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: PQRS"
                               required>
                    </div>

                    <div>
                        <label for="${mode}_descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="${mode}_descripcion"
                                  name="descripcion"
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                                  placeholder="Descripción de la serie">${data?.descripcion || ''}</textarea>
                    </div>

                    <div>
                        <label for="${mode}_dias_respuesta" class="block text-sm font-medium text-gray-700">Días de Respuesta</label>
                        <input type="number"
                               id="${mode}_dias_respuesta"
                               name="dias_respuesta"
                               value="${data?.dias_respuesta || ''}"
                               min="1"
                               max="365"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: 15">
                        <p class="mt-1 text-sm text-gray-500">Días límite para respuesta según TRD o ley</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="${mode}_activa"
                               name="activa"
                               ${data?.activa !== false ? 'checked' : ''}
                               class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                        <label for="${mode}_activa" class="ml-2 block text-sm text-gray-900">Serie activa</label>
                    </div>
                </form>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            onclick="closeSerieModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="saveSerie('${mode}', ${data?.id || 'null'})"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        ${isEdit ? 'Actualizar' : 'Crear'}
                    </button>
                </div>
            </div>
        </div>
    `;

    return modal;
}

function closeSerieModal() {
    const modal = document.getElementById('serie-modal');
    if (modal) {
        modal.remove();
    }
}

function loadUnidadesAdministrativas(selectId, selectedId = null) {
    fetch('/admin/unidades-administrativas/para-select')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById(selectId);
                if (select) {
                    // Limpiar opciones existentes excepto la primera
                    select.innerHTML = '<option value="">Seleccionar unidad administrativa...</option>';

                    // Agregar opciones
                    data.unidades.forEach(unidad => {
                        const option = document.createElement('option');
                        option.value = unidad.id;
                        option.textContent = `${unidad.codigo} - ${unidad.nombre}`;
                        if (selectedId && unidad.id == selectedId) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error loading unidades administrativas:', error);
        });
}

function saveSerie(mode, serieId) {
    const form = document.getElementById('serie-form');
    const formData = new FormData(form);

    const data = {
        unidad_administrativa_id: formData.get('unidad_administrativa_id'),
        numero_serie: formData.get('numero_serie'),
        nombre: formData.get('nombre'),
        descripcion: formData.get('descripcion'),
        dias_respuesta: formData.get('dias_respuesta'),
        activa: formData.has('activa')
    };

    const url = mode === 'edit'
        ? `/admin/series/${serieId}`
        : '/admin/series';

    const method = mode === 'edit' ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeSerieModal();
            showSuccessMessage(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error al procesar la solicitud');
    });
}

function toggleSerieStatus(serieId, nombre) {
    if (confirm(`¿Está seguro de cambiar el estado de la serie "${nombre}"?`)) {
        fetch(`/admin/series/${serieId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showErrorMessage('Error al cambiar el estado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Error al procesar la solicitud');
        });
    }
}

function showDeleteConfirmModal(serieId, nombre) {
    if (confirm(`¿Está seguro de eliminar la serie "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        deleteSerie(serieId);
    }
}

function deleteSerie(serieId) {
    fetch(`/admin/series/${serieId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrorMessage(data.message || 'Error al eliminar la serie');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error al procesar la solicitud');
    });
}

function buscarSeriesFunc(termino, unidadId) {
    const params = new URLSearchParams();
    if (termino) params.append('termino', termino);
    if (unidadId) params.append('unidad_id', unidadId);

    fetch(`/admin/series/buscar?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            updateSeriesTable(data.series);
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
        });
}

function showAllSeries() {
    const rows = document.querySelectorAll('.serie-row');
    rows.forEach(row => {
        row.style.display = '';
    });
}

function updateSeriesTable(series) {
    const tbody = document.getElementById('series-table-body');
    if (!tbody) return;

    tbody.innerHTML = '';

    series.forEach(serie => {
        const row = createSerieRow(serie);
        tbody.appendChild(row);
    });

    // Re-agregar event listeners
    setupEventListeners();
}

function createSerieRow(serie) {
    const row = document.createElement('tr');
    row.className = 'serie-row hover:bg-gray-50';

    const diasRespuesta = serie.dias_respuesta
        ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">${serie.dias_respuesta} días</span>`
        : '<span class="text-xs text-gray-400">No definido</span>';

    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div>
                <span class="text-sm font-medium text-gray-900">${serie.unidad_administrativa.codigo}</span>
                <p class="text-xs text-gray-500">${serie.unidad_administrativa.nombre.substring(0, 30)}${serie.unidad_administrativa.nombre.length > 30 ? '...' : ''}</p>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-medium text-gray-900">${serie.numero_serie}</span>
        </td>
        <td class="px-6 py-4">
            <span class="text-sm text-gray-900">${serie.nombre}</span>
            ${serie.descripcion ? `<p class="text-xs text-gray-500">${serie.descripcion.substring(0, 50)}${serie.descripcion.length > 50 ? '...' : ''}</p>` : ''}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            ${diasRespuesta}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                ${serie.subseries_count || 0} subseries
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${serie.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${serie.activa ? 'Activa' : 'Inactiva'}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
            <button data-action="edit-serie"
                    data-serie-id="${serie.id}"
                    data-serie-unidad="${serie.unidad_administrativa_id}"
                    data-serie-numero="${serie.numero_serie}"
                    data-serie-nombre="${serie.nombre}"
                    data-serie-descripcion="${serie.descripcion || ''}"
                    data-serie-dias="${serie.dias_respuesta || ''}"
                    data-serie-activa="${serie.activa ? 'true' : 'false'}"
                    class="text-indigo-600 hover:text-indigo-900">
                Editar
            </button>
            <button data-action="toggle-status"
                    data-serie-id="${serie.id}"
                    data-serie-nombre="${serie.nombre}"
                    class="text-yellow-600 hover:text-yellow-900">
                ${serie.activa ? 'Desactivar' : 'Activar'}
            </button>
            ${(serie.subseries_count || 0) === 0 ? `
            <button data-action="delete-serie"
                    data-serie-id="${serie.id}"
                    data-serie-nombre="${serie.nombre}"
                    class="text-red-600 hover:text-red-900">
                Eliminar
            </button>
            ` : ''}
        </td>
    `;

    return row;
}

function showSuccessMessage(message) {
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'success',
            title: 'Éxito',
            message: message
        });
    } else {
        alert(message);
    }
}

function showErrorMessage(message) {
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'error',
            title: 'Error',
            message: message
        });
    } else {
        alert(message);
    }
}

function showErrors(errors) {
    let errorMessage = 'Errores encontrados:\n';
    for (const field in errors) {
        errorMessage += `- ${errors[field].join(', ')}\n`;
    }
    showErrorMessage(errorMessage);
}
