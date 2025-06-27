// Gestión de Unidades Administrativas - Admin
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de unidades administrativas
    if (!document.querySelector('[data-page="admin-unidades-administrativas"]')) {
        return;
    }

    console.log('DOM loaded, initializing Unidades Administrativas management...');

    // Configurar event listeners
    setupEventListeners();
    setupConfirmModalEventListeners();

    // Inicializar búsqueda en tiempo real
    initializeRealTimeSearch();

    console.log('Unidades Administrativas management initialized successfully');
});

function setupEventListeners() {
    // Botón crear unidad administrativa
    document.querySelector('[data-action="create-unidad"]')?.addEventListener('click', openCreateModal);

    // Botones editar
    document.querySelectorAll('[data-action="edit-unidad"]').forEach(button => {
        button.addEventListener('click', function() {
            const unidadId = this.dataset.unidadId;
            const codigo = this.dataset.unidadCodigo;
            const nombre = this.dataset.unidadNombre;
            const descripcion = this.dataset.unidadDescripcion;
            const activa = this.dataset.unidadActiva === 'true';

            openEditModal(unidadId, codigo, nombre, descripcion, activa);
        });
    });

    // Botones toggle status
    document.querySelectorAll('[data-action="toggle-status"]').forEach(button => {
        button.addEventListener('click', function() {
            const unidadId = this.dataset.unidadId;
            const nombre = this.dataset.unidadNombre;
            toggleUnidadStatus(unidadId, nombre);
        });
    });

    // Botones eliminar
    document.querySelectorAll('[data-action="delete-unidad"]').forEach(button => {
        button.addEventListener('click', function() {
            const unidadId = this.dataset.unidadId;
            const nombre = this.dataset.unidadNombre;
            showDeleteConfirmModal(unidadId, nombre);
        });
    });
}

function openCreateModal() {
    const modal = createUnidadModal('create');
    document.body.appendChild(modal);
}

function openEditModal(unidadId, codigo, nombre, descripcion, activa) {
    const modal = createUnidadModal('edit', {
        id: unidadId,
        codigo: codigo,
        nombre: nombre,
        descripcion: descripcion,
        activa: activa
    });
    document.body.appendChild(modal);
}

function createUnidadModal(mode, data = null) {
    const isEdit = mode === 'edit';
    const title = isEdit ? 'Editar Unidad Administrativa' : 'Nueva Unidad Administrativa';

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.id = 'unidad-modal';

    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeUnidadModal()">
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
                            onclick="closeUnidadModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="button"
                            onclick="saveUnidad('${mode}', ${data?.id || 'null'})"
                            class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                        ${isEdit ? 'Actualizar' : 'Crear'}
                    </button>
                </div>
            </div>
        </div>
    `;

    return modal;
}

function closeUnidadModal() {
    const modal = document.getElementById('unidad-modal');
    if (modal) {
        modal.remove();
    }
}

function saveUnidad(mode, unidadId) {
    const form = document.getElementById('unidad-form');
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
    if (confirm(`¿Está seguro de cambiar el estado de la unidad administrativa "${nombre}"?`)) {
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
}

function showDeleteConfirmModal(unidadId, nombre) {
    if (confirm(`¿Está seguro de eliminar la unidad administrativa "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        deleteUnidad(unidadId);
    }
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
            if (termino.length === 0) {
                // Si no hay término de búsqueda, recargar todas las unidades
                location.reload();
            } else if (termino.length >= 2) {
                // Buscar solo si hay al menos 2 caracteres
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
    // Recargar la página para mostrar todas las unidades
    location.reload();
}

function updateUnidadesTable(unidades) {
    const tbody = document.getElementById('unidades-table-body');
    if (!tbody) return;

    tbody.innerHTML = '';

    unidades.forEach(unidad => {
        const row = createUnidadRow(unidad);
        tbody.appendChild(row);
    });

    // Re-agregar event listeners
    setupEventListeners();
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
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
            <button data-action="edit-unidad"
                    data-unidad-id="${unidad.id}"
                    data-unidad-codigo="${unidad.codigo}"
                    data-unidad-nombre="${unidad.nombre}"
                    data-unidad-descripcion="${unidad.descripcion || ''}"
                    data-unidad-activa="${unidad.activa ? 'true' : 'false'}"
                    class="text-indigo-600 hover:text-indigo-900">
                Editar
            </button>
            <button data-action="toggle-status"
                    data-unidad-id="${unidad.id}"
                    data-unidad-nombre="${unidad.nombre}"
                    class="text-yellow-600 hover:text-yellow-900">
                ${unidad.activa ? 'Desactivar' : 'Activar'}
            </button>
            ${(unidad.series_count || 0) === 0 ? `
            <button data-action="delete-unidad"
                    data-unidad-id="${unidad.id}"
                    data-unidad-nombre="${unidad.nombre}"
                    class="text-red-600 hover:text-red-900">
                Eliminar
            </button>
            ` : ''}
        </td>
    `;

    return row;
}

function setupConfirmModalEventListeners() {
    // Implementar si es necesario
}

function showSuccessMessage(message) {
    // Usar el sistema de notificaciones existente si está disponible
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
    // Usar el sistema de notificaciones existente si está disponible
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
