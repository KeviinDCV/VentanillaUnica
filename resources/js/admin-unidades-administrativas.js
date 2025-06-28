// Gestión de Unidades Administrativas - Admin

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
    document.querySelectorAll('[id^="dropdown-unidad-"]').forEach(d => {
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

function adjustDropdownPosition(dropdown) {
    const rect = dropdown.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    const spaceBelow = viewportHeight - rect.bottom;
    const spaceAbove = rect.top;

    // Si no hay suficiente espacio abajo pero sí arriba, mostrar hacia arriba
    if (spaceBelow < 0 && spaceAbove > Math.abs(spaceBelow)) {
        dropdown.classList.remove('origin-top-right', 'mt-2');
        dropdown.classList.add('origin-bottom-right', 'mb-2');
        dropdown.style.bottom = '100%';
        dropdown.style.top = 'auto';
    } else {
        dropdown.classList.remove('origin-bottom-right', 'mb-2');
        dropdown.classList.add('origin-top-right', 'mt-2');
        dropdown.style.top = '100%';
        dropdown.style.bottom = 'auto';
    }

    // Ajustar posición horizontal si se sale del viewport
    const dropdownRect = dropdown.getBoundingClientRect();
    if (dropdownRect.right > window.innerWidth) {
        dropdown.style.right = '0';
        dropdown.style.left = 'auto';
    }
}

// Función para cerrar modal (disponible globalmente)
function closeCreateModal() {
    const modal = document.getElementById('createUnidadModal');
    if (modal) {
        const modalContent = modal.querySelector('.relative');

        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Resetear modal para crear si la función existe
            if (typeof resetModalToCreate === 'function') {
                resetModalToCreate();
            }
        }, 200);
    }
}

// Hacer funciones disponibles globalmente inmediatamente
window.toggleDropdown = toggleDropdown;
window.closeCreateModal = closeCreateModal;

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de unidades administrativas
    if (!document.querySelector('[data-page="admin-unidades-administrativas"]')) {
        return;
    }

    console.log('DOM loaded, initializing Unidades Administrativas management...');

    // Configurar event listeners
    setupEventListeners();

    // Configurar event listeners para modal de confirmación
    setupConfirmModalEventListeners();

    // Inicializar búsqueda en tiempo real
    initializeRealTimeSearch();

    console.log('Unidades Administrativas management initialized successfully');
});

function setupEventListeners() {
    // Botón crear unidad administrativa
    document.querySelector('[data-action="create-unidad"]')?.addEventListener('click', openCreateModal);

    // Event listeners para el modal
    setupModalEventListeners();

    // Usar delegación de eventos para botones dinámicos
    document.addEventListener('click', function(e) {
        // Botones editar
        if (e.target.closest('[data-action="edit-unidad"]')) {
            const button = e.target.closest('[data-action="edit-unidad"]');
            const unidadId = button.dataset.unidadId;
            const codigo = button.dataset.unidadCodigo;
            const nombre = button.dataset.unidadNombre;
            const descripcion = button.dataset.unidadDescripcion;
            const activa = button.dataset.unidadActiva === 'true';

            openEditModal(unidadId, codigo, nombre, descripcion, activa);
        }

        // Botones toggle status
        if (e.target.closest('[data-action="toggle-status"]')) {
            const button = e.target.closest('[data-action="toggle-status"]');
            const unidadId = button.dataset.unidadId;
            const nombre = button.dataset.unidadNombre;
            toggleUnidadStatus(unidadId, nombre);
        }

        // Botones eliminar
        if (e.target.closest('[data-action="delete-unidad"]')) {
            const button = e.target.closest('[data-action="delete-unidad"]');
            const unidadId = button.dataset.unidadId;
            const nombre = button.dataset.unidadNombre;
            showDeleteConfirmModal(unidadId, nombre);
        }
    });
}



function openCreateModal() {
    const modal = document.getElementById('createUnidadModal');
    const form = document.getElementById('createUnidadForm');

    // Limpiar formulario
    form.reset();
    document.getElementById('activa').checked = true;

    // Resetear modal a modo crear
    resetModalToCreate();

    // Ocultar errores
    document.getElementById('createModalErrors').classList.add('hidden');

    // Mostrar modal con animación
    showModalWithAnimation(modal);
}

