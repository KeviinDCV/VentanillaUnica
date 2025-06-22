/**
 * Sistema de Protección de Formularios para UniRadic
 * Previene pérdida de datos y confirma acciones críticas
 */

class FormProtection {
    constructor() {
        this.hasUnsavedChanges = false;
        this.formsToProtect = [];
        this.bypassBeforeUnload = false; // Flag para bypass temporal
        this.bypassTimeout = null; // Timeout para resetear bypass
        this.init();
    }

    init() {
        this.setupFormProtection();
        this.setupBeforeUnloadWarning();
        this.setupNavigationWarnings();
    }

    setupFormProtection() {
        // Proteger formularios automáticamente
        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('form[data-protect="true"], #radicacionEntradaForm, #radicacionInternaForm, #radicacionSalidaForm');
            forms.forEach(form => this.protectForm(form));
        });
    }

    protectForm(form) {
        if (!form || this.formsToProtect.includes(form)) return;

        this.formsToProtect.push(form);

        // Detectar cambios en el formulario
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            // Guardar valor inicial
            input.dataset.initialValue = this.getInputValue(input);

            // Escuchar cambios
            input.addEventListener('input', () => this.checkForChanges(form));
            input.addEventListener('change', () => this.checkForChanges(form));
        });

        // Manejar envío del formulario
        form.addEventListener('submit', (e) => {
            this.hasUnsavedChanges = false; // Resetear al enviar
            this.enableBypass(2000); // Activar bypass por 2 segundos
        });
    }

    getInputValue(input) {
        if (input.type === 'checkbox' || input.type === 'radio') {
            return input.checked;
        }
        return input.value;
    }

    checkForChanges(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        let hasChanges = false;

        inputs.forEach(input => {
            const currentValue = this.getInputValue(input);
            const initialValue = input.dataset.initialValue;
            
            if (currentValue != initialValue) {
                hasChanges = true;
            }
        });

        this.hasUnsavedChanges = hasChanges;
    }

    setupBeforeUnloadWarning() {
        // Usar capture: true para ejecutar antes que otros listeners
        window.addEventListener('beforeunload', (e) => {
            console.log('FormProtection beforeunload:', {
                bypassActive: this.bypassBeforeUnload,
                hasUnsavedChanges: this.hasUnsavedChanges
            });

            // Si está en bypass temporal, prevenir TODOS los otros listeners
            if (this.bypassBeforeUnload) {
                console.log('FormProtection: Bypass activo, previniendo beforeunload');
                e.stopImmediatePropagation();
                return;
            }

            if (this.hasUnsavedChanges) {
                console.log('FormProtection: Mostrando advertencia de cambios sin guardar');
                e.preventDefault();
                e.returnValue = 'Es posible que los cambios no se guarden. ¿Está seguro de que desea salir?';
                return e.returnValue;
            }
        }, { capture: true }); // Usar capture para ejecutar primero
    }

    setupNavigationWarnings() {
        // Interceptar clics en enlaces cuando hay cambios sin guardar
        document.addEventListener('click', (e) => {
            if (!this.hasUnsavedChanges) return;

            const link = e.target.closest('a');
            if (link && !link.dataset.ignoreWarning) {
                e.preventDefault();

                window.UniRadicNotifications.confirm(
                    'Cambios Sin Guardar',
                    'Es posible que los cambios no se guarden. ¿Está seguro de que desea continuar?',
                    () => {
                        // Activar bypass antes de navegar
                        this.hasUnsavedChanges = false;
                        this.enableBypass(5000); // 5 segundos de bypass (más tiempo)

                        // Navegar después de un delay más largo para asegurar que el bypass esté activo
                        setTimeout(() => {
                            window.location.href = link.href;
                        }, 200);
                    }
                );
            }
        });
    }

    // Métodos públicos para confirmaciones específicas
    confirmDelete(itemName, onConfirm, onCancel = null) {
        window.UniRadicNotifications.confirm(
            'Confirmar Eliminación',
            `¿Está seguro de que desea eliminar "${itemName}"? Esta acción no se puede deshacer.`,
            onConfirm,
            onCancel
        );
    }

    confirmSubmit(formName, onConfirm, onCancel = null) {
        window.UniRadicNotifications.confirm(
            'Confirmar Envío',
            `¿Está seguro de que desea enviar ${formName}? Verifique que toda la información sea correcta.`,
            onConfirm,
            onCancel
        );
    }

    confirmNavigation(destination, onConfirm, onCancel = null) {
        if (this.hasUnsavedChanges) {
            window.UniRadicNotifications.confirm(
                'Cambios Sin Guardar',
                'Es posible que los cambios no se guarden. ¿Está seguro de que desea continuar?',
                () => {
                    this.hasUnsavedChanges = false;
                    this.enableBypass(5000); // Activar bypass por 5 segundos

                    if (onConfirm) {
                        // Ejecutar callback después de un delay más largo
                        setTimeout(() => {
                            onConfirm();
                        }, 200);
                    }
                },
                onCancel
            );
        } else {
            if (onConfirm) onConfirm();
        }
    }

    // Activar bypass temporal para beforeunload
    enableBypass(duration = 1000) {
        console.log(`FormProtection: Activando bypass por ${duration}ms`);
        this.bypassBeforeUnload = true;

        // Limpiar timeout anterior si existe
        if (this.bypassTimeout) {
            clearTimeout(this.bypassTimeout);
        }

        // Override temporal de window.onbeforeunload
        this.originalOnBeforeUnload = window.onbeforeunload;
        window.onbeforeunload = null;
        console.log('FormProtection: window.onbeforeunload anulado temporalmente');

        // Resetear bypass después del tiempo especificado
        this.bypassTimeout = setTimeout(() => {
            console.log('FormProtection: Bypass expirado, restaurando estado normal');
            this.bypassBeforeUnload = false;
            this.bypassTimeout = null;
            // Restaurar onbeforeunload original si existía
            if (this.originalOnBeforeUnload) {
                window.onbeforeunload = this.originalOnBeforeUnload;
                this.originalOnBeforeUnload = null;
                console.log('FormProtection: window.onbeforeunload restaurado');
            }
        }, duration);
    }

    // Desactivar bypass inmediatamente
    disableBypass() {
        this.bypassBeforeUnload = false;
        if (this.bypassTimeout) {
            clearTimeout(this.bypassTimeout);
            this.bypassTimeout = null;
        }
        // Restaurar onbeforeunload original si existía
        if (this.originalOnBeforeUnload) {
            window.onbeforeunload = this.originalOnBeforeUnload;
            this.originalOnBeforeUnload = null;
        }
    }

    // Resetear estado de cambios
    resetChanges() {
        this.hasUnsavedChanges = false;
        this.disableBypass(); // También desactivar bypass
        this.formsToProtect.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.dataset.initialValue = this.getInputValue(input);
            });
        });
    }

    // Marcar como guardado
    markAsSaved() {
        this.hasUnsavedChanges = false;
        this.disableBypass(); // Desactivar bypass al guardar
        window.UniRadicNotifications.success(
            'Guardado Exitoso',
            'Los cambios han sido guardados correctamente.'
        );
    }

    // Mostrar advertencia de sesión
    showSessionWarning() {
        window.UniRadicNotifications.warning(
            'Sesión por Expirar',
            'Su sesión expirará en 1 minuto. Guarde sus cambios para evitar pérdida de información.',
            { duration: 10000 }
        );
    }

    // Mostrar error de validación
    showValidationError(field, message) {
        window.UniRadicNotifications.error(
            'Error de Validación',
            `${field}: ${message}`,
            { duration: 6000 }
        );
    }

    // Mostrar progreso de carga
    showUploadProgress(filename) {
        return window.UniRadicNotifications.info(
            'Subiendo Archivo',
            `Subiendo "${filename}"... Por favor espere.`,
            { persistent: true }
        );
    }

    // Completar carga
    completeUpload(notificationId, filename) {
        window.UniRadicNotifications.close(notificationId);
        window.UniRadicNotifications.success(
            'Archivo Subido',
            `"${filename}" se ha subido correctamente.`
        );
    }

    // Error de carga
    errorUpload(notificationId, filename, error) {
        window.UniRadicNotifications.close(notificationId);
        window.UniRadicNotifications.error(
            'Error de Subida',
            `No se pudo subir "${filename}": ${error}`
        );
    }

    // Navegación segura sin beforeunload
    safeNavigate(url) {
        this.hasUnsavedChanges = false;
        this.enableBypass(5000); // 5 segundos de bypass

        setTimeout(() => {
            window.location.href = url;
        }, 200);
    }

    // Envío seguro de formulario
    safeSubmit(form) {
        this.hasUnsavedChanges = false;
        this.enableBypass(1000); // 1 segundo de bypass

        setTimeout(() => {
            if (typeof form === 'string') {
                document.getElementById(form).submit();
            } else {
                form.submit();
            }
        }, 50);
    }
}

