// Gestión de Series - Admin

// Funciones disponibles globalmente para onclick
function closeSerieModal() {
    const modal = document.getElementById('serie-modal');
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
    document.querySelectorAll('[id^="dropdown-serie-"]').forEach(d => {
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
        const button = dropdown.previousElementSibling;
        if (button) {
            const buttonRect = button.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.top = (buttonRect.bottom + 5) + 'px';
            dropdown.style.left = (buttonRect.left) + 'px';
            dropdown.style.zIndex = '9999';
        }

        console.log('Dropdown should be visible now');
    } else {
        console.log('Hiding dropdown');
        // Ocultar dropdown
        dropdown.classList.add('hidden');
    }
}

// Hacer funciones disponibles globalmente inmediatamente
window.closeSerieModal = closeSerieModal;
window.saveSerie = saveSerie;
window.toggleDropdown = toggleDropdown;

document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de series
    if (!document.querySelector('[data-page="admin-series"]')) {
        return;
    }

    console.log('DOM loaded, initializing Series management...');

    // Configurar event listeners
    setupEventListeners();
    setupFilters();

    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            closeAllDropdowns();
        }
    });

    // Event listeners para el modal de confirmación
    setupConfirmModalEventListeners();

    console.log('Series management initialized successfully');
});

// Función para cerrar todos los dropdowns
function closeAllDropdowns() {
    document.querySelectorAll('[id^="dropdown-serie-"]').forEach(dropdown => {
        dropdown.classList.add('hidden');
    });
}

// Variable global para almacenar la función de confirmación
let currentConfirmAction = null;

