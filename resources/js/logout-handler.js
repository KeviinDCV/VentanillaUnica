/**
 * Logout Handler para UniRadic
 * Maneja el logout de forma segura y elegante
 */
class LogoutHandler {
    constructor() {
        this.init();
    }

    init() {
        // Buscar todos los enlaces de logout y agregar event listeners
        document.addEventListener('DOMContentLoaded', () => {
            this.attachLogoutHandlers();
        });

        // Si el DOM ya está cargado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.attachLogoutHandlers();
            });
        } else {
            this.attachLogoutHandlers();
        }
    }

    attachLogoutHandlers() {
        // Buscar todos los enlaces de logout con la clase específica
        const logoutLinks = document.querySelectorAll('.logout-link');

        logoutLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const form = link.closest('form[action*="logout"]');
                if (form) {
                    this.handleLogout(e, form);
                } else {
                    console.warn('LogoutHandler: No se encontró formulario de logout para el enlace');
                }
            });
        });

        // También manejar cualquier botón o enlace con atributo data-logout
        const logoutButtons = document.querySelectorAll('[data-logout]');
        logoutButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleLogoutButton(e);
            });
        });

        console.debug('LogoutHandler: Event listeners agregados a', logoutLinks.length, 'enlaces de logout');
    }

    handleLogout(event, form) {
        event.preventDefault();
        
        // Mostrar confirmación opcional (comentado por defecto)
        // if (!confirm('¿Está seguro que desea cerrar sesión?')) {
        //     return;
        // }

        // Deshabilitar el enlace para prevenir múltiples clicks
        const link = event.target;
        link.style.pointerEvents = 'none';
        link.style.opacity = '0.6';

        // Agregar indicador de carga
        const originalText = link.textContent;
        link.textContent = 'Cerrando sesión...';

        // Verificar que el formulario tenga token CSRF
        if (!this.hasCSRFToken(form)) {
            this.addCSRFToken(form);
        }

        // Limpiar sesión del lado del cliente antes del logout
        if (window.SessionSecurityHandler) {
            window.SessionSecurityHandler.clearSession();
        }

        // Enviar formulario
        try {
            form.submit();
        } catch (error) {
            console.error('Error al cerrar sesión:', error);

            // Restaurar enlace en caso de error
            link.style.pointerEvents = 'auto';
            link.style.opacity = '1';
            link.textContent = originalText;

            // Mostrar error al usuario
            alert('Error al cerrar sesión. Por favor, intente nuevamente.');
        }
    }

    handleLogoutButton(event) {
        event.preventDefault();
        
        // Crear formulario dinámicamente
        const form = this.createLogoutForm();
        document.body.appendChild(form);
        
        // Enviar formulario
        form.submit();
    }

    hasCSRFToken(form) {
        return form.querySelector('input[name="_token"]') !== null;
    }

    addCSRFToken(form) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.getAttribute('content');
            form.appendChild(tokenInput);
        }
    }

    createLogoutForm() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        form.style.display = 'none';

        // Agregar token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.getAttribute('content');
            form.appendChild(tokenInput);
        }

        return form;
    }

    // Método público para logout programático
    static logout() {
        const handler = new LogoutHandler();
        const form = handler.createLogoutForm();
        document.body.appendChild(form);
        form.submit();
    }
}

// Inicializar el handler automáticamente
const logoutHandler = new LogoutHandler();

// Exportar para uso global
window.LogoutHandler = LogoutHandler;