// Crear instancia global
window.FormProtection = new FormProtection();

// Funciones de conveniencia globales
window.confirmDelete = (itemName, onConfirm, onCancel) => {
    window.FormProtection.confirmDelete(itemName, onConfirm, onCancel);
};

window.confirmSubmit = (formName, onConfirm, onCancel) => {
    window.FormProtection.confirmSubmit(formName, onConfirm, onCancel);
};

window.showSuccess = (title, message) => {
    window.UniRadicNotifications.success(title, message);
};

window.showError = (title, message) => {
    window.UniRadicNotifications.error(title, message);
};

window.showWarning = (title, message) => {
    window.UniRadicNotifications.warning(title, message);
};

window.showInfo = (title, message) => {
    window.UniRadicNotifications.info(title, message);
};

// Funciones de navegación segura
window.safeNavigate = (url) => {
    window.FormProtection.safeNavigate(url);
};

window.safeSubmit = (form) => {
    window.FormProtection.safeSubmit(form);
};

window.enableBypass = (duration) => {
    window.FormProtection.enableBypass(duration);
};

window.disableBypass = () => {
    window.FormProtection.disableBypass();
};

// Funciones de debug (solo en desarrollo)
if (import.meta.env.DEV) {
    window.debugFormProtection = () => {
        console.log('FormProtection Debug Info:', {
            hasUnsavedChanges: window.FormProtection.hasUnsavedChanges,
            bypassBeforeUnload: window.FormProtection.bypassBeforeUnload,
            formsProtected: window.FormProtection.formsToProtect.length,
            bypassTimeout: window.FormProtection.bypassTimeout !== null
        });
    };

    window.testBypass = (duration = 5000) => {
        console.log(`Activando bypass por ${duration}ms`);
        window.FormProtection.enableBypass(duration);
    };

    window.simulateChanges = () => {
        window.FormProtection.hasUnsavedChanges = true;
        console.log('Cambios simulados activados');
    };
}

export default FormProtection;