// Función para mostrar modal de confirmación
function showConfirmModal(options) {
    const modal = document.getElementById('confirmStatusModal');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const actionButton = document.getElementById('confirmModalAction');
    const iconContainer = document.getElementById('confirmModalIcon');

    // Configurar contenido del modal
    title.textContent = options.title;
    message.textContent = options.message;
    actionButton.textContent = options.actionText;

    // Limpiar clases existentes y aplicar nuevas clases CSS para el botón
    actionButton.className = '';
    actionButton.className = `px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}`;

    // Forzar la aplicación de estilos
    actionButton.style.cssText = '';
    if (options.actionClass.includes('bg-orange-600')) {
        actionButton.style.backgroundColor = '#ea580c';
        actionButton.style.borderColor = '#ea580c';
    } else if (options.actionClass.includes('bg-green-600')) {
        actionButton.style.backgroundColor = '#16a34a';
        actionButton.style.borderColor = '#16a34a';
    } else if (options.actionClass.includes('bg-red-600')) {
        actionButton.style.backgroundColor = '#dc2626';
        actionButton.style.borderColor = '#dc2626';
    }

    // Configurar icono
    iconContainer.className = `flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full ${options.iconClass}`;
    iconContainer.innerHTML = `
        <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    `;

    // Guardar la función de confirmación
    currentConfirmAction = options.onConfirm;

    // Mostrar modal
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

// Función para cerrar modal de confirmación
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

// Función para configurar event listeners del modal de confirmación
function setupConfirmModalEventListeners() {
    // Botones de cerrar modal de confirmación
    document.querySelectorAll('[data-action="close-confirm-modal"]').forEach(button => {
        button.addEventListener('click', closeConfirmModal);
    });

    // Botón de confirmación
    document.getElementById('confirmModalAction').addEventListener('click', function() {
        if (currentConfirmAction) {
            currentConfirmAction();
        }
        closeConfirmModal();
    });

    // Cerrar modal con Escape
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('confirmStatusModal');
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeConfirmModal();
        }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('confirmStatusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });
}

function setupEventListeners() {
    // Botón crear serie
    document.querySelector('[data-action="create-serie"]')?.addEventListener('click', openCreateModal);

    // Usar delegación de eventos para botones dinámicos
    document.addEventListener('click', function(e) {
        // Botones editar
        if (e.target.closest('[data-action="edit-serie"]')) {
            const button = e.target.closest('[data-action="edit-serie"]');
            const serieId = button.dataset.serieId;
            const unidadId = button.dataset.serieUnidad;
            const numero = button.dataset.serieNumero;
            const nombre = button.dataset.serieNombre;
            const descripcion = button.dataset.serieDescripcion;
            const dias = button.dataset.serieDias;
            const activa = button.dataset.serieActiva === 'true';

            // Cerrar dropdown antes de abrir modal
            closeAllDropdowns();
            openEditModal(serieId, unidadId, numero, nombre, descripcion, dias, activa);
        }

        // Botones toggle status
        if (e.target.closest('[data-action="toggle-status"]')) {
            const button = e.target.closest('[data-action="toggle-status"]');
            const serieId = button.dataset.serieId;
            const nombre = button.dataset.serieNombre;

            // Cerrar dropdown antes de ejecutar acción
            closeAllDropdowns();
            toggleSerieStatus(serieId, nombre);
        }

        // Botones eliminar
        if (e.target.closest('[data-action="delete-serie"]')) {
            const button = e.target.closest('[data-action="delete-serie"]');
            const serieId = button.dataset.serieId;
            const nombre = button.dataset.serieNombre;

            // Cerrar dropdown antes de ejecutar acción
            closeAllDropdowns();
            showDeleteConfirmModal(serieId, nombre);
        }
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

    // Mostrar modal con animación
    showModalWithAnimation(modal);

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

    // Mostrar modal con animación
    showModalWithAnimation(modal);

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
                            class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors min-w-[100px]">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="saveSerie('${mode}', ${data?.id || 'null'})"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors min-w-[100px]">
                        ${isEdit ? 'Actualizar' : 'Crear'}
                    </button>
                </div>
            </div>
        </div>
    `;

    // Agregar event listeners después de crear el modal
    setTimeout(() => {
        setupSerieModalEventListeners(modal);
    }, 0);

    return modal;
}

function setupSerieModalEventListeners(modal) {
    // Cerrar modal al hacer clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeSerieModal();
        }
    });

    // Cerrar modal con tecla Escape
    const escapeHandler = function(e) {
        if (e.key === 'Escape' && document.getElementById('serie-modal')) {
            closeSerieModal();
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);
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



function toggleSerieStatus(serieId, nombre) {
    // Obtener el estado actual de la serie para determinar la acción
    const serieRow = document.querySelector(`[data-action="toggle-status"][data-serie-id="${serieId}"]`);
    const isActive = serieRow.textContent.trim().includes('Desactivar');

    const accion = isActive ? 'desactivar' : 'activar';
    const accionCapital = isActive ? 'Desactivar' : 'Activar';
    const mensaje = `¿Está seguro que desea ${accion} la serie "${nombre}"?`;

    showConfirmModal({
        title: `${accionCapital} Serie`,
        message: mensaje,
        actionText: accionCapital,
        // Si está activa (true) y queremos desactivar → naranja
        // Si está inactiva (false) y queremos activar → verde
        actionClass: isActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
        iconClass: isActive ? 'bg-orange-100' : 'bg-green-100',
        iconColor: isActive ? 'text-orange-600' : 'text-green-600',
        onConfirm: function() {
            executeToggleSerieStatus(serieId);
        }
    });
}

// Función para ejecutar el cambio de estado
function executeToggleSerieStatus(serieId) {
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

function showDeleteConfirmModal(serieId, nombre) {
    const mensaje = `¿Está seguro que desea eliminar la serie "${nombre}"?\n\nEsta acción no se puede deshacer.`;

    showConfirmModal({
        title: 'Eliminar Serie',
        message: mensaje,
        actionText: 'Eliminar',
        actionClass: 'bg-red-600 hover:bg-red-700',
        iconClass: 'bg-red-100',
        iconColor: 'text-red-600',
        onConfirm: function() {
            executeDeleteSerie(serieId);
        }
    });
}

// Función para ejecutar la eliminación
function executeDeleteSerie(serieId) {
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
