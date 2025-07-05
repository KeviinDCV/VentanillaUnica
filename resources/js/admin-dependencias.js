// Gestión de Dependencias - Admin

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de dependencias
    if (!document.querySelector('[data-page="admin-dependencias"]')) {
        return;
    }

    // Configurar event listeners
    setupEventListeners();
    setupConfirmModalEventListeners();

    // Inicializar búsqueda en tiempo real
    initializeRealTimeSearch();
});

function setupEventListeners() {
    // Usar delegación de eventos para manejar clics en todo el documento
    document.addEventListener('click', function(e) {
        // Botón crear dependencia
        if (e.target.closest('[data-action="create-dependencia"]')) {
            e.preventDefault();
            openCreateModal();
        }

        // Botones editar dependencia
        if (e.target.closest('[data-action="edit-dependencia"]')) {
            e.preventDefault();
            const button = e.target.closest('[data-action="edit-dependencia"]');
            const dependenciaId = button.dataset.dependenciaId;
            const codigo = button.dataset.dependenciaCodigo;
            const nombre = button.dataset.dependenciaNombre;
            const sigla = button.dataset.dependenciaSigla;
            const descripcion = button.dataset.dependenciaDescripcion;
            const responsable = button.dataset.dependenciaResponsable;
            const telefono = button.dataset.dependenciaTelefono;
            const email = button.dataset.dependenciaEmail;
            const activa = button.dataset.dependenciaActiva === 'true';

            openEditModal(dependenciaId, codigo, nombre, sigla, descripcion, responsable, telefono, email, activa);
        }

        // Botones eliminar dependencia
        if (e.target.closest('[data-action="delete-dependencia"]')) {
            e.preventDefault();
            const button = e.target.closest('[data-action="delete-dependencia"]');
            const dependenciaId = button.dataset.dependenciaId;
            const dependenciaName = button.dataset.dependenciaName;
            const radicadosCount = parseInt(button.dataset.radicadosCount);

            if (radicadosCount > 0) {
                showConfirmModal({
                    title: 'No se puede eliminar',
                    message: `La dependencia "${dependenciaName}" tiene ${radicadosCount} radicado(s) asociado(s). No se puede eliminar. Puede desactivarla en su lugar.`,
                    actionText: 'Entendido',
                    actionClass: 'bg-blue-600 hover:bg-blue-700',
                    iconClass: 'bg-yellow-100',
                    iconColor: 'text-yellow-600',
                    onConfirm: function() {
                        closeConfirmModal();
                    }
                });
            } else {
                showConfirmModal({
                    title: 'Eliminar Dependencia',
                    message: `¿Estás seguro de que deseas eliminar permanentemente la dependencia "${dependenciaName}"? Esta acción no se puede deshacer.`,
                    actionText: 'Eliminar',
                    actionClass: 'bg-red-600 hover:bg-red-700',
                    iconClass: 'bg-red-100',
                    iconColor: 'text-red-600',
                    onConfirm: function() {
                        eliminarDependencia(dependenciaId);
                    }
                });
            }
        }

        // Botones cambiar estado (activar/desactivar)
        if (e.target.closest('button[data-dependencia-name][data-form-id]')) {
            e.preventDefault();
            const button = e.target.closest('button[data-dependencia-name][data-form-id]');

            console.log('Toggle status button clicked for dependencia:', button.dataset.dependenciaName);

            const dependenciaName = button.dataset.dependenciaName;
            const isActive = button.dataset.dependenciaActive === 'true';
            const formId = button.dataset.formId;
            const accion = isActive ? 'desactivar' : 'activar';
            const accionCapital = isActive ? 'Desactivar' : 'Activar';

            console.log('Showing toggle status modal:', accionCapital);

            // Usar modal personalizado en lugar de confirm()
            showConfirmModal({
                title: `${accionCapital} Dependencia`,
                message: `¿Estás seguro de que deseas ${accion} la dependencia "${dependenciaName}"?`,
                actionText: accionCapital,
                actionClass: isActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
                iconClass: isActive ? 'bg-orange-100' : 'bg-green-100',
                iconColor: isActive ? 'text-orange-600' : 'text-green-600',
                onConfirm: function() {
                    toggleDependenciaStatus(formId);
                }
            });
        }
    });

    // Botones eliminar dependencia
    document.querySelectorAll('[data-action="delete-dependencia"]').forEach(button => {
        button.addEventListener('click', function() {
            const dependenciaId = this.dataset.dependenciaId;
            const dependenciaName = this.dataset.dependenciaName;
            const radicadosCount = parseInt(this.dataset.radicadosCount);

            if (radicadosCount > 0) {
                showConfirmModal({
                    title: 'No se puede eliminar',
                    message: `La dependencia "${dependenciaName}" tiene ${radicadosCount} radicado(s) asociado(s). No se puede eliminar. Puede desactivarla en su lugar.`,
                    actionText: 'Entendido',
                    actionClass: 'bg-blue-600 hover:bg-blue-700',
                    iconClass: 'bg-yellow-100',
                    iconColor: 'text-yellow-600',
                    onConfirm: function() {
                        closeConfirmModal();
                    }
                });
            } else {
                console.log('Showing delete confirmation modal');
                showConfirmModal({
                    title: 'Eliminar Dependencia',
                    message: `¿Estás seguro de que deseas eliminar permanentemente la dependencia "${dependenciaName}"? Esta acción no se puede deshacer.`,
                    actionText: 'Eliminar',
                    actionClass: 'bg-red-600 hover:bg-red-700',
                    iconClass: 'bg-red-100',
                    iconColor: 'text-red-600',
                    onConfirm: function() {
                        eliminarDependencia(dependenciaId);
                    }
                });
            }
        });
    });

    // Los botones de cambiar estado ya están manejados con delegación de eventos

    // Formularios
    document.getElementById('createDependenciaForm')?.addEventListener('submit', handleCreateSubmit);
    document.getElementById('editDependenciaForm')?.addEventListener('submit', handleEditSubmit);

    // Cerrar modales
    document.querySelectorAll('[data-action="close-create-modal"]').forEach(button => {
        button.addEventListener('click', closeCreateModal);
    });

    document.querySelectorAll('[data-action="close-edit-modal"]').forEach(button => {
        button.addEventListener('click', closeEditModal);
    });

    // Cerrar modales al hacer clic fuera
    document.getElementById('createDependenciaModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeCreateModal();
    });

    document.getElementById('editDependenciaModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Cerrar modales con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('createDependenciaModal').classList.contains('hidden')) {
                closeCreateModal();
            }
            if (!document.getElementById('editDependenciaModal').classList.contains('hidden')) {
                closeEditModal();
            }
        }
    });
}



