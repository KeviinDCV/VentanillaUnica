/**
 * Funcionalidad para la página de administración de logs
 * UniRadic - Sistema de Gestión Documental
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeLogsPage();
});

/**
 * Inicializar funcionalidad de la página de logs
 */
function initializeLogsPage() {
    setupExportButton();
    setupTableInteractions();
}

/**
 * Configurar botón de exportación de logs
 */
function setupExportButton() {
    const exportBtn = document.getElementById('exportLogsBtn');

    if (exportBtn) {
        exportBtn.addEventListener('click', handleExportLogs);
    }
}

/**
 * Manejar exportación de logs
 */
function handleExportLogs(event) {
    event.preventDefault();

    const button = event.target;
    const originalText = button.textContent;

    // Mostrar estado de carga
    button.disabled = true;
    button.textContent = 'Exportando...';
    button.classList.add('opacity-50');

    // Simular proceso de exportación
    setTimeout(() => {
        try {
            // Obtener datos de la tabla
            const tableData = extractTableData();

            if (tableData.length > 0) {
                // Exportar como CSV
                exportLogsToCSV(tableData);

                // Mostrar notificación de éxito
                showSuccessNotification('Logs exportados exitosamente');
            } else {
                showWarningNotification('No hay datos para exportar');
            }
        } catch (error) {
            console.error('Error al exportar logs:', error);
            showErrorNotification('Error al exportar los logs');
        } finally {
            // Restaurar botón
            button.disabled = false;
            button.textContent = originalText;
            button.classList.remove('opacity-50');
        }
    }, 1500);
}

/**
 * Extraer datos de la tabla de logs
 */
function extractTableData() {
    const table = document.querySelector('table');
    if (!table) return [];

    const data = [];
    const headers = [];

    // Extraer headers
    const headerRow = table.querySelector('thead tr');
    if (headerRow) {
        headerRow.querySelectorAll('th').forEach(th => {
            headers.push(th.textContent.trim());
        });
        data.push(headers);
    }

    // Extraer filas de datos
    const bodyRows = table.querySelectorAll('tbody tr');
    bodyRows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach(td => {
            // Limpiar texto y remover elementos innecesarios
            let cellText = td.textContent.trim();
            // Remover espacios extra y saltos de línea
            cellText = cellText.replace(/\s+/g, ' ');
            rowData.push(cellText);
        });
        if (rowData.length > 0) {
            data.push(rowData);
        }
    });

    return data;
}

/**
 * Exportar logs a archivo CSV
 */
function exportLogsToCSV(data) {
    const csvContent = data.map(row =>
        row.map(field => `"${field.replace(/"/g, '""')}"`).join(',')
    ).join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        const filename = `logs_uniradical_${getCurrentDateString()}.csv`;

        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Limpiar URL
        URL.revokeObjectURL(url);
    }
}

/**
 * Configurar interacciones de la tabla
 */
function setupTableInteractions() {
    // Agregar efectos hover mejorados a las filas
    const tableRows = document.querySelectorAll('tbody tr');

    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('bg-blue-50');
        });

        row.addEventListener('mouseleave', function() {
            this.classList.remove('bg-blue-50');
        });
    });
}

/**
 * Obtener fecha actual como string para nombres de archivo
 */
function getCurrentDateString() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    return `${year}${month}${day}_${hours}${minutes}`;
}

/**
 * Mostrar notificación de éxito
 */
function showSuccessNotification(message) {
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'success',
            title: 'Exportación Exitosa',
            message: message,
            duration: 3000
        });
    } else {
        // Fallback para navegadores sin el sistema de notificaciones
        alert(message);
    }
}

/**
 * Mostrar notificación de advertencia
 */
function showWarningNotification(message) {
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'warning',
            title: 'Advertencia',
            message: message,
            duration: 4000
        });
    } else {
        alert(message);
    }
}

/**
 * Mostrar notificación de error
 */
function showErrorNotification(message) {
    if (window.UniRadicNotifications) {
        window.UniRadicNotifications.show({
            type: 'error',
            title: 'Error',
            message: message,
            duration: 5000
        });
    } else {
        alert('Error: ' + message);
    }
}

// Exportar funciones para uso global si es necesario
window.AdminLogs = {
    initializeLogsPage,
    handleExportLogs,
    extractTableData,
    exportLogsToCSV
};
