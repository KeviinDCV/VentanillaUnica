/**
 * UniRadic - Sistema de Previsualización de Radicados
 * Funcionalidad avanzada para previsualización con sello movible y digitalización
 */

class PreviewManager {
    constructor() {
        this.isDragging = false;
        this.currentX = 0;
        this.currentY = 0;
        this.initialX = 0;
        this.initialY = 0;
        this.xOffset = 0;
        this.yOffset = 0;
        this.selloPosition = { x: 0, y: 0 };

        this.init();
    }

    init() {
        this.initDragAndDrop();
        this.initActionButtons();
        this.initDigitalizationFlow();
        this.setupKeyboardShortcuts();
        this.addDragIndicator();
        this.setupResizeHandler();
        this.setupEmergencyCheck();
        this.setupPrintEvents();
    }

    /**
     * Inicializar funcionalidad de arrastrar y soltar para el sello de radicado
     */
    initDragAndDrop() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (!sello || !container) {
            console.warn('Elementos de drag and drop no encontrados');
            return;
        }

        // Deshabilitar drag nativo del navegador
        sello.draggable = false;
        sello.setAttribute('draggable', 'false');

        // Prevenir comportamientos nativos del navegador
        sello.addEventListener('dragstart', (e) => {
            e.preventDefault();
            return false;
        });

        sello.addEventListener('drag', (e) => {
            e.preventDefault();
            return false;
        });

        // Configurar eventos de mouse
        sello.addEventListener('mousedown', (e) => this.dragStart(e));
        document.addEventListener('mousemove', (e) => this.dragMove(e));
        document.addEventListener('mouseup', () => this.dragEnd());

        // Configurar eventos táctiles para dispositivos móviles
        sello.addEventListener('touchstart', (e) => {
            e.preventDefault();
            this.dragStart(e.touches[0]);
        });
        document.addEventListener('touchmove', (e) => {
            if (this.isDragging) {
                e.preventDefault();
                this.dragMove(e.touches[0]);
            }
        });
        document.addEventListener('touchend', () => this.dragEnd());

        // Prevenir selección de texto y otros comportamientos
        sello.addEventListener('selectstart', (e) => e.preventDefault());
        sello.addEventListener('contextmenu', (e) => e.preventDefault());

        // Prevenir que las imágenes dentro del sello sean arrastrables
        const images = sello.querySelectorAll('img');
        images.forEach(img => {
            img.draggable = false;
            img.addEventListener('dragstart', (e) => e.preventDefault());
        });

