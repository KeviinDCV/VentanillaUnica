// Gestión de usuarios en el panel de administración
document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar en la página de usuarios
    if (!document.querySelector('[data-page="admin-usuarios"]')) {
        return;
    }

    initializeUserManagement();
});

let currentUserId = null;

function initializeUserManagement() {
    // Agregar event listeners a los botones de editar
    const editButtons = document.querySelectorAll('[data-action="edit-user"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const userDocumentoIdentidad = this.dataset.userDocumentoIdentidad || '';
            const userEmail = this.dataset.userEmail;
            const userRole = this.dataset.userRole;
            const userActive = this.dataset.userActive === 'true';

            openEditModal(userId, userName, userDocumentoIdentidad, userEmail, userRole, userActive);
        });
    });

    // Agregar event listener al botón de crear usuario
    const createButton = document.querySelector('[data-action="create-user"]');
    if (createButton) {
        createButton.addEventListener('click', function() {
            openCreateModal();
        });
    }

    // Agregar event listeners a los botones de eliminar
    attachDeleteEventListeners();

    // Agregar event listeners a los botones de activar/desactivar
    attachToggleStatusEventListeners();

    // Event listeners para los modales
    setupModalEventListeners();
    setupCreateModalEventListeners();
    setupConfirmModalEventListeners();

    // Inicializar búsqueda en tiempo real
    initializeRealTimeSearch();
}

function attachDeleteEventListeners() {
    // Remover event listeners existentes para evitar duplicados
    const deleteButtons = document.querySelectorAll('button[data-user-name][data-form-id]');
    console.log(`Encontrados ${deleteButtons.length} botones de eliminar`);

    deleteButtons.forEach(button => {
        // Clonar el botón para remover todos los event listeners
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);

        // Agregar el nuevo event listener
        newButton.addEventListener('click', function(e) {
            e.preventDefault();

            const userName = this.dataset.userName;
            const formId = this.dataset.formId;

            // Usar modal personalizado para eliminar
            showConfirmModal({
                title: 'Eliminar Usuario',
                message: `¿Estás seguro de que deseas eliminar al usuario "${userName}"?\n\nEsta acción no se puede deshacer.`,
                actionText: 'Eliminar',
                actionClass: 'bg-red-600 hover:bg-red-700',
                iconClass: 'bg-red-100',
                iconColor: 'text-red-600',
                onConfirm: () => {
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Formulario no encontrado:', formId);
                    }
                }
            });
        });
    });
}

function attachToggleStatusEventListeners() {
    const toggleButtons = document.querySelectorAll('button[data-user-active][data-form-id]');

    toggleButtons.forEach(button => {
        // Clonar el botón para remover todos los event listeners
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);

        // Agregar el nuevo event listener
        newButton.addEventListener('click', function(e) {
            e.preventDefault();

            const userName = this.dataset.userName;
            const userActive = this.dataset.userActive === 'true';
            const formId = this.dataset.formId;
            const accion = userActive ? 'desactivar' : 'activar';
            const accionCapital = userActive ? 'Desactivar' : 'Activar';

            // Usar modal personalizado en lugar de confirm()
            showConfirmModal({
                title: `${accionCapital} Usuario`,
                message: `¿Estás seguro de que deseas ${accion} al usuario "${userName}"?`,
                actionText: accionCapital,
                actionClass: userActive ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700',
                iconClass: userActive ? 'bg-orange-100' : 'bg-green-100',
                iconColor: userActive ? 'text-orange-600' : 'text-green-600',
                onConfirm: () => {
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Formulario no encontrado:', formId);
                    }
                }
            });
        });
    });
}

function openEditModal(userId, name, documentoIdentidad, email, role, active) {
    currentUserId = userId;

    // Llenar el formulario con los datos del usuario
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_documento_identidad').value = documentoIdentidad;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_active').checked = active;

    // Limpiar contraseñas
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_password_confirmation').value = '';

    // Configurar la acción del formulario
    document.getElementById('editUserForm').action = `/admin/usuarios/${userId}`;

    // Ocultar errores
    document.getElementById('modalErrors').classList.add('hidden');

    // Mostrar el modal con animación
    const modal = document.getElementById('editUserModal');
    const modalContent = modal.querySelector('.relative');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevenir scroll del fondo

    // Animación de entrada
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modalContent.style.opacity = '1';
        modalContent.style.transform = 'scale(1) translateY(0)';
    }, 10);
}

