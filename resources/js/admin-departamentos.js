/**
 * Funcionalidad para la administración de departamentos
 */

document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let departamentoActual = null;
    let accionActual = null;

    // Elementos del DOM
    const modalDepartamento = document.getElementById('modal-departamento');
    const modalConfirmacion = document.getElementById('modal-confirmacion');
    const formDepartamento = document.getElementById('form-departamento');
    const buscarInput = document.getElementById('buscar-departamento');
    const filtroEstado = document.getElementById('filtro-estado');

    // Botones
    const btnCrear = document.getElementById('btn-crear-departamento');
    const btnCerrar = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnCancelarConfirmacion = document.getElementById('btn-cancelar-confirmacion');
    const btnConfirmarAccion = document.getElementById('btn-confirmar-accion');

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

    if (btnCancelarConfirmacion) {
        btnCancelarConfirmacion.addEventListener('click', cerrarModalConfirmacion);
    }

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
            toggleEstado(btn.dataset.id);
        }

        if (e.target.closest('.btn-eliminar')) {
            const btn = e.target.closest('.btn-eliminar');
            confirmarEliminacion(btn.dataset.id, btn.dataset.nombre);
        }
    });

    // Cerrar modales al hacer click fuera
    window.addEventListener('click', function(e) {
        if (e.target === modalDepartamento) {
            cerrarModal();
        }
        if (e.target === modalConfirmacion) {
            cerrarModalConfirmacion();
        }
    });

    // Funciones
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

    function confirmarEliminacion(id, nombre) {
        accionActual = { tipo: 'eliminar', id: id };
        document.getElementById('confirmacion-mensaje').textContent =
            `¿Estás seguro de que deseas eliminar el departamento "${nombre}"?`;
        document.getElementById('btn-confirmar-accion').textContent = 'Eliminar';

        // Mostrar modal
        modalConfirmacion.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        const modalContent = modalConfirmacion.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
        }, 10);

        btnConfirmarAccion.onclick = function() {
            eliminarDepartamento(id);
        };
    }

    async function eliminarDepartamento(id) {
        try {
            btnConfirmarAccion.disabled = true;
            btnConfirmarAccion.textContent = 'Eliminando...';

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
                cerrarModalConfirmacion();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el departamento', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        } finally {
            btnConfirmarAccion.disabled = false;
            btnConfirmarAccion.textContent = 'Eliminar';
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
});