function openEditModal(unidadId, codigo, nombre, descripcion, activa) {
    const modal = document.getElementById('createUnidadModal');

    // Cambiar título del modal
    document.getElementById('createModalTitle').textContent = 'Editar Unidad Administrativa';

    // Llenar formulario con datos existentes
    document.getElementById('codigo').value = codigo;
    document.getElementById('nombre').value = nombre;
    document.getElementById('descripcion').value = descripcion || '';
    document.getElementById('activa').checked = activa;

    // Cambiar action del formulario para editar
    const form = document.getElementById('createUnidadForm');
    form.action = `/admin/unidades-administrativas/${unidadId}`;

    // Agregar método PUT
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';

    // Cambiar texto del botón
    const submitButton = form.parentElement.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.textContent = 'Actualizar Unidad Administrativa';
    }

    // Ocultar errores
    document.getElementById('createModalErrors').classList.add('hidden');

    // Mostrar modal con animación
    showModalWithAnimation(modal);
}

function resetModalToCreate() {
    // Cambiar título
    document.getElementById('createModalTitle').textContent = 'Nueva Unidad Administrativa';

    // Resetear formulario
    const form = document.getElementById('createUnidadForm');
    form.action = '/admin/unidades-administrativas';

    // Remover método PUT si existe
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }

    // Cambiar texto del botón
    const submitButton = form.parentElement.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.textContent = 'Crear Unidad Administrativa';
    }
}