function setupConfirmModalEventListeners() {
    // Verificar si el modal existe antes de agregar event listeners
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
    const confirmButton = document.getElementById('confirmModalAction');
    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            closeConfirmModal();
            if (currentConfirmAction) {
                currentConfirmAction();
                currentConfirmAction = null;
            }
        });
    }
}

// Variable global para almacenar la función de confirmación
let currentConfirmAction = null;

function showConfirmModal(options) {
    console.log('showConfirmModal called with options:', options);

    const modal = document.getElementById('confirmStatusModal');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const actionButton = document.getElementById('confirmModalAction');
    const iconContainer = document.getElementById('confirmModalIcon');

    // Limpiar contenido anterior del modal
    title.textContent = '';
    message.textContent = '';
    actionButton.textContent = '';
    iconContainer.innerHTML = '';

    // Configurar contenido del modal
    title.textContent = options.title;
    message.textContent = options.message;
    actionButton.textContent = options.actionText;

    console.log('Modal content set - Title:', options.title, 'Message:', options.message, 'Action:', options.actionText);

    // Aplicar clases CSS completas
    actionButton.className = `px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}`;

    // Como respaldo, aplicar estilos inline también
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    `;

    // Guardar la función de confirmación globalmente
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

function closeConfirmModal() {
    const modal = document.getElementById('confirmStatusModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function toggleDependenciaStatus(formId) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    const url = form.action;

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            // Recargar página para mostrar los cambios
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('Error al cambiar el estado de la dependencia');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cambiar el estado de la dependencia');
    });
}

function openCreateModal() {
    const modal = document.getElementById('createDependenciaModal');
    const form = document.getElementById('createDependenciaForm');

    // Limpiar formulario
    form.reset();
    document.getElementById('create_activa').checked = true;

    // Ocultar errores
    document.getElementById('createModalErrors').classList.add('hidden');

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
        // Focus en el primer campo
        document.getElementById('create_codigo').focus();
    }, 10);
}

function closeCreateModal() {
    const modal = document.getElementById('createDependenciaModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function openEditModal(dependenciaId, codigo, nombre, sigla, descripcion, responsable, telefono, email, activa) {
    const modal = document.getElementById('editDependenciaModal');

    // Llenar formulario con datos
    document.getElementById('edit_dependencia_id').value = dependenciaId;
    document.getElementById('edit_codigo').value = codigo || '';
    document.getElementById('edit_nombre').value = nombre || '';
    document.getElementById('edit_sigla').value = sigla || '';
    document.getElementById('edit_descripcion').value = descripcion || '';
    document.getElementById('edit_responsable').value = responsable || '';
    document.getElementById('edit_telefono').value = telefono || '';
    document.getElementById('edit_email').value = email || '';
    document.getElementById('edit_activa').checked = activa;

    // Ocultar errores
    document.getElementById('editModalErrors').classList.add('hidden');

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
        // Focus en el primer campo
        document.getElementById('edit_codigo').focus();
    }, 10);
}

function closeEditModal() {
    const modal = document.getElementById('editDependenciaModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function handleCreateSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // Convertir checkbox a boolean
    formData.set('activa', document.getElementById('create_activa').checked ? '1' : '0');

    fetch('/admin/dependencias', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCreateModal();
            showSuccessMessage(data.message);
            // Recargar página para mostrar la nueva dependencia
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showModalErrors(data.errors, 'create');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModalErrors({ general: ['Error al crear la dependencia'] }, 'create');
    });
}

function handleEditSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const dependenciaId = document.getElementById('edit_dependencia_id').value;

    // Convertir checkbox a boolean
    formData.set('activa', document.getElementById('edit_activa').checked ? '1' : '0');

    fetch(`/admin/dependencias/${dependenciaId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            showSuccessMessage(data.message);
            // Recargar página para mostrar los cambios
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showModalErrors(data.errors, 'edit');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModalErrors({ general: ['Error al actualizar la dependencia'] }, 'edit');
    });
}

