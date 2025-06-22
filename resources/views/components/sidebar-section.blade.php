@props(['title' => null, 'collapsible' => false, 'defaultOpen' => true, 'storageKey' => null, 'icon' => null, 'collapsedLink' => null])

@if($title && $collapsible)
    @php
        $sectionId = Str::slug($title);
        $storageKey = $storageKey ?? 'sidebar_section_' . $sectionId;
    @endphp

    <div x-data="{
        isOpen: localStorage.getItem('{{ $storageKey }}') !== null ?
                localStorage.getItem('{{ $storageKey }}') === 'true' :
                {{ $defaultOpen ? 'true' : 'false' }},
        showFloatingMenu: false,
        isSidebarCollapsed: false,
        menuClickedOpen: false,
        hideTimeout: null,
        init() {
            this.checkSidebarState();

            // Escuchar cambios en localStorage
            window.addEventListener('storage', () => {
                this.checkSidebarState();
            });

            // Escuchar eventos personalizados
            document.addEventListener('sidebarToggled', () => {
                this.checkSidebarState();
            });

            // Escuchar cambios de tamaño de ventana
            window.addEventListener('resize', () => {
                this.checkSidebarState();
            });
        },
        checkSidebarState() {
            const sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
            const isDesktop = window.innerWidth >= 1024;
            this.isSidebarCollapsed = !sidebarOpen && isDesktop;
        },
        toggle() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('{{ $storageKey }}', this.isOpen);
        },
        showMenu() {
            if (this.isSidebarCollapsed) {
                this.clearHideTimeout();
                this.showFloatingMenu = true;
                this.positionFloatingMenu();
            }
        },
        positionFloatingMenu() {
            this.$nextTick(() => {
                const button = this.$el.querySelector('.sidebar-section-header');
                const menu = this.$el.querySelector('.sidebar-floating-menu');

                if (button && menu) {
                    const buttonRect = button.getBoundingClientRect();
                    menu.style.top = buttonRect.top + 'px';
                    menu.style.left = '88px'; // Posición fija desde el borde izquierdo
                }
            });
        },
        hideMenu() {
            // Solo ocultar si no fue abierto con click
            if (!this.menuClickedOpen) {
                this.clearHideTimeout();
                this.hideTimeout = setTimeout(() => {
                    this.showFloatingMenu = false;
                }, 500); // Delay más largo
            }
        },
        keepMenuOpen() {
            this.clearHideTimeout();
            this.showFloatingMenu = true;
        },
        clearHideTimeout() {
            if (this.hideTimeout) {
                clearTimeout(this.hideTimeout);
                this.hideTimeout = null;
            }
        },
        handleClick() {
            if (this.isSidebarCollapsed) {
                this.clearHideTimeout();
                this.showFloatingMenu = !this.showFloatingMenu;
                this.menuClickedOpen = this.showFloatingMenu;
                if (this.showFloatingMenu) {
                    this.positionFloatingMenu();
                }
            } else {
                this.toggle();
            }
        },
        closeMenu() {
            this.showFloatingMenu = false;
            this.menuClickedOpen = false;
            this.clearHideTimeout();
        }
    }"
    class="sidebar-collapsible-section"
    style="position: relative;"
    @click.away="closeMenu()"

        <!-- Título clickeable con ícono -->
        <button @click="handleClick()"
                @mouseenter="showMenu()"
                @mouseleave="hideMenu()"
                class="sidebar-section-header"
                :aria-expanded="isOpen"
                aria-controls="section-{{ $sectionId }}"
                :data-tooltip="'{{ $title }}'"
                role="button"
                tabindex="0"
                :title="'Expandir/Contraer sección ' + '{{ $title }}'">
            @if($icon)
                <div class="sidebar-section-icon">
                    {!! $icon !!}
                </div>
            @endif
            <span class="sidebar-section-title-text">{{ $title }}</span>
            <svg class="sidebar-section-chevron"
                 :class="{ 'rotate-180': isOpen }"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24"
                 aria-hidden="true">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Menú flotante para sidebar colapsada -->
        <div x-show="showFloatingMenu && isSidebarCollapsed"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95 -translate-x-2"
             x-transition:enter-end="opacity-100 transform scale-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100 translate-x-0"
             x-transition:leave-end="opacity-0 transform scale-95 -translate-x-2"
             @mouseenter="keepMenuOpen()"
             @mouseleave="hideMenu()"
             @click.away="closeMenu()"
             class="sidebar-floating-menu"
             style="display: none;">
            <div class="sidebar-floating-menu-header">
                @if($icon)
                    <div class="sidebar-floating-menu-icon">
                        {!! $icon !!}
                    </div>
                @endif
                <span class="sidebar-floating-menu-title">{{ $title }}</span>
            </div>
            <div class="sidebar-floating-menu-content"
                 @mouseenter="keepMenuOpen()">
                {{ $slot }}
            </div>
        </div>

        <!-- Contenido desplegable normal -->
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             id="section-{{ $sectionId }}"
             class="sidebar-section-content">
            {{ $slot }}
        </div>


    </div>
@elseif($title)
    <div class="sidebar-section-title">{{ $title }}</div>
@else
    <div class="sidebar-separator"></div>
@endif
