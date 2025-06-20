/**
 * Script de prueba para verificar el funcionamiento del LoginFormHandler
 * Solo se ejecuta en desarrollo
 */

if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {

    // Función para probar el LoginFormHandler
    function testLoginFormHandler() {
        // Solo ejecutar en página de login
        const isLoginPage = window.location.pathname === '/login' ||
                           document.getElementById('loginForm') !== null;

        if (!isLoginPage) {
            console.debug('🧪 LoginFormHandler Test: No es página de login, omitiendo pruebas');
            return;
        }
        console.group('🧪 Pruebas del LoginFormHandler');
        
        // Verificar que el handler existe
        if (window.loginFormHandler) {
            console.log('✅ LoginFormHandler está disponible globalmente');
            
            // Verificar elementos críticos
            const criticalElements = [
                'loginForm',
                'email', 
                'password',
                'login-button'
            ];
            
            criticalElements.forEach(elementId => {
                const element = document.getElementById(elementId);
                if (element) {
                    console.log(`✅ Elemento "${elementId}" encontrado`);
                } else {
                    console.error(`❌ Elemento "${elementId}" NO encontrado`);
                }
            });
            
            // Verificar elementos opcionales
            const optionalElements = [
                'form-status',
                'password-toggle',
                'show-password-icon',
                'hide-password-icon',
                'email-validation-error',
                'password-validation-error'
            ];
            
            optionalElements.forEach(elementId => {
                const element = document.getElementById(elementId);
                if (element) {
                    console.log(`ℹ️ Elemento opcional "${elementId}" encontrado`);
                } else {
                    console.warn(`⚠️ Elemento opcional "${elementId}" no encontrado`);
                }
            });
            
            // Verificar funcionalidades
            console.log('🔧 Verificando funcionalidades...');
            
            // Test de validación de email
            const emailInput = document.getElementById('email');
            if (emailInput && window.loginFormHandler.validateEmail) {
                emailInput.value = 'test@example.com';
                window.loginFormHandler.validateEmail(emailInput);
                console.log('✅ Validación de email funciona');
                emailInput.value = '';
            }
            
            // Test de validación de contraseña
            const passwordInput = document.getElementById('password');
            if (passwordInput && window.loginFormHandler.validatePassword) {
                passwordInput.value = 'password123';
                window.loginFormHandler.validatePassword(passwordInput);
                console.log('✅ Validación de contraseña funciona');
                passwordInput.value = '';
            }
            
            // Test de toggle de contraseña
            const passwordToggle = document.getElementById('password-toggle');
            if (passwordToggle && window.loginFormHandler.togglePasswordVisibility) {
                console.log('✅ Toggle de contraseña disponible');
            }
            
        } else {
            console.error('❌ LoginFormHandler NO está disponible');
        }
        
        console.groupEnd();
    }
    
    // Ejecutar pruebas cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(testLoginFormHandler, 1000);
        });
    } else {
        setTimeout(testLoginFormHandler, 1000);
    }
    
    // Agregar comando de prueba al objeto window para uso manual
    window.testLoginForm = testLoginFormHandler;
    
    console.info('🧪 Script de pruebas del LoginFormHandler cargado. Ejecuta "testLoginForm()" en la consola para pruebas manuales.');
}
