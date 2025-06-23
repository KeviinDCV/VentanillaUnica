/**
 * Simple Navigation Enhancement
 * Mejoras básicas de UX sin SPA complejo
 */

class SimpleNavigation {
    constructor() {
        this.init();
    }

    init() {
        // Solo agregar mejoras básicas de UX
        this.addLoadingStates();
        this.improveFormSubmissions();
        this.addSidebarPersistence();
    }

    addLoadingStates() {
        // Agregar indicadores de carga simples a los enlaces
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && this.isInternalLink(link)) {
                this.showLinkLoading(link);
            }
        });
    }

    isInternalLink(link) {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
            return false;
        }
        
        try {
            const url = new URL(href, window.location.origin);
            return url.origin === window.location.origin;
        } catch {
            return false;
        }
    }

    showLinkLoading(link) {
        // Agregar estado de carga visual simple
        link.style.opacity = '0.7';
        link.style.pointerEvents = 'none';
        
        // Restaurar después de un tiempo
        setTimeout(() => {
            link.style.opacity = '';
            link.style.pointerEvents = '';
        }, 2000);
    }

    improveFormSubmissions() {
        // Mejorar la experiencia de envío de formularios
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
                
                const originalText = submitBtn.textContent || submitBtn.value;
                if (submitBtn.textContent) {
                    submitBtn.textContent = 'Procesando...';
                } else {
                    submitBtn.value = 'Procesando...';
                }
                
                // Restaurar si hay error
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '';
                    if (submitBtn.textContent) {
                        submitBtn.textContent = originalText;
                    } else {
                        submitBtn.value = originalText;
                    }
                }, 5000);
            }
        });
    }

    addSidebarPersistence() {
        // Mantener el estado del sidebar entre navegaciones
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            // Guardar estado del sidebar
            const sidebarState = localStorage.getItem('sidebar-collapsed');
            if (sidebarState === 'true') {
                sidebar.classList.add('collapsed');
            }
            
            // Escuchar cambios en el sidebar
            const toggleBtn = document.querySelector('[data-sidebar-toggle]');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebar-collapsed', !isCollapsed);
                });
            }
        }
    }
}

// Inicializar el sistema simple inmediatamente
// Asegurar que solo se inicialice una vez
if (!window.simpleNavigationInstance) {
    window.simpleNavigationInstance = new SimpleNavigation();
} else {
    console.log('⚠️ Simple Navigation already exists');
}

// Exportar para uso global
window.SimpleNavigation = window.simpleNavigationInstance;
