/**
 * Sistema de Notificaciones Personalizado para UniRadic
 * Reemplaza alerts, confirms y mensajes emergentes del navegador
 */

class UniRadicNotifications {
    constructor() {
        this.container = null;
        this.notifications = [];
        this.init();
    }

    init() {
        this.createContainer();
        this.addStyles();
        this.setupEventDelegation();
    }

    setupEventDelegation() {
        // Delegación de eventos para todos los botones de notificaciones
        document.addEventListener('click', (e) => {
            // Botones de cerrar notificación
            if (e.target.closest('.uniradica-notification-close')) {
                const button = e.target.closest('.uniradica-notification-close');
                const notificationId = button.dataset.notificationId;
                if (notificationId) {
                    this.close(notificationId);
                }
                return;
            }

            // Botones de confirmación
            if (e.target.closest('[data-action="confirm"]')) {
                const button = e.target.closest('[data-action="confirm"]');
                const confirmId = button.dataset.confirmId;
                if (confirmId) {
                    this.handleConfirm(true, confirmId);
                }
                return;
            }

            // Botones de cancelación
            if (e.target.closest('[data-action="cancel"]')) {
                const button = e.target.closest('[data-action="cancel"]');
                const confirmId = button.dataset.confirmId;
                if (confirmId) {
                    this.handleConfirm(false, confirmId);
                }
                return;
            }

            // Botones de extender sesión
            if (e.target.closest('[data-action="extend-session"]')) {
                if (window.sessionTimeout) {
                    window.sessionTimeout.resetTimeout();
                    // Encontrar y cerrar la notificación de sesión
                    const notification = e.target.closest('.uniradica-notification');
                    if (notification) {
                        const notificationId = notification.dataset.id;
                        this.close(notificationId);
                    }
                }
                return;
            }
        });
    }

    createContainer() {
        // Crear contenedor principal para notificaciones
        this.container = document.createElement('div');
        this.container.id = 'uniradica-notifications-container';
        this.container.className = 'uniradica-notifications-container';
        document.body.appendChild(this.container);
    }

    addStyles() {
        // Verificar si ya existen los estilos
        if (document.getElementById('uniradica-notifications-styles')) {
            return;
        }

        const styles = document.createElement('style');
        styles.id = 'uniradica-notifications-styles';
        styles.textContent = `
            .uniradica-notifications-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                pointer-events: none;
                max-width: 400px;
            }

            .uniradica-notification {
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                margin-bottom: 12px;
                padding: 16px 20px;
                border-left: 4px solid #082ca4;
                pointer-events: auto;
                transform: translateX(100%);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                max-width: 100%;
                word-wrap: break-word;
            }

            .uniradica-notification.show {
                transform: translateX(0);
                opacity: 1;
            }

            .uniradica-notification.hide {
                transform: translateX(100%);
                opacity: 0;
            }

            .uniradica-notification.success {
                border-left-color: #10b981;
                background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
            }

            .uniradica-notification.error {
                border-left-color: #ef4444;
                background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            }

            .uniradica-notification.warning {
                border-left-color: #f59e0b;
                background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
            }

            .uniradica-notification.info {
                border-left-color: #082ca4;
                background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            }

            .uniradica-notification-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px;
            }

            .uniradica-notification-title {
                font-weight: 600;
                font-size: 14px;
                color: #1f2937;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .uniradica-notification-icon {
                width: 20px;
                height: 20px;
                flex-shrink: 0;
            }

            .uniradica-notification-close {
                background: none;
                border: none;
                color: #6b7280;
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .uniradica-notification-close:hover {
                background: rgba(0, 0, 0, 0.1);
                color: #374151;
            }

            .uniradica-notification-message {
                color: #4b5563;
                font-size: 13px;
                line-height: 1.5;
                margin: 0;
            }

            .uniradica-notification-actions {
                margin-top: 12px;
                display: flex;
                gap: 8px;
                justify-content: flex-end;
            }

            .uniradica-notification-btn {
                padding: 6px 12px;
                border-radius: 4px;
                border: none;
                font-size: 12px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
            }

            .uniradica-notification-btn.primary {
                background: #082ca4;
                color: white;
            }

            .uniradica-notification-btn.primary:hover {
                background: #0c2d5f;
            }

            .uniradica-notification-btn.secondary {
                background: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
            }

            .uniradica-notification-btn.secondary:hover {
                background: #e5e7eb;
            }

            .uniradica-notification-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: rgba(8, 44, 164, 0.3);
                border-radius: 0 0 8px 8px;
                transition: width linear;
            }

            @media (max-width: 640px) {
                .uniradica-notifications-container {
                    top: 10px;
                    right: 10px;
                    left: 10px;
                    max-width: none;
                }
            }
        `;
        document.head.appendChild(styles);
    }

