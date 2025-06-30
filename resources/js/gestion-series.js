// Gestión de Series - Módulo de Gestión
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de series de gestión
    if (!document.querySelector('[data-page="gestion-series"]')) {
        return;
    }

    console.log('DOM loaded, initializing Series management...');

    // Configurar event listeners
    setupEventListeners();
    setupFilters();
    setupConfirmModalEventListeners();

    console.log('Series management initialized successfully');
});

function setupEventListeners() {
    // Botón crear serie
    document.querySelector('[data-action="create-serie"]')?.addEventListener('click', openCreateModal);

    // Botones de editar serie
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

    // Botones de cambiar estado
    document.querySelectorAll('[data-action="toggle-status"]').forEach(button => {
        button.addEventListener('click', function() {
            const serieId = this.dataset.serieId;
            const activa = this.dataset.serieActiva === 'true';
            toggleSerieStatus(serieId, activa);
        });
    });

    // Botones de eliminar
    document.querySelectorAll('[data-action="delete-serie"]').forEach(button => {
        button.addEventListener('click', function() {
            const serieId = this.dataset.serieId;
            const nombre = this.dataset.serieNombre;
            showDeleteConfirmation(serieId, nombre);
        });
    });

    // Dropdowns
    document.querySelectorAll('[data-dropdown-toggle]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdownId = this.getAttribute('data-dropdown-toggle');
            toggleDropdown(dropdownId);
        });
    });

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function() {
        document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
            menu.classList.add('hidden');
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
        const termino = buscarSeries?.value?.trim() || '';
        buscarSeriesFunc(termino, unidadId);
    });

    // Búsqueda en tiempo real
    buscarSeries?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();
        const unidadId = filtroUnidad?.value || '';

        searchTimeout = setTimeout(() => {
            if (termino.length >= 2 || termino.length === 0) {
                buscarSeriesFunc(termino, unidadId);
            }
        }, 300);
    });

    // Limpiar filtros
    limpiarFiltros?.addEventListener('click', function() {
        if (buscarSeries) buscarSeries.value = '';
        if (filtroUnidad) filtroUnidad.value = '';
        showAllSeries();
    });
}