function createUnidadModal(mode, data = null) {
    // Esta función ya no se usa, pero la mantengo por compatibilidad
    return null;

    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white" style="transition: opacity 0.3s ease-out, transform 0.3s ease-out;">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                    <button type="button" id="close-modal-btn" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="unidad-form" class="space-y-4">
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700">Código *</label>
                        <input type="text"
                               id="codigo"
                               name="codigo"
                               value="${data?.codigo || ''}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: 100"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Código numérico único para la unidad administrativa</p>
                    </div>

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text"
                               id="nombre"
                               name="nombre"
                               value="${data?.nombre || ''}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                               placeholder="Ej: Atención al Usuario"
                               required>
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-uniradical-blue focus:border-uniradical-blue"
                                  placeholder="Descripción de la unidad administrativa">${data?.descripcion || ''}</textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               id="activa"
                               name="activa"
                               ${data?.activa !== false ? 'checked' : ''}
                               class="h-4 w-4 text-uniradical-blue focus:ring-uniradical-blue border-gray-300 rounded">
                        <label for="activa" class="ml-2 block text-sm text-gray-900">Unidad administrativa activa</label>
                    </div>
                </form>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            id="cancel-modal-btn"
                            class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors min-w-[100px]">
                        Cancelar
                    </button>
                    <button type="button"
                            id="save-modal-btn"
                            data-mode="${mode}"
                            data-unidad-id="${data?.id || ''}"
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uniradical-blue hover:bg-opacity-90 transition-colors min-w-[100px]">
                        ${isEdit ? 'Actualizar' : 'Crear'}
                    </button>
                </div>
            </div>
        </div>
    `;

    // Agregar event listeners después de crear el modal
    setTimeout(() => {
        setupModalEventListeners(mode, data?.id);
    }, 0);

    return modal;
}

function setupModalEventListeners() {
    // Manejar envío del formulario
    const form = document.getElementById('createUnidadForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Determinar si es edición o creación basado en el action del formulario
            const isEdit = form.action.includes('/admin/unidades-administrativas/') &&
                          form.action !== '/admin/unidades-administrativas';

            const mode = isEdit ? 'edit' : 'create';

            // Extraer ID de la URL si es edición
            let unidadId = null;
            if (isEdit) {
                const matches = form.action.match(/\/admin\/unidades-administrativas\/(\d+)/);
                unidadId = matches ? matches[1] : null;
            }

            saveUnidad(mode, unidadId);
        });
    }

    // Cerrar modal al hacer clic fuera
    const modal = document.getElementById('createUnidadModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeUnidadModal();
            }
        });
    }

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('createUnidadModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeUnidadModal();
            }
        }
    });
}

function showModalWithAnimation(modal) {
    // Mostrar modal
    modal.classList.remove('hidden');

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
            const firstInput = modal.querySelector('input[type="text"]');
            if (firstInput) {
                firstInput.focus();
            }
        }, 100);
    }, 10);
}

function closeUnidadModal() {
    const modal = document.getElementById('createUnidadModal');
    if (modal) {
        const modalContent = modal.querySelector('.relative');

        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Resetear modal para crear
            resetModalToCreate();
        }, 200);
    }
}



function saveUnidad(mode, unidadId) {
    const form = document.getElementById('createUnidadForm');
    const formData = new FormData(form);

    const data = {
        codigo: formData.get('codigo'),
        nombre: formData.get('nombre'),
        descripcion: formData.get('descripcion'),
        activa: formData.has('activa')
    };

    const url = mode === 'edit'
        ? `/admin/unidades-administrativas/${unidadId}`
        : '/admin/unidades-administrativas';

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
            closeUnidadModal();
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

function toggleUnidadStatus(unidadId, nombre) {
    // Obtener el estado actual de la unidad para determinar la acción
    const unidadRow = document.querySelector(`[data-action="toggle-status"][data-unidad-id="${unidadId}"]`);
    const isActive = unidadRow.textContent.trim().includes('Desactivar');

    const accion = isActive ? 'desactivar' : 'activar';
    const accionCapital = isActive ? 'Desactivar' : 'Activar';
    const mensaje = `¿Está seguro que desea ${accion} la unidad administrativa "${nombre}"?`;

    showConfirmModal({
        title: `${accionCapital} Unidad Administrativa`,
        message: mensaje,
        actionText: accionCapital,
        // Si está activa (true) y queremos desactivar → naranja
        // Si está inactiva (false) y queremos activar → verde
        actionClass: isActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
        iconClass: isActive ? 'bg-orange-100' : 'bg-green-100',
        iconColor: isActive ? 'text-orange-600' : 'text-green-600',
        onConfirm: function() {
            executeToggleUnidadStatus(unidadId);
        }
    });
}

function executeToggleUnidadStatus(unidadId) {
    fetch(`/admin/unidades-administrativas/${unidadId}/toggle-status`, {
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

function showDeleteConfirmModal(unidadId, nombre) {
    // Usar modal personalizado
    showConfirmModal({
        title: 'Eliminar Unidad Administrativa',
        message: `¿Estás seguro de que deseas eliminar permanentemente la unidad administrativa "${nombre}"?\n\nEsta acción no se puede deshacer.`,
        actionText: 'Eliminar',
        actionClass: 'bg-red-600 hover:bg-red-700',
        iconClass: 'bg-red-100',
        iconColor: 'text-red-600',
        onConfirm: function() {
            deleteUnidad(unidadId);
        }
    });
}

function deleteUnidad(unidadId) {
    fetch(`/admin/unidades-administrativas/${unidadId}`, {
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
            showErrorMessage(data.message || 'Error al eliminar la unidad administrativa');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error al procesar la solicitud');
    });
}

function initializeRealTimeSearch() {
    const searchInput = document.getElementById('buscar-unidades');
    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();

        searchTimeout = setTimeout(() => {
            if (termino.length >= 2 || termino.length === 0) {
                // Buscar si hay al menos 2 caracteres o mostrar todas si está vacío
                searchUnidades(termino);
            }
        }, 300);
    });
}

function searchUnidades(termino) {
    fetch(`/admin/unidades-administrativas/buscar?termino=${encodeURIComponent(termino)}`)
        .then(response => response.json())
        .then(data => {
            updateUnidadesTable(data.unidades);
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
        });
}

function showAllUnidades() {
    // Buscar con término vacío para mostrar todas las unidades
    searchUnidades('');
}

function updateUnidadesTable(unidades) {
    const tbody = document.getElementById('unidades-table-body');
    if (!tbody) return;

    tbody.innerHTML = '';

    unidades.forEach(unidad => {
        const row = createUnidadRow(unidad);
        tbody.appendChild(row);
    });

    // No necesitamos re-agregar event listeners porque usamos delegación de eventos
}

function createUnidadRow(unidad) {
    const row = document.createElement('tr');
    row.className = 'unidad-row hover:bg-gray-50';

    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="text-sm font-medium text-gray-900">${unidad.codigo}</span>
        </td>
        <td class="px-6 py-4">
            <span class="text-sm text-gray-900">${unidad.nombre}</span>
        </td>
        <td class="px-6 py-4">
            <span class="text-sm text-gray-600">${unidad.descripcion ? unidad.descripcion.substring(0, 50) + (unidad.descripcion.length > 50 ? '...' : '') : ''}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                ${unidad.series_count || 0} series
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${unidad.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${unidad.activa ? 'Activa' : 'Inactiva'}
            </span>
        </td>
        <td class="px-3 py-4 text-sm font-medium">
            <div class="relative inline-block text-left">
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                        onclick="toggleDropdown('dropdown-unidad-${unidad.id}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>

                <div id="dropdown-unidad-${unidad.id}"
                     class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                     style="z-index: 9999;"
                     data-dropdown-menu>
                    <div class="py-1" role="menu">
                        <!-- Editar -->
                        <button data-action="edit-unidad"
                                data-unidad-id="${unidad.id}"
                                data-unidad-codigo="${unidad.codigo}"
                                data-unidad-nombre="${unidad.nombre}"
                                data-unidad-descripcion="${unidad.descripcion || ''}"
                                data-unidad-activa="${unidad.activa ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>

                        <!-- Activar/Desactivar -->
                        <button data-action="toggle-status"
                                data-unidad-id="${unidad.id}"
                                data-unidad-nombre="${unidad.nombre}"
                                class="w-full text-left px-4 py-2 text-sm ${unidad.activa ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50'} flex items-center">
                            ${unidad.activa ? `
                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Desactivar
                            ` : `
                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activar
                            `}
                        </button>

                        <!-- Separador -->
                        <div class="border-t border-gray-100"></div>

                        <!-- Eliminar -->
                        ${(unidad.series_count || 0) > 0 ? `
                            <div class="w-full text-left px-4 py-2 text-sm text-gray-400 flex items-center cursor-not-allowed"
                                 title="No se puede eliminar: tiene ${unidad.series_count || 0} serie(s) asociada(s)">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </div>
                        ` : `
                            <button data-action="delete-unidad"
                                    data-unidad-id="${unidad.id}"
                                    data-unidad-nombre="${unidad.nombre}"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        `}
                    </div>
                </div>
            </div>
        </td>
    `;

    return row;
}

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
    } else if (options.actionClass.includes('bg-yellow-600')) {
        actionButton.style.backgroundColor = '#ca8a04';
        actionButton.style.borderColor = '#ca8a04';
    }

    // Configurar icono
    iconContainer.className = `flex-shrink-0 w-10 h-10 mx-auto rounded-full flex items-center justify-center ${options.iconClass}`;
    iconContainer.innerHTML = `
        <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    `;

    // Guardar la función de confirmación globalmente
    window.currentConfirmAction = options.onConfirm;

    // Configurar event listener para el botón de acción
    actionButton.onclick = function() {
        if (window.currentConfirmAction) {
            window.currentConfirmAction();
        }
        closeConfirmModal();
    };

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
    if (!modal) return;

    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        window.currentConfirmAction = null;
    }, 200);
}

