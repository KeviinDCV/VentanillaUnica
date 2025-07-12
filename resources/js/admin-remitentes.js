// Función para manejar los menús desplegables (disponible globalmente)
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) {
        console.error('No se encontró el dropdown con ID:', dropdownId);
        return;
    }

    const isHidden = dropdown.classList.contains('hidden');

    // Cerrar todos los dropdowns abiertos
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== dropdownId) {
            d.classList.add('hidden');
        }
    });

    if (isHidden) {
        // Mostrar dropdown
        dropdown.classList.remove('hidden');
    } else {
        // Ocultar dropdown
        dropdown.classList.add('hidden');
    }
}

// Hacer la función disponible globalmente inmediatamente
window.toggleDropdown = toggleDropdown;

document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let remitenteActual = null;

    // Referencias a elementos del DOM
    const modalRemitente = document.getElementById('modal-remitente');
    const formRemitente = document.getElementById('form-remitente');
    const btnCrearRemitente = document.getElementById('btn-crear-remitente');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const btnCancelar = document.getElementById('btn-cancelar');
    const buscarInput = document.getElementById('buscar-remitente');

    // Event Listeners
    btnCerrarModal?.addEventListener('click', cerrarModal);
    btnCancelar?.addEventListener('click', cerrarModal);
    formRemitente?.addEventListener('submit', guardarRemitente);

    // Event listeners para búsqueda en tiempo real
    if (buscarInput) {
        initializeRealTimeSearch();
    }

    // Configurar event listeners para el modal de confirmación
    setupConfirmModalEventListeners();

    // Event listeners para botones de la tabla
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar')) {
            e.preventDefault();
            e.stopPropagation();
            const btn = e.target.closest('.btn-editar');
            abrirModalEditar(btn);
            return;
        } else if (e.target.closest('.btn-eliminar')) {
            e.preventDefault();
            e.stopPropagation();
            const btn = e.target.closest('.btn-eliminar');
            confirmarEliminacion(btn);
            return;
        } else if (e.target.closest('[data-action="create-remitente"]')) {
            abrirModalCrear();
            return;
        }
    });

    // Cerrar modal al hacer clic en el fondo
    if (modalRemitente) {
        modalRemitente.addEventListener('click', function(e) {
            if (e.target === modalRemitente) {
                cerrarModal();
            }
        });
    }

    // Funciones principales
    function closeAllDropdowns() {
        // Cerrar todos los dropdowns abiertos
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }

    function setupConfirmModalEventListeners() {
        // Solo manejar tecla Escape - el resto se maneja en el event listener principal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const confirmModal = document.getElementById('confirmStatusModal');
                if (confirmModal && !confirmModal.classList.contains('hidden')) {
                    closeConfirmModal();
                }
                if (modalRemitente && !modalRemitente.classList.contains('hidden')) {
                    cerrarModal();
                }
            }
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
        console.log('abrirModalEditar ejecutándose');

        // Cerrar todos los menús desplegables
        closeAllDropdowns();

        remitenteActual = {
            id: btn.dataset.id,
            nombre_completo: btn.dataset.nombreCompleto,
            tipo: btn.dataset.tipo,
            tipo_documento: btn.dataset.tipoDocumento,
            numero_documento: btn.dataset.numeroDocumento,
            email: btn.dataset.email,
            telefono: btn.dataset.telefono,
            direccion: btn.dataset.direccion,
            ciudad: btn.dataset.ciudad,
            departamento: btn.dataset.departamento,
            entidad: btn.dataset.entidad
        };

        document.getElementById('modal-titulo').textContent = 'Editar Remitente';
        document.getElementById('btn-guardar').textContent = 'Actualizar';

        // Llenar el formulario
        document.getElementById('remitente-id').value = remitenteActual.id;
        document.getElementById('nombre_completo').value = remitenteActual.nombre_completo;
        document.getElementById('tipo_documento').value = remitenteActual.tipo_documento || '';
        document.getElementById('numero_documento').value = remitenteActual.numero_documento || '';
        document.getElementById('email').value = remitenteActual.email || '';
        document.getElementById('telefono').value = remitenteActual.telefono || '';
        document.getElementById('direccion').value = remitenteActual.direccion || '';
        document.getElementById('entidad').value = remitenteActual.entidad || '';

        // Manejar departamento y ciudad
        const departamentoNombre = remitenteActual.departamento || '';
        const ciudadNombre = remitenteActual.ciudad || '';

        // Buscar departamento por nombre y seleccionarlo
        const departamentoSelect = document.getElementById('departamento_id');
        const departamentoNombreInput = document.getElementById('departamento_nombre');

        if (departamentoSelect && departamentoNombre) {
            const departamentoOption = Array.from(departamentoSelect.options).find(option =>
                option.dataset.nombre === departamentoNombre
            );

            if (departamentoOption) {
                departamentoSelect.value = departamentoOption.value;
                if (departamentoNombreInput) {
                    departamentoNombreInput.value = departamentoNombre;
                }

                // Cargar ciudades y seleccionar la ciudad correspondiente
                if (ciudadNombre) {
                    cargarCiudadesPorDepartamento(departamentoOption.value).then(() => {
                        const ciudadSelect = document.getElementById('ciudad_id');
                        const ciudadNombreInput = document.getElementById('ciudad_nombre');

                        if (ciudadSelect) {
                            const ciudadOption = Array.from(ciudadSelect.options).find(option =>
                                option.dataset.nombre === ciudadNombre
                            );

                            if (ciudadOption) {
                                ciudadSelect.value = ciudadOption.value;
                                if (ciudadNombreInput) {
                                    ciudadNombreInput.value = ciudadNombre;
                                }
                            }
                        }
                    });
                }
            }
        }

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

        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;

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
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    // Función para mostrar modal de confirmación personalizado
    function showConfirmModal(options) {
        console.log('Creando modal dinámico...');

        // Eliminar cualquier modal previo
        const existingModal = document.getElementById('dynamic-confirm-modal');
        if (existingModal) {
            existingModal.remove();
        }

        // Crear modal con la estructura exacta de ciudades
        const modalHtml = `
            <div id="dynamic-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full backdrop-blur-sm" style="z-index: 999999 !important; display: block !important;">
                <div id="modal-content" class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-2xl rounded-lg bg-white transform transition-all duration-300 ease-in-out" style="opacity: 0; transform: scale(0.95) translateY(-20px);">
                    <!-- Header del Modal -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">${options.title}</h3>
                        <button id="dynamic-close-btn" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-4">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full ${options.iconClass}">
                                <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm text-gray-600">${options.message}</p>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="dynamic-cancel-btn" class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="button" id="dynamic-confirm-btn" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}">
                                ${options.actionText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insertar en el body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Event listeners
        const modal = document.getElementById('dynamic-confirm-modal');
        const modalContent = document.getElementById('modal-content');
        const cancelBtn = document.getElementById('dynamic-cancel-btn');
        const confirmBtn = document.getElementById('dynamic-confirm-btn');

        const closeModal = () => {
            if (modal && modal.parentNode) {
                // Animación de salida
                modalContent.style.opacity = '0';
                modalContent.style.transform = 'scale(0.95) translateY(-20px)';

                setTimeout(() => {
                    if (modal && modal.parentNode) {
                        modal.remove();
                    }
                    document.body.style.overflow = '';
                }, 300);
            }
        };

        cancelBtn.onclick = closeModal;
        confirmBtn.onclick = () => {
            if (options.onConfirm) {
                options.onConfirm();
            }
            closeModal();
        };

        // Cerrar con Escape
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);

        // Cerrar al hacer clic en el fondo
        modal.onclick = (e) => {
            if (e.target === modal) {
                closeModal();
            }
        };

        // Bloquear scroll del body
        document.body.style.overflow = 'hidden';

        // Animación de entrada
        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
        }, 10);

        console.log('Modal dinámico creado y mostrado');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmStatusModal');
        if (!modal) return;

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
    }

    // Funciones para búsqueda en tiempo real del lado del servidor
    function initializeRealTimeSearch() {
        const searchInput = document.getElementById('buscar-remitente');
        if (!searchInput) return;

        let searchTimeout;

        // Event listener para el campo de búsqueda
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const termino = this.value.trim();

            // Debounce para evitar demasiadas peticiones
            searchTimeout = setTimeout(() => {
                if (termino.length >= 2 || termino.length === 0) {
                    // Buscar si hay al menos 2 caracteres o mostrar todos si está vacío
                    searchRemitentes(termino);
                }
            }, 300);
        });
    }

    function searchRemitentes(termino) {
        // Mostrar indicador de carga
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            tableContainer.style.opacity = '0.6';
        }

        // Construir URL con parámetro de búsqueda
        const url = new URL(window.location.href);
        if (termino) {
            url.searchParams.set('buscar', termino);
        } else {
            url.searchParams.delete('buscar');
        }
        url.searchParams.delete('page'); // Resetear paginación

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Crear un documento temporal para extraer el contenido
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            // Extraer la tabla actualizada
            const newTableContainer = tempDiv.querySelector('.bg-white.border.border-gray-200.rounded-lg.shadow-sm');
            const currentTableContainer = document.querySelector('.bg-white.border.border-gray-200.rounded-lg.shadow-sm');

            if (newTableContainer && currentTableContainer) {
                currentTableContainer.innerHTML = newTableContainer.innerHTML;

                // Reinicializar event listeners para los nuevos elementos
                initializeTableEventListeners();
            }

            // Actualizar URL sin recargar la página
            window.history.pushState({}, '', url.toString());
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
            showConfirmModal({
                title: 'Error de Búsqueda',
                message: 'No se pudo realizar la búsqueda. Verifique su conexión a internet.',
                actionText: 'Aceptar',
                actionClass: 'bg-red-600 hover:bg-red-700',
                iconClass: 'bg-red-100',
                iconColor: 'text-red-600',
                onConfirm: () => {}
            });
        })
        .finally(() => {
            // Quitar indicador de carga
            if (tableContainer) {
                tableContainer.style.opacity = '1';
            }
        });
    }

    // Función para reinicializar event listeners después de actualizar la tabla
    function initializeTableEventListeners() {
        // Reinicializar tooltips si existen
        if (typeof initializeTooltips === 'function') {
            initializeTooltips();
        }

        // Reinicializar otros event listeners específicos de la tabla
        // (agregar aquí cualquier otro event listener que necesite reinicializarse)
    }



    // Función para cargar ciudades por departamento (retorna promesa)
    function cargarCiudadesPorDepartamento(departamentoId) {
        const ciudadSelect = document.getElementById('ciudad_id');
        const ciudadNombreInput = document.getElementById('ciudad_nombre');

        if (!ciudadSelect) return Promise.resolve();

        // Mostrar estado de carga
        ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
        ciudadSelect.disabled = true;
        ciudadSelect.classList.add('bg-gray-100');

        // Realizar petición AJAX
        return fetch(`/api/ciudades/por-departamento?departamento_id=${departamentoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar ciudades');
                }
                return response.json();
            })
            .then(ciudades => {
                // Limpiar select
                ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';

                // Agregar ciudades
                ciudades.forEach(ciudad => {
                    const option = document.createElement('option');
                    option.value = ciudad.id;
                    option.textContent = ciudad.nombre;
                    option.dataset.nombre = ciudad.nombre;
                    ciudadSelect.appendChild(option);
                });

                // Habilitar select
                ciudadSelect.disabled = false;
                ciudadSelect.classList.remove('bg-gray-100');

                return ciudades;
            })
            .catch(error => {
                console.error('Error al cargar ciudades:', error);
                ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
                ciudadSelect.disabled = true;
                ciudadSelect.classList.add('bg-gray-100');
                throw error;
            });
    }









    // Hacer las funciones globales para que puedan ser llamadas desde el HTML
    window.showConfirmModal = showConfirmModal;
    window.closeConfirmModal = closeConfirmModal;
    window.cargarCiudadesPorDepartamento = cargarCiudadesPorDepartamento;
});
