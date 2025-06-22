/**
 * Funcionalidad para manejar la relación entre departamentos y ciudades
 * en los formularios de radicación con búsqueda en tiempo real
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidad para radicación de entrada
    initSearchableSelect('departamento_id', 'ciudad_id');

    // Inicializar funcionalidad para radicación de salida
    initSearchableSelect('departamento_destinatario_id', 'ciudad_destinatario_id');
});

/**
 * Crear un select con funcionalidad de búsqueda
 * @param {HTMLSelectElement} selectElement - Elemento select original
 * @param {Array} options - Array de opciones {value, text, dataset}
 * @param {string} placeholder - Texto placeholder
 * @returns {HTMLElement} - Contenedor del searchable select
 */
function createSearchableSelect(selectElement, options, placeholder = 'Buscar...') {
    // Crear contenedor principal
    const container = document.createElement('div');
    container.className = 'searchable-select-container relative';

    // Crear input de búsqueda
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = selectElement.className;
    searchInput.placeholder = placeholder;
    searchInput.autocomplete = 'off';

    // Crear dropdown
    const dropdown = document.createElement('div');
    dropdown.className = 'searchable-dropdown absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden';
    dropdown.style.top = '100%';
    dropdown.style.left = '0';

    // Crear input hidden para el valor
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = selectElement.name;
    hiddenInput.id = selectElement.id;

    // Función para renderizar opciones
    function renderOptions(filteredOptions) {
        dropdown.innerHTML = '';

        if (filteredOptions.length === 0) {
            const noResults = document.createElement('div');
            noResults.className = 'px-3 py-2 text-gray-500 text-sm';
            noResults.textContent = 'No se encontraron resultados';
            dropdown.appendChild(noResults);
            return;
        }

        filteredOptions.forEach(option => {
            const optionElement = document.createElement('div');
            optionElement.className = 'px-3 py-2 cursor-pointer hover:bg-blue-50 text-sm';
            optionElement.textContent = option.text;
            optionElement.dataset.value = option.value;

            // Copiar dataset del option original
            if (option.dataset) {
                Object.keys(option.dataset).forEach(key => {
                    optionElement.dataset[key] = option.dataset[key];
                });
            }

            optionElement.addEventListener('click', () => {
                selectOption(option);
            });

            dropdown.appendChild(optionElement);
        });
    }

    // Función para seleccionar una opción
    function selectOption(option) {
        hiddenInput.value = option.value;
        searchInput.value = option.text;
        dropdown.classList.add('hidden');

        // Disparar evento change
        const changeEvent = new Event('change', { bubbles: true });
        hiddenInput.dispatchEvent(changeEvent);
    }

    // Función para filtrar opciones
    function filterOptions(searchTerm) {
        const filtered = options.filter(option =>
            option.text.toLowerCase().includes(searchTerm.toLowerCase())
        );
        renderOptions(filtered);
    }

    // Event listeners
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value;
        if (searchTerm.length === 0) {
            hiddenInput.value = '';
            renderOptions(options);
        } else {
            filterOptions(searchTerm);
        }
        dropdown.classList.remove('hidden');
    });

    searchInput.addEventListener('focus', () => {
        renderOptions(options);
        dropdown.classList.remove('hidden');
    });

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Manejar teclas
    searchInput.addEventListener('keydown', (e) => {
        const visibleOptions = dropdown.querySelectorAll('[data-value]:not(.hidden)');
        let currentIndex = Array.from(visibleOptions).findIndex(opt => opt.classList.contains('bg-blue-100'));

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = Math.min(currentIndex + 1, visibleOptions.length - 1);
                updateHighlight(visibleOptions, currentIndex);
                break;
            case 'ArrowUp':
                e.preventDefault();
                currentIndex = Math.max(currentIndex - 1, 0);
                updateHighlight(visibleOptions, currentIndex);
                break;
            case 'Enter':
                e.preventDefault();
                if (currentIndex >= 0 && visibleOptions[currentIndex]) {
                    const selectedValue = visibleOptions[currentIndex].dataset.value;
                    const selectedOption = options.find(opt => opt.value === selectedValue);
                    if (selectedOption) {
                        selectOption(selectedOption);
                    }
                }
                break;
            case 'Escape':
                dropdown.classList.add('hidden');
                break;
        }
    });

    function updateHighlight(visibleOptions, index) {
        visibleOptions.forEach((opt, i) => {
            if (i === index) {
                opt.classList.add('bg-blue-100');
            } else {
                opt.classList.remove('bg-blue-100');
            }
        });
    }

    // Ensamblar componente
    container.appendChild(searchInput);
    container.appendChild(dropdown);
    container.appendChild(hiddenInput);

    // Reemplazar select original
    selectElement.parentNode.insertBefore(container, selectElement);
    selectElement.style.display = 'none';

    return {
        container,
        searchInput,
        hiddenInput,
        dropdown,
        selectOption,
        updateOptions: (newOptions) => {
            options.splice(0, options.length, ...newOptions);
            renderOptions(options);
        }
    };
}

/**
 * Inicializar selectores con funcionalidad de búsqueda
 * @param {string} departamentoSelectId - ID del select de departamento
 * @param {string} ciudadSelectId - ID del select de ciudad
 */