        // Asegurar que todos los elementos hijos no sean arrastrables
        const allChildren = sello.querySelectorAll('*');
        allChildren.forEach(child => {
            child.draggable = false;
            child.addEventListener('dragstart', (e) => e.preventDefault());
            child.addEventListener('selectstart', (e) => e.preventDefault());
        });
    }

    dragStart(e) {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');
        if (!sello || !container) return;

        // Prevenir comportamiento por defecto
        e.preventDefault();
        e.stopPropagation();

        // Calcular offset inicial relativo al contenedor
        const containerRect = container.getBoundingClientRect();
        const selloRect = sello.getBoundingClientRect();

        // Offset del mouse dentro del sticker
        this.initialX = e.clientX - selloRect.left;
        this.initialY = e.clientY - selloRect.top;

        // Posición actual del sticker relativa al contenedor
        this.currentX = selloRect.left - containerRect.left;
        this.currentY = selloRect.top - containerRect.top;

        if (e.target === sello || sello.contains(e.target)) {
            this.isDragging = true;
            sello.classList.add('dragging');
            document.body.classList.add('dragging');

            // Cambiar cursor en todo el documento
            document.body.style.cursor = 'grabbing !important';
            document.body.style.userSelect = 'none';

            // Asegurar que el sello esté por encima de todo
            sello.style.zIndex = '9999';

            // Deshabilitar transiciones durante el arrastre
            sello.style.transition = 'none';

            console.log('Drag iniciado en posición:', { x: this.currentX, y: this.currentY });
        }
    }

    dragMove(e) {
        if (!this.isDragging) return;

        e.preventDefault();
        e.stopPropagation();

        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (!sello || !container) return;

        // Calcular nueva posición basada en la posición del mouse relativa al contenedor
        const containerRect = container.getBoundingClientRect();
        const newX = e.clientX - containerRect.left - this.initialX;
        const newY = e.clientY - containerRect.top - this.initialY;

        // Obtener dimensiones actuales
        const containerWidth = container.offsetWidth;
        const containerHeight = container.offsetHeight;
        const selloWidth = sello.offsetWidth;
        const selloHeight = sello.offsetHeight;

        // Calcular límites seguros
        const margin = 15;
        const maxX = containerWidth - selloWidth - margin;
        const maxY = containerHeight - selloHeight - margin;
        const minX = margin;
        const minY = margin;

        // Aplicar límites
        this.currentX = Math.max(minX, Math.min(newX, maxX));
        this.currentY = Math.max(minY, Math.min(newY, maxY));

        // Verificar proximidad a bordes para feedback visual
        this.checkBoundaryProximity(this.currentX, this.currentY, minX, minY, maxX, maxY, sello);

        // Aplicar posición inmediatamente
        this.setTranslate(this.currentX, this.currentY, sello);

        // Guardar posición actual
        this.selloPosition = { x: this.currentX, y: this.currentY };

        // Mantener cursor de grabbing
        document.body.style.cursor = 'grabbing';
    }

    dragEnd() {
        if (!this.isDragging) return;

        const sello = document.getElementById('sello-radicado');
        if (sello) {
            sello.classList.remove('dragging');
            sello.style.zIndex = '10'; // Restaurar z-index original
            sello.style.transition = ''; // Restaurar transiciones
        }

        // Restaurar cursor del documento
        document.body.classList.remove('dragging');
        document.body.style.cursor = '';
        document.body.style.userSelect = '';

        this.isDragging = false;

        // Guardar posición en localStorage para persistencia
        localStorage.setItem('selloPosition', JSON.stringify(this.selloPosition));

        console.log('Drag terminado, posición guardada:', this.selloPosition); // Debug
    }

    setTranslate(xPos, yPos, el) {
        // Usar left y top en lugar de transform para mejor control
        el.style.left = `${xPos}px`;
        el.style.top = `${yPos}px`;
        el.style.transform = 'none'; // Limpiar cualquier transform previo
    }

    /**
     * Verificar proximidad a los bordes y dar feedback visual
     */
    checkBoundaryProximity(currentX, currentY, minX, minY, maxX, maxY, sello) {
        const proximityThreshold = 25; // Distancia en px para activar feedback

        let nearBoundary = false;
        let boundaryClass = '';

        // Verificar proximidad a cada borde
        if (currentX <= minX + proximityThreshold) {
            nearBoundary = true;
            boundaryClass = 'near-left-boundary';
        } else if (currentX >= maxX - proximityThreshold) {
            nearBoundary = true;
            boundaryClass = 'near-right-boundary';
        }

        if (currentY <= minY + proximityThreshold) {
            nearBoundary = true;
            boundaryClass += ' near-top-boundary';
        } else if (currentY >= maxY - proximityThreshold) {
            nearBoundary = true;
            boundaryClass += ' near-bottom-boundary';
        }

        // Aplicar o remover clases de proximidad
        sello.className = sello.className.replace(/near-\w+-boundary/g, '').trim();
        if (nearBoundary) {
            sello.className += ' ' + boundaryClass.trim();
        }

        // Feedback adicional cuando está en el límite exacto
        if (currentX === minX || currentX === maxX || currentY === minY || currentY === maxY) {
            sello.classList.add('at-boundary');
            // Vibración sutil en dispositivos compatibles
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        } else {
            sello.classList.remove('at-boundary');
        }
    }

    /**
     * Agregar indicador visual para el arrastre
     */
    addDragIndicator() {
        const sello = document.getElementById('sello-radicado');
        if (!sello) return;

        // Agregar tooltip informativo
        sello.title = 'Arrastra para reposicionar el sello de radicado';

        // Agregar indicador visual
        const indicator = document.createElement('div');
        indicator.innerHTML = '⋮⋮';
        indicator.style.cssText = `
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 8px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1;
            pointer-events: none;
        `;
        sello.appendChild(indicator);
    }

    /**
     * Restaurar posición del sello desde localStorage
     */
    restoreSelloPosition() {
        const savedPosition = localStorage.getItem('selloPosition');
        if (savedPosition) {
            try {
                const position = JSON.parse(savedPosition);
                const sello = document.getElementById('sello-radicado');
                const container = document.getElementById('documento-preview');

                if (sello && container) {
                    // Validar que la posición esté dentro de los límites actuales
                    const validatedPosition = this.validatePosition(position, container, sello);

                    this.setTranslate(validatedPosition.x, validatedPosition.y, sello);
                    this.xOffset = validatedPosition.x;
                    this.yOffset = validatedPosition.y;
                    this.selloPosition = validatedPosition;

                    // Guardar la posición corregida si fue modificada
                    if (validatedPosition.x !== position.x || validatedPosition.y !== position.y) {
                        localStorage.setItem('selloPosition', JSON.stringify(validatedPosition));
                        console.log('Posición del sello corregida para estar dentro de los límites');
                    }
                }
            } catch (e) {
                console.warn('Error al restaurar posición del sello:', e);
                // Posición por defecto si hay error
                this.setDefaultPosition();
            }
        } else {
            // Establecer posición por defecto si no hay posición guardada
            this.setDefaultPosition();
        }
    }

    /**
     * Validar que una posición esté dentro de los límites permitidos
     */
    validatePosition(position, container, sello) {
        const containerWidth = container.offsetWidth;
        const containerHeight = container.offsetHeight;
        const selloWidth = sello.offsetWidth;
        const selloHeight = sello.offsetHeight;

        // Margen mínimo para asegurar que siempre sea visible y accesible
        const margin = Math.max(15, Math.min(containerWidth * 0.05, containerHeight * 0.05));

        // Calcular límites seguros
        const maxX = Math.max(margin, containerWidth - selloWidth - margin);
        const maxY = Math.max(margin, containerHeight - selloHeight - margin);
        const minX = margin;
        const minY = margin;

        // Validación adicional para contenedores muy pequeños
        if (maxX <= minX || maxY <= minY) {
            console.warn('Contenedor demasiado pequeño, usando posición por defecto');
            return {
                x: Math.max(0, (containerWidth - selloWidth) / 2),
                y: Math.max(0, (containerHeight - selloHeight) / 2)
            };
        }

        return {
            x: Math.max(minX, Math.min(position.x, maxX)),
            y: Math.max(minY, Math.min(position.y, maxY))
        };
    }

    /**
     * Establecer posición por defecto del sello
     */
    setDefaultPosition() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (sello && container) {
            // Esperar a que el DOM esté completamente renderizado
            setTimeout(() => {
                // Posición por defecto: esquina superior derecha con margen
                const margin = 20;
                const containerWidth = container.offsetWidth;
                const containerHeight = container.offsetHeight;
                const selloWidth = sello.offsetWidth;
                const selloHeight = sello.offsetHeight;

                console.log('Setting default position with dimensions:', {
                    containerWidth,
                    containerHeight,
                    selloWidth,
                    selloHeight
                });

                const defaultX = containerWidth - selloWidth - margin;
                const defaultY = margin;

                const validatedPosition = this.validatePosition({ x: defaultX, y: defaultY }, container, sello);

                this.setTranslate(validatedPosition.x, validatedPosition.y, sello);
                this.xOffset = validatedPosition.x;
                this.yOffset = validatedPosition.y;
                this.selloPosition = validatedPosition;

                // Guardar posición por defecto
                localStorage.setItem('selloPosition', JSON.stringify(validatedPosition));

                console.log('Default position set to:', validatedPosition);
            }, 100);
        }
    }

    /**
     * Inicializar botones de acción
     */
    initActionButtons() {
        // Botón de imprimir
        const printBtn = document.getElementById('btn-imprimir');
        if (printBtn) {
            printBtn.addEventListener('click', () => this.handlePrint());
        }

        // Botón de digitalizar
        const digitizeBtn = document.getElementById('btn-digitalizar');
        if (digitizeBtn) {
            digitizeBtn.addEventListener('click', () => this.showDigitalizationModal());
        }

        // Botón de finalizar
        const finalizeBtn = document.getElementById('btn-finalizar');
        if (finalizeBtn) {
            finalizeBtn.addEventListener('click', () => this.handleFinalize());
        }

        // Botón de volver
        const backBtn = document.getElementById('btn-volver');
        if (backBtn) {
            backBtn.addEventListener('click', () => this.handleBack());
        }
    }

    /**
     * Manejar impresión del documento
     */
    handlePrint() {
        // Sincronizar posición actual del sticker antes de imprimir
        this.syncStickerPosition();

        // Guardar posición actual del sello antes de imprimir
        localStorage.setItem('selloPosition', JSON.stringify(this.selloPosition));

        // Preparar documento para impresión
        this.prepareForPrint();

        // Imprimir después de un pequeño delay para asegurar que los estilos se apliquen
        setTimeout(() => {
            window.print();
        }, 200);

        // Restaurar después de imprimir
        setTimeout(() => {
            this.restoreAfterPrint();
        }, 1000);
    }

    /**
     * Sincronizar posición actual del sticker
     */
    syncStickerPosition() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (sello && container) {
            // Método 1: Usar getBoundingClientRect (actual)
            const containerRect = container.getBoundingClientRect();
            const selloRect = sello.getBoundingClientRect();
            const currentX = selloRect.left - containerRect.left;
            const currentY = selloRect.top - containerRect.top;

            // Método 2: Usar offsetLeft/offsetTop (más directo)
            const offsetX = sello.offsetLeft;
            const offsetY = sello.offsetTop;

            // Método 3: Usar computed styles
            const computedStyle = window.getComputedStyle(sello);
            const styleX = parseInt(computedStyle.left) || 0;
            const styleY = parseInt(computedStyle.top) || 0;

            console.log('Comparación de métodos de posición:', {
                boundingRect: { x: currentX, y: currentY },
                offset: { x: offsetX, y: offsetY },
                computedStyle: { x: styleX, y: styleY },
                current: this.selloPosition
            });

            // Usar el método más confiable (offset)
            this.selloPosition = { x: offsetX, y: offsetY };

            // Actualizar también las propiedades de offset
            this.xOffset = offsetX;
            this.yOffset = offsetY;

            console.log('Posición sincronizada (usando offset):', this.selloPosition);
        }
    }

    /**
     * Configurar eventos de impresión
     */
    setupPrintEvents() {
        // Evento antes de imprimir
        window.addEventListener('beforeprint', () => {
            console.log('Evento beforeprint disparado');
            this.syncStickerPosition();
            this.prepareForPrint();
        });

        // Evento después de imprimir
        window.addEventListener('afterprint', () => {
            console.log('Evento afterprint disparado');
            this.restoreAfterPrint();
        });
    }

    /**
     * Preparar documento para impresión optimizada
     */
    prepareForPrint() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (sello && container && this.selloPosition) {
            console.log('Preparando impresión con posición exacta:', this.selloPosition);

            // Calcular posición como porcentajes para mayor consistencia
            const containerWidth = container.offsetWidth;
            const containerHeight = container.offsetHeight;

            // Convertir posición absoluta a porcentajes
            const percentX = (this.selloPosition.x / containerWidth) * 100;
            const percentY = (this.selloPosition.y / containerHeight) * 100;

            console.log('Posición en porcentajes:', { percentX, percentY });
            console.log('Dimensiones del contenedor:', { containerWidth, containerHeight });

            // Usar posición absoluta en píxeles para impresión
            const exactX = this.selloPosition.x;
            const exactY = this.selloPosition.y;

            // Establecer variables CSS para la posición del sticker en impresión
            document.documentElement.style.setProperty('--sticker-left', `${exactX}px`);
            document.documentElement.style.setProperty('--sticker-top', `${exactY}px`);
            document.documentElement.style.setProperty('--sticker-percent-x', `${percentX}%`);
            document.documentElement.style.setProperty('--sticker-percent-y', `${percentY}%`);

            // Fijar posición del sello para impresión con máxima precisión
            sello.style.setProperty('position', 'absolute', 'important');
            sello.style.setProperty('left', `${exactX}px`, 'important');
            sello.style.setProperty('top', `${exactY}px`, 'important');
            sello.style.setProperty('transform', 'none', 'important');
            sello.style.setProperty('z-index', '1000', 'important');

            // Eliminar cualquier estilo que pueda afectar la posición
            sello.style.setProperty('margin', '0', 'important');
            sello.style.setProperty('box-shadow', 'none', 'important');
            sello.style.setProperty('text-shadow', 'none', 'important');
            sello.style.setProperty('backdrop-filter', 'none', 'important');
            sello.style.setProperty('filter', 'none', 'important');
            sello.style.setProperty('transition', 'none', 'important');

            // Asegurar que el sticker sea visible en impresión
            sello.style.setProperty('display', 'block', 'important');
            sello.style.setProperty('visibility', 'visible', 'important');
            sello.style.setProperty('opacity', '1', 'important');

            console.log('Sticker preparado para impresión:', {
                pixelPosition: { left: exactX, top: exactY },
                percentPosition: { x: percentX, y: percentY },
                containerDimensions: { width: containerWidth, height: containerHeight },
                computed: {
                    left: window.getComputedStyle(sello).getPropertyValue('left'),
                    top: window.getComputedStyle(sello).getPropertyValue('top')
                }
            });
        }

        if (container) {
            // Optimizar contenedor para impresión manteniendo estructura exacta
            container.style.setProperty('height', 'auto', 'important');
            container.style.setProperty('min-height', 'auto', 'important');
            container.style.setProperty('max-height', 'none', 'important');
            container.style.setProperty('overflow', 'visible', 'important');
            container.style.setProperty('position', 'relative', 'important');
            container.style.setProperty('margin', '0', 'important');
            container.style.setProperty('padding', '32px', 'important'); // Mantener padding exacto del preview
            container.style.setProperty('border', 'none', 'important');
            container.style.setProperty('box-shadow', 'none', 'important');
        }

        // Ocultar scrollbars temporalmente
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }

    /**
     * Restaurar estilos después de impresión
     */
    restoreAfterPrint() {
        const container = document.getElementById('documento-preview');

        if (container) {
            // Restaurar estilos del contenedor
            container.style.height = '';
            container.style.minHeight = '';
            container.style.maxHeight = '';
            container.style.overflow = '';
        }

        // Restaurar scrollbars
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }

    /**
     * Mostrar modal de digitalización
     */
    showDigitalizationModal() {
        const modal = this.createDigitalizationModal();
        document.body.appendChild(modal);

        // Mostrar modal con animación
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.modal-content').classList.remove('scale-95');
        }, 10);
    }

    /**
     * Crear modal de digitalización
     */
    createDigitalizationModal() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 opacity-0 transition-opacity duration-300';
        modal.id = 'digitalization-modal';

        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white modal-content transform scale-95 transition-transform duration-300">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Digitalización de Documento</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="this.closest('#digitalization-modal').remove()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">
                            Siga estos pasos para digitalizar el documento con el sello de radicado:
                        </p>

                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                            <li>Imprima el documento con el sello en la posición deseada</li>
                            <li>Coloque el documento impreso en el escáner</li>
                            <li>Escanee el documento en formato PDF</li>
                            <li>Cargue el archivo digitalizado usando el botón de abajo</li>
                        </ol>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cargar Documento Digitalizado
                        </label>
                        <input type="file" id="documento-digitalizado" accept=".pdf"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-uniradical-blue focus:ring-uniradical-blue">
                        <p class="text-xs text-gray-500 mt-1">Solo archivos PDF. Tamaño máximo: 10MB</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200"
                                onclick="this.closest('#digitalization-modal').remove()">
                            Cancelar
                        </button>
                        <button type="button" id="upload-digitalized-doc"
                                class="px-4 py-2 bg-uniradical-blue text-white rounded-md hover:bg-opacity-90 transition duration-200">
                            Cargar Documento
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Configurar evento de carga
        const uploadBtn = modal.querySelector('#upload-digitalized-doc');
        uploadBtn.addEventListener('click', () => this.handleDigitalizedUpload());

        return modal;
    }

    /**
     * Manejar carga de documento digitalizado
     */
    handleDigitalizedUpload() {
        const fileInput = document.getElementById('documento-digitalizado');
        const file = fileInput?.files[0];

        if (!file) {
            this.showNotification('Por favor seleccione un archivo', 'error');
            return;
        }

        if (file.type !== 'application/pdf') {
            this.showNotification('Solo se permiten archivos PDF', 'error');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            this.showNotification('El archivo es demasiado grande (máximo 10MB)', 'error');
            return;
        }

        // Simular carga del archivo
        this.uploadDigitalizedDocument(file);
    }

    /**
     * Subir documento digitalizado
     */
    uploadDigitalizedDocument(file) {
        const modal = document.getElementById('digitalization-modal');
        const uploadBtn = modal.querySelector('#upload-digitalized-doc');

        uploadBtn.disabled = true;
        uploadBtn.textContent = 'Cargando...';

        // Crear FormData para envío
        const formData = new FormData();
        formData.append('documento_digitalizado', file);
        formData.append('numero_radicado', this.radicadoData?.numero_radicado || '');
        formData.append('tipo_radicado', this.radicadoData?.tipo || '');

        // Obtener token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/radicacion/upload-digitalized', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Documento digitalizado cargado exitosamente', 'success');
                modal.remove();

                // Marcar como digitalizado
                localStorage.setItem('hasDigitalizedDoc', 'true');

                // Habilitar botón de finalizar
                const finalizeBtn = document.getElementById('btn-finalizar');
                if (finalizeBtn) {
                    finalizeBtn.disabled = false;
                    finalizeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    finalizeBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                    finalizeBtn.textContent = 'Finalizar Radicado';
                }
            } else {
                throw new Error(data.message || 'Error al cargar el documento');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showNotification('Error al cargar el documento: ' + error.message, 'error');
        })
        .finally(() => {
            uploadBtn.disabled = false;
            uploadBtn.textContent = 'Cargar Documento';
        });
    }

    /**
     * Inicializar flujo de digitalización
     */
    initDigitalizationFlow() {
        // Restaurar posición del sello
        this.restoreSelloPosition();

        // Verificar si hay documento digitalizado
        const hasDigitalizedDoc = localStorage.getItem('hasDigitalizedDoc') === 'true';
        if (hasDigitalizedDoc) {
            const finalizeBtn = document.getElementById('btn-finalizar');
            if (finalizeBtn) {
                finalizeBtn.disabled = false;
                finalizeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    /**
     * Configurar atajos de teclado
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + P para imprimir
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                this.handlePrint();
            }

            // Escape para volver
            if (e.key === 'Escape') {
                this.handleBack();
            }
        });

        // Detectar cuando termina la impresión
        window.addEventListener('afterprint', () => {
            this.restoreAfterPrint();
        });

        // Detectar cuando se cancela la impresión
        window.addEventListener('beforeprint', () => {
            this.prepareForPrint();
        });
    }

    /**
     * Configurar manejador de redimensionamiento de ventana
     */
    setupResizeHandler() {
        let resizeTimeout;

        window.addEventListener('resize', () => {
            // Debounce para evitar múltiples ejecuciones
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 250);
        });
    }

    /**
     * Manejar redimensionamiento de ventana
     */
    handleResize() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (sello && container && this.selloPosition) {
            // Revalidar posición actual con las nuevas dimensiones
            const validatedPosition = this.validatePosition(this.selloPosition, container, sello);

            // Solo actualizar si la posición cambió
            if (validatedPosition.x !== this.selloPosition.x || validatedPosition.y !== this.selloPosition.y) {
                this.setTranslate(validatedPosition.x, validatedPosition.y, sello);
                this.xOffset = validatedPosition.x;
                this.yOffset = validatedPosition.y;
                this.selloPosition = validatedPosition;

                // Guardar nueva posición
                localStorage.setItem('selloPosition', JSON.stringify(validatedPosition));

                console.log('Posición del sello ajustada por redimensionamiento:', validatedPosition);
            }
        }
    }

    /**
     * Manejar finalización del radicado
     */
    handleFinalize() {
        const finalizeBtn = document.getElementById('btn-finalizar');
        if (finalizeBtn && finalizeBtn.disabled) {
            this.showNotification('Debe cargar el documento digitalizado antes de finalizar', 'warning');
            return;
        }

        // Mostrar confirmación
        if (confirm('¿Está seguro de que desea finalizar este radicado? Esta acción no se puede deshacer.')) {
            this.finalizeRadicado();
        }
    }

    /**
     * Finalizar radicado
     */
    finalizeRadicado() {
        const finalizeBtn = document.getElementById('btn-finalizar');
        if (finalizeBtn) {
            finalizeBtn.disabled = true;
            finalizeBtn.textContent = 'Finalizando...';
        }

        // Preparar datos para envío
        const formData = new FormData();
        formData.append('numero_radicado', this.radicadoData?.numero_radicado || '');
        formData.append('tipo_radicado', this.radicadoData?.tipo || '');
        formData.append('posicion_sello', JSON.stringify(this.selloPosition));

        // Obtener token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/radicacion/finalizar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Radicado finalizado exitosamente', 'success');

                // Limpiar localStorage
                localStorage.removeItem('selloPosition');
                localStorage.removeItem('hasDigitalizedDoc');

                // Redirigir después de un breve delay
                setTimeout(() => {
                    window.location.href = this.radicadoData?.redirect_url || '/radicacion';
                }, 2000);
            } else {
                throw new Error(data.message || 'Error al finalizar el radicado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showNotification('Error al finalizar el radicado: ' + error.message, 'error');

            // Restaurar botón
            if (finalizeBtn) {
                finalizeBtn.disabled = false;
                finalizeBtn.textContent = 'Finalizar';
            }
        });
    }

    /**
     * Manejar botón volver
     */
    handleBack() {
        if (confirm('¿Está seguro de que desea volver? Los cambios no guardados se perderán.')) {
            window.history.back();
        }
    }

    /**
     * Mostrar notificación
     */
    showNotification(message, type = 'info') {
        if (window.UniRadicNotifications) {
            window.UniRadicNotifications[type]('Información', message);
        } else {
            alert(message);
        }
    }



    /**
     * Configurar verificación de emergencia para detectar sticker perdido
     */
    setupEmergencyCheck() {
        // Temporalmente deshabilitado para debugging
        // TODO: Rehabilitar después de corregir el cálculo de límites

        // Verificar solo una vez después de cargar para debug
        setTimeout(() => {
            this.debugStickerPosition();
        }, 2000);
    }

    /**
     * Verificar si el sticker está visible y accesible
     */
    checkStickerVisibility() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (!sello || !container) return;

        // Obtener posición relativa al contenedor, no absoluta
        const containerRect = container.getBoundingClientRect();
        const selloRect = sello.getBoundingClientRect();

        // Calcular posición relativa
        const relativeLeft = selloRect.left - containerRect.left;
        const relativeTop = selloRect.top - containerRect.top;
        const relativeRight = relativeLeft + sello.offsetWidth;
        const relativeBottom = relativeTop + sello.offsetHeight;

        // Límites del contenedor
        const containerWidth = container.offsetWidth;
        const containerHeight = container.offsetHeight;
        const margin = 15;

        // Debug info
        console.log('Debug sticker position:', {
            relativeLeft,
            relativeTop,
            relativeRight,
            relativeBottom,
            containerWidth,
            containerHeight,
            selloWidth: sello.offsetWidth,
            selloHeight: sello.offsetHeight,
            currentPosition: this.selloPosition
        });

        // Verificar si está fuera de los límites con margen
        const isOutside = (
            relativeLeft < margin ||
            relativeTop < margin ||
            relativeRight > containerWidth - margin ||
            relativeBottom > containerHeight - margin
        );

        if (isOutside) {
            console.warn('Sticker detectado fuera de límites, reposicionando automáticamente');
            this.emergencyRepositionSticker();
        }
    }

    /**
     * Reposicionar sticker de emergencia sin animación
     */
    emergencyRepositionSticker() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (sello && container) {
            // Posición segura: esquina superior derecha
            const margin = 20;
            const safeX = container.offsetWidth - sello.offsetWidth - margin;
            const safeY = margin;

            const validatedPosition = this.validatePosition({ x: safeX, y: safeY }, container, sello);

            // Aplicar sin animación para corrección inmediata
            sello.style.transition = 'none';
            this.setTranslate(validatedPosition.x, validatedPosition.y, sello);

            // Actualizar variables
            this.xOffset = validatedPosition.x;
            this.yOffset = validatedPosition.y;
            this.selloPosition = validatedPosition;

            // Guardar posición corregida
            localStorage.setItem('selloPosition', JSON.stringify(validatedPosition));

            // Restaurar transición después de un momento
            setTimeout(() => {
                sello.style.transition = '';
            }, 100);

            console.log('Sticker reposicionado automáticamente a posición segura:', validatedPosition);
        }
    }

    /**
     * Función de debug para analizar la posición del sticker
     */
    debugStickerPosition() {
        const sello = document.getElementById('sello-radicado');
        const container = document.getElementById('documento-preview');

        if (!sello || !container) {
            console.log('Debug: Elementos no encontrados');
            return;
        }

        const containerRect = container.getBoundingClientRect();
        const selloRect = sello.getBoundingClientRect();

        console.log('=== DEBUG STICKER POSITION ===');
        console.log('Container dimensions:', {
            width: container.offsetWidth,
            height: container.offsetHeight,
            clientWidth: container.clientWidth,
            clientHeight: container.clientHeight
        });

        console.log('Container position:', {
            left: containerRect.left,
            top: containerRect.top,
            right: containerRect.right,
            bottom: containerRect.bottom
        });

        console.log('Sticker dimensions:', {
            width: sello.offsetWidth,
            height: sello.offsetHeight
        });

        console.log('Sticker absolute position:', {
            left: selloRect.left,
            top: selloRect.top,
            right: selloRect.right,
            bottom: selloRect.bottom
        });

        console.log('Sticker relative position:', {
            left: selloRect.left - containerRect.left,
            top: selloRect.top - containerRect.top,
            right: (selloRect.left - containerRect.left) + sello.offsetWidth,
            bottom: (selloRect.top - containerRect.top) + sello.offsetHeight
        });

        console.log('Current transform:', sello.style.transform);
        console.log('Saved position:', this.selloPosition);

        // Calcular límites teóricos
        const margin = 15;
        const maxX = container.offsetWidth - sello.offsetWidth - margin;
        const maxY = container.offsetHeight - sello.offsetHeight - margin;

        console.log('Calculated limits:', {
            minX: margin,
            minY: margin,
            maxX: maxX,
            maxY: maxY
        });

        console.log('=== END DEBUG ===');
    }
}

// Funcionalidad para la página de previsualización de radicados
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar manager de previsualización
    if (document.getElementById('sello-radicado')) {
        window.previewManager = new PreviewManager();
    }
});

// Exportar para uso global
window.PreviewManager = PreviewManager;


