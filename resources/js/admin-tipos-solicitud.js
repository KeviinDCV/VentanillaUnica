// Gestión de Tipos de Solicitud
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const btnCrearTipo = document.getElementById('btn-crear-tipo');
    const modalTipo = document.getElementById('modal-tipo');
    const modalConfirmacion = document.getElementById('modal-confirmacion');
    const formTipo = document.getElementById('form-tipo');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const btnCancelarConfirmacion = document.getElementById('btn-cancelar-confirmacion');
    const btnConfirmarAccion = document.getElementById('btn-confirmar-accion');
    const buscarTipo = document.getElementById('buscar-tipo');
    const filtroEstado = document.getElementById('filtro-estado');

    // Variables globales
    let tipoActual = null;
    let accionConfirmacion = null;

    // Event Listeners
    btnCrearTipo?.addEventListener('click', () => abrirModalCrear());
    btnCerrarModal?.addEventListener('click', cerrarModal);
    btnCancelar?.addEventListener('click', cerrarModal);
    btnCancelarConfirmacion?.addEventListener('click', cerrarModalConfirmacion);
    btnConfirmarAccion?.addEventListener('click', ejecutarAccionConfirmacion);
    formTipo?.addEventListener('submit', guardarTipo);
    buscarTipo?.addEventListener('input', debounce(buscarTipos, 300));
    filtroEstado?.addEventListener('change', buscarTipos);

    // Event delegation para botones dinámicos
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar')) {
            const btn = e.target.closest('.btn-editar');
            abrirModalEditar(btn);
        } else if (e.target.closest('.btn-toggle-estado')) {
            const btn = e.target.closest('.btn-toggle-estado');
            toggleEstado(btn.dataset.id);
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
        modalTipo.classList.remove('hidden');
        document.getElementById('tipo-nombre').focus();
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
        
        modalTipo.classList.remove('hidden');
        document.getElementById('tipo-nombre').focus();
    }

    function cerrarModal() {
        modalTipo.classList.add('hidden');
        formTipo.reset();
        tipoActual = null;
    }

    function cerrarModalConfirmacion() {
        modalConfirmacion.classList.add('hidden');
        accionConfirmacion = null;
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
        const nombre = btn.dataset.nombre;
        const uso = parseInt(btn.dataset.uso) || 0;
        
        if (uso > 0) {
            showNotification(`No se puede eliminar "${nombre}" porque tiene ${uso} radicado(s) asociado(s)`, 'error');
            return;
        }

        document.getElementById('confirmacion-titulo').textContent = 'Confirmar Eliminación';
        document.getElementById('confirmacion-mensaje').textContent = 
            `¿Está seguro de que desea eliminar el tipo de solicitud "${nombre}"? Esta acción no se puede deshacer.`;
        
        accionConfirmacion = () => eliminarTipo(btn.dataset.id);
        modalConfirmacion.classList.remove('hidden');
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
                cerrarModalConfirmacion();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el tipo de solicitud', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        }
    }

    function ejecutarAccionConfirmacion() {
        if (accionConfirmacion) {
            accionConfirmacion();
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
        tr.dataset.codigo = tipo.codigo.toLowerCase();
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
                            ${tipo.codigo} • ${tipo.radicados_count} radicados
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-3 py-4 hidden md:table-cell">
                <div class="text-sm text-gray-900 font-mono">${tipo.codigo}</div>
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
                    <div id="dropdown-tipo-${tipo.id}" class="hidden origin-top-right fixed w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="z-index: 9999;" data-dropdown-menu>
                        <div class="py-1" role="menu">
                            <button class="btn-editar w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center" data-id="${tipo.id}" data-nombre="${tipo.nombre}" data-codigo="${tipo.codigo}" data-descripcion="${tipo.descripcion || ''}" data-activo="${tipo.activo}">
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
});
