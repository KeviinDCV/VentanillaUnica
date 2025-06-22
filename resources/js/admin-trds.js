// Gestión de TRDs - Admin
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de TRDs
    if (!document.querySelector('[data-page="admin-trds"]')) {
        return;
    }

    console.log('DOM loaded, initializing TRDs management...');

    // Configurar event listeners
    setupEventListeners();
    setupConfirmModalEventListeners();

    // Inicializar búsqueda en tiempo real
    initializeRealTimeSearch();

    console.log('TRDs management initialized successfully');
});

function setupEventListeners() {
    // Botón crear TRD
    document.querySelector('[data-action="create-trd"]')?.addEventListener('click', openCreateModal);
    
    // Botones editar TRD
    document.querySelectorAll('[data-action="edit-trd"]').forEach(button => {
        button.addEventListener('click', function() {
            const trdId = this.dataset.trdId;
            const codigo = this.dataset.trdCodigo;
            const serie = this.dataset.trdSerie;
            const subserie = this.dataset.trdSubserie;
            const asunto = this.dataset.trdAsunto;
            const retencionGestion = this.dataset.trdRetencionGestion;
            const retencionCentral = this.dataset.trdRetencionCentral;
            const disposicion = this.dataset.trdDisposicion;
            const observaciones = this.dataset.trdObservaciones;
            const activo = this.dataset.trdActivo === 'true';

            openEditModal(trdId, codigo, serie, subserie, asunto, retencionGestion, retencionCentral, disposicion, observaciones, activo);
        });
    });

    // Botones cambiar estado
    attachToggleStatusEventListeners();

    // Botones eliminar
    attachDeleteEventListeners();

    // Event listeners para los modales
    setupModalEventListeners();
    setupCreateModalEventListeners();
}

function attachToggleStatusEventListeners() {
    const toggleButtons = document.querySelectorAll('button[data-trd-name][data-form-id]:not([data-form-id*="delete"])');
    console.log(`Encontrados ${toggleButtons.length} botones de cambiar estado`);

    toggleButtons.forEach(button => {
        // Remover event listeners existentes
        button.removeEventListener('click', handleToggleStatus);
        // Agregar nuevo event listener
        button.addEventListener('click', handleToggleStatus);
    });
}

function attachDeleteEventListeners() {
    const deleteButtons = document.querySelectorAll('button[data-form-id*="delete-trd-form"]');
    console.log(`Encontrados ${deleteButtons.length} botones de eliminar`);

    deleteButtons.forEach(button => {
        // Remover event listeners existentes
        button.removeEventListener('click', handleDeleteTrd);
        // Agregar nuevo event listener
        button.addEventListener('click', handleDeleteTrd);
    });
}

function handleToggleStatus(event) {
    event.preventDefault();

    const button = event.target;
    const trdCodigo = button.dataset.trdName;
    const isActive = button.dataset.trdActive === 'true';
    const formId = button.dataset.formId;
    
    console.log(`Toggle status clicked for TRD: ${trdCodigo}, currently active: ${isActive}`);
    
    // Configurar modal de confirmación
    const modal = document.getElementById('confirmStatusModal');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const icon = document.getElementById('confirmModalIcon');
    const actionButton = document.getElementById('confirmModalAction');
    
    if (isActive) {
        title.textContent = 'Desactivar TRD';
        message.textContent = `¿Estás seguro de que deseas desactivar el TRD "${trdCodigo}"?`;
        icon.innerHTML = '<svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
        icon.className = 'flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full bg-orange-100';
        actionButton.textContent = 'Desactivar';
        actionButton.className = 'px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors min-w-[100px]';

        // Como respaldo, aplicar estilos inline también
        actionButton.style.backgroundColor = '#ea580c';
        actionButton.style.borderColor = '#ea580c';
        actionButton.style.color = '#ffffff';
    } else {
        title.textContent = 'Activar TRD';
        message.textContent = `¿Estás seguro de que deseas activar el TRD "${trdCodigo}"?`;
        icon.innerHTML = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        icon.className = 'flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full bg-green-100';
        actionButton.textContent = 'Activar';
        actionButton.className = 'px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors min-w-[100px]';

        // Como respaldo, aplicar estilos inline también
        actionButton.style.backgroundColor = '#16a34a';
        actionButton.style.borderColor = '#16a34a';
        actionButton.style.color = '#ffffff';
    }
    
    // Configurar acción del botón
    actionButton.onclick = function() {
        toggleTrdStatus(formId);
        closeConfirmModal();
    };
    
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

function handleDeleteTrd(event) {
    event.preventDefault();

    const button = event.target;
    const trdCodigo = button.dataset.trdName;
    const formId = button.dataset.formId;

    console.log(`Delete clicked for TRD: ${trdCodigo}`);

    // Configurar modal de confirmación
    const modal = document.getElementById('confirmStatusModal');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const icon = document.getElementById('confirmModalIcon');
    const actionButton = document.getElementById('confirmModalAction');

    title.textContent = 'Eliminar TRD';
    message.textContent = `¿Estás seguro de que deseas eliminar permanentemente el TRD "${trdCodigo}"? Esta acción no se puede deshacer.`;
    icon.innerHTML = '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
    icon.className = 'flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full bg-red-100';
    actionButton.textContent = 'Eliminar';
    actionButton.className = 'px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors min-w-[100px]';

    // Como respaldo, aplicar estilos inline también
    actionButton.style.backgroundColor = '#dc2626';
    actionButton.style.borderColor = '#dc2626';
    actionButton.style.color = '#ffffff';

    // Configurar acción del botón
    actionButton.onclick = function() {
        deleteTrd(formId);
        closeConfirmModal();
    };

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

function toggleTrdStatus(formId) {
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
            alert('Error al cambiar el estado del TRD');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cambiar el estado del TRD');
    });
}

