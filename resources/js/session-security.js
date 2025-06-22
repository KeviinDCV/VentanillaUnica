/**
 * Session Security Handler para UniRadic
 * Previene acceso no autorizado mediante navegación hacia atrás
 * y verifica la validez de la sesión
 */
class SessionSecurityHandler {
    constructor() {
        this.sessionKey = 'uniradical_session_active';
        this.lastActivityKey = 'uniradical_last_activity';
        this.sessionTimeout = this.getSessionTimeout();
        this.checkInterval = 30000; // Verificar cada 30 segundos
        this.warningTime = 60000; // Advertir 1 minuto antes del timeout
        this.verifyingSession = false; // Prevenir verificaciones múltiples

        this.init();
    }

    init() {
        // TEMPORALMENTE DESHABILITADO para resolver problema de sesión inmediata
        console.debug('SessionSecurityHandler: Temporalmente deshabilitado para estabilizar sesiones');
        return;

        // Solo ejecutar en páginas autenticadas
        if (!this.isAuthenticatedPage()) {
            console.debug('SessionSecurityHandler: No es página autenticada, omitiendo inicialización');
            return;
        }

        document.addEventListener('DOMContentLoaded', () => {
            this.initializeSessionSecurity();
        });

        // Si el DOM ya está cargado
        if (document.readyState !== 'loading') {
            this.initializeSessionSecurity();
        }
    }

    initializeSessionSecurity() {
        console.debug('SessionSecurityHandler: Inicializando seguridad de sesión');
        
        // Marcar sesión como activa
        this.markSessionActive();
        
        // Configurar verificaciones periódicas
        this.startSessionMonitoring();
        
        // Manejar eventos de navegación
        this.setupNavigationHandlers();
        
        // Manejar visibilidad de página
        this.setupVisibilityHandlers();
        
        // Verificar si la página fue cargada desde cache
        this.checkPageFromCache();
    }

    isAuthenticatedPage() {
        // NO ejecutar en páginas de login o guest
        const currentPath = window.location.pathname;
        const guestPages = ['/login', '/register', '/forgot-password', '/reset-password'];

        // Si estamos en una página de guest, no es autenticada
        if (guestPages.some(page => currentPath.startsWith(page))) {
            return false;
        }

        // Verificar si hay elementos que indican una página autenticada
        // Excluir meta csrf-token porque también existe en login
        const authIndicators = [
            'form[action*="logout"]',
            '.logout-link',
            '[data-authenticated]',
            'nav[x-data]' // Navegación de usuario autenticado
        ];

        return authIndicators.some(selector => document.querySelector(selector) !== null);
    }

    getSessionTimeout() {
        // Obtener timeout de sesión desde meta tag o usar default
        const timeoutMeta = document.querySelector('meta[name="session-timeout"]');
        if (timeoutMeta) {
            return parseInt(timeoutMeta.getAttribute('content')) * 60 * 1000; // Convertir minutos a ms
        }
        return 5 * 60 * 1000; // Default: 5 minutos
    }

    markSessionActive() {
        const now = Date.now();
        sessionStorage.setItem(this.sessionKey, 'true');
        sessionStorage.setItem(this.lastActivityKey, now.toString());
        localStorage.setItem(this.lastActivityKey, now.toString());
    }

    startSessionMonitoring() {
        // Verificar sesión periódicamente
        setInterval(() => {
            this.checkSessionValidity();
        }, this.checkInterval);

        // Actualizar actividad en eventos de usuario
        ['click', 'keypress', 'scroll', 'mousemove'].forEach(event => {
            document.addEventListener(event, () => {
                this.updateLastActivity();
            }, { passive: true });
        });
    }

    updateLastActivity() {
        const now = Date.now();
        sessionStorage.setItem(this.lastActivityKey, now.toString());
        localStorage.setItem(this.lastActivityKey, now.toString());
    }

