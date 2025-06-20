/**
 * UniRadic - Session Manager
 * Manejo inteligente de sesiones y prevención de expiración
 */

class SessionManager {
    constructor() {
        this.sessionLifetime = 120; // minutos (debe coincidir con config)
        this.warningTime = 10; // minutos antes de expirar para mostrar advertencia
        this.checkInterval = 60000; // 1 minuto
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.isActive = true;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.startSessionCheck();
        this.setupCSRFRefresh();
    }

    bindEvents() {
        // Detectar actividad del usuario
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, { passive: true });
        });

        // Detectar cuando la ventana pierde/gana foco
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.checkSessionStatus();
            }
        });

        // NOTA: La protección de beforeunload ahora se maneja en form-protection.js
        // para evitar conflictos y dobles confirmaciones
    }

    updateActivity() {
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.hideWarning();
    }

    startSessionCheck() {
        setInterval(() => {
            this.checkSessionStatus();
        }, this.checkInterval);
    }

    checkSessionStatus() {
        const now = Date.now();
        const timeSinceActivity = (now - this.lastActivity) / 1000 / 60; // minutos
        const timeUntilExpiry = this.sessionLifetime - timeSinceActivity;

        if (timeUntilExpiry <= 0) {
            this.handleSessionExpired();
        } else if (timeUntilExpiry <= this.warningTime && !this.warningShown) {
            this.showSessionWarning(Math.ceil(timeUntilExpiry));
        }
    }

    showSessionWarning(minutesLeft) {
        this.warningShown = true;
        
        // Crear modal de advertencia
        const modal = this.createWarningModal(minutesLeft);
        document.body.appendChild(modal);
        
        // Auto-hide después de 30 segundos si no hay interacción
        setTimeout(() => {
            if (modal.parentNode) {
                this.hideWarning();
            }
        }, 30000);
    }

    createWarningModal(minutesLeft) {
        const modal = document.createElement('div');
        modal.id = 'session-warning-modal';
        modal.className = 'fixed inset-0 z-50 overflow-y-auto';
        modal.innerHTML = `
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Sesión por Expirar
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Tu sesión expirará en <strong id="countdown">${minutesLeft}</strong> minuto${minutesLeft !== 1 ? 's' : ''}.
                                        ¿Deseas extender tu sesión?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="sessionManager.extendSession()" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Extender Sesión
                        </button>
                        <button type="button" onclick="sessionManager.logout()" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Iniciar countdown
        this.startCountdown(minutesLeft);

        return modal;
    }

    startCountdown(minutes) {
        let timeLeft = minutes * 60; // convertir a segundos
        
        const countdownInterval = setInterval(() => {
            const minutesLeft = Math.floor(timeLeft / 60);
            const secondsLeft = timeLeft % 60;
            
            const countdownElement = document.getElementById('countdown');
            if (countdownElement) {
                if (minutesLeft > 0) {
                    countdownElement.textContent = `${minutesLeft}:${secondsLeft.toString().padStart(2, '0')}`;
                } else {
                    countdownElement.textContent = `${secondsLeft}`;
                }
            }
            
            timeLeft--;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                this.handleSessionExpired();
            }
        }, 1000);
    }

    extendSession() {
        // Hacer una petición para extender la sesión
        fetch('/csrf-token', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Actualizar token CSRF
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                csrfMeta.setAttribute('content', data.token);
            }
            
            // Actualizar todos los tokens en formularios
            const csrfInputs = document.querySelectorAll('input[name="_token"]');
            csrfInputs.forEach(input => {
                input.value = data.token;
            });
            
            // Resetear actividad
            this.updateActivity();
            this.hideWarning();
            
            // Mostrar mensaje de éxito
            this.showNotification('Sesión extendida exitosamente', 'success');
        })
        .catch(error => {
            console.error('Error extendiendo sesión:', error);
            this.showNotification('Error al extender la sesión', 'error');
        });
    }

    logout() {
        window.location.href = '/logout';
    }

    hideWarning() {
        const modal = document.getElementById('session-warning-modal');
        if (modal) {
            modal.remove();
        }
        this.warningShown = false;
    }

    handleSessionExpired() {
        this.hideWarning();
        this.showNotification('Tu sesión ha expirado. Serás redirigido al login.', 'warning');
        
        setTimeout(() => {
            window.location.href = '/login';
        }, 3000);
    }

    setupCSRFRefresh() {
        // Refrescar token CSRF cada 30 minutos
        setInterval(() => {
            this.refreshCSRFToken();
        }, 1800000); // 30 minutos
    }

    refreshCSRFToken() {
        fetch('/csrf-token')
            .then(response => response.json())
            .then(data => {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) {
                    csrfMeta.setAttribute('content', data.token);
                }
                
                const csrfInputs = document.querySelectorAll('input[name="_token"]');
                csrfInputs.forEach(input => {
                    input.value = data.token;
                });
            })
            .catch(error => {
                console.log('Error refreshing CSRF token:', error);
            });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
            type === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove después de 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    hasUnsavedChanges() {
        // Verificar si hay formularios con cambios no guardados
        const forms = document.querySelectorAll('form');
        for (let form of forms) {
            if (form.dataset.changed === 'true') {
                return true;
            }
        }
        return false;
    }
}

// Inicializar el gestor de sesiones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.sessionManager === 'undefined') {
        window.sessionManager = new SessionManager();
    }
});

// Detectar cambios en formularios
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                form.dataset.changed = 'true';
            });
        });
        
        form.addEventListener('submit', function() {
            form.dataset.changed = 'false';
        });
    });
});
