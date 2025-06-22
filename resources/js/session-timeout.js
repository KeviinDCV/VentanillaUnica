// Session timeout management for UniRadic
class SessionTimeout {
    constructor(timeoutMinutes = 5) {
        this.timeoutMinutes = timeoutMinutes;
        this.timeoutMs = timeoutMinutes * 60 * 1000;
        this.warningMs = (timeoutMinutes - 1) * 60 * 1000; // Warning 1 minute before timeout
        this.timeoutId = null;
        this.warningId = null;
        this.lastActivity = Date.now();
        
        this.init();
    }
    
    init() {
        // Events that reset the timeout
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => this.resetTimeout(), true);
        });
        
        // Start the timeout
        this.resetTimeout();
    }
    
    resetTimeout() {
        this.lastActivity = Date.now();
        
        // Clear existing timeouts
        if (this.timeoutId) clearTimeout(this.timeoutId);
        if (this.warningId) clearTimeout(this.warningId);
        
        // Hide warning if visible
        this.hideWarning();
        
        // Set warning timeout (1 minute before session expires)
        this.warningId = setTimeout(() => {
            this.showWarning();
        }, this.warningMs);
        
        // Set session timeout
        this.timeoutId = setTimeout(() => {
            this.logout();
        }, this.timeoutMs);
    }
    
    showWarning() {
        // Usar el sistema de notificaciones personalizado
        const sessionId = Date.now() + Math.random();
        const actions = `
            <button class="uniradica-notification-btn primary" data-action="extend-session" data-session-id="${sessionId}">
                Continuar Sesión
            </button>
        `;

        this.warningNotificationId = window.UniRadicNotifications.show({
            type: 'warning',
            title: 'Sesión por Expirar',
            message: 'Su sesión expirará en 60 segundos por inactividad. Es posible que los cambios no se guarden.',
            persistent: true,
            actions: actions
        });

        // Start countdown
        this.startCountdown();
    }
    
    hideWarning() {
        if (this.warningNotificationId) {
            window.UniRadicNotifications.close(this.warningNotificationId);
            this.warningNotificationId = null;
        }
    }
    
    startCountdown() {
        let seconds = 60;
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            seconds--;
            if (countdownElement) {
                countdownElement.textContent = seconds;
            }
            
            if (seconds <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
    
    logout() {
        // Create a form to submit logout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize session timeout when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get timeout from meta tag or default to 5 minutes
    const timeoutMeta = document.querySelector('meta[name="session-timeout"]');
    const timeoutMinutes = timeoutMeta ? parseInt(timeoutMeta.getAttribute('content')) : 5;

    // Crear instancia global para acceso desde notificaciones
    window.sessionTimeout = new SessionTimeout(timeoutMinutes);
});
