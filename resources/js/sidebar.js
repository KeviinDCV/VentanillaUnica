/**
 * Funcionalidad de la Sidebar
 * Maneja el estado de la sidebar, persistencia y responsive behavior
 */

// Evitar reinicialización múltiple
if (!window.sidebarInitialized) {
    window.sidebarInitialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar estado de la sidebar desde localStorage
        const sidebarState = localStorage.getItem('sidebarOpen');
        const defaultOpen = window.innerWidth >= 1024; // Abierta por defecto en desktop

        if (sidebarState === null) {
            localStorage.setItem('sidebarOpen', defaultOpen.toString());
        }

    // Manejar cambios de tamaño de ventana
    window.addEventListener('resize', function() {
        const isMobile = window.innerWidth < 1024;
        
        if (isMobile) {
            // En móviles, cerrar sidebar automáticamente
            const mobileEvent = new CustomEvent('closeMobileSidebar');
            document.dispatchEvent(mobileEvent);
        }
    });

    // Cerrar sidebar móvil al hacer clic en enlaces
    document.addEventListener('click', function(e) {
        const sidebarLink = e.target.closest('.sidebar-link');
        const isMobile = window.innerWidth < 1024;
        
        if (sidebarLink && isMobile) {
            // Cerrar sidebar móvil después de un pequeño delay
            setTimeout(() => {
                const mobileEvent = new CustomEvent('closeMobileSidebar');
                document.dispatchEvent(mobileEvent);
            }, 150);
        }
    });

    // Manejar teclas de acceso rápido
    document.addEventListener('keydown', function(e) {
        // Ctrl + B para toggle sidebar (solo en desktop)
        if (e.ctrlKey && e.key === 'b' && window.innerWidth >= 1024) {
            e.preventDefault();
            const toggleEvent = new CustomEvent('toggleSidebar');
            document.dispatchEvent(toggleEvent);
        }
        
        // Escape para cerrar sidebar móvil
        if (e.key === 'Escape' && window.innerWidth < 1024) {
            const mobileEvent = new CustomEvent('closeMobileSidebar');
            document.dispatchEvent(mobileEvent);
        }
    });

    // Agregar tooltips para sidebar colapsada
    function updateTooltips() {
        const sidebar = document.querySelector('.sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        
        if (sidebar && sidebar.classList.contains('collapsed')) {
            sidebarLinks.forEach(link => {
                const text = link.querySelector('.sidebar-link-text');
                if (text) {
                    link.setAttribute('title', text.textContent.trim());
                }
            });
        } else {
            sidebarLinks.forEach(link => {
                link.removeAttribute('title');
            });
        }
    }

    // Observar cambios en la clase collapsed
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    updateTooltips();
                }
            });
        });
        
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['class']
        });
        
        // Actualizar tooltips inicialmente
        updateTooltips();
    }

    // Smooth scroll para navegación interna y manejar enlaces deshabilitados
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');

        // Manejar enlaces deshabilitados
        if (link && link.dataset.disabled === 'true') {
            e.preventDefault();
            return;
        }

        // Smooth scroll para navegación interna
        if (link && link.getAttribute('href').startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });

    // Funcionalidad para menús desplegables
    initializeCollapsibleMenus();

    // Manejar error de carga de imagen del logo
    initializeLogoFallback();


    });

    // Escuchar eventos SPA para actualizar enlaces activos
    document.addEventListener('spa:pageLoaded', function(e) {
        updateActiveLinks(e.detail.path);
    });

    function updateActiveLinks(pathname) {
        // Actualizar enlaces activos en el sidebar
        document.querySelectorAll('.sidebar-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href) {
                try {
                    const linkPath = new URL(href, window.location.origin).pathname;
                    if (linkPath === pathname) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                } catch (e) {
                    // Ignorar URLs malformadas
                }
            }
        });
    }
}

/**
 * Inicializar funcionalidad de menús desplegables
 */
function initializeCollapsibleMenus() {
    // Manejar teclas de acceso rápido para menús
    document.addEventListener('keydown', function(e) {
        // Alt + R para toggle sección Radicación
        if (e.altKey && e.key.toLowerCase() === 'r') {
            e.preventDefault();
            toggleSectionByKey('sidebar_radicacion');
        }

        // Alt + G para toggle sección Gestión
        if (e.altKey && e.key.toLowerCase() === 'g') {
            e.preventDefault();
            toggleSectionByKey('sidebar_gestion');
        }
    });

    // Función para toggle por teclado
    function toggleSectionByKey(storageKey) {
        const currentState = localStorage.getItem(storageKey) === 'true';
        const newState = !currentState;
        localStorage.setItem(storageKey, newState);

        // Disparar evento personalizado para que Alpine.js se actualice
        const event = new CustomEvent('sidebarSectionToggle', {
            detail: { storageKey, isOpen: newState }
        });
        document.dispatchEvent(event);
    }

    // Mejorar accesibilidad con ARIA
    document.addEventListener('click', function(e) {
        const sectionHeader = e.target.closest('.sidebar-section-header');
        if (sectionHeader) {
            // Actualizar atributos ARIA
            const isExpanded = sectionHeader.getAttribute('aria-expanded') === 'true';
            sectionHeader.setAttribute('aria-expanded', !isExpanded);
        }
    });

    // Auto-colapsar secciones en móviles para ahorrar espacio
    function handleMobileCollapse() {
        const isMobile = window.innerWidth < 768;

        if (isMobile) {
            // En móviles, colapsar todas las secciones por defecto
            const sections = ['sidebar_radicacion', 'sidebar_gestion'];
            sections.forEach(section => {
                if (localStorage.getItem(section) === null) {
                    localStorage.setItem(section, 'false');
                }
            });
        }
    }

    // Ejecutar al cargar y al redimensionar
    handleMobileCollapse();
    window.addEventListener('resize', handleMobileCollapse);


}

/**
 * Inicializar fallback para el logo de la sidebar
 */
function initializeLogoFallback() {
    const logoImg = document.querySelector('.sidebar-logo-img');
    const logoFallback = document.querySelector('.sidebar-logo-fallback');

    if (logoImg && logoFallback) {
        logoImg.addEventListener('error', function() {
            this.style.display = 'none';
            logoFallback.style.display = 'flex';
        });

        // También verificar si la imagen ya falló al cargar
        if (logoImg.complete && logoImg.naturalHeight === 0) {
            logoImg.style.display = 'none';
            logoFallback.style.display = 'flex';
        }
    }
}