function buscarSeriesFunc(termino, unidadId) {
    const params = new URLSearchParams();
    if (termino) params.append('termino', termino);
    if (unidadId) params.append('unidad_id', unidadId);

    fetch(`/gestion/series/buscar?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            updateSeriesTable(data.series);
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
        });
}

function updateSeriesTable(series) {
    const tbody = document.getElementById('series-table-body');
    if (!tbody) return;

    // Limpiar tabla actual
    tbody.innerHTML = '';

    // Agregar series filtradas
    series.forEach(serie => {
        const row = createSerieRow(serie);
        tbody.appendChild(row);
    });

    // Re-agregar event listeners
    setupEventListeners();
}

function createSerieRow(serie) {
    const row = document.createElement('tr');
    row.className = 'serie-row';

    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div>
                <div class="text-sm font-medium text-gray-900">${serie.unidad_administrativa.codigo}-${serie.numero_serie}</div>
                <div class="text-sm text-gray-500">${serie.unidad_administrativa.nombre.substring(0, 25)}${serie.unidad_administrativa.nombre.length > 25 ? '...' : ''}</div>
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="text-sm font-medium text-gray-900">${serie.nombre}</span>
        </td>
        <td class="px-6 py-4">
            <span class="text-sm text-gray-600">${serie.descripcion ? (serie.descripcion.substring(0, 40) + (serie.descripcion.length > 40 ? '...' : '')) : 'Sin descripción'}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm text-gray-900">${serie.dias_respuesta ? serie.dias_respuesta + ' días' : 'No definido'}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="space-y-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${serie.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${serie.activa ? 'Activa' : 'Inactiva'}
                </span>
                <div class="text-xs text-gray-500">
                    ${serie.subseries_count || 0} subseries
                </div>
            </div>
        </td>
        <td class="px-3 py-4 text-sm font-medium">
            <div class="relative inline-block text-left">
                <button data-dropdown-toggle="dropdown-serie-${serie.id}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>

                <div id="dropdown-serie-${serie.id}"
                     class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                     style="z-index: 9999;"
                     data-dropdown-menu>
                    <div class="py-1" role="menu">
                        <button data-action="edit-serie"
                                data-serie-id="${serie.id}"
                                data-serie-unidad="${serie.unidad_administrativa_id}"
                                data-serie-numero="${serie.numero_serie}"
                                data-serie-nombre="${serie.nombre}"
                                data-serie-descripcion="${serie.descripcion || ''}"
                                data-serie-dias="${serie.dias_respuesta || ''}"
                                data-serie-activa="${serie.activa ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>

                        <!-- Activar/Desactivar -->
                        <button data-action="toggle-status"
                                data-serie-id="${serie.id}"
                                data-serie-nombre="${serie.nombre}"
                                data-serie-activa="${serie.activa ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm ${serie.activa ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50'} flex items-center">
                            ${serie.activa ?
                                `<svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Desactivar` :
                                `<svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activar`
                            }
                        </button>

                        <!-- Separador -->
                        <div class="border-t border-gray-100"></div>

                        <!-- Eliminar -->
                        ${(serie.subseries_count || 0) > 0 ?
                            `<div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                 title="No se puede eliminar: tiene ${serie.subseries_count} subserie(s) asociada(s)">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </div>` :
                            `<button data-action="delete-serie"
                                    data-serie-id="${serie.id}"
                                    data-serie-nombre="${serie.nombre}"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>`
                        }
                    </div>
                </div>
            </div>
        </td>
    `;

    return row;
}

function showAllSeries() {
    const rows = document.querySelectorAll('.serie-row');
    rows.forEach(row => {
        row.style.display = '';
    });
}

// Función para ajustar la posición del dropdown
function adjustDropdownPosition(dropdown) {
    const button = document.querySelector(`[data-dropdown-toggle="${dropdown.id}"]`);
    if (!button) return;

    const buttonRect = button.getBoundingClientRect();
    const dropdownRect = dropdown.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    const viewportWidth = window.innerWidth;

    // Resetear clases y estilos
    dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
    dropdown.style.position = 'fixed';
    dropdown.style.zIndex = '9999';

    // Determinar si hay espacio suficiente abajo
    const spaceBelow = viewportHeight - buttonRect.bottom;
    const spaceAbove = buttonRect.top;
    const dropdownHeight = 200; // Altura estimada del dropdown

    if (spaceBelow >= dropdownHeight || spaceBelow >= spaceAbove) {
        // Mostrar abajo
        dropdown.style.top = (buttonRect.bottom + 5) + 'px';
        dropdown.style.bottom = '';
        dropdown.classList.add('origin-top-right', 'mt-2');
    } else {
        // Mostrar arriba
        dropdown.style.bottom = (viewportHeight - buttonRect.top + 5) + 'px';
        dropdown.style.top = '';
        dropdown.classList.add('origin-bottom-right', 'mb-2');
    }

    // Determinar posición horizontal
    const spaceRight = viewportWidth - buttonRect.right;
    const dropdownWidth = 192; // w-48 = 12rem = 192px

    if (spaceRight >= dropdownWidth) {
        // Alinear a la derecha del botón
        dropdown.style.left = buttonRect.left + 'px';
        dropdown.style.right = '';
    } else {
        // Alinear a la izquierda del botón
        dropdown.style.right = (viewportWidth - buttonRect.right) + 'px';
        dropdown.style.left = '';
    }
}

function toggleDropdown(dropdownId) {
    // Cerrar todos los otros dropdowns
    document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
        if (menu.id !== dropdownId) {
            menu.classList.add('hidden');
            // Resetear estilos
            menu.style.position = '';
            menu.style.top = '';
            menu.style.bottom = '';
            menu.style.left = '';
            menu.style.right = '';
            menu.style.transform = '';
            menu.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
        }
    });

    // Toggle el dropdown actual
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        const isHidden = dropdown.classList.contains('hidden');

        if (isHidden) {
            dropdown.classList.remove('hidden');
            // Ajustar posición del dropdown
            adjustDropdownPosition(dropdown);
        } else {
            dropdown.classList.add('hidden');
            // Resetear estilos al cerrar
            dropdown.style.position = '';
            dropdown.style.top = '';
            dropdown.style.bottom = '';
            dropdown.style.left = '';
            dropdown.style.right = '';
            dropdown.style.transform = '';
            dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
        }
    }
}