function deleteTrd(formId) {
    const form = document.getElementById(formId);
    const url = form.action;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
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
            alert(data.message || 'Error al eliminar el TRD');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el TRD');
    });
}

function setupModalEventListeners() {
    // Modal de edición
    const editModal = document.getElementById('editTrdModal');
    const editForm = document.getElementById('editTrdForm');

    // Cerrar modal
    document.querySelectorAll('[data-action="close-edit-modal"]').forEach(button => {
        button.addEventListener('click', closeEditModal);
    });
    
    // Envío del formulario de edición
    editForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = this.action;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                location.reload(); // Recargar para mostrar cambios
            } else {
                showModalErrors('editModalErrors', 'editErrorsList', data.errors || ['Error al actualizar el TRD']);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModalErrors('editModalErrors', 'editErrorsList', ['Error de conexión']);
        });
    });
}

function setupCreateModalEventListeners() {
    // Modal de creación
    const createModal = document.getElementById('createTrdModal');
    const createForm = document.getElementById('createTrdForm');

    // Cerrar modal
    document.querySelectorAll('[data-action="close-create-modal"]').forEach(button => {
        button.addEventListener('click', closeCreateModal);
    });
    
    // Envío del formulario de creación
    createForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeCreateModal();
                location.reload(); // Recargar para mostrar el nuevo TRD
            } else {
                showModalErrors('createModalErrors', 'createErrorsList', data.errors || ['Error al crear el TRD']);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModalErrors('createModalErrors', 'createErrorsList', ['Error de conexión']);
        });
    });
}

function setupConfirmModalEventListeners() {
    // Modal de confirmación
    const confirmModal = document.getElementById('confirmStatusModal');

    // Cerrar modal
    document.querySelectorAll('[data-action="close-confirm-modal"]').forEach(button => {
        button.addEventListener('click', closeConfirmModal);
    });
    
    // Cerrar modal al hacer clic fuera
    confirmModal?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });
}

function openCreateModal() {
    const modal = document.getElementById('createTrdModal');
    const form = document.getElementById('createTrdForm');

    // Limpiar formulario
    form.reset();
    document.getElementById('create_activo').checked = true;

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
    const modal = document.getElementById('createTrdModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function openEditModal(trdId, codigo, serie, subserie, asunto, retencionGestion, retencionCentral, disposicion, observaciones, activo) {
    const modal = document.getElementById('editTrdModal');

    // Llenar formulario con datos
    document.getElementById('edit_trd_id').value = trdId;
    document.getElementById('edit_codigo').value = codigo || '';
    document.getElementById('edit_serie').value = serie || '';
    document.getElementById('edit_subserie').value = subserie || '';
    document.getElementById('edit_asunto').value = asunto || '';
    document.getElementById('edit_retencion_gestion').value = retencionGestion || '';
    document.getElementById('edit_retencion_central').value = retencionCentral || '';
    document.getElementById('edit_disposicion_final').value = disposicion || '';
    document.getElementById('edit_observaciones').value = observaciones || '';
    document.getElementById('edit_activo').checked = activo;

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
    const modal = document.getElementById('editTrdModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function showModalErrors(modalErrorsId, errorsListId, errors) {
    const modalErrors = document.getElementById(modalErrorsId);
    const errorsList = document.getElementById(errorsListId);
    
    if (modalErrors && errorsList) {
        errorsList.innerHTML = '';
        
        if (Array.isArray(errors)) {
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorsList.appendChild(li);
            });
        } else if (typeof errors === 'object') {
            Object.values(errors).flat().forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorsList.appendChild(li);
            });
        }
        
        modalErrors.classList.remove('hidden');
    }
}

function clearModalErrors(modalErrorsId) {
    const modalErrors = document.getElementById(modalErrorsId);
    if (modalErrors) {
        modalErrors.classList.add('hidden');
    }
}

// Variables para búsqueda
let searchTimeout;

