import './bootstrap';
import './session-timeout';
import './logout-handler';
import './login-form';
import './session-security';
import './sidebar';
import './colombia-time';
import './notifications';
import './form-protection';
import './admin-logs';
import './admin-usuarios';
import './admin-trds';

// Importar script de pruebas solo en desarrollo
if (import.meta.env.DEV) {
    import('./login-form-test');
}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
