document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin ciudades module loaded');
    
    // Variables globales
    let ciudadActual = null;
    let accionActual = null;
    
    // Referencias a elementos del DOM
    const modalCiudad = document.getElementById('modal-ciudad');
    const modalConfirmacion = document.getElementById('modal-confirmacion');
    const formCiudad = document.getElementById('form-ciudad');
    const btnCrearCiudad = document.getElementById('btn-crear-ciudad');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const btnCancelarConfirmacion = document.getElementById('btn-cancelar-confirmacion');
    const btnConfirmarAccion = document.getElementById('btn-confirmar-accion');
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
    
    if (btnCancelarConfirmacion) {
        btnCancelarConfirmacion.addEventListener('click', cerrarModalConfirmacion);
    }
    
    if (btnConfirmarAccion) {
        btnConfirmarAccion.addEventListener('click', ejecutarAccionConfirmada);
    }
    
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
            confirmarEliminacion(btn.dataset.id, btn.dataset.nombre);
        } else if (e.target.closest('.btn-toggle-estado')) {
            const btn = e.target.closest('.btn-toggle-estado');
            toggleEstado(btn.dataset.id);
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

    if (modalConfirmacion) {
        modalConfirmacion.addEventListener('click', function(e) {
            if (e.target === modalConfirmacion) {
                cerrarModalConfirmacion();
            }
        });
    }

    // Funciones principales
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
                cerrarModalConfirmacion();
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

    function confirmarEliminacion(id, nombre) {
        accionActual = { tipo: 'eliminar', id: id };
        document.getElementById('confirmacion-mensaje').textContent =
            `¿Estás seguro de que deseas eliminar la ciudad "${nombre}"?`;
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
            eliminarCiudad(id);
        };
    }

    function ejecutarAccionConfirmada() {
        if (accionActual && accionActual.tipo === 'eliminar') {
            eliminarCiudad(accionActual.id);
        }
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
});