function initializeRealTimeSearch() {
    const searchInput = document.getElementById('buscar-trds');
    const filterSelect = document.getElementById('filtro-estado');
    
    if (!searchInput || !filterSelect) {
        console.log('Elementos de búsqueda no encontrados');
        return;
    }
    
    console.log('Inicializando búsqueda en tiempo real para TRDs...');
    
    // Event listener para el campo de búsqueda
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();
        const filtroEstado = filterSelect.value;

        // Debounce para evitar demasiadas peticiones
        searchTimeout = setTimeout(() => {
            if (termino.length === 0 && filtroEstado === '') {
                // Si no hay término de búsqueda ni filtro, recargar todas los TRDs
                location.reload();
            } else if (termino.length >= 2 || filtroEstado !== '') {
                // Buscar si hay al menos 2 caracteres o hay filtro seleccionado
                searchTrds(termino, filtroEstado);
            } else if (termino.length === 0 && filtroEstado !== '') {
                // Solo filtro sin búsqueda
                searchTrds('', filtroEstado);
            }
        }, 300);
    });

    // Event listener para el filtro de estado
    filterSelect.addEventListener('change', function() {
        const termino = searchInput.value.trim();
        const filtroEstado = this.value;
        
        if (termino.length === 0 && filtroEstado === '') {
            location.reload();
        } else {
            searchTrds(termino, filtroEstado);
        }
    });
}

function searchTrds(termino, filtroEstado = '') {
    let url = `/admin/trds/buscar?termino=${encodeURIComponent(termino)}`;

    if (filtroEstado) {
        url += `&estado=${encodeURIComponent(filtroEstado)}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateTrdsTable(data.trds);
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
        });
}

function updateTrdsTable(trds) {
    const tbody = document.getElementById('trds-table-body');
    if (!tbody) return;

    // Limpiar tabla actual
    tbody.innerHTML = '';

    // Agregar TRDs filtrados
    trds.forEach(trd => {
        const row = createTrdRow(trd);
        tbody.appendChild(row);
    });

    // Re-agregar event listeners
    attachToggleStatusEventListeners();
    attachDeleteEventListeners();

    // Re-agregar event listeners para editar
    document.querySelectorAll('[data-action="edit-trd"]').forEach(button => {
        button.addEventListener('click', function() {
            const trdId = this.dataset.trdId;
            const codigo = this.dataset.trdCodigo;
            const serie = this.dataset.trdSerie;
            const subserie = this.dataset.trdSubserie;
            const asunto = this.dataset.trdAsunto;
            const retencionGestion = this.dataset.trdRetencionGestion;
            const retencionCentral = this.dataset.trdRetencionCentral;
            const disposicion = this.dataset.trdDisposicion;
            const observaciones = this.dataset.trdObservaciones;
            const activo = this.dataset.trdActivo === 'true';

            openEditModal(trdId, codigo, serie, subserie, asunto, retencionGestion, retencionCentral, disposicion, observaciones, activo);
        });
    });
}

function createTrdRow(trd) {
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50';

    const estadoBadge = trd.activo
        ? '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>'
        : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>';

    const toggleButtonText = trd.activo ? 'Desactivar' : 'Activar';
    const toggleButtonClass = trd.activo
        ? 'text-orange-600 hover:text-orange-900'
        : 'text-green-600 hover:text-green-900';

    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">${trd.codigo}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900">${trd.serie}</div>
            ${trd.subserie ? `<div class="text-sm text-gray-500">${trd.subserie}</div>` : ''}
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900">${trd.asunto}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                AG: ${trd.retencion_archivo_gestion} años
            </div>
            <div class="text-sm text-gray-500">
                AC: ${trd.retencion_archivo_central} años
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            ${estadoBadge}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            ${new Intl.NumberFormat().format(trd.radicados_count || 0)}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                <button data-action="edit-trd"
                        data-trd-id="${trd.id}"
                        data-trd-codigo="${trd.codigo}"
                        data-trd-serie="${trd.serie}"
                        data-trd-subserie="${trd.subserie || ''}"
                        data-trd-asunto="${trd.asunto}"
                        data-trd-retencion-gestion="${trd.retencion_archivo_gestion}"
                        data-trd-retencion-central="${trd.retencion_archivo_central}"
                        data-trd-disposicion="${trd.disposicion_final}"
                        data-trd-observaciones="${trd.observaciones || ''}"
                        data-trd-activo="${trd.activo ? 'true' : 'false'}"
                        class="text-blue-600 hover:text-blue-900 font-medium text-xs sm:text-sm">
                    Editar
                </button>

                <form action="/admin/trds/${trd.id}/toggle-status"
                      method="POST"
                      class="inline"
                      id="toggle-trd-form-${trd.id}">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="button"
                            class="${toggleButtonClass} font-medium text-xs sm:text-sm cursor-pointer"
                            data-trd-name="${trd.codigo}"
                            data-trd-active="${trd.activo ? 'true' : 'false'}"
                            data-form-id="toggle-trd-form-${trd.id}">
                        ${toggleButtonText}
                    </button>
                </form>
            </div>
        </td>
    `;

    return row;
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
