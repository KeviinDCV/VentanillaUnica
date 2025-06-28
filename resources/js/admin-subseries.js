// Gestión de Subseries - Admin

// Variables globales
let currentConfirmAction = null;

// Función para manejar los menús desplegables (disponible globalmente)
function toggleDropdown(dropdownId) {
    console.log('toggleDropdown called with ID:', dropdownId);

    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) {
        console.error('No se encontró el dropdown con ID:', dropdownId);
        return;
    }

    console.log('Dropdown found:', dropdown);
    const isHidden = dropdown.classList.contains('hidden');
    console.log('Is hidden:', isHidden);

    // Cerrar todos los dropdowns abiertos
    document.querySelectorAll('[id^="dropdown-subserie-"]').forEach(d => {
        if (d.id !== dropdownId) {
            d.classList.add('hidden');
            // Resetear estilos completamente
            d.style.position = '';
            d.style.top = '';
            d.style.bottom = '';
            d.style.left = '';
            d.style.right = '';
            d.style.transform = '';
            d.classList.remove('origin-bottom-right', 'mb-2', 'origin-top-right', 'mt-2');
        }
    });

    if (isHidden) {
        console.log('Showing dropdown');
        // Mostrar dropdown
        dropdown.classList.remove('hidden');

        // Posicionar el dropdown
        adjustDropdownPosition(dropdown);

        console.log('Dropdown should be visible now');
    } else {
        console.log('Hiding dropdown');
        // Ocultar dropdown
        dropdown.classList.add('hidden');
    }
}