function closeEditModal() {
    const modal = document.getElementById('editUserModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restaurar scroll
        currentUserId = null;
    }, 200);
}

function setupModalEventListeners() {
    // Cerrar modal al hacer clic fuera de él
    document.getElementById('editUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('editUserModal').classList.contains('hidden')) {
            closeEditModal();
        }
    });

    // Botones de cerrar modal
    document.querySelectorAll('[data-action="close-modal"]').forEach(button => {
        button.addEventListener('click', closeEditModal);
    });

    // Manejar envío del formulario
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 422) {
                return response.json();
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito y cerrar modal
                closeEditModal();

                // Mostrar notificación de éxito
                showSuccessMessage('Usuario actualizado exitosamente');

                // Recargar página después de un breve delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (data.errors) {
                // Mostrar errores de validación
                showModalErrors(data.errors);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModalErrors({'general': ['Ocurrió un error al actualizar el usuario. Por favor, intenta nuevamente.']});
        });
    });
}

function openCreateModal() {
    const modal = document.getElementById('createUserModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body

        // Limpiar formulario
        document.getElementById('createUserForm').reset();

        // Marcar como activo por defecto
        document.getElementById('create_active').checked = true;

        // Ocultar errores
        document.getElementById('createModalErrors').classList.add('hidden');

        // Animación de entrada
        const modalContent = modal.querySelector('.relative');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95) translateY(-20px)';

        setTimeout(() => {
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1) translateY(0)';
        }, 10);

        // Enfocar el primer campo
        setTimeout(() => {
            document.getElementById('create_name').focus();
        }, 100);
    }
}

function closeCreateModal() {
    const modal = document.getElementById('createUserModal');
    const modalContent = modal.querySelector('.relative');

    // Animación de salida
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'scale(0.95) translateY(-20px)';

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restaurar scroll

        // Limpiar formulario
        document.getElementById('createUserForm').reset();

        // Ocultar errores
        document.getElementById('createModalErrors').classList.add('hidden');
    }, 200);
}

function setupCreateModalEventListeners() {
    // Cerrar modal al hacer clic fuera de él
    document.getElementById('createUserModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('createUserModal').classList.contains('hidden')) {
            closeCreateModal();
        }
    });

    // Botones de cerrar modal
    document.querySelectorAll('[data-action="close-create-modal"]').forEach(button => {
        button.addEventListener('click', closeCreateModal);
    });

    // Manejar envío del formulario
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 422) {
                return response.json();
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito y cerrar modal
                closeCreateModal();

                // Mostrar notificación de éxito
                showSuccessMessage('Usuario creado exitosamente');

                // Recargar página después de un breve delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (data.errors) {
                // Mostrar errores de validación
                showCreateModalErrors(data.errors);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCreateModalErrors({'general': ['Ocurrió un error al crear el usuario. Por favor, intenta nuevamente.']});
        });
    });
}

function showCreateModalErrors(errors) {
    const errorsList = document.getElementById('createErrorsList');
    const errorsContainer = document.getElementById('createModalErrors');

    // Limpiar errores anteriores
    errorsList.innerHTML = '';

    // Agregar nuevos errores
    Object.values(errors).flat().forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorsList.appendChild(li);
    });

    // Mostrar contenedor de errores
    errorsContainer.classList.remove('hidden');

    // Scroll al top del modal para ver los errores
    document.querySelector('#createUserModal .relative').scrollTop = 0;
}

function setupConfirmModalEventListeners() {
    // Cerrar modal al hacer clic fuera de él
    document.getElementById('confirmStatusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('confirmStatusModal').classList.contains('hidden')) {
            closeConfirmModal();
        }
    });

    // Botones de cerrar modal
    document.querySelectorAll('[data-action="close-confirm-modal"]').forEach(button => {
        button.addEventListener('click', closeConfirmModal);
    });

    // Botón de confirmación
    document.getElementById('confirmModalAction').addEventListener('click', function() {
        closeConfirmModal();
        if (currentConfirmAction) {
            currentConfirmAction();
            currentConfirmAction = null;
        }
    });
}