    checkSessionValidity() {
        const lastActivity = parseInt(sessionStorage.getItem(this.lastActivityKey) || '0');
        const now = Date.now();
        const timeSinceActivity = now - lastActivity;

        // Verificar si la sesión ha expirado
        if (timeSinceActivity > this.sessionTimeout) {
            this.handleSessionExpired();
            return;
        }

        // Advertir si está cerca de expirar
        if (timeSinceActivity > (this.sessionTimeout - this.warningTime)) {
            this.showSessionWarning(this.sessionTimeout - timeSinceActivity);
        }
    }

    handleSessionExpired() {
        console.warn('SessionSecurityHandler: Sesión expirada detectada');
        
        // Limpiar storage
        sessionStorage.removeItem(this.sessionKey);
        sessionStorage.removeItem(this.lastActivityKey);
        
        // Mostrar mensaje y redirigir
        alert('Su sesión ha expirado por inactividad. Será redirigido al login.');
        window.location.href = '/login';
    }

    showSessionWarning(timeRemaining) {
        const minutes = Math.ceil(timeRemaining / 60000);
        console.warn(`SessionSecurityHandler: Sesión expirará en ${minutes} minuto(s)`);
        
        // Aquí se podría mostrar una notificación no intrusiva
        // Por ahora solo log para debugging
    }

    setupNavigationHandlers() {
        // Prevenir navegación hacia atrás después del logout
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                // Página cargada desde cache
                this.checkPageFromCache();
            }
        });

        // Manejar botón atrás del navegador
        window.addEventListener('popstate', () => {
            // Verificar si la sesión sigue siendo válida
            setTimeout(() => {
                this.verifySessionWithServer();
            }, 100);
        });
    }

    setupVisibilityHandlers() {
        // Verificar sesión cuando la página vuelve a ser visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.verifySessionWithServer();
            }
        });

        // Verificar sesión cuando la ventana vuelve a tener foco
        window.addEventListener('focus', () => {
            this.verifySessionWithServer();
        });
    }

    checkPageFromCache() {
        // Verificar si la página fue cargada desde cache después del logout
        const sessionActive = sessionStorage.getItem(this.sessionKey);
        const lastActivity = localStorage.getItem(this.lastActivityKey);
        
        if (!sessionActive && lastActivity) {
            const timeSinceActivity = Date.now() - parseInt(lastActivity);
            if (timeSinceActivity > this.sessionTimeout) {
                console.warn('SessionSecurityHandler: Página cargada desde cache después de logout');
                window.location.replace('/login');
                return;
            }
        }

        // Si llegamos aquí, la sesión parece válida
        this.markSessionActive();
    }

    async verifySessionWithServer() {
        // Prevenir verificaciones múltiples simultáneas
        if (this.verifyingSession) {
            return;
        }

        this.verifyingSession = true;

        try {
            const response = await fetch('/api/verify-session', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            });

            if (!response.ok || response.status === 401) {
                this.handleSessionExpired();
            } else {
                this.markSessionActive();
            }
        } catch (error) {
            console.warn('SessionSecurityHandler: Error verificando sesión con servidor:', error);
            // En caso de error de red, no forzar logout inmediatamente
            // Pero marcar para verificación en la próxima interacción
        } finally {
            this.verifyingSession = false;
        }
    }

    // Método público para limpiar sesión (llamado desde logout)
    static clearSession() {
        sessionStorage.removeItem('uniradical_session_active');
        sessionStorage.removeItem('uniradical_last_activity');
        localStorage.removeItem('uniradical_last_activity');
        console.debug('SessionSecurityHandler: Sesión limpiada');
    }

    // Método público para verificar si hay sesión activa
    static hasActiveSession() {
        return sessionStorage.getItem('uniradical_session_active') === 'true';
    }
}

// Inicializar el handler automáticamente
const sessionSecurityHandler = new SessionSecurityHandler();

// Exportar para uso global
window.SessionSecurityHandler = SessionSecurityHandler;