function showModalErrors(errors, modalType) {
    const errorsList = document.getElementById(`${modalType}ErrorsList`);
    const errorsContainer = document.getElementById(`${modalType}ModalErrors`);

    // Limpiar errores anteriores
    errorsList.innerHTML = '';

    // Agregar nuevos errores
    Object.values(errors).flat().forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorsList.appendChild(li);
    });

    // Mostrar contenedor de errores
    errorsContainer.classList.remove('hidden');

    // Scroll al top del modal para ver los errores
    document.querySelector(`#${modalType}DependenciaModal .relative`).scrollTop = 0;
}

function showSuccessMessage(message) {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg z-50 transform transition-all duration-300 ease-in-out';
    notification.innerHTML = `
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">${message}</p>
            </div>
        </div>
    `;

    // Agregar al DOM
    document.body.appendChild(notification);

    // Animación de entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);

    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function eliminarDependencia(dependenciaId) {
    fetch(`/admin/dependencias/${dependenciaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        closeConfirmModal();

        if (data.success) {
            showSuccessMessage(data.message);
            // Recargar la página para actualizar la tabla
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showConfirmModal({
                title: 'Error al eliminar',
                message: data.message || 'No se pudo eliminar la dependencia',
                actionText: 'Entendido',
                actionClass: 'bg-red-600 hover:bg-red-700',
                iconClass: 'bg-red-100',
                iconColor: 'text-red-600',
                onConfirm: function() {
                    closeConfirmModal();
                }
            });
        }
    })
    .catch(error => {
        // Error silencioso por seguridad
        closeConfirmModal();
        showConfirmModal({
            title: 'Error',
            message: 'Error al eliminar la dependencia',
            actionText: 'Entendido',
            actionClass: 'bg-red-600 hover:bg-red-700',
            iconClass: 'bg-red-100',
            iconColor: 'text-red-600',
            onConfirm: function() {
                closeConfirmModal();
            }
        });
    });
}

// Hacer funciones disponibles globalmente para debugging
window.UniRadicDependenciaManagement = {
    openEditModal,
    closeEditModal,
    openCreateModal,
    closeCreateModal,
    showConfirmModal,
    closeConfirmModal,
    showSuccessMessage,
    eliminarDependencia
};

// Funciones para búsqueda en tiempo real
function initializeRealTimeSearch() {
    const searchInput = document.getElementById('buscar-dependencias');
    const filterSelect = document.getElementById('filtro-estado');
    if (!searchInput || !filterSelect) return;

    let searchTimeout;

    // Event listener para el campo de búsqueda
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();
        const filtroEstado = filterSelect.value;

        // Debounce para evitar demasiadas peticiones
        searchTimeout = setTimeout(() => {
            if (termino.length >= 2 || termino.length === 0 || filtroEstado !== '') {
                // Buscar si hay al menos 2 caracteres, está vacío, o hay filtro seleccionado
                searchDependencias(termino, filtroEstado);
            }
        }, 300);
    });

    // Event listener para el filtro de estado
    filterSelect.addEventListener('change', function() {
        const termino = searchInput.value.trim();
        const filtroEstado = this.value;

        // Aplicar filtro inmediatamente
        searchDependencias(termino, filtroEstado);
    });
}

function searchDependencias(termino, filtroEstado = '') {
    let url = `/admin/dependencias/buscar?termino=${encodeURIComponent(termino)}`;

    if (filtroEstado) {
        url += `&estado=${encodeURIComponent(filtroEstado)}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateDependenciasTable(data.dependencias);
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
        });
}