function openCreateModal() {
    const modal = createSerieModal('create');
    document.body.appendChild(modal);
    showModalWithAnimation(modal);
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
    showModalWithAnimation(modal);
    loadUnidadesAdministrativas('edit_unidad_administrativa_id', unidadId);
}

function createSerieModal(mode, data = {}) {
    const isEdit = mode === 'edit';
    const modalId = `${mode}-serie-modal`;

    const modal = document.createElement('div');
    modal.id = modalId;
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 opacity-0 transition-opacity duration-300';

    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white transform scale-95 transition-transform duration-300">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        ${isEdit ? 'Editar Serie' : 'Crear Nueva Serie'}
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('${modalId}')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="${mode}-modal-errors" class="hidden mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                    <ul id="${mode}-modal-errors-list"></ul>
                </div>

                <form id="serie-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="${mode}_unidad_administrativa_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Unidad Administrativa *
                            </label>
                            <select id="${mode}_unidad_administrativa_id"
                                    name="unidad_administrativa_id"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uniradical-blue focus:border-uniradical-blue">
                                <option value="">Seleccionar unidad administrativa...</option>
                            </select>
                        </div>

                        <div>
                            <label for="${mode}_numero_serie" class="block text-sm font-medium text-gray-700 mb-1">
                                Número de Serie *
                            </label>
                            <input type="text"
                                   id="${mode}_numero_serie"
                                   name="numero_serie"
                                   value="${data.numero_serie || ''}"
                                   maxlength="10"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uniradical-blue focus:border-uniradical-blue">
                        </div>
                    </div>

                    <div>
                        <label for="${mode}_nombre" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Serie *
                        </label>
                        <input type="text"
                               id="${mode}_nombre"
                               name="nombre"
                               value="${data.nombre || ''}"
                               maxlength="255"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uniradical-blue focus:border-uniradical-blue">
                    </div>

                    <div>
                        <label for="${mode}_descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea id="${mode}_descripcion"
                                  name="descripcion"
                                  rows="3"
                                  maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uniradical-blue focus:border-uniradical-blue">${data.descripcion || ''}</textarea>
                    </div>

                    <div>
                        <label for="${mode}_dias_respuesta" class="block text-sm font-medium text-gray-700 mb-1">
                            Días de Respuesta
                        </label>
                        <input type="number"
                               id="${mode}_dias_respuesta"
                               name="dias_respuesta"
                               value="${data.dias_respuesta || ''}"
                               min="1"
                               max="365"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-uniradical-blue focus:border-uniradical-blue">
                        <p class="mt-1 text-sm text-gray-500">Días límite para respuesta según TRD o ley</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="${mode}_activa"
                               name="activa"
                               ${data.activa !== false ? 'checked' : ''}
                               class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                        <label for="${mode}_activa" class="ml-2 block text-sm text-gray-900">
                            Serie activa
                        </label>
                    </div>
                </form>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button"
                            onclick="closeModal('${modalId}')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="saveSerie('${mode}', ${isEdit ? data.id || 'null' : 'null'})"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        ${isEdit ? 'Actualizar' : 'Crear'} Serie
                    </button>
                </div>
            </div>
        </div>
    `;

    return modal;
}

function loadUnidadesAdministrativas(selectId, selectedId = null) {
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
        ? `/gestion/series/${serieId}`
        : '/gestion/series';

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
            closeModal(`${mode}-serie-modal`);
            location.reload(); // Recargar la página para mostrar los cambios
        } else {
            showModalErrors(mode, data.errors || { general: [data.message] });
        }
    })
    .catch(error => {
        console.error('Error saving serie:', error);
        showModalErrors(mode, { general: ['Error al guardar la serie'] });
    });
}

function toggleSerieStatus(serieId, currentStatus) {
    const action = currentStatus ? 'desactivar' : 'activar';

    if (confirm(`¿Está seguro de que desea ${action} esta serie?`)) {
        fetch(`/gestion/series/${serieId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cambiar el estado de la serie');
            }
        })
        .catch(error => {
            console.error('Error toggling status:', error);
            alert('Error al cambiar el estado de la serie');
        });
    }
}

