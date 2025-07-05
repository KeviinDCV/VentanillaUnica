document.addEventListener('DOMContentLoaded', function() {
    // Verificar que estamos en la página correcta
    if (!document.querySelector('[data-page="admin-comunicaciones"]')) {
        return;
    }

    console.log('Admin comunicaciones module loaded');

    // Variables globales
    let currentComunicacionId = null;
    let isEditing = false;

    // Elementos del DOM
    const createModal = document.getElementById('createComunicacionModal');
    const editModal = document.getElementById('editComunicacionModal');
    const confirmModal = document.getElementById('confirmStatusModal');
    const createForm = document.getElementById('createComunicacionForm');
    const editForm = document.getElementById('editComunicacionForm');
    const searchInput = document.getElementById('buscar-comunicaciones');
    const filterSelect = document.getElementById('filtro-estado');

    // Event Listeners principales
    document.addEventListener('click', handleDocumentClick);
    createForm?.addEventListener('submit', handleCreateSubmit);
    editForm?.addEventListener('submit', handleEditSubmit);
    searchInput?.addEventListener('input', debounce(handleSearch, 300));
    filterSelect?.addEventListener('change', handleFilter);

    // Función principal para manejar clics
    function handleDocumentClick(e) {
        // Botón crear comunicación
        if (e.target.closest('[data-action="create-comunicacion"]')) {
            e.preventDefault();
            openCreateModal();
        }

        // Botón editar comunicación
        if (e.target.closest('[data-action="edit-comunicacion"]')) {
            e.preventDefault();
            const btn = e.target.closest('[data-action="edit-comunicacion"]');
            openEditModal(btn);
        }

        // Botón eliminar comunicación
        if (e.target.closest('[data-action="delete-comunicacion"]')) {
            e.preventDefault();
            const btn = e.target.closest('[data-action="delete-comunicacion"]');
            showDeleteConfirmation(btn);
        }

        // Botones de toggle estado
        if (e.target.closest('[data-form-id]')) {
            e.preventDefault();
            const btn = e.target.closest('[data-form-id]');
            showStatusConfirmation(btn);
        }

        // Cerrar modales
        if (e.target.closest('[data-action="close-create-modal"]')) {
            closeModal('createComunicacionModal');
        }
        if (e.target.closest('[data-action="close-edit-modal"]')) {
            closeModal('editComunicacionModal');
        }
        if (e.target.closest('[data-action="close-confirm-modal"]')) {
            closeModal('confirmStatusModal');
        }

        // Cerrar modal al hacer clic en el overlay
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            if (e.target.id === 'createComunicacionModal') closeModal('createComunicacionModal');
            if (e.target.id === 'editComunicacionModal') closeModal('editComunicacionModal');
            if (e.target.id === 'confirmStatusModal') closeModal('confirmStatusModal');
        }
    }

    // Funciones para modales
    function openCreateModal() {
        const modal = document.getElementById('createComunicacionModal');
        const form = document.getElementById('createComunicacionForm');

        // Limpiar formulario
        form.reset();

        // Limpiar errores
        hideErrors('createModalErrors');

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
            // Focus en el primer campo
            document.getElementById('create_nombre').focus();
        }, 10);

        console.log('Modal de crear comunicación abierto');
    }

    function openEditModal(btn) {
        const id = btn.dataset.comunicacionId;
        const nombre = btn.dataset.comunicacionNombre;
        const codigo = btn.dataset.comunicacionCodigo;
        const descripcion = btn.dataset.comunicacionDescripcion || '';
        const activo = btn.dataset.comunicacionActivo === 'true';

        // Llenar formulario de edición
        document.getElementById('edit_comunicacion_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_codigo').value = codigo;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_activo').checked = activo;

        clearForm('editComunicacionForm', false);
        hideErrors('editModalErrors');

        // Mostrar modal con animación
        const modal = document.getElementById('editComunicacionModal');
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
            document.getElementById('edit_nombre').focus();
        }, 10);
    }

    function showDeleteConfirmation(btn) {
        const id = btn.dataset.comunicacionId;
        const nombre = btn.dataset.comunicacionName;
        const radicadosCount = parseInt(btn.dataset.radicadosCount) || 0;

        if (radicadosCount > 0) {
            showNotification('No se puede eliminar el tipo de comunicación porque tiene radicados asociados', 'error');
            return;
        }

        if (confirm(`¿Estás seguro de que deseas eliminar el tipo de comunicación "${nombre}"?`)) {
            deleteComunicacion(id);
        }
    }

    function showStatusConfirmation(btn) {
        const formId = btn.dataset.formId;
        const nombre = btn.dataset.comunicacionName;
        const isActive = btn.dataset.comunicacionActive === 'true';
        const action = isActive ? 'desactivar' : 'activar';

        if (confirm(`¿Estás seguro de que deseas ${action} el tipo de comunicación "${nombre}"?`)) {
            document.getElementById(formId).submit();
        }
    }

    // Funciones de envío de formularios
    async function handleCreateSubmit(e) {
        e.preventDefault();

        const formData = new FormData(createForm);
        const data = Object.fromEntries(formData.entries());

        // Asegurar que el checkbox se incluya correctamente
        data.activo = document.getElementById('create_activo').checked;

        try {
            const response = await fetch('/admin/comunicaciones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                closeModal('createComunicacionModal');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (result.errors) {
                    showFormErrors('createModalErrors', 'createErrorsList', result.errors);
                } else {
                    showNotification(result.message || 'Error al crear el tipo de comunicación', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    async function handleEditSubmit(e) {
        e.preventDefault();

        const formData = new FormData(editForm);
        const data = Object.fromEntries(formData.entries());
        const id = document.getElementById('edit_comunicacion_id').value;

        // Asegurar que el checkbox se incluya correctamente
        data.activo = document.getElementById('edit_activo').checked;

        try {
            const response = await fetch(`/admin/comunicaciones/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                closeModal('editComunicacionModal');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (result.errors) {
                    showFormErrors('editModalErrors', 'editErrorsList', result.errors);
                } else {
                    showNotification(result.message || 'Error al actualizar el tipo de comunicación', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    async function deleteComunicacion(id) {
        try {
            const response = await fetch(`/admin/comunicaciones/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(result.message || 'Error al eliminar el tipo de comunicación', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    // Funciones de búsqueda y filtros
    function handleSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value;
        filterTable(searchTerm, filterValue);
    }

    function handleFilter() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value;
        filterTable(searchTerm, filterValue);
    }

    function filterTable(searchTerm, filterValue) {
        const rows = document.querySelectorAll('.comunicacion-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const codigo = row.dataset.codigo || '';
            const descripcion = row.dataset.descripcion || '';

            // Determinar el estado de la fila
            const statusElement = row.querySelector('.bg-green-100, .bg-red-100');
            const isActive = statusElement && statusElement.classList.contains('bg-green-100');

            const matchesSearch = !searchTerm ||
                name.includes(searchTerm) ||
                codigo.includes(searchTerm) ||
                descripcion.includes(searchTerm);

            let matchesFilter = true;
            if (filterValue === 'activo') {
                matchesFilter = isActive;
            } else if (filterValue === 'inactivo') {
                matchesFilter = !isActive;
            }

            if (matchesSearch && matchesFilter) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Actualizar mensaje si no hay resultados
        updateNoResultsMessage(visibleCount);
    }

    function updateNoResultsMessage(count) {
        let noResultsRow = document.getElementById('no-results-row');
        const tbody = document.getElementById('comunicaciones-table-body');

        if (count === 0) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.id = 'no-results-row';
                noResultsRow.innerHTML = `
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-900 mb-1">No se encontraron resultados</p>
                            <p class="text-sm text-gray-500">Intenta ajustar los filtros de búsqueda</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(noResultsRow);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    // Funciones de utilidad
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animación de entrada
            const modalContent = modal.querySelector('.relative');
            if (modalContent) {
                modalContent.style.opacity = '0';
                modalContent.style.transform = 'scale(0.95) translateY(-20px)';

                setTimeout(() => {
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'scale(1) translateY(0)';
                }, 10);
            }

            console.log(`Modal ${modalId} mostrado`);
        } else {
            console.error(`Modal ${modalId} no encontrado`);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const modalContent = modal.querySelector('.relative');

            if (modalContent) {
                // Animación de salida
                modalContent.style.opacity = '0';
                modalContent.style.transform = 'scale(0.95) translateY(-20px)';

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 200);
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            console.log(`Modal ${modalId} cerrado`);
        }
    }

    function clearForm(formId, clearValues = true) {
        const form = document.getElementById(formId);
        if (form && clearValues) {
            form.reset();
        }
    }

    function hideErrors(errorContainerId) {
        const errorContainer = document.getElementById(errorContainerId);
        if (errorContainer) {
            errorContainer.classList.add('hidden');
        }
    }

    function showFormErrors(errorContainerId, errorListId, errors) {
        const errorContainer = document.getElementById(errorContainerId);
        const errorList = document.getElementById(errorListId);

        if (errorContainer && errorList) {
            errorList.innerHTML = '';

            Object.keys(errors).forEach(field => {
                errors[field].forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
            });

            errorContainer.classList.remove('hidden');
        }
    }

    function showNotification(message, type = 'info') {
        // Usar el sistema de notificaciones existente si está disponible
        if (window.UniRadicNotifications) {
            window.UniRadicNotifications.show({
                type: type,
                title: type === 'success' ? 'Éxito' : type === 'error' ? 'Error' : 'Información',
                message: message,
                duration: 4000
            });
        } else {
            // Fallback a console.log para desarrollo
            console.log(`${type.toUpperCase()}: ${message}`);

            // Mostrar una notificación simple
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
            } text-white`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 4000);
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
