/**
 * Funcionalidad para la administración de departamentos
 */

document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let departamentoActual = null;

    // Elementos del DOM
    const modalDepartamento = document.getElementById('modal-departamento');
    const formDepartamento = document.getElementById('form-departamento');
    const buscarInput = document.getElementById('buscar-departamento');
    const filtroEstado = document.getElementById('filtro-estado');

    // Botones
    const btnCrear = document.getElementById('btn-crear-departamento');
    const btnCerrar = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const btnGuardar = document.getElementById('btn-guardar');

    // Event Listeners
    if (btnCrear) {
        btnCrear.addEventListener('click', abrirModalCrear);
    }

    if (btnCerrar) {
        btnCerrar.addEventListener('click', cerrarModal);
    }

    if (btnCancelar) {
        btnCancelar.addEventListener('click', cerrarModal);
    }

    // Configurar event listeners para el modal de confirmación
    setupConfirmModalEventListeners();

    if (formDepartamento) {
        formDepartamento.addEventListener('submit', guardarDepartamento);
    }

    if (buscarInput) {
        buscarInput.addEventListener('input', filtrarDepartamentos);
    }

    if (filtroEstado) {
        filtroEstado.addEventListener('change', filtrarDepartamentos);
    }

    // Event listeners para botones de acción
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar')) {
            const btn = e.target.closest('.btn-editar');
            abrirModalEditar(btn);
        }

        if (e.target.closest('.btn-toggle-estado')) {
            const btn = e.target.closest('.btn-toggle-estado');
            confirmarToggleEstado(btn);
        }

        if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            confirmarEliminacion(btn);
        }
    });

    // Cerrar modales al hacer click fuera
    window.addEventListener('click', function(e) {
        if (e.target === modalDepartamento) {
            cerrarModal();
        }
    });

    // Funciones
    function closeAllDropdowns() {
        // Cerrar todos los dropdowns abiertos
        document.querySelectorAll('[id^="dropdown-departamento-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
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
    }

    function abrirModalCrear() {
        departamentoActual = null;
        document.getElementById('modal-title').textContent = 'Crear Nuevo Departamento';
        document.getElementById('btn-guardar').textContent = 'Crear Departamento';
        formDepartamento.reset();
        document.getElementById('campo-activo').style.display = 'none';

        // Mostrar modal
        modalDepartamento.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        const modalContent = modalDepartamento.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
            // Focus en el primer campo
            document.getElementById('departamento-nombre').focus();
        }, 10);
    }

    function abrirModalEditar(btn) {
        departamentoActual = btn.dataset.id;
        document.getElementById('modal-title').textContent = 'Editar Departamento';
        document.getElementById('btn-guardar').textContent = 'Actualizar Departamento';

        // Llenar el formulario
        document.getElementById('departamento-nombre').value = btn.dataset.nombre || '';
        document.getElementById('departamento-codigo').value = btn.dataset.codigo || '';
        document.getElementById('departamento-activo').checked = btn.dataset.activo === 'true';
        document.getElementById('campo-activo').style.display = 'block';

        // Mostrar modal
        modalDepartamento.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        const modalContent = modalDepartamento.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
            // Focus en el primer campo
            document.getElementById('departamento-nombre').focus();
        }, 10);
    }

    function cerrarModal() {
        const modalContent = modalDepartamento.querySelector('.relative');

        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalDepartamento.classList.add('hidden');
            document.body.style.overflow = 'auto';
            formDepartamento.reset();
            departamentoActual = null;
        }, 200);
    }

    function cerrarModalConfirmacion() {
        const modalContent = modalConfirmacion.querySelector('.relative');

        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalConfirmacion.classList.add('hidden');
            document.body.style.overflow = 'auto';
            accionActual = null;
        }, 200);
    }

    async function guardarDepartamento(e) {
        e.preventDefault();
        
        const formData = new FormData(formDepartamento);
        const url = departamentoActual 
            ? `/admin/departamentos/${departamentoActual}`
            : '/admin/departamentos';
        
        if (departamentoActual) {
            formData.append('_method', 'PUT');
        }

        try {
            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                cerrarModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al guardar el departamento', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.textContent = departamentoActual ? 'Actualizar Departamento' : 'Crear Departamento';
        }
    }

    async function toggleEstado(id) {
        try {
            const response = await fetch(`/admin/departamentos/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al cambiar el estado', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    function confirmarToggleEstado(btn) {
        // Cerrar todos los menús desplegables
        closeAllDropdowns();

        const id = btn.dataset.id;
        const isActive = btn.textContent.trim().includes('Desactivar');
        const action = isActive ? 'desactivar' : 'activar';
        const actionCapital = isActive ? 'Desactivar' : 'Activar';

        // Buscar el nombre del departamento desde la fila
        const row = btn.closest('tr');
        const nombreElement = row.querySelector('.text-sm.font-medium.text-gray-900');
        const nombre = nombreElement ? nombreElement.textContent.trim() : 'este departamento';

        showConfirmModal({
            title: `${actionCapital} Departamento`,
            message: `¿Estás seguro de que deseas ${action} el departamento "${nombre}"?`,
            actionText: actionCapital,
            actionClass: isActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
            iconClass: isActive ? 'bg-orange-100' : 'bg-green-100',
            iconColor: isActive ? 'text-orange-600' : 'text-green-600',
            onConfirm: () => {
                toggleEstado(id);
            }
        });
    }

    function confirmarEliminacion(btn) {
        // Cerrar todos los menús desplegables
        closeAllDropdowns();

        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;

        showConfirmModal({
            title: 'Eliminar Departamento',
            message: `¿Estás seguro de que deseas eliminar permanentemente el departamento "${nombre}"? Esta acción no se puede deshacer.`,
            actionText: 'Eliminar',
            actionClass: 'bg-red-600 hover:bg-red-700',
            iconClass: 'bg-red-100',
            iconColor: 'text-red-600',
            onConfirm: () => {
                eliminarDepartamento(id);
            }
        });
    }

    async function eliminarDepartamento(id) {
        try {
            const response = await fetch(`/admin/departamentos/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                closeConfirmModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el departamento', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    function filtrarDepartamentos() {
        const busqueda = buscarInput.value.toLowerCase();
        const estado = filtroEstado.value;
        const filas = document.querySelectorAll('.departamento-row');

        filas.forEach(fila => {
            const nombre = fila.dataset.name || '';
            const activo = fila.dataset.active;

            const coincideBusqueda = nombre.includes(busqueda);

            // Convertir el valor del filtro a formato boolean string para comparar
            let coincideEstado = true;
            if (estado !== '') {
                const estadoBoolean = estado === '1' ? 'true' : 'false';
                coincideEstado = activo === estadoBoolean;
            }

            if (coincideBusqueda && coincideEstado) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'info') {
        if (typeof window.UniRadicNotifications !== 'undefined') {
            const title = type === 'success' ? 'Éxito' :
                         type === 'error' ? 'Error' :
                         type === 'warning' ? 'Advertencia' : 'Información';

            window.UniRadicNotifications.show({
                type: type,
                title: title,
                message: message,
                duration: 4000
            });
        } else {
            alert(message);
        }
    }

    // Función para mostrar modal de confirmación personalizado
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

        // Configurar ícono
        iconContainer.className = `flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full ${options.iconClass}`;
        iconContainer.innerHTML = `
            <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        `;

        // Limpiar todas las clases del botón y aplicar las nuevas
        actionButton.className = '';
        actionButton.className = `px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}`;

        // Forzar los estilos con colores específicos
        if (options.actionClass.includes('bg-orange-600')) {
            actionButton.style.backgroundColor = '#ea580c';
            actionButton.style.borderColor = '#ea580c';
        } else if (options.actionClass.includes('bg-red-600')) {
            actionButton.style.backgroundColor = '#dc2626';
            actionButton.style.borderColor = '#dc2626';
        } else if (options.actionClass.includes('bg-green-600')) {
            actionButton.style.backgroundColor = '#16a34a';
            actionButton.style.borderColor = '#16a34a';
        } else if (options.actionClass.includes('bg-blue-600')) {
            actionButton.style.backgroundColor = '#2563eb';
            actionButton.style.borderColor = '#2563eb';
        }

        // Asegurar que el texto sea blanco
        actionButton.style.color = '#ffffff';

        // Configurar event listener para el botón de acción
        actionButton.onclick = function() {
            if (options.onConfirm) {
                options.onConfirm();
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
        if (modal) {
            const modalContent = modal.querySelector('.relative');

            // Resetear estilos del botón
            const actionButton = document.getElementById('confirmModalAction');
            if (actionButton) {
                actionButton.className = 'px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors min-w-[100px]';
                actionButton.style.backgroundColor = '';
                actionButton.style.borderColor = '';
                actionButton.style.color = '';
                actionButton.onclick = null;
            }

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
        }
    }

    // Hacer las funciones globales para que puedan ser llamadas desde el HTML
    window.showConfirmModal = showConfirmModal;
    window.closeConfirmModal = closeConfirmModal;
});