    getIcon(type) {
        const icons = {
            success: `<svg fill="currentColor" viewBox="0 0 20 20" class="uniradica-notification-icon" style="color: #10b981;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>`,
            error: `<svg fill="currentColor" viewBox="0 0 20 20" class="uniradica-notification-icon" style="color: #ef4444;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>`,
            warning: `<svg fill="currentColor" viewBox="0 0 20 20" class="uniradica-notification-icon" style="color: #f59e0b;">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>`,
            info: `<svg fill="currentColor" viewBox="0 0 20 20" class="uniradica-notification-icon" style="color: #082ca4;">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>`
        };
        return icons[type] || icons.info;
    }

    show(options) {
        const {
            type = 'info',
            title = '',
            message = '',
            duration = 5000,
            persistent = false,
            actions = null
        } = options;

        const notification = document.createElement('div');
        notification.className = `uniradica-notification ${type}`;

        const notificationId = Date.now() + Math.random();
        notification.dataset.id = notificationId;

        // Crear contenido
        notification.innerHTML = `
            <div class="uniradica-notification-header">
                <div class="uniradica-notification-title">
                    ${this.getIcon(type)}
                    ${title}
                </div>
                <button class="uniradica-notification-close" data-notification-id="${notificationId}">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <p class="uniradica-notification-message">${message}</p>
            ${actions ? `<div class="uniradica-notification-actions">${actions}</div>` : ''}
            ${!persistent && duration > 0 ? `<div class="uniradica-notification-progress"></div>` : ''}
        `;

        // Los event listeners se manejan por delegación de eventos

        // Agregar al contenedor
        this.container.appendChild(notification);
        this.notifications.push({ id: notificationId, element: notification });

        // Mostrar con animación
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Auto-cerrar si no es persistente
        if (!persistent && duration > 0) {
            const progressBar = notification.querySelector('.uniradica-notification-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                setTimeout(() => {
                    progressBar.style.width = '0%';
                }, 100);
            }

            setTimeout(() => {
                this.close(notificationId);
            }, duration);
        }

        return notificationId;
    }

    close(notificationId) {
        const notification = this.notifications.find(n => n.id == notificationId);
        if (notification) {
            notification.element.classList.add('hide');
            setTimeout(() => {
                if (notification.element.parentNode) {
                    notification.element.parentNode.removeChild(notification.element);
                }
                this.notifications = this.notifications.filter(n => n.id !== notificationId);
            }, 300);
        }
    }

    closeAll() {
        this.notifications.forEach(notification => {
            this.close(notification.id);
        });
    }

    // Métodos de conveniencia
    success(title, message, options = {}) {
        return this.show({ type: 'success', title, message, ...options });
    }

    error(title, message, options = {}) {
        return this.show({ type: 'error', title, message, ...options });
    }

    warning(title, message, options = {}) {
        return this.show({ type: 'warning', title, message, ...options });
    }

    info(title, message, options = {}) {
        return this.show({ type: 'info', title, message, ...options });
    }

    confirm(title, message, onConfirm, onCancel = null) {
        const confirmId = Date.now() + Math.random();
        const actions = `
            <button class="uniradica-notification-btn secondary" data-action="cancel" data-confirm-id="${confirmId}">
                Cancelar
            </button>
            <button class="uniradica-notification-btn primary" data-action="confirm" data-confirm-id="${confirmId}">
                Confirmar
            </button>
        `;

        const notificationId = this.show({
            type: 'warning',
            title,
            message,
            persistent: true,
            actions
        });

        // Guardar callbacks temporalmente
        window._uniRadicConfirmCallbacks = window._uniRadicConfirmCallbacks || {};
        window._uniRadicConfirmCallbacks[confirmId] = { onConfirm, onCancel, notificationId };

        return notificationId;
    }

    handleConfirm(confirmed, confirmId) {
        const callbacks = window._uniRadicConfirmCallbacks?.[confirmId];

        if (confirmed && callbacks?.onConfirm) {
            callbacks.onConfirm();
        } else if (!confirmed && callbacks?.onCancel) {
            callbacks.onCancel();
        }

        // Cerrar la notificación
        if (callbacks?.notificationId) {
            this.close(callbacks.notificationId);
        }

        // Limpiar callbacks
        if (window._uniRadicConfirmCallbacks) {
            delete window._uniRadicConfirmCallbacks[confirmId];
        }
    }
}

// Crear instancia global
window.UniRadicNotifications = new UniRadicNotifications();

// Exportar para uso en módulos
export default UniRadicNotifications;