function showSuccessMessage(message) {
    // Usar el sistema de notificaciones personalizado
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'success',
            title: 'Éxito',
            message: message,
            duration: 4000
        });
    } else if (window.showSuccess) {
        window.showSuccess('Éxito', message);
    } else {
        // Fallback a alert solo si no hay sistema de notificaciones
        alert(message);
    }
}

function showErrorMessage(message) {
    // Usar el sistema de notificaciones personalizado
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'error',
            title: 'Error',
            message: message,
            duration: 5000
        });
    } else if (window.showError) {
        window.showError('Error', message);
    } else {
        // Fallback a alert solo si no hay sistema de notificaciones
        alert(message);
    }
}

function showErrors(errors) {
    const errorsContainer = document.getElementById('createModalErrors');
    const errorsList = document.getElementById('createErrorsList');

    if (errorsContainer && errorsList) {
        // Limpiar errores anteriores
        errorsList.innerHTML = '';

        // Agregar nuevos errores
        for (const field in errors) {
            errors[field].forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorsList.appendChild(li);
            });
        }

        // Mostrar contenedor de errores
        errorsContainer.classList.remove('hidden');
    } else {
        // Fallback si no se encuentran los elementos
        let errorMessage = 'Errores encontrados:\n';
        for (const field in errors) {
            errorMessage += `- ${errors[field].join(', ')}\n`;
        }
        showErrorMessage(errorMessage);
    }
}





// Configurar event listeners para cerrar dropdowns
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        document.querySelectorAll('[id^="dropdown-unidad-"]').forEach(dropdown => {
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
    if (event.target.closest('[id^="dropdown-unidad-"] button')) {
        // Pequeño delay para permitir que la acción se ejecute
        setTimeout(() => {
            document.querySelectorAll('[id^="dropdown-unidad-"]').forEach(dropdown => {
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
    document.querySelectorAll('[id^="dropdown-unidad-"]:not(.hidden)').forEach(dropdown => {
        adjustDropdownPosition(dropdown);
    });
});

// Cerrar dropdowns al hacer scroll
window.addEventListener('scroll', function() {
    document.querySelectorAll('[id^="dropdown-unidad-"]:not(.hidden)').forEach(dropdown => {
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

// Hacer funciones disponibles globalmente para debugging
window.UniRadicUnidadesAdministrativas = {
    openCreateModal,
    openEditModal,
    closeUnidadModal,
    closeCreateModal,
    toggleUnidadStatus,
    showDeleteConfirmModal,
    toggleDropdown,
    showSuccessMessage,
    showErrorMessage
};




