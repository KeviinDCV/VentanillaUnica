// Funcionalidad para la página de previsualización de radicados
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidad de arrastrar y soltar para el sello
    initDragAndDrop();
    
    // Inicializar botones de acción
    initActionButtons();
});

/**
 * Inicializar funcionalidad de arrastrar y soltar para el sello de radicado
 */
function initDragAndDrop() {
    let isDragging = false;
    let currentX;
    let currentY;
    let initialX;
    let initialY;
    let xOffset = 0;
    let yOffset = 0;

    const sello = document.getElementById('sello-radicado');
    const container = document.getElementById('documento-preview');

    if (!sello || !container) {
        console.warn('Elementos de drag and drop no encontrados');
        return;
    }

    sello.addEventListener('dragstart', dragStart);
    sello.addEventListener('dragend', dragEnd);
    container.addEventListener('dragover', dragOver);
    container.addEventListener('drop', drop);

    function dragStart(e) {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;
        
        if (e.target === sello) {
            isDragging = true;
            sello.classList.add('dragging');
        }
    }

    function dragEnd(e) {
        initialX = currentX;
        initialY = currentY;
        isDragging = false;
        sello.classList.remove('dragging');
    }

    function dragOver(e) {
        e.preventDefault();
    }

    function drop(e) {
        e.preventDefault();
        
        if (isDragging) {
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;
            
            xOffset = currentX;
            yOffset = currentY;
            
            setTranslate(currentX, currentY, sello);
        }
    }

    function setTranslate(xPos, yPos, el) {
        el.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
    }
}

/**
 * Inicializar botones de acción (Imprimir y Cerrar)
 */
function initActionButtons() {
    // Botón de imprimir
    const printButton = document.querySelector('[data-action="print"]');
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }

    // Botón de cerrar
    const closeButton = document.querySelector('[data-action="close"]');
    if (closeButton) {
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.close();
        });
    }
}