// Función para ajustar la posición del dropdown
function adjustDropdownPosition(dropdown) {
    const button = dropdown.previousElementSibling;
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

// Función para cerrar todos los dropdowns
function closeAllDropdowns() {
    document.querySelectorAll('[id^="dropdown-subserie-"]').forEach(dropdown => {
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
}

// Hacer funciones disponibles globalmente inmediatamente
window.toggleDropdown = toggleDropdown;

document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de subseries
    if (!document.querySelector('[data-page="admin-subseries"]')) {
        return;
    }

    console.log('DOM loaded, initializing Subseries management...');

    // Configurar event listeners
    setupEventListeners();
    setupFilters();
    setupConfirmModalEventListeners();

    console.log('Subseries management initialized successfully');
});

function setupEventListeners() {
    // Botón crear subserie
    document.querySelector('[data-action="create-subserie"]')?.addEventListener('click', openCreateModal);

    // Usar delegación de eventos para botones dinámicos
    document.addEventListener('click', function(e) {
        // Botones editar
        if (e.target.closest('[data-action="edit-subserie"]')) {
            const button = e.target.closest('[data-action="edit-subserie"]');
            const subserieId = button.dataset.subserieId;
            const serieId = button.dataset.subserieSerie;
            const numero = button.dataset.subserieNumero;
            const nombre = button.dataset.subserieNombre;
            const descripcion = button.dataset.subserieDescripcion;
            const dias = button.dataset.subserieDias;
            const activa = button.dataset.subserieActiva === 'true';

            // Cerrar dropdown antes de abrir modal
            closeAllDropdowns();
            openEditModal(subserieId, serieId, numero, nombre, descripcion, dias, activa);
        }

        // Botones cambiar estado
        if (e.target.closest('[data-action="toggle-status"]')) {
            const button = e.target.closest('[data-action="toggle-status"]');
            const subserieId = button.dataset.subserieId;
            const nombre = button.dataset.subserieNombre;
            const activa = button.dataset.subserieActiva === 'true';

            // Cerrar dropdown antes de mostrar modal
            closeAllDropdowns();
            showStatusConfirmModal(subserieId, nombre, activa);
        }

        // Botones eliminar
        if (e.target.closest('[data-action="delete-subserie"]')) {
            const button = e.target.closest('[data-action="delete-subserie"]');
            const subserieId = button.dataset.subserieId;
            const nombre = button.dataset.subserieNombre;

            // Cerrar dropdown antes de mostrar modal
            closeAllDropdowns();
            showDeleteConfirmModal(subserieId, nombre);
        }
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

    // Mostrar modal con animación
    showModalWithAnimation(modal);

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

    // Mostrar modal con animación
    showModalWithAnimation(modal);

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
                            class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors min-w-[100px]">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="saveSubserie('${mode}', ${data?.id || 'null'})"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors min-w-[100px]">
                        ${isEdit ? 'Actualizar' : 'Crear'}
                    </button>
                </div>
            </div>
        </div>
    `;

    return modal;
}

function showModalWithAnimation(modal) {
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';

    // Animación de entrada
    const modalContent = modal.querySelector('.relative');
    modalContent.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modalContent.style.opacity = '1';
        modalContent.style.transform = 'scale(1) translateY(0)';

        // Focus en el primer campo después de la animación
        setTimeout(() => {
            const firstInput = modal.querySelector('input[type="text"], select');
            if (firstInput) {
                firstInput.focus();
            }
        }, 100);
    }, 10);
}

function closeSubserieModal() {
    const modal = document.getElementById('subserie-modal');
    if (modal) {
        const modalContent = modal.querySelector('.relative');

        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modal.remove();
            document.body.style.overflow = 'auto';
        }, 200);
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

// Función para mostrar modal de confirmación de cambio de estado
function showStatusConfirmModal(subserieId, nombre, isActive) {
    const accion = isActive ? 'desactivar' : 'activar';
    const accionCapital = isActive ? 'Desactivar' : 'Activar';
    const mensaje = `¿Está seguro que desea ${accion} la subserie "${nombre}"?`;

    showConfirmModal({
        title: `${accionCapital} Subserie`,
        message: mensaje,
        actionText: accionCapital,
        // Si está activa (true) y queremos desactivar → naranja
        // Si está inactiva (false) y queremos activar → verde
        actionClass: isActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
        iconClass: isActive ? 'bg-orange-100' : 'bg-green-100',
        iconColor: isActive ? 'text-orange-600' : 'text-green-600',
        onConfirm: function() {
            executeToggleSubserieStatus(subserieId);
        }
    });
}

function executeToggleSubserieStatus(subserieId) {
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

function showDeleteConfirmModal(subserieId, nombre) {
    const mensaje = `¿Está seguro que desea eliminar la subserie "${nombre}"?\n\nEsta acción no se puede deshacer.`;

    showConfirmModal({
        title: 'Eliminar Subserie',
        message: mensaje,
        actionText: 'Eliminar',
        actionClass: 'bg-red-600 hover:bg-red-700',
        iconClass: 'bg-red-100',
        iconColor: 'text-red-600',
        onConfirm: function() {
            executeDeleteSubserie(subserieId);
        }
    });
}

function executeDeleteSubserie(subserieId) {
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
        <td class="px-3 py-4 text-sm font-medium">
            <div class="relative inline-block text-left">
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                        onclick="toggleDropdown('dropdown-subserie-${subserie.id}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>

                <div id="dropdown-subserie-${subserie.id}"
                     class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                     style="z-index: 9999;"
                     data-dropdown-menu>
                    <div class="py-1" role="menu">
                        <!-- Editar -->
                        <button data-action="edit-subserie"
                                data-subserie-id="${subserie.id}"
                                data-subserie-serie="${subserie.serie_id}"
                                data-subserie-numero="${subserie.numero_subserie}"
                                data-subserie-nombre="${subserie.nombre}"
                                data-subserie-descripcion="${subserie.descripcion || ''}"
                                data-subserie-dias="${subserie.dias_respuesta || ''}"
                                data-subserie-activa="${subserie.activa ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>

                        <!-- Cambiar Estado -->
                        <button data-action="toggle-status"
                                data-subserie-id="${subserie.id}"
                                data-subserie-nombre="${subserie.nombre}"
                                data-subserie-activa="${subserie.activa ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            ${subserie.activa ? `
                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                </svg>
                                Desactivar
                            ` : `
                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activar
                            `}
                        </button>

                        <!-- Eliminar -->
                        <button data-action="delete-subserie"
                                data-subserie-id="${subserie.id}"
                                data-subserie-nombre="${subserie.nombre}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
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

// Funciones de modal de confirmación
function setupConfirmModalEventListeners() {
    // Verificar si el modal de confirmación existe
    const confirmModal = document.getElementById('confirmStatusModal');
    if (!confirmModal) return;

    // Cerrar modal al hacer clic fuera de él
    confirmModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !confirmModal.classList.contains('hidden')) {
            closeConfirmModal();
        }
    });

    // Botones de cerrar modal
    document.querySelectorAll('[data-action="close-confirm-modal"]').forEach(button => {
        button.addEventListener('click', closeConfirmModal);
    });

    // Botón de confirmación
    const actionButton = document.getElementById('confirmModalAction');
    if (actionButton) {
        actionButton.addEventListener('click', function() {
            if (currentConfirmAction) {
                currentConfirmAction();
            }
            closeConfirmModal();
        });
    }
}

function showConfirmModal(options) {
    const modal = document.getElementById('confirmStatusModal');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const actionButton = document.getElementById('confirmModalAction');
    const iconContainer = document.getElementById('confirmModalIcon');

    if (!modal || !title || !message || !actionButton || !iconContainer) {
        console.error('Modal elements not found, falling back to confirm()');
        if (confirm(options.message)) {
            options.onConfirm();
        }
        return;
    }

    // Configurar contenido del modal
    title.textContent = options.title;
    message.textContent = options.message;
    actionButton.textContent = options.actionText;

    // Limpiar clases existentes y aplicar nuevas clases CSS para el botón
    actionButton.className = '';
    actionButton.className = `px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}`;

    // Forzar la aplicación de estilos inline como respaldo
    actionButton.style.cssText = '';
    if (options.actionClass.includes('bg-orange-600')) {
        actionButton.style.backgroundColor = '#ea580c';
        actionButton.style.borderColor = '#ea580c';
        actionButton.style.color = '#ffffff';
    } else if (options.actionClass.includes('bg-green-600')) {
        actionButton.style.backgroundColor = '#16a34a';
        actionButton.style.borderColor = '#16a34a';
        actionButton.style.color = '#ffffff';
    } else if (options.actionClass.includes('bg-red-600')) {
        actionButton.style.backgroundColor = '#dc2626';
        actionButton.style.borderColor = '#dc2626';
        actionButton.style.color = '#ffffff';
    } else if (options.actionClass.includes('bg-yellow-600')) {
        actionButton.style.backgroundColor = '#ca8a04';
        actionButton.style.borderColor = '#ca8a04';
        actionButton.style.color = '#ffffff';
    }

    // Configurar icono
    iconContainer.className = `flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full ${options.iconClass}`;
    iconContainer.innerHTML = `
        <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
    `;

    // Guardar la función de confirmación globalmente
    currentConfirmAction = options.onConfirm;

    // Mostrar modal con animación
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Animación de entrada
    const modalContent = modal.querySelector('.relative');
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modalContent.style.opacity = '1';
        modalContent.style.transform = 'scale(1) translateY(0)';
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmStatusModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentConfirmAction = null;
    }, 200);
}

// Configurar event listeners para cerrar dropdowns
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        document.querySelectorAll('[id^="dropdown-subserie-"]').forEach(dropdown => {
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
    }
});

// Cerrar dropdown después de hacer clic en una acción
document.addEventListener('click', function(event) {
    if (event.target.closest('[id^="dropdown-subserie-"] button')) {
        // Pequeño delay para permitir que la acción se ejecute
        setTimeout(() => {
            document.querySelectorAll('[id^="dropdown-subserie-"]').forEach(dropdown => {
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
        }, 100);
    }
});

// Reposicionar dropdowns al redimensionar la ventana
window.addEventListener('resize', function() {
    document.querySelectorAll('[id^="dropdown-subserie-"]:not(.hidden)').forEach(dropdown => {
        adjustDropdownPosition(dropdown);
    });
});

// Cerrar dropdowns al hacer scroll
window.addEventListener('scroll', function() {
    document.querySelectorAll('[id^="dropdown-subserie-"]:not(.hidden)').forEach(dropdown => {
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

// Hacer funciones disponibles globalmente después de que estén definidas
window.closeSubserieModal = closeSubserieModal;
window.saveSubserie = saveSubserie;