// Variable global para almacenar la función de confirmación
let currentConfirmAction = null;

function showConfirmModal(options) {
    const modal = document.getElementById('confirmStatusModal');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const actionButton = document.getElementById('confirmModalAction');
    const iconContainer = document.getElementById('confirmModalIcon');

    // Configurar contenido del modal
    title.textContent = options.title;
    message.textContent = options.message;
    actionButton.textContent = options.actionText;

    // Aplicar clases CSS completas
    actionButton.className = `px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition-colors min-w-[100px] ${options.actionClass}`;

    // Como respaldo, aplicar estilos inline también
    if (options.actionClass.includes('bg-orange-600')) {
        actionButton.style.backgroundColor = '#ea580c';
        actionButton.style.borderColor = '#ea580c';
    } else if (options.actionClass.includes('bg-green-600')) {
        actionButton.style.backgroundColor = '#16a34a';
        actionButton.style.borderColor = '#16a34a';
    } else if (options.actionClass.includes('bg-red-600')) {
        actionButton.style.backgroundColor = '#dc2626';
        actionButton.style.borderColor = '#dc2626';
    }

    console.log('Applied classes:', actionButton.className);
    console.log('Options actionClass:', options.actionClass);
    console.log('Button styles:', actionButton.style.backgroundColor);

    // Configurar icono
    iconContainer.className = `flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full ${options.iconClass}`;
    iconContainer.innerHTML = `
        <svg class="w-6 h-6 ${options.iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    `;

    // Guardar la función de confirmación globalmente
    currentConfirmAction = options.onConfirm;

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

function showModalErrors(errors) {
    const errorsList = document.getElementById('errorsList');
    const errorsContainer = document.getElementById('modalErrors');

    // Limpiar errores anteriores
    errorsList.innerHTML = '';

    // Agregar nuevos errores
    Object.values(errors).flat().forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorsList.appendChild(li);
    });

    // Mostrar contenedor de errores
    errorsContainer.classList.remove('hidden');

    // Scroll al top del modal para ver los errores
    document.querySelector('#editUserModal .relative').scrollTop = 0;
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



// Hacer funciones disponibles globalmente para debugging
window.UniRadicUserManagement = {
    openEditModal,
    closeEditModal,
    openCreateModal,
    closeCreateModal,
    showConfirmModal,
    closeConfirmModal,
    showSuccessMessage
};

// Funciones para búsqueda en tiempo real
function initializeRealTimeSearch() {
    const searchInput = document.getElementById('buscar-usuarios');
    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const termino = this.value.trim();

        // Debounce para evitar demasiadas peticiones
        searchTimeout = setTimeout(() => {
            if (termino.length >= 2 || termino.length === 0) {
                // Buscar si hay al menos 2 caracteres o mostrar todos si está vacío
                searchUsers(termino);
            }
        }, 300);
    });
}

function searchUsers(termino) {
    fetch(`/admin/usuarios/buscar?termino=${encodeURIComponent(termino)}`)
        .then(response => response.json())
        .then(data => {
            updateUsersTable(data.usuarios);
        })
        .catch(error => {
            // Error silencioso por seguridad
        });
}

function showAllUsers() {
    // Mostrar todas las filas que estaban ocultas
    const rows = document.querySelectorAll('.usuario-row');
    rows.forEach(row => {
        row.style.display = '';
    });
}

function updateUsersTable(usuarios) {
    const tbody = document.getElementById('usuarios-table-body');
    if (!tbody) return;

    // Limpiar tabla actual
    tbody.innerHTML = '';

    // Agregar usuarios filtrados
    usuarios.forEach(usuario => {
        const row = createUserRow(usuario);
        tbody.appendChild(row);
    });

    // Re-agregar event listeners para los nuevos elementos
    attachDeleteEventListeners();
    attachToggleStatusEventListeners();

    // Re-agregar event listeners para editar
    document.querySelectorAll('[data-action="edit-user"]').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const userDocumentoIdentidad = this.dataset.userDocumentoIdentidad || '';
            const userEmail = this.dataset.userEmail;
            const userRole = this.dataset.userRole;
            const userActive = this.dataset.userActive === 'true';

            openEditModal(userId, userName, userDocumentoIdentidad, userEmail, userRole, userActive);
        });
    });
}

