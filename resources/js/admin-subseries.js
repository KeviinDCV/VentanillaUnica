// Gestión de Subseries - Admin
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de subseries
    if (!document.querySelector('[data-page="admin-subseries"]')) {
        return;
    }

    console.log('DOM loaded, initializing Subseries management...');

    // Configurar event listeners
    setupEventListeners();
    setupFilters();

    console.log('Subseries management initialized successfully');
});

function setupEventListeners() {
    // Botón crear subserie
    document.querySelector('[data-action="create-subserie"]')?.addEventListener('click', openCreateModal);

    // Botones editar
    document.querySelectorAll('[data-action="edit-subserie"]').forEach(button => {
        button.addEventListener('click', function() {
            const subserieId = this.dataset.subserieId;
            const serieId = this.dataset.subserieSerie;
            const numero = this.dataset.subserieNumero;
            const nombre = this.dataset.subserieNombre;
            const descripcion = this.dataset.subserieDescripcion;
            const dias = this.dataset.subserieDias;
            const activa = this.dataset.subserieActiva === 'true';

            openEditModal(subserieId, serieId, numero, nombre, descripcion, dias, activa);
        });
    });

    // Botones toggle status
    document.querySelectorAll('[data-action="toggle-status"]').forEach(button => {
        button.addEventListener('click', function() {
            const subserieId = this.dataset.subserieId;
            const nombre = this.dataset.subserieNombre;
            toggleSubserieStatus(subserieId, nombre);
        });
    });

    // Botones eliminar
    document.querySelectorAll('[data-action="delete-subserie"]').forEach(button => {
        button.addEventListener('click', function() {
            const subserieId = this.dataset.subserieId;
            const nombre = this.dataset.subserieNombre;
            showDeleteConfirmModal(subserieId, nombre);
        });
    });
}

function setupFilters() {
    const filtroUnidad = document.getElementById('filtro-unidad');
    const filtroSerie = document.getElementById('filtro-serie');
    const buscarSubseries = document.getElementById('buscar-subseries');
    const limpiarFiltros = document.getElementById('limpiar-filtros');

    let searchTimeout;

    // Filtro por unidad administrativa
    filtroUnidad?.addEventListener('change', function() {
        const unidadId = this.value;
        loadSeriesByUnidad(unidadId);
        
        // Buscar con filtros actuales
        const serieId = filtroSerie.value;
        const termino = buscarSubseries.value.trim();
        buscarSubseriesFunc(termino, serieId, unidadId);
    });

    // Filtro por serie
    filtroSerie?.addEventListener('change', function() {
        const serieId = this.value;
        const unidadId = filtroUnidad.value;
        const termino = buscarSubseries.value.trim();
        buscarSubseriesFunc(termino, serieId, unidadId);
    });

    // Búsqueda en tiempo real
    buscarSubseries?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();
        const serieId = filtroSerie.value;
        const unidadId = filtroUnidad.value;
        
        searchTimeout = setTimeout(() => {
            if (termino.length >= 2 || termino.length === 0) {
                buscarSubseriesFunc(termino, serieId, unidadId);
            }
        }, 300);
    });

    // Limpiar filtros
    limpiarFiltros?.addEventListener('click', function() {
        filtroUnidad.value = '';
        filtroSerie.innerHTML = '<option value="">Todas las series</option>';
        buscarSubseries.value = '';
        showAllSubseries();
    });
}