function initSearchableSelect(departamentoSelectId, ciudadSelectId) {
    const departamentoSelect = document.getElementById(departamentoSelectId);
    const ciudadSelect = document.getElementById(ciudadSelectId);

    if (!departamentoSelect || !ciudadSelect) {
        return;
    }

    // Obtener opciones de departamentos
    const departamentoOptions = Array.from(departamentoSelect.options)
        .filter(option => option.value !== '')
        .map(option => ({
            value: option.value,
            text: option.textContent,
            dataset: { ...option.dataset }
        }));

    // Obtener opciones de ciudades
    const ciudadOptions = Array.from(ciudadSelect.options)
        .filter(option => option.value !== '')
        .map(option => ({
            value: option.value,
            text: option.textContent,
            dataset: { ...option.dataset }
        }));

    // Crear searchable selects
    const departamentoSearchable = createSearchableSelect(
        departamentoSelect,
        departamentoOptions,
        'Buscar departamento...'
    );

    const ciudadSearchable = createSearchableSelect(
        ciudadSelect,
        [],  // Inicialmente vacío
        'Primero seleccione un departamento...'
    );

    // Deshabilitar ciudad inicialmente
    ciudadSearchable.searchInput.disabled = true;
    ciudadSearchable.searchInput.classList.add('bg-gray-100', 'cursor-not-allowed');

    // Función para filtrar ciudades por departamento
    function filtrarCiudadesPorDepartamento(departamentoId) {
        if (!departamentoId) {
            // Deshabilitar ciudad si no hay departamento
            ciudadSearchable.updateOptions([]);
            ciudadSearchable.searchInput.disabled = true;
            ciudadSearchable.searchInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            ciudadSearchable.searchInput.placeholder = 'Primero seleccione un departamento...';
        } else {
            // Habilitar ciudad y filtrar opciones
            const ciudadesFiltradas = ciudadOptions.filter(ciudad =>
                ciudad.dataset.departamento === departamentoId
            );
            ciudadSearchable.updateOptions(ciudadesFiltradas);
            ciudadSearchable.searchInput.disabled = false;
            ciudadSearchable.searchInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            ciudadSearchable.searchInput.placeholder = 'Buscar ciudad...';
        }

        // Limpiar selección de ciudad
        ciudadSearchable.hiddenInput.value = '';
        ciudadSearchable.searchInput.value = '';
    }

    // Event listener para cambio de departamento
    departamentoSearchable.hiddenInput.addEventListener('change', (e) => {
        filtrarCiudadesPorDepartamento(e.target.value);
    });

    // Event listener para cambio de ciudad (NO seleccionar departamento automáticamente)
    // Esto fuerza al usuario a seleccionar primero el departamento
    ciudadSearchable.hiddenInput.addEventListener('change', (e) => {
        const ciudadId = e.target.value;
        if (ciudadId && !departamentoSearchable.hiddenInput.value) {
            // Si se selecciona una ciudad sin departamento, mostrar advertencia
            alert('Por favor, seleccione primero un departamento.');
            ciudadSearchable.hiddenInput.value = '';
            ciudadSearchable.searchInput.value = '';
        }
    });

    // Guardar referencias para uso externo
    window.searchableSelects = window.searchableSelects || {};
    window.searchableSelects[departamentoSelectId] = departamentoSearchable;
    window.searchableSelects[ciudadSelectId] = ciudadSearchable;
}

/**
 * Función para establecer valores de ciudad y departamento (útil para autocompletado)
 * @param {string} departamentoSelectId - ID del select de departamento
 * @param {string} ciudadSelectId - ID del select de ciudad
 * @param {string} departamentoId - ID del departamento a seleccionar
 * @param {string} ciudadId - ID de la ciudad a seleccionar
 */
function setCiudadDepartamento(departamentoSelectId, ciudadSelectId, departamentoId, ciudadId) {
    const searchableSelects = window.searchableSelects || {};
    const departamentoSearchable = searchableSelects[departamentoSelectId];
    const ciudadSearchable = searchableSelects[ciudadSelectId];

    if (!departamentoSearchable || !ciudadSearchable) {
        return;
    }

    // Buscar opciones por ID
    const departamentoSelect = document.getElementById(departamentoSelectId.replace('_searchable', '')) || document.getElementById(departamentoSelectId);
    const ciudadSelect = document.getElementById(ciudadSelectId.replace('_searchable', '')) || document.getElementById(ciudadSelectId);

    if (departamentoId && departamentoSelect) {
        const departamentoOption = departamentoSelect.querySelector(`option[value="${departamentoId}"]`);
        if (departamentoOption) {
            const departamento = {
                value: departamentoOption.value,
                text: departamentoOption.textContent,
                dataset: { ...departamentoOption.dataset }
            };
            departamentoSearchable.selectOption(departamento);

            // Filtrar ciudades
            const ciudadOptions = Array.from(ciudadSelect.options)
                .filter(option => option.value !== '')
                .map(option => ({
                    value: option.value,
                    text: option.textContent,
                    dataset: { ...option.dataset }
                }));

            const ciudadesFiltradas = ciudadOptions.filter(ciudad =>
                ciudad.dataset.departamento === departamentoId
            );
            ciudadSearchable.updateOptions(ciudadesFiltradas);

            // Seleccionar ciudad si se proporciona
            if (ciudadId) {
                setTimeout(() => {
                    const ciudadOption = ciudadSelect.querySelector(`option[value="${ciudadId}"]`);
                    if (ciudadOption) {
                        const ciudad = {
                            value: ciudadOption.value,
                            text: ciudadOption.textContent,
                            dataset: { ...ciudadOption.dataset }
                        };
                        ciudadSearchable.selectOption(ciudad);
                    }
                }, 100);
            }
        }
    }
}

// Exportar funciones para uso global
window.setCiudadDepartamento = setCiudadDepartamento;