function updateDependenciasTable(dependencias) {
    const tbody = document.getElementById('dependencias-table-body');
    if (!tbody) return;

    // Limpiar tabla actual
    tbody.innerHTML = '';

    // Agregar dependencias filtradas
    dependencias.forEach(dependencia => {
        const row = createDependenciaRow(dependencia);
        tbody.appendChild(row);
    });

    // Los event listeners ya están configurados con delegación de eventos
    // No necesitamos re-agregarlos
}

function createDependenciaRow(dependencia) {
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50 dependencia-row';
    row.setAttribute('data-name', dependencia.nombre.toLowerCase());
    row.setAttribute('data-codigo', dependencia.codigo.toLowerCase());
    row.setAttribute('data-responsable', (dependencia.responsable || '').toLowerCase());

    // Determinar el color del badge del estado
    const estadoBadgeClass = dependencia.activa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    const estadoTexto = dependencia.activa ? 'Activa' : 'Inactiva';

    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                        ${dependencia.nombre}
                    </div>
                    <div class="text-sm text-gray-500">
                        ${dependencia.descripcion || ''}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">${dependencia.codigo}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">${dependencia.responsable || 'No asignado'}</div>
            ${dependencia.telefono ? `<div class="text-sm text-gray-500">${dependencia.telefono}</div>` : ''}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${estadoBadgeClass}">
                ${estadoTexto}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                <span class="font-medium">${dependencia.radicados_destino_count.toLocaleString()}</span> destino
            </div>
            ${dependencia.radicados_origen_count > 0 ? `<div class="text-sm text-gray-500">${dependencia.radicados_origen_count.toLocaleString()} origen</div>` : ''}
        </td>
        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
            <div class="relative inline-block text-left">
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                        onclick="toggleDropdown('dropdown-${dependencia.id}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>

                <div id="dropdown-${dependencia.id}"
                     class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                     data-dropdown-menu>
                    <div class="py-1" role="menu">
                        <!-- Editar -->
                        <button data-action="edit-dependencia"
                                data-dependencia-id="${dependencia.id}"
                                data-dependencia-codigo="${dependencia.codigo}"
                                data-dependencia-nombre="${dependencia.nombre}"
                                data-dependencia-sigla="${dependencia.sigla || ''}"
                                data-dependencia-descripcion="${dependencia.descripcion || ''}"
                                data-dependencia-responsable="${dependencia.responsable || ''}"
                                data-dependencia-telefono="${dependencia.telefono || ''}"
                                data-dependencia-email="${dependencia.email || ''}"
                                data-dependencia-activa="${dependencia.activa ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>

                        <!-- Activar/Desactivar -->
                        <form action="/admin/dependencias/${dependencia.id}/toggle-status"
                              method="POST"
                              class="inline w-full"
                              id="toggle-form-${dependencia.id}">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="button"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                    data-dependencia-name="${dependencia.nombre}"
                                    data-dependencia-active="${dependencia.activa ? 'true' : 'false'}"
                                    data-form-id="toggle-form-${dependencia.id}"
                                    data-listener-attached="false">
                                <svg class="w-4 h-4 mr-3 ${dependencia.activa ? 'text-orange-500' : 'text-green-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${dependencia.activa ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}"/>
                                </svg>
                                ${dependencia.activa ? 'Desactivar' : 'Activar'}
                            </button>
                        </form>

                        <!-- Separador -->
                        <div class="border-t border-gray-100"></div>

                        <!-- Eliminar -->
                        <button data-action="delete-dependencia"
                                data-dependencia-id="${dependencia.id}"
                                data-dependencia-name="${dependencia.nombre}"
                                data-radicados-count="${dependencia.radicados_destino_count + dependencia.radicados_origen_count}"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
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

console.log('Admin dependencias module loaded');
