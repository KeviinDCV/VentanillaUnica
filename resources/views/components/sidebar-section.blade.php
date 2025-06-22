@props(['title' => null, 'collapsible' => false, 'defaultOpen' => true, 'storageKey' => null])

@if($title && $collapsible)
    @php
        $sectionId = Str::slug($title);
        $storageKey = $storageKey ?? 'sidebar_section_' . $sectionId;
    @endphp

    <div x-data="{
        isOpen: localStorage.getItem('{{ $storageKey }}') !== null ?
                localStorage.getItem('{{ $storageKey }}') === 'true' :
                {{ $defaultOpen ? 'true' : 'false' }},
        toggle() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('{{ $storageKey }}', this.isOpen);
        }
    }" class="sidebar-collapsible-section">

        <!-- Título clickeable con ícono -->
        <button @click="toggle()"
                class="sidebar-section-header"
                :aria-expanded="isOpen"
                aria-controls="section-{{ $sectionId }}"
                :data-tooltip="'{{ $title }}'"
                role="button"
                tabindex="0"
                :title="'Expandir/Contraer sección ' + '{{ $title }}'">
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

        <!-- Contenido desplegable -->
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