function loadSeriesByUnidad(unidadId) {
    const filtroSerie = document.getElementById('filtro-serie');
    if (!filtroSerie) return;

    // Limpiar series
    filtroSerie.innerHTML = '<option value="">Todas las series</option>';

    if (!unidadId) return;

    fetch(`/admin/series/por-unidad/${unidadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.series.forEach(serie => {
                    const option = document.createElement('option');
                    option.value = serie.id;
                    option.textContent = `${serie.numero_serie} - ${serie.nombre}`;
                    filtroSerie.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading series:', error);
        });
}

function openCreateModal() {
    const modal = createSubserieModal('create');
    document.body.appendChild(modal);
    
    // Cargar unidades administrativas
    loadUnidadesAdministrativas('create_unidad_administrativa_id');
}

function openEditModal(subserieId, serieId, numero, nombre, descripcion, dias, activa) {
    const modal = createSubserieModal('edit', {
        id: subserieId,
        serie_id: serieId,
        numero_subserie: numero,
        nombre: nombre,
        descripcion: descripcion,
        dias_respuesta: dias,
        activa: activa
    });
    document.body.appendChild(modal);
    
    // Cargar unidades administrativas y series
    loadUnidadesAdministrativasForEdit('edit_unidad_administrativa_id', 'edit_serie_id', serieId);
}

function createSubserieModal(mode, data = null) {
    const isEdit = mode === 'edit';
    const title = isEdit ? 'Editar Subserie' : 'Nueva Subserie';
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.id = 'subserie-modal';
    
    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeSubserieModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="subserie-form" class="space-y-4">
                    <div>
                        <label for="${mode}_unidad_administrativa_id" class="block text-sm font-medium text-gray-700">Unidad Administrativa *</label>
                        <select id="${mode}_unidad_administrativa_id" 
                                name="unidad_administrativa_id" 
                                required
                                onchange="loadSeriesForModal('${mode}')"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue">
                            <option value="">Seleccionar unidad administrativa...</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="${mode}_serie_id" class="block text-sm font-medium text-gray-700">Serie *</label>
                        <select id="${mode}_serie_id" 
                                name="serie_id" 
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue">
                            <option value="">Seleccionar serie...</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="${mode}_numero_subserie" class="block text-sm font-medium text-gray-700">Número de Subserie *</label>
                        <input type="text" 
                               id="${mode}_numero_subserie" 
                               name="numero_subserie" 
                               value="${data?.numero_subserie || ''}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: 1"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Número único de la subserie dentro de la serie</p>
                    </div>
                    
                    <div>
                        <label for="${mode}_nombre" class="block text-sm font-medium text-gray-700">Nombre de la Subserie *</label>
                        <input type="text" 
                               id="${mode}_nombre" 
                               name="nombre" 
                               value="${data?.nombre || ''}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: Petición"
                               required>
                    </div>
                    
                    <div>
                        <label for="${mode}_descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="${mode}_descripcion" 
                                  name="descripcion" 
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                                  placeholder="Descripción de la subserie">${data?.descripcion || ''}</textarea>
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
                        <p class="mt-1 text-sm text-gray-500">Días específicos para esta subserie (opcional, hereda de la serie si no se define)</p>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="${mode}_activa" 
                               name="activa" 
                               ${data?.activa !== false ? 'checked' : ''}
                               class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                        <label for="${mode}_activa" class="ml-2 block text-sm text-gray-900">Subserie activa</label>
                    </div>
                </form>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeSubserieModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="button" 
                            onclick="saveSubserie('${mode}', ${data?.id || 'null'})"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        ${isEdit ? 'Actualizar' : 'Crear'}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    return modal;
}

function closeSubserieModal() {
    const modal = document.getElementById('subserie-modal');
    if (modal) {
        modal.remove();
    }
}

function loadUnidadesAdministrativas(selectId) {
    fetch('/admin/unidades-administrativas/para-select')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Seleccionar unidad administrativa...</option>';
                    
                    data.unidades.forEach(unidad => {
                        const option = document.createElement('option');
                        option.value = unidad.id;
                        option.textContent = `${unidad.codigo} - ${unidad.nombre}`;
                        select.appendChild(option);
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error loading unidades administrativas:', error);
        });
}

function loadUnidadesAdministrativasForEdit(unidadSelectId, serieSelectId, selectedSerieId) {
    fetch('/admin/unidades-administrativas/para-select')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const unidadSelect = document.getElementById(unidadSelectId);
                if (unidadSelect) {
                    unidadSelect.innerHTML = '<option value="">Seleccionar unidad administrativa...</option>';
                    
                    data.unidades.forEach(unidad => {
                        const option = document.createElement('option');
                        option.value = unidad.id;
                        option.textContent = `${unidad.codigo} - ${unidad.nombre}`;
                        unidadSelect.appendChild(option);
                    });

                    // Cargar todas las series y encontrar la unidad de la serie seleccionada
                    if (selectedSerieId) {
                        loadAllSeriesAndSelectUnidad(unidadSelectId, serieSelectId, selectedSerieId);
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error loading unidades administrativas:', error);
        });
}

function loadAllSeriesAndSelectUnidad(unidadSelectId, serieSelectId, selectedSerieId) {
    // Cargar todas las series para encontrar la unidad administrativa
    fetch('/admin/series/buscar')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const serie = data.series.find(s => s.id == selectedSerieId);
                if (serie) {
                    // Seleccionar la unidad administrativa
                    const unidadSelect = document.getElementById(unidadSelectId);
                    unidadSelect.value = serie.unidad_administrativa_id;
                    
                    // Cargar series de esa unidad
                    loadSeriesForModalWithSelected(serieSelectId, serie.unidad_administrativa_id, selectedSerieId);
                }
            }
        })
        .catch(error => {
            console.error('Error loading series:', error);
        });
}

function loadSeriesForModal(mode) {
    const unidadSelect = document.getElementById(`${mode}_unidad_administrativa_id`);
    const serieSelect = document.getElementById(`${mode}_serie_id`);
    
    if (!unidadSelect || !serieSelect) return;
    
    const unidadId = unidadSelect.value;
    
    // Limpiar series
    serieSelect.innerHTML = '<option value="">Seleccionar serie...</option>';
    
    if (!unidadId) return;
    
    loadSeriesForModalWithSelected(`${mode}_serie_id`, unidadId);
}

function loadSeriesForModalWithSelected(serieSelectId, unidadId, selectedSerieId = null) {
    fetch(`/admin/series/por-unidad/${unidadId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const serieSelect = document.getElementById(serieSelectId);
                if (serieSelect) {
                    serieSelect.innerHTML = '<option value="">Seleccionar serie...</option>';
                    
                    data.series.forEach(serie => {
                        const option = document.createElement('option');
                        option.value = serie.id;
                        option.textContent = `${serie.numero_serie} - ${serie.nombre}`;
                        if (selectedSerieId && serie.id == selectedSerieId) {
                            option.selected = true;
                        }
                        serieSelect.appendChild(option);
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error loading series:', error);
        });
}

// Hacer la función global para que pueda ser llamada desde el HTML
window.loadSeriesForModal = loadSeriesForModal;

function saveSubserie(mode, subserieId) {
    const form = document.getElementById('subserie-form');
    const formData = new FormData(form);
    
    const data = {
        serie_id: formData.get('serie_id'),
        numero_subserie: formData.get('numero_subserie'),
        nombre: formData.get('nombre'),
        descripcion: formData.get('descripcion'),
        dias_respuesta: formData.get('dias_respuesta'),
        activa: formData.has('activa')
    };
    
    const url = mode === 'edit' 
        ? `/admin/subseries/${subserieId}`
        : '/admin/subseries';
    
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
            closeSubserieModal();
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

function toggleSubserieStatus(subserieId, nombre) {
    if (confirm(`¿Está seguro de cambiar el estado de la subserie "${nombre}"?`)) {
        fetch(`/admin/subseries/${subserieId}/toggle-status`, {
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

function showDeleteConfirmModal(subserieId, nombre) {
    if (confirm(`¿Está seguro de eliminar la subserie "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        deleteSubserie(subserieId);
    }
}

function deleteSubserie(subserieId) {
    fetch(`/admin/subseries/${subserieId}`, {
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
            showErrorMessage(data.message || 'Error al eliminar la subserie');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error al procesar la solicitud');
    });
}

function buscarSubseriesFunc(termino, serieId, unidadId) {
    const params = new URLSearchParams();
    if (termino) params.append('termino', termino);
    if (serieId) params.append('serie_id', serieId);
    if (unidadId) params.append('unidad_id', unidadId);

    fetch(`/admin/subseries/buscar?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            updateSubseriesTable(data.subseries);
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
        });
}

function showAllSubseries() {
    const rows = document.querySelectorAll('.subserie-row');
    rows.forEach(row => {
        row.style.display = '';
    });
}

function updateSubseriesTable(subseries) {
    const tbody = document.getElementById('subseries-table-body');
    if (!tbody) return;

    tbody.innerHTML = '';

    subseries.forEach(subserie => {
        const row = createSubserieRow(subserie);
        tbody.appendChild(row);
    });

    // Re-agregar event listeners
    setupEventListeners();
}

function createSubserieRow(subserie) {
    const row = document.createElement('tr');
    row.className = 'subserie-row hover:bg-gray-50';
    
    let diasRespuesta = '';
    if (subserie.dias_respuesta) {
        diasRespuesta = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">${subserie.dias_respuesta} días</span>`;
    } else if (subserie.serie && subserie.serie.dias_respuesta) {
        diasRespuesta = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${subserie.serie.dias_respuesta} días (serie)</span>`;
    } else {
        diasRespuesta = '<span class="text-xs text-gray-400">No definido</span>';
    }
    
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div>
                <span class="text-sm font-medium text-gray-900">${subserie.serie.unidad_administrativa.codigo}-${subserie.serie.numero_serie}-${subserie.numero_subserie}</span>
                <p class="text-xs text-gray-500">${subserie.serie.unidad_administrativa.nombre.substring(0, 25)}${subserie.serie.unidad_administrativa.nombre.length > 25 ? '...' : ''}</p>
            </div>
        </td>
        <td class="px-6 py-4">
            <div>
                <span class="text-sm font-medium text-gray-900">${subserie.serie.numero_serie} ${subserie.serie.nombre}</span>
                ${subserie.serie.descripcion ? `<p class="text-xs text-gray-500">${subserie.serie.descripcion.substring(0, 30)}${subserie.serie.descripcion.length > 30 ? '...' : ''}</p>` : ''}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-medium text-gray-900">${subserie.numero_subserie}</span>
        </td>
        <td class="px-6 py-4">
            <span class="text-sm text-gray-900">${subserie.nombre}</span>
            ${subserie.descripcion ? `<p class="text-xs text-gray-500">${subserie.descripcion.substring(0, 40)}${subserie.descripcion.length > 40 ? '...' : ''}</p>` : ''}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            ${diasRespuesta}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${subserie.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${subserie.activa ? 'Activa' : 'Inactiva'}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
            <button data-action="edit-subserie"
                    data-subserie-id="${subserie.id}"
                    data-subserie-serie="${subserie.serie_id}"
                    data-subserie-numero="${subserie.numero_subserie}"
                    data-subserie-nombre="${subserie.nombre}"
                    data-subserie-descripcion="${subserie.descripcion || ''}"
                    data-subserie-dias="${subserie.dias_respuesta || ''}"
                    data-subserie-activa="${subserie.activa ? 'true' : 'false'}"
                    class="text-indigo-600 hover:text-indigo-900">
                Editar
            </button>
            <button data-action="toggle-status"
                    data-subserie-id="${subserie.id}"
                    data-subserie-nombre="${subserie.nombre}"
                    class="text-yellow-600 hover:text-yellow-900">
                ${subserie.activa ? 'Desactivar' : 'Activar'}
            </button>
            <button data-action="delete-subserie"
                    data-subserie-id="${subserie.id}"
                    data-subserie-nombre="${subserie.nombre}"
                    class="text-red-600 hover:text-red-900">
                Eliminar
            </button>
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
