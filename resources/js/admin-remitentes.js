document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let remitenteActual = null;

    // Referencias a elementos del DOM
    const modalRemitente = document.getElementById('modal-remitente');
    const formRemitente = document.getElementById('form-remitente');
    const btnCrearRemitente = document.getElementById('btn-crear-remitente');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');

    // Event Listeners
    btnCerrarModal?.addEventListener('click', cerrarModal);
    btnCancelar?.addEventListener('click', cerrarModal);
    formRemitente?.addEventListener('submit', guardarRemitente);

    // Configurar event listeners para el modal de confirmación
    setupConfirmModalEventListeners();

    // Event delegation para botones con data-action
    document.addEventListener('click', function(e) {
        const action = e.target.closest('[data-action]')?.getAttribute('data-action');

        switch(action) {
            case 'create-remitente':
                abrirModalCrear();
                break;
            case 'edit-remitente':
                const editBtn = e.target.closest('[data-action="edit-remitente"]');
                abrirModalEditar(editBtn);
                break;
            case 'delete-remitente':
                const deleteBtn = e.target.closest('[data-action="delete-remitente"]');
                confirmarEliminacion(deleteBtn);
                break;
        }
    });

    // Cerrar modales al hacer click fuera
    window.addEventListener('click', function(e) {
        if (e.target === modalRemitente) {
            cerrarModal();
        }
    });

    // Funciones principales
    function closeAllDropdowns() {
        // Cerrar todos los dropdowns abiertos
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
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
        remitenteActual = null;
        document.getElementById('modal-titulo').textContent = 'Crear Remitente';
        document.getElementById('btn-guardar').textContent = 'Crear';
        formRemitente.reset();
        document.getElementById('remitente-id').value = '';
        
        // Mostrar modal
        modalRemitente.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animación de entrada
        const modalContent = modalRemitente.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';
        
        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
        }, 10);
    }

    function abrirModalEditar(btn) {
        remitenteActual = {
            id: btn.dataset.remitenteId,
            nombre_completo: btn.dataset.remitenteNombre,
            tipo: btn.dataset.remitenteTipo,
            tipo_documento: btn.dataset.remitenteTipoDocumento,
            numero_documento: btn.dataset.remitenteNumeroDocumento,
            email: btn.dataset.remitenteEmail,
            telefono: btn.dataset.remitenteTelefono,
            direccion: btn.dataset.remitenteDireccion,
            ciudad: btn.dataset.remitenteCiudad,
            departamento: btn.dataset.remitenteDepartamento,
            entidad: btn.dataset.remitenteEntidad
        };

        document.getElementById('modal-titulo').textContent = 'Editar Remitente';
        document.getElementById('btn-guardar').textContent = 'Actualizar';

        // Llenar el formulario
        document.getElementById('remitente-id').value = remitenteActual.id;
        document.getElementById('nombre_completo').value = remitenteActual.nombre_completo;
        document.getElementById('tipo').value = remitenteActual.tipo;
        document.getElementById('tipo_documento').value = remitenteActual.tipo_documento || '';
        document.getElementById('numero_documento').value = remitenteActual.numero_documento || '';
        document.getElementById('email').value = remitenteActual.email || '';
        document.getElementById('telefono').value = remitenteActual.telefono || '';
        document.getElementById('direccion').value = remitenteActual.direccion || '';
        document.getElementById('ciudad').value = remitenteActual.ciudad || '';
        document.getElementById('departamento').value = remitenteActual.departamento || '';
        document.getElementById('entidad').value = remitenteActual.entidad || '';

        // Mostrar modal
        modalRemitente.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        const modalContent = modalRemitente.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
        }, 10);
    }

    function cerrarModal() {
        const modalContent = modalRemitente.querySelector('.relative');
        
        // Animación de salida
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';
        
        setTimeout(() => {
            modalRemitente.classList.add('hidden');
            document.body.style.overflow = '';
            formRemitente.reset();
            remitenteActual = null;
        }, 200);
    }

    async function guardarRemitente(e) {
        e.preventDefault();
        
        const formData = new FormData(formRemitente);
        const data = Object.fromEntries(formData);
        
        const btnGuardar = document.getElementById('btn-guardar');
        const textoOriginal = btnGuardar.textContent;
        
        try {
            btnGuardar.disabled = true;
            btnGuardar.textContent = remitenteActual ? 'Actualizando...' : 'Creando...';
            
            const url = remitenteActual 
                ? `/admin/remitentes/${remitenteActual.id}`
                : '/admin/remitentes';
            
            const method = remitenteActual ? 'PUT' : 'POST';
            
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
                    window.location.reload();
                }, 1000);
            } else {
                if (result.errors) {
                    // Mostrar errores de validación
                    let errorMessage = 'Errores de validación:\n';
                    Object.values(result.errors).forEach(errors => {
                        errors.forEach(error => {
                            errorMessage += `• ${error}\n`;
                        });
                    });
                    showNotification(errorMessage, 'error');
                } else {
                    showNotification(result.message || 'Error al guardar el remitente', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.textContent = textoOriginal;
        }
    }

    function confirmarEliminacion(btn) {
        // Cerrar todos los menús desplegables
        closeAllDropdowns();

        const id = btn.dataset.remitenteId;
        const nombre = btn.dataset.remitenteName;

        showConfirmModal({
            title: 'Eliminar Remitente',
            message: `¿Estás seguro de que deseas eliminar permanentemente el remitente "${nombre}"? Esta acción no se puede deshacer.`,
            actionText: 'Eliminar',
            actionClass: 'bg-red-600 hover:bg-red-700',
            iconClass: 'bg-red-100',
            iconColor: 'text-red-600',
            onConfirm: () => {
                eliminarRemitente(id);
            }
        });
    }

    async function eliminarRemitente(id) {
        try {
            const response = await fetch(`/admin/remitentes/${id}`, {
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
                showNotification(data.message || 'Error al eliminar el remitente', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    function showNotification(message, type = 'info') {
        if (window.UniRadicNotifications) {
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
