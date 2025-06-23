import './bootstrap';
import './session-timeout';
import './logout-handler';
import './login-form';
import './session-security';
import './sidebar';
import './simple-navigation';
import './colombia-time';
import './notifications';
import './form-protection';
import './admin-logs';
import './admin-usuarios';
import './admin-trds';
import './admin-dependencias';
import './radicacion';
import './reportes';
import './preview';

// Deshabilitar todos los logs en producción por seguridad
if (import.meta.env.PROD) {
    console.log = () => {};
    console.warn = () => {};
    console.error = () => {};
    console.info = () => {};
    console.debug = () => {};
    console.trace = () => {};
    console.group = () => {};
    console.groupEnd = () => {};
    console.groupCollapsed = () => {};
    console.table = () => {};
    console.time = () => {};
    console.timeEnd = () => {};
    console.count = () => {};
    console.clear = () => {};
}

// Importar script de pruebas solo en desarrollo
if (import.meta.env.DEV) {
    import('./login-form-test');
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
