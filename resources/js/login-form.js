/**
 * Login Form Enhancement Script para UniRadic
 * Maneja validación, UX y funcionalidades del formulario de login
 */
class LoginFormHandler {
    constructor() {
        this.form = null;
        this.emailInput = null;
        this.passwordInput = null;
        this.loginButton = null;
        this.formStatus = null;
        this.passwordToggle = null;
        this.elementsInitialized = false;

        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            if (this.initializeElements()) {
                this.attachEventListeners();
                this.initializeFormAnimation();
            }
        });
    }

    initializeElements() {
        // Evitar inicialización múltiple
        if (this.elementsInitialized) {
            return true;
        }

        this.form = document.getElementById('loginForm');
        this.emailInput = document.getElementById('email');
        this.passwordInput = document.getElementById('password');
        this.loginButton = document.getElementById('login-button');
        this.formStatus = document.getElementById('form-status');
        this.passwordToggle = document.getElementById('password-toggle');

        // Verificar elementos críticos
        const missingElements = [];
        if (!this.form) missingElements.push('loginForm');
        if (!this.emailInput) missingElements.push('email');
        if (!this.passwordInput) missingElements.push('password');
        if (!this.loginButton) missingElements.push('login-button');

        if (missingElements.length > 0) {
            // Elementos críticos no encontrados
            return false;
        }

        // Elementos opcionales - verificación silenciosa
        // form-status y password-toggle son opcionales

        this.elementsInitialized = true;
        return true;
    }

    attachEventListeners() {
        // Los elementos ya fueron verificados en init()
        if (!this.elementsInitialized) return;

        // Validación en tiempo real para email
        this.emailInput.addEventListener('input', () => {
            this.validateEmail(this.emailInput);
        });

        this.emailInput.addEventListener('blur', () => {
            this.validateEmail(this.emailInput);
        });

        // Validación en tiempo real para contraseña
        this.passwordInput.addEventListener('input', () => {
            this.validatePassword(this.passwordInput);
        });

        this.passwordInput.addEventListener('blur', () => {
            this.validatePassword(this.passwordInput);
        });

        // Funcionalidad de mostrar/ocultar contraseña
        if (this.passwordToggle) {
            this.passwordToggle.addEventListener('click', () => {
                this.togglePasswordVisibility();
            });
        }

        // Efecto ripple para el botón
        this.loginButton.addEventListener('click', (e) => {
            this.createRipple(e, this.loginButton);
        });

        // Manejo del envío del formulario
        this.form.addEventListener('submit', (e) => {
            this.handleFormSubmit(e);
        });

        // Mejorar accesibilidad con navegación por teclado
        this.form.addEventListener('keydown', (e) => {
            this.handleKeyNavigation(e);
        });
    }

    // Función para alternar visibilidad de contraseña
    togglePasswordVisibility() {
        if (!this.passwordInput || !this.passwordToggle) {
            console.warn('LoginFormHandler: Elementos de toggle de contraseña no disponibles');
            return;
        }

        const showIcon = document.getElementById('show-password-icon');
        const hideIcon = document.getElementById('hide-password-icon');
        const isPassword = this.passwordInput.type === 'password';

        if (isPassword) {
            // Mostrar contraseña
            this.passwordInput.type = 'text';
            if (showIcon) showIcon.style.display = 'block';
            if (hideIcon) hideIcon.style.display = 'none';
            this.passwordToggle.setAttribute('aria-label', 'Ocultar contraseña');
            this.passwordToggle.setAttribute('title', 'Ocultar contraseña');
            this.passwordToggle.classList.add('toggled');
        } else {
            // Ocultar contraseña
            this.passwordInput.type = 'password';
            if (showIcon) showIcon.style.display = 'none';
            if (hideIcon) hideIcon.style.display = 'block';
            this.passwordToggle.setAttribute('aria-label', 'Mostrar contraseña');
            this.passwordToggle.setAttribute('title', 'Mostrar contraseña');
            this.passwordToggle.classList.remove('toggled');
        }

        // Mantener el focus en el campo de contraseña
        this.passwordInput.focus();

        // Mover el cursor al final del texto
        setTimeout(() => {
            const length = this.passwordInput.value.length;
            this.passwordInput.setSelectionRange(length, length);
        }, 0);
    }

    // Función de validación de email
    validateEmail(input) {
        if (!input) return;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errorElement = document.getElementById('email-validation-error');

        if (input.value.trim() === '') {
            this.hideError(input, errorElement);
            return;
        }

        if (!emailRegex.test(input.value)) {
            this.showError(input, errorElement, 'Por favor, ingresa un correo electrónico válido');
        } else {
            this.clearStates(input, errorElement);
        }
    }

    // Función de validación de contraseña
    validatePassword(input) {
        if (!input) return;

        const errorElement = document.getElementById('password-validation-error');

        if (input.value.trim() === '') {
            this.hideError(input, errorElement);
            return;
        }

        if (input.value.length < 6) {
            this.showError(input, errorElement, 'La contraseña debe tener al menos 6 caracteres');
        } else {
            this.clearStates(input, errorElement);
        }
    }

    // Función para mostrar errores
    showError(input, errorElement, message) {
        if (!input || !errorElement) return;

        const container = input.closest('.input-container');

        input.classList.add('error');
        if (container) container.classList.add('error');

        errorElement.textContent = message;
        errorElement.style.display = 'flex';
        errorElement.setAttribute('aria-live', 'polite');

        // Enfocar el campo con error
        setTimeout(() => {
            if (input && typeof input.focus === 'function') {
                input.focus();
            }
        }, 100);
    }

    // Función para limpiar estados
    clearStates(input, errorElement) {
        if (!input) return;

        const container = input.closest('.input-container');

        input.classList.remove('error');
        if (container) container.classList.remove('error');

        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    // Función para ocultar errores
    hideError(input, errorElement) {
        if (!input) return;

        const container = input.closest('.input-container');

        input.classList.remove('error');
        if (container) container.classList.remove('error');

        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    // Efecto ripple para el botón
    createRipple(event, button) {
        if (!event || !button) return;

        try {
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            button.appendChild(ripple);

            setTimeout(() => {
                if (ripple && ripple.parentNode) {
                    ripple.remove();
                }
            }, 600);
        } catch (error) {
            console.warn('LoginFormHandler: Error creando efecto ripple:', error);
        }
    }

    // Manejo del envío del formulario
    handleFormSubmit(e) {
        e.preventDefault();

        // Validar todos los campos antes del envío
        const isEmailValid = this.validateFormField(this.emailInput, 'email');
        const isPasswordValid = this.validateFormField(this.passwordInput, 'password');

        if (isEmailValid && isPasswordValid) {
            // Mostrar estado de carga
            this.showLoadingState();

            // Asegurar que el campo remember esté incluido correctamente
            this.ensureRememberFieldIncluded();

            // Enviar formulario
            setTimeout(() => {
                this.form.submit();
            }, 500);
        } else {
            // Enfocar el primer campo con error
            const firstError = this.form.querySelector('.input-modern.error');
            if (firstError) {
                firstError.focus();
                if (this.formStatus) {
                    this.formStatus.textContent = 'Por favor, corrige los errores en el formulario';
                }
            }
        }
    }

    // Asegurar que el campo remember esté incluido en el envío
    ensureRememberFieldIncluded() {
        const rememberCheckbox = this.form.querySelector('input[name="remember"]');
        if (rememberCheckbox) {
            // Verificar que el checkbox tenga un valor apropiado
            if (rememberCheckbox.checked && !rememberCheckbox.value) {
                rememberCheckbox.value = '1';
            }
            console.debug('LoginFormHandler: Campo remember configurado:', {
                checked: rememberCheckbox.checked,
                value: rememberCheckbox.value,
                name: rememberCheckbox.name
            });
        }
    }

    // Validación completa de campo
    validateFormField(input, type) {
        if (type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return input.value.trim() !== '' && emailRegex.test(input.value);
        } else if (type === 'password') {
            return input.value.trim() !== '' && input.value.length >= 6;
        }
        return false;
    }

    // Estado de carga
    showLoadingState() {
        if (!this.loginButton || !this.form) return;

        this.loginButton.classList.add('loading');
        this.loginButton.disabled = true;
        this.form.classList.add('form-loading');

        const buttonText = this.loginButton.querySelector('.button-text');
        if (buttonText) {
            buttonText.textContent = 'Iniciando sesión...';
        }

        const loginStatus = document.getElementById('login-status');
        if (loginStatus) {
            loginStatus.textContent = 'Procesando inicio de sesión...';
        }
    }

    // Navegación por teclado
    handleKeyNavigation(e) {
        if (!e || !this.form) return;

        if (e.key === 'Enter') {
            const activeElement = document.activeElement;
            if (activeElement && activeElement.tagName === 'INPUT') {
                const inputs = Array.from(this.form.querySelectorAll('input[type="email"], input[type="password"]'));
                const currentIndex = inputs.indexOf(activeElement);

                if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
                    e.preventDefault();
                    const nextInput = inputs[currentIndex + 1];
                    if (nextInput && typeof nextInput.focus === 'function') {
                        nextInput.focus();
                    }
                }
            }
        }
    }

    // Animación de entrada para el formulario
    initializeFormAnimation() {
        if (!this.form) return;

        setTimeout(() => {
            this.form.style.opacity = '1';
            this.form.style.transform = 'translateY(0)';
        }, 100);
    }
}

// Inicializar el handler automáticamente de forma segura
let loginFormHandler = null;

// Función de inicialización segura
function initializeLoginFormHandler() {
    // Solo inicializar si estamos en la página de login
    const isLoginPage = window.location.pathname === '/login' ||
                       document.getElementById('loginForm') !== null;

    if (!isLoginPage) {
        console.debug('LoginFormHandler: No es página de login, omitiendo inicialización');
        return;
    }

    try {
        if (!loginFormHandler) {
            loginFormHandler = new LoginFormHandler();
            console.info('LoginFormHandler: Inicializado correctamente en página de login');
        }
    } catch (error) {
        console.error('LoginFormHandler: Error durante la inicialización:', error);
    }
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeLoginFormHandler);
} else {
    // El DOM ya está listo
    initializeLoginFormHandler();
}

// Exportar para uso global si es necesario
window.LoginFormHandler = LoginFormHandler;
window.loginFormHandler = loginFormHandler;