function createUserRow(usuario) {
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50 usuario-row';
    row.setAttribute('data-name', usuario.name.toLowerCase());
    row.setAttribute('data-email', usuario.email.toLowerCase());
    row.setAttribute('data-role', usuario.role_display.toLowerCase());

    // Determinar el color del badge del rol
    let roleBadgeClass = 'bg-gray-100 text-gray-800';
    if (usuario.role === 'administrador') {
        roleBadgeClass = 'bg-purple-100 text-purple-800';
    } else if (usuario.role === 'ventanilla') {
        roleBadgeClass = 'bg-blue-100 text-blue-800';
    }

    // Determinar el color del badge del estado
    const estadoBadgeClass = usuario.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    const estadoTexto = usuario.active ? 'Activo' : 'Inactivo';

    // Obtener el ID del usuario actual para validaciones
    const currentUserId = parseInt(document.querySelector('meta[name="user-id"]')?.getAttribute('content'));

    row.innerHTML = `
        <td class="px-4 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-xs font-medium text-gray-700">
                            ${usuario.initials}
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900 truncate max-w-32">
                        ${usuario.name}
                    </div>
                    <div class="text-xs text-gray-500 md:hidden">
                        ${usuario.email}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-4 py-4 hidden md:table-cell">
            <div class="text-sm text-gray-900 truncate max-w-40">${usuario.email}</div>
        </td>
        <td class="px-4 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${roleBadgeClass}">
                ${usuario.role_display}
            </span>
        </td>
        <td class="px-4 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${estadoBadgeClass}">
                ${estadoTexto}
            </span>
        </td>
        <td class="px-4 py-4 text-sm text-gray-900 hidden lg:table-cell">
            <div class="flex items-center">
                <span class="font-medium">${usuario.radicados_count.toLocaleString()}</span>
                ${usuario.radicados_count > 0 ? '<span class="ml-1 w-2 h-2 bg-blue-500 rounded-full"></span>' : ''}
            </div>
        </td>
        <td class="px-4 py-4 text-sm text-gray-500 hidden xl:table-cell">
            ${usuario.created_at}
        </td>
        <td class="px-3 py-4 text-sm font-medium">
            <div class="relative inline-block text-left">
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uniradical-blue"
                        onclick="toggleDropdown('dropdown-user-${usuario.id}')">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>

                <div id="dropdown-user-${usuario.id}"
                     class="hidden absolute right-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                     data-dropdown-menu>
                    <div class="py-1" role="menu">
                        <!-- Editar -->
                        <button data-action="edit-user"
                                data-user-id="${usuario.id}"
                                data-user-name="${usuario.name}"
                                data-user-documento-identidad="${usuario.documento_identidad || ''}"
                                data-user-email="${usuario.email}"
                                data-user-role="${usuario.role}"
                                data-user-active="${usuario.active ? 'true' : 'false'}"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>

                        ${usuario.id !== currentUserId ? `
                        <!-- Activar/Desactivar -->
                        <form action="/admin/usuarios/${usuario.id}/toggle-status"
                              method="POST"
                              class="inline w-full"
                              id="toggle-form-${usuario.id}">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="button"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                    data-user-name="${usuario.name}"
                                    data-user-active="${usuario.active ? 'true' : 'false'}"
                                    data-form-id="toggle-form-${usuario.id}">
                                <svg class="w-4 h-4 mr-3 ${usuario.active ? 'text-orange-500' : 'text-green-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${usuario.active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}"/>
                                </svg>
                                ${usuario.active ? 'Desactivar' : 'Activar'}
                            </button>
                        </form>
                        ` : ''}

                        ${usuario.id !== currentUserId && usuario.radicados_count === 0 ? `
                        <!-- Eliminar -->
                        <form action="/admin/usuarios/${usuario.id}"
                              method="POST"
                              class="inline w-full"
                              id="delete-form-${usuario.id}">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                    data-user-name="${usuario.name}"
                                    data-form-id="delete-form-${usuario.id}">
                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                        ` : ''}
                    </div>
                </div>
            </div>
        </td>
    `;

    return row;
}