function showDeleteConfirmation(serieId, nombre) {
    const modal = createConfirmModal(
        'delete-serie-confirm',
        'Confirmar Eliminación',
        `¿Está seguro de que desea eliminar la serie "${nombre}"?`,
        'Esta acción no se puede deshacer.',
        'Eliminar',
        'bg-red-600 hover:bg-red-700',
        () => deleteSerie(serieId)
    );

    document.body.appendChild(modal);
    showModalWithAnimation(modal);
}

function deleteSerie(serieId) {
    fetch(`/gestion/series/${serieId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        closeModal('delete-serie-confirm');
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error al eliminar la serie');
        }
    })
    .catch(error => {
        console.error('Error deleting serie:', error);
        closeModal('delete-serie-confirm');
        alert('Error al eliminar la serie');
    });
}

function createConfirmModal(id, title, message, description, actionText, actionClass, onConfirm) {
    const modal = document.createElement('div');
    modal.id = id;
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 opacity-0 transition-opacity duration-300';

    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform scale-95 transition-transform duration-300">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">${title}</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-2">${message}</p>
                    ${description ? `<p class="text-xs text-gray-400">${description}</p>` : ''}
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button type="button"
                            onclick="closeModal('${id}')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="confirmAction('${id}')"
                            class="px-4 py-2 text-white rounded-md transition duration-200 ${actionClass}">
                        ${actionText}
                    </button>
                </div>
            </div>
        </div>
    `;

    // Guardar la función de confirmación
    modal.confirmAction = onConfirm;

    return modal;
}

function confirmAction(modalId) {
    const modal = document.getElementById(modalId);
    if (modal && modal.confirmAction) {
        modal.confirmAction();
    }
}

function setupConfirmModalEventListeners() {
    // Esta función se llama desde el setup principal
}

// Reposicionar dropdowns al redimensionar la ventana
window.addEventListener('resize', function() {
    document.querySelectorAll('[data-dropdown-menu]:not(.hidden)').forEach(dropdown => {
        adjustDropdownPosition(dropdown);
    });
});

// Cerrar dropdowns al hacer scroll
window.addEventListener('scroll', function() {
    document.querySelectorAll('[data-dropdown-menu]:not(.hidden)').forEach(dropdown => {
        dropdown.classList.add('hidden');
        // Resetear estilos
        dropdown.style.position = '';
        dropdown.style.top = '';
        dropdown.style.bottom = '';
        dropdown.style.left = '';
        dropdown.style.right = '';
        dropdown.style.transform = '';
        dropdown.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
    });
});

function showModalWithAnimation(modal) {
    // Mostrar modal con animación
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        const content = modal.querySelector('div > div');
        if (content) {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Animación de salida
        modal.classList.add('opacity-0');
        const content = modal.querySelector('div > div');
        if (content) {
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
        }

        // Remover modal después de la animación
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

function showModalErrors(mode, errors) {
    const errorsContainer = document.getElementById(`${mode}-modal-errors`);
    const errorsList = document.getElementById(`${mode}-modal-errors-list`);

    if (errorsContainer && errorsList) {
        errorsList.innerHTML = '';

        Object.keys(errors).forEach(field => {
            if (Array.isArray(errors[field])) {
                errors[field].forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorsList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = errors[field];
                errorsList.appendChild(li);
            }
        });

        errorsContainer.classList.remove('hidden');
    }
}

// Hacer funciones disponibles globalmente
window.toggleDropdown = toggleDropdown;
window.closeModal = closeModal;
window.saveSerie = saveSerie;
window.confirmAction = confirmAction;
