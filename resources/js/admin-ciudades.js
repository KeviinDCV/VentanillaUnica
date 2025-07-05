document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin ciudades module loaded');
    
    // Variables globales
    let ciudadActual = null;
    
    // Referencias a elementos del DOM
    const modalCiudad = document.getElementById('modal-ciudad');
    const formCiudad = document.getElementById('form-ciudad');
    const btnCrearCiudad = document.getElementById('btn-crear-ciudad');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const buscarInput = document.getElementById('buscar-ciudad');
    const filtroDepartamento = document.getElementById('filtro-departamento');
    const filtroEstado = document.getElementById('filtro-estado');

    // Event listeners
    if (btnCrearCiudad) {
        btnCrearCiudad.addEventListener('click', abrirModalCrear);
    }
    
    if (btnCerrarModal) {
        btnCerrarModal.addEventListener('click', cerrarModal);
    }
    
    if (btnCancelar) {
        btnCancelar.addEventListener('click', cerrarModal);
    }
    
    // Configurar event listeners para el modal de confirmación
    setupConfirmModalEventListeners();
    
    if (formCiudad) {
        formCiudad.addEventListener('submit', guardarCiudad);
    }
    
    if (buscarInput) {
        buscarInput.addEventListener('input', filtrarCiudades);
    }
    
    if (filtroDepartamento) {
        filtroDepartamento.addEventListener('change', filtrarCiudades);
    }
    
    if (filtroEstado) {
        filtroEstado.addEventListener('change', filtrarCiudades);
    }

    // Event listeners para botones de la tabla
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar')) {
            const btn = e.target.closest('.btn-editar');
            abrirModalEditar(btn);
        } else if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            confirmarEliminacion(btn);
        } else if (e.target.closest('.btn-toggle-estado')) {
            const btn = e.target.closest('.btn-toggle-estado');
            confirmarToggleEstado(btn);
        }
    });

    // Cerrar modal al hacer clic en el fondo
    if (modalCiudad) {
        modalCiudad.addEventListener('click', function(e) {
            if (e.target === modalCiudad) {
                cerrarModal();
            }
        });
    }



    // Funciones principales
    function closeAllDropdowns() {
        // Cerrar todos los dropdowns abiertos
        document.querySelectorAll('[id^="dropdown-ciudad-"]').forEach(dropdown => {
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
        ciudadActual = null;
        document.getElementById('modal-title').textContent = 'Crear Nueva Ciudad';
        document.getElementById('btn-guardar').textContent = 'Crear Ciudad';
        formCiudad.reset();
        document.getElementById('campo-activo').style.display = 'none';
        
        // Mostrar modal
        modalCiudad.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animación de entrada
        const modalContent = modalCiudad.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';
        
        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
            // Focus en el primer campo
            document.getElementById('ciudad-nombre').focus();
        }, 10);
    }

    function abrirModalEditar(btn) {
        ciudadActual = btn.dataset.id;
        document.getElementById('modal-title').textContent = 'Editar Ciudad';
        document.getElementById('btn-guardar').textContent = 'Actualizar Ciudad';

        // Llenar el formulario
        document.getElementById('ciudad-nombre').value = btn.dataset.nombre || '';
        document.getElementById('ciudad-codigo').value = btn.dataset.codigo || '';
        document.getElementById('ciudad-departamento').value = btn.dataset.departamentoId || '';
        document.getElementById('ciudad-activo').checked = btn.dataset.activo === 'true';
        document.getElementById('campo-activo').style.display = 'block';

        // Mostrar modal
        modalCiudad.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animación de entrada
        const modalContent = modalCiudad.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';
        
        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
            // Focus en el primer campo
            document.getElementById('ciudad-nombre').focus();
        }, 10);
    }

    function cerrarModal() {
        const modalContent = modalCiudad.querySelector('.relative');
        
        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';
        
        setTimeout(() => {
            modalCiudad.classList.add('hidden');
            document.body.style.overflow = 'auto';
            formCiudad.reset();
            ciudadActual = null;
        }, 200);
    }



    async function guardarCiudad(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const isEdit = ciudadActual !== null;
        
        const data = {
            nombre: formData.get('nombre'),
            codigo: formData.get('codigo') || null,
            departamento_id: formData.get('departamento_id'),
        };
        
        if (isEdit) {
            data.activo = document.getElementById('ciudad-activo').checked;
        }
        
        try {
            const url = isEdit ? `/admin/ciudades/${ciudadActual}` : '/admin/ciudades';
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message, 'success');
                cerrarModal();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification(result.message || 'Error al guardar la ciudad', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al procesar la solicitud', 'error');
        }
    }

    async function eliminarCiudad(id) {
        try {
            const response = await fetch(`/admin/ciudades/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                closeConfirmModal();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification(result.message || 'Error al eliminar la ciudad', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al eliminar la ciudad', 'error');
        }
    }

    async function toggleEstado(id) {
        try {
            const response = await fetch(`/admin/ciudades/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification(result.message || 'Error al cambiar el estado', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    function confirmarToggleEstado(btn) {
        // Cerrar todos los menús desplegables
        closeAllDropdowns();

        const id = btn.dataset.id;
        const isActive = btn.textContent.trim().includes('Desactivar');
        const action = isActive ? 'desactivar' : 'activar';
        const actionCapital = isActive ? 'Desactivar' : 'Activar';

        // Buscar el nombre de la ciudad desde la fila
        const row = btn.closest('tr');
        const nombreElement = row.querySelector('.text-sm.font-medium.text-gray-900');
        const nombre = nombreElement ? nombreElement.textContent.trim() : 'esta ciudad';

        showConfirmModal({
            title: `${actionCapital} Ciudad`,
            message: `¿Estás seguro de que deseas ${action} la ciudad "${nombre}"?`,
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
            title: 'Eliminar Ciudad',
            message: `¿Estás seguro de que deseas eliminar permanentemente la ciudad "${nombre}"? Esta acción no se puede deshacer.`,
            actionText: 'Eliminar',
            actionClass: 'bg-red-600 hover:bg-red-700',
            iconClass: 'bg-red-100',
            iconColor: 'text-red-600',
            onConfirm: () => {
                eliminarCiudad(id);
            }
        });
    }

    function filtrarCiudades() {
        const busqueda = buscarInput.value.toLowerCase();
        const departamento = filtroDepartamento.value;
        const estado = filtroEstado.value;
        const filas = document.querySelectorAll('.ciudad-row');

        filas.forEach(fila => {
            const nombre = fila.dataset.name || '';
            const departamentoName = fila.dataset.departamentoName || '';
            const departamentoId = fila.dataset.departamentoId;
            const activo = fila.dataset.active;
            
            const coincideBusqueda = nombre.includes(busqueda) || departamentoName.includes(busqueda);
            
            let coincideDepartamento = true;
            if (departamento !== '') {
                coincideDepartamento = departamentoId === departamento;
            }
            
            let coincideEstado = true;
            if (estado !== '') {
                const estadoBoolean = estado === '1' ? 'true' : 'false';
                coincideEstado = activo === estadoBoolean;
            }
            
            if (coincideBusqueda && coincideDepartamento && coincideEstado) {
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
