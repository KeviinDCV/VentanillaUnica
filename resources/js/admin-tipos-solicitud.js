// Gestión de Tipos de Solicitud
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const btnCrearTipo = document.getElementById('btn-crear-tipo');
    const modalTipo = document.getElementById('modal-tipo');
    const formTipo = document.getElementById('form-tipo');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const buscarTipo = document.getElementById('buscar-tipo');
    const filtroEstado = document.getElementById('filtro-estado');

    // Variables globales
    let tipoActual = null;

    // Event Listeners
    btnCrearTipo?.addEventListener('click', () => abrirModalCrear());
    btnCerrarModal?.addEventListener('click', cerrarModal);
    btnCancelar?.addEventListener('click', cerrarModal);
    formTipo?.addEventListener('submit', guardarTipo);
    buscarTipo?.addEventListener('input', debounce(buscarTipos, 300));
    filtroEstado?.addEventListener('change', buscarTipos);

    // Event listeners para el modal de confirmación
    setupConfirmModalEventListeners();

    // Event delegation para botones dinámicos
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar')) {
            const btn = e.target.closest('.btn-editar');
            abrirModalEditar(btn);
        } else if (e.target.closest('.btn-toggle-estado')) {
            const btn = e.target.closest('.btn-toggle-estado');
            confirmarToggleEstado(btn);
        } else if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            confirmarEliminacion(btn);
        }
    });

    // Funciones principales
    function abrirModalCrear() {
        tipoActual = null;
        document.getElementById('modal-title').textContent = 'Nuevo Tipo de Solicitud';
        document.getElementById('btn-guardar').textContent = 'Guardar Tipo';
        document.getElementById('campo-activo').style.display = 'none';
        formTipo.reset();

        // Mostrar modal con animación
        modalTipo.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        const modalContent = modalTipo.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
            document.getElementById('tipo-nombre').focus();
        }, 10);
    }

    function abrirModalEditar(btn) {
        tipoActual = btn.dataset.id;
        document.getElementById('modal-title').textContent = 'Editar Tipo de Solicitud';
        document.getElementById('btn-guardar').textContent = 'Actualizar Tipo';
        document.getElementById('campo-activo').style.display = 'block';

        // Llenar formulario
        document.getElementById('tipo-id').value = btn.dataset.id;
        document.getElementById('tipo-nombre').value = btn.dataset.nombre;
        document.getElementById('tipo-codigo').value = btn.dataset.codigo;
        document.getElementById('tipo-descripcion').value = btn.dataset.descripcion || '';
        document.getElementById('tipo-activo').checked = btn.dataset.activo === 'true';

        // Mostrar modal con animación
        modalTipo.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        const modalContent = modalTipo.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
            document.getElementById('tipo-nombre').focus();
        }, 10);
    }

    function cerrarModal() {
        const modalContent = modalTipo.querySelector('.relative');

        if (modalContent) {
            // Animación de salida
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'scale(0.95) translateY(-20px)';

            setTimeout(() => {
                modalTipo.classList.add('hidden');
                document.body.style.overflow = '';
                formTipo.reset();
                tipoActual = null;
            }, 200);
        } else {
            modalTipo.classList.add('hidden');
            document.body.style.overflow = '';
            formTipo.reset();
            tipoActual = null;
        }
    }

    function closeAllDropdowns() {
        // Cerrar todos los dropdowns abiertos
        document.querySelectorAll('[id^="dropdown-tipo-"]').forEach(dropdown => {
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

    async function guardarTipo(e) {
        e.preventDefault();
        
        const formData = new FormData(formTipo);
        const url = tipoActual ? 
            `/admin/tipos-solicitud/${tipoActual}` : 
            '/admin/tipos-solicitud';
        const method = tipoActual ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                cerrarModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification(data.message || 'Error al guardar el tipo de solicitud', 'error');
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

        // Buscar el nombre del tipo desde la fila
        const row = btn.closest('tr');
        const nombreElement = row.querySelector('.text-sm.font-medium.text-gray-900');
        const nombre = nombreElement ? nombreElement.textContent.trim() : 'este tipo de solicitud';

        showConfirmModal({
            title: `${actionCapital} Tipo de Solicitud`,
            message: `¿Estás seguro de que deseas ${action} el tipo de solicitud "${nombre}"?`,
            actionText: actionCapital,
            actionClass: isActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
            iconClass: isActive ? 'bg-orange-100' : 'bg-green-100',
            iconColor: isActive ? 'text-orange-600' : 'text-green-600',
            onConfirm: () => {
                toggleEstado(id);
            }
        });
    }

    async function toggleEstado(id) {
        try {
            const response = await fetch(`/admin/tipos-solicitud/${id}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification(data.message || 'Error al cambiar el estado', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    function confirmarEliminacion(btn) {
        // Cerrar todos los menús desplegables
        closeAllDropdowns();

        const nombre = btn.dataset.nombre;
        const uso = parseInt(btn.dataset.uso) || 0;

        if (uso > 0) {
            showConfirmModal({
                title: 'No se puede eliminar',
                message: `El tipo de solicitud "${nombre}" tiene ${uso} radicado(s) asociado(s). No se puede eliminar. Puede desactivarlo en su lugar.`,
                actionText: 'Entendido',
                actionClass: 'bg-blue-600 hover:bg-blue-700',
                iconClass: 'bg-yellow-100',
                iconColor: 'text-yellow-600',
                onConfirm: () => {
                    closeConfirmModal();
                }
            });
            return;
        }

        showConfirmModal({
            title: 'Eliminar Tipo de Solicitud',
            message: `¿Estás seguro de que deseas eliminar permanentemente el tipo de solicitud "${nombre}"? Esta acción no se puede deshacer.`,
            actionText: 'Eliminar',
            actionClass: 'bg-red-600 hover:bg-red-700',
            iconClass: 'bg-red-100',
            iconColor: 'text-red-600',
            onConfirm: () => {
                eliminarTipo(btn.dataset.id);
            }
        });
    }

    async function eliminarTipo(id) {
        try {
            const response = await fetch(`/admin/tipos-solicitud/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                closeConfirmModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el tipo de solicitud', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }



    async function buscarTipos() {
        const termino = buscarTipo.value;
        const estado = filtroEstado.value;

        try {
            const params = new URLSearchParams();
            if (termino) params.append('termino', termino);
            if (estado) params.append('estado', estado);

            const response = await fetch(`/admin/tipos-solicitud/buscar?${params}`, {
                headers: {
                    'Accept': 'application/json',
                }
            });

            const tipos = await response.json();
            actualizarTablaTipos(tipos);
        } catch (error) {
            console.error('Error en búsqueda:', error);
        }
    }

    function actualizarTablaTipos(tipos) {
        const tbody = document.getElementById('tabla-tipos');
        if (!tbody) return;

        tbody.innerHTML = '';

        tipos.forEach(tipo => {
            const row = crearFilaTipo(tipo);
            tbody.appendChild(row);
        });
    }

    function crearFilaTipo(tipo) {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 tipo-row';
        tr.dataset.id = tipo.id;
        tr.dataset.name = tipo.nombre.toLowerCase();
        tr.dataset.codigo = tipo.codigo ? tipo.codigo.toLowerCase() : '';
        tr.dataset.active = tipo.activo ? 'true' : 'false';

        const estadoBadge = tipo.activo ? 
            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>Activo</span>' :
            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>Inactivo</span>';

        const toggleAction = tipo.activo ? 'Desactivar' : 'Activar';
        const toggleColor = tipo.activo ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50';
        const toggleIcon = tipo.activo ? 
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';

        tr.innerHTML = `
            <td class="px-4 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <div class="text-sm font-medium text-gray-900 truncate" title="${tipo.nombre}">
                            ${tipo.nombre}
                        </div>
                        <div class="text-xs text-gray-500 md:hidden truncate">
                            ${tipo.codigo || 'Sin código'} • ${tipo.radicados_count} radicados
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-3 py-4 hidden md:table-cell">
                <div class="text-sm text-gray-900 font-mono">${tipo.codigo || 'Sin código'}</div>
            </td>
            <td class="px-3 py-4 hidden lg:table-cell">
                <div class="text-sm text-gray-900 truncate" title="${tipo.descripcion || ''}">
                    ${tipo.descripcion || 'Sin descripción'}
                </div>
            </td>
            <td class="px-3 py-4 hidden md:table-cell">
                <div class="text-sm text-gray-900">${tipo.radicados_count}</div>
            </td>
            <td class="px-3 py-4">
                ${estadoBadge}
            </td>
            <td class="px-3 py-4 text-sm font-medium">
                <div class="relative inline-block text-left">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue" onclick="toggleDropdown('dropdown-tipo-${tipo.id}')">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                    <div id="dropdown-tipo-${tipo.id}" class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" data-dropdown-menu>
                        <div class="py-1" role="menu">
                            <button class="btn-editar w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${tipo.id}" data-nombre="${tipo.nombre}" data-codigo="${tipo.codigo || ''}" data-descripcion="${tipo.descripcion || ''}" data-activo="${tipo.activo}">
                                <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </button>
                            <button class="btn-toggle-estado w-full text-left px-4 py-2 text-sm ${toggleColor} flex items-center" data-id="${tipo.id}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${toggleIcon}
                                </svg>
                                ${toggleAction}
                            </button>
                            <div class="border-t border-gray-100"></div>
                            <button class="btn-eliminar w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center" data-id="${tipo.id}" data-nombre="${tipo.nombre}" data-uso="${tipo.radicados_count}">
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

        return tr;
    }

    // Utilidades
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

    function showNotification(message, type = 'info') {
        // Usar el sistema de notificaciones personalizado si está disponible
        if (window.UniRadicNotifications) {
            window.UniRadicNotifications.show({
                type: type,
                title: type === 'success' ? 'Éxito' : type === 'error' ? 'Error' : 'Información',
                message: message,
                duration: 4000
            });
        } else {
            // Fallback a alert
            alert(message);
        }
    }

    // Función para mostrar modal de confirmación personalizado
    function showConfirmModal(options) {
        // Buscar el modal en la página actual
        let modal = document.getElementById('confirmStatusModal');

        // Si no existe, crearlo dinámicamente
        if (!modal) {
            modal = createConfirmModal();
            document.body.appendChild(modal);
        }

        const title = document.getElementById('confirmModalTitle');
        const message = document.getElementById('confirmModalMessage');
        const actionButton = document.getElementById('confirmModalAction');
        const iconContainer = document.getElementById('confirmModalIcon');

        if (!title || !message || !actionButton || !iconContainer) {
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

    function createConfirmModal() {
        const modal = document.createElement('div');
        modal.id = 'confirmStatusModal';
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden';

        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transition-all duration-300 ease-in-out">
                <div class="mt-3 text-center">
                    <div id="confirmModalIcon" class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 id="confirmModalTitle" class="text-lg font-medium text-gray-900 mt-4">Confirmar Acción</h3>
                    <div class="mt-2 px-7 py-3">
                        <p id="confirmModalMessage" class="text-sm text-gray-500">¿Estás seguro de que deseas realizar esta acción?</p>
                    </div>
                    <div class="flex justify-center space-x-4 px-4 py-3">
                        <button onclick="closeConfirmModal()" class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors min-w-[100px]">
                            Cancelar
                        </button>
                        <button id="confirmModalAction" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors min-w-[100px]">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        `;

        return modal;
    }

    // Hacer las funciones globales para que puedan ser llamadas desde el HTML
    window.showConfirmModal = showConfirmModal;
    window.closeConfirmModal = closeConfirmModal;
});
