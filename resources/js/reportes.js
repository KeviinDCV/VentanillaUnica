// Funcionalidad para el módulo de Reportes
document.addEventListener('DOMContentLoaded', function() {
    initializeReportesModule();
});

/**
 * Inicializar módulo de reportes
 */
function initializeReportesModule() {
    // Solo ejecutar en páginas de reportes
    if (!document.querySelector('[data-page="reportes"]') && !window.location.pathname.includes('reportes')) {
        return;
    }

    initializeExportButton();
    initializeCustomReportForm();
    initializeCharts();
    
    console.log('Reportes module initialized');
}

/**
 * Inicializar botón de exportar
 */
function initializeExportButton() {
    // Buscar botón por ID específico primero
    let exportButton = document.querySelector('#exportReportBtn');

    // Si no se encuentra por ID, buscar por clase
    if (!exportButton) {
        exportButton = document.querySelector('.export-button');
    }

    // Si aún no se encuentra, buscar por texto como respaldo
    if (!exportButton) {
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            if (button.textContent.trim() === 'Exportar Reporte') {
                button.addEventListener('click', handleExportReport);
            }
        });
    } else {
        exportButton.addEventListener('click', handleExportReport);
    }
}

/**
 * Manejar exportación de reportes de forma segura
 */
function handleExportReport(event) {
    event.preventDefault();

    const button = event.target;
    const originalText = button.textContent;

    // Prevenir múltiples clics
    if (button.disabled) {
        return;
    }

    // Mostrar estado de carga
    button.disabled = true;
    button.textContent = 'Exportando...';
    button.classList.add('opacity-75');

    // Simular exportación (aquí iría la lógica real)
    setTimeout(() => {
        try {
            // Crear datos de ejemplo para exportar
            const reportData = generateReportData();

            // Validar que hay datos para exportar
            if (!reportData || reportData.length <= 1) {
                throw new Error('No hay datos disponibles para exportar');
            }

            // Generar nombre de archivo único
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
            const filename = `reporte_uniradical_${timestamp}.csv`;

            // Exportar como CSV
            exportToCSV(reportData, filename);

            // Mostrar mensaje de éxito
            showNotification('Reporte exportado exitosamente', 'success');

        } catch (error) {
            console.error('Error en exportación de reporte:', error);
            showNotification(`Error al exportar reporte: ${error.message}`, 'error');
        } finally {
            // Restaurar botón siempre
            button.disabled = false;
            button.textContent = originalText;
            button.classList.remove('opacity-75');
        }
    }, 2000);
}

/**
 * Generar datos del reporte de forma segura
 */
function generateReportData() {
    try {
        const data = [];

        // Header con información más detallada
        data.push([
            'Fecha Radicación',
            'Tipo Documento',
            'Número Radicado',
            'Remitente/Origen',
            'Asunto',
            'Dependencia Destino',
            'Estado Actual',
            'Fecha Vencimiento',
            'Usuario Responsable'
        ]);

        // Datos de ejemplo más realistas (en implementación real vendría del servidor)
        const tipos = ['Entrada', 'Interno', 'Salida'];
        const estados = ['Pendiente', 'En Proceso', 'Respondido', 'Archivado'];
        const dependencias = [
            'Gerencia General',
            'Recursos Humanos',
            'Contabilidad',
            'Sistemas e Informática',
            'Jurídica',
            'Planeación',
            'Calidad',
            'Compras y Suministros'
        ];
        const usuarios = [
            'Ana García',
            'Carlos Rodríguez',
            'María López',
            'Juan Pérez',
            'Laura Martínez'
        ];

        // Generar entre 25 y 75 registros
        const numRegistros = Math.floor(Math.random() * 50) + 25;

        for (let i = 1; i <= numRegistros; i++) {
            const fechaRadicacion = new Date();
            fechaRadicacion.setDate(fechaRadicacion.getDate() - Math.floor(Math.random() * 90));

            const fechaVencimiento = new Date(fechaRadicacion);
            fechaVencimiento.setDate(fechaVencimiento.getDate() + Math.floor(Math.random() * 30) + 5);

            // Sanitizar datos para evitar problemas en CSV
            const asunto = `Asunto del documento ${i}`.replace(/"/g, '""');
            const remitente = `Remitente ${i}`.replace(/"/g, '""');

            data.push([
                fechaRadicacion.toLocaleDateString('es-CO'),
                tipos[Math.floor(Math.random() * tipos.length)],
                `RAD-${String(i).padStart(6, '0')}`,
                remitente,
                asunto,
                dependencias[Math.floor(Math.random() * dependencias.length)],
                estados[Math.floor(Math.random() * estados.length)],
                fechaVencimiento.toLocaleDateString('es-CO'),
                usuarios[Math.floor(Math.random() * usuarios.length)]
            ]);
        }

        return data;

    } catch (error) {
        // Error silencioso en producción por seguridad
        // Retornar datos mínimos en caso de error
        return [
            ['Fecha', 'Tipo', 'Número Radicado', 'Estado'],
            [new Date().toLocaleDateString('es-CO'), 'Error', 'N/A', 'Error al generar datos']
        ];
    }
}

/**
 * Exportar datos a CSV de forma segura
 */
function exportToCSV(data, filename) {
    try {
        // Validar datos de entrada
        if (!data || !Array.isArray(data) || data.length === 0) {
            throw new Error('No hay datos para exportar');
        }

        if (!filename || typeof filename !== 'string') {
            throw new Error('Nombre de archivo inválido');
        }

        // Escapar comillas dobles en los campos para evitar problemas en CSV
        const csvContent = data.map(row =>
            row.map(field => {
                // Convertir a string y escapar comillas dobles
                const stringField = String(field || '');
                return `"${stringField.replace(/"/g, '""')}"`;
            }).join(',')
        ).join('\n');

        // Crear blob con BOM para compatibilidad con Excel
        const BOM = '\uFEFF';
        const blob = new Blob([BOM + csvContent], {
            type: 'text/csv;charset=utf-8;'
        });

        // Verificar soporte para descarga
        const link = document.createElement('a');
        if (typeof link.download === 'undefined') {
            throw new Error('Su navegador no soporta la descarga de archivos');
        }

        // Crear URL temporal y configurar descarga
        const url = URL.createObjectURL(blob);

        try {
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            link.style.position = 'absolute';
            link.style.left = '-9999px';

            // Agregar al DOM, hacer clic y remover
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

        } finally {
            // Limpiar URL del blob inmediatamente después del uso
            // Usar setTimeout para asegurar que la descarga se complete
            setTimeout(() => {
                URL.revokeObjectURL(url);
            }, 100);
        }

    } catch (error) {
        // Error silencioso en producción por seguridad
        showNotification(`Error al exportar: ${error.message}`, 'error');
        throw error; // Re-lanzar para manejo en función llamadora
    }
}

/**
 * Inicializar formulario de reporte personalizado
 */
function initializeCustomReportForm() {
    // Buscar específicamente el formulario de reporte personalizado
    const customReportForm = document.querySelector('#customReportForm');

    if (customReportForm) {
        customReportForm.addEventListener('submit', handleCustomReport);

        // Configurar fechas por defecto
        setDefaultDates();

        console.log('Custom report form initialized');
    } else {
        console.log('Custom report form not found');
    }
}

/**
 * Configurar fechas por defecto de forma robusta
 */
function setDefaultDates() {
    try {
        // Buscar campos específicos por ID
        const fechaDesde = document.querySelector('#fechaDesde');
        const fechaHasta = document.querySelector('#fechaHasta');

        if (fechaDesde && fechaHasta) {
            const hoy = new Date();
            const hace30Dias = new Date();
            hace30Dias.setDate(hoy.getDate() - 30);

            // Formatear fechas en formato YYYY-MM-DD
            const fechaDesdeStr = hace30Dias.toISOString().split('T')[0];
            const fechaHastaStr = hoy.toISOString().split('T')[0];

            fechaDesde.value = fechaDesdeStr;
            fechaHasta.value = fechaHastaStr;

            console.log(`Fechas configuradas: ${fechaDesdeStr} a ${fechaHastaStr}`);
        } else {
            console.warn('No se encontraron los campos de fecha');
        }
    } catch (error) {
        console.error('Error al configurar fechas por defecto:', error);
    }
}

/**
 * Manejar reporte personalizado de forma completa
 */
function handleCustomReport(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const button = form.querySelector('button[type="submit"]');
    const originalText = button.textContent;

    // Prevenir múltiples envíos
    if (button.disabled) {
        return;
    }

    try {
        // Obtener y validar datos del formulario
        const fechaDesde = formData.get('fecha_desde') || document.querySelector('#fechaDesde').value;
        const fechaHasta = formData.get('fecha_hasta') || document.querySelector('#fechaHasta').value;
        const tipoRadicado = formData.get('tipo_radicado') || '';

        // Validaciones
        if (!fechaDesde || !fechaHasta) {
            showNotification('Por favor seleccione las fechas del reporte', 'error');
            return;
        }

        const dateDesde = new Date(fechaDesde);
        const dateHasta = new Date(fechaHasta);

        if (dateDesde > dateHasta) {
            showNotification('La fecha desde no puede ser mayor que la fecha hasta', 'error');
            return;
        }

        // Validar que el rango no sea mayor a 1 año
        const diffTime = Math.abs(dateHasta - dateDesde);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 365) {
            showNotification('El rango de fechas no puede ser mayor a 1 año', 'error');
            return;
        }

        // Mostrar estado de carga
        button.disabled = true;
        button.textContent = 'Generando...';
        button.classList.add('opacity-75');

        // Simular generación de reporte personalizado
        setTimeout(() => {
            try {
                // Generar datos filtrados
                const reportData = generateCustomReportData(fechaDesde, fechaHasta, tipoRadicado);

                if (!reportData || reportData.length <= 1) {
                    throw new Error('No se encontraron datos para el período seleccionado');
                }

                // Generar nombre de archivo descriptivo
                const tipoTexto = tipoRadicado ? `_${tipoRadicado}` : '_todos';
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
                const filename = `reporte_personalizado${tipoTexto}_${fechaDesde}_${fechaHasta}_${timestamp}.csv`;

                // Exportar reporte
                exportToCSV(reportData, filename);

                // Mostrar mensaje de éxito
                showNotification(`Reporte personalizado generado exitosamente (${reportData.length - 1} registros)`, 'success');

            } catch (error) {
                console.error('Error en generación de reporte personalizado:', error);
                showNotification(`Error al generar reporte: ${error.message}`, 'error');
            } finally {
                // Restaurar botón siempre
                button.disabled = false;
                button.textContent = originalText;
                button.classList.remove('opacity-75');
            }
        }, 2000);

    } catch (error) {
        console.error('Error en validación de reporte personalizado:', error);
        showNotification(`Error en validación: ${error.message}`, 'error');

        // Restaurar botón en caso de error
        button.disabled = false;
        button.textContent = originalText;
        button.classList.remove('opacity-75');
    }
}

/**
 * Generar datos de reporte personalizado con filtros
 */
function generateCustomReportData(fechaDesde, fechaHasta, tipoRadicado) {
    try {
        const data = [];

        // Header específico para reporte personalizado
        data.push([
            'Fecha Radicación',
            'Tipo Documento',
            'Número Radicado',
            'Remitente/Origen',
            'Asunto',
            'Dependencia Destino',
            'Estado Actual',
            'Fecha Vencimiento',
            'Usuario Responsable',
            'Días Transcurridos'
        ]);

        // Convertir fechas
        const startDate = new Date(fechaDesde);
        const endDate = new Date(fechaHasta);
        const today = new Date();

        // Datos de ejemplo más realistas filtrados
        const tipos = tipoRadicado ? [tipoRadicado.charAt(0).toUpperCase() + tipoRadicado.slice(1)] : ['Entrada', 'Interno', 'Salida'];
        const estados = ['Pendiente', 'En Proceso', 'Respondido', 'Archivado'];
        const dependencias = [
            'Gerencia General',
            'Recursos Humanos',
            'Contabilidad',
            'Sistemas e Informática',
            'Jurídica',
            'Planeación',
            'Calidad',
            'Compras y Suministros'
        ];
        const usuarios = [
            'Ana García',
            'Carlos Rodríguez',
            'María López',
            'Juan Pérez',
            'Laura Martínez'
        ];

        // Calcular número de registros basado en el rango de fechas
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const numRegistros = Math.max(5, Math.min(100, Math.floor(diffDays * 0.8))); // Entre 5 y 100 registros

        for (let i = 1; i <= numRegistros; i++) {
            // Generar fecha aleatoria dentro del rango
            const randomTime = startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime());
            const fechaRadicacion = new Date(randomTime);

            const fechaVencimiento = new Date(fechaRadicacion);
            fechaVencimiento.setDate(fechaVencimiento.getDate() + Math.floor(Math.random() * 30) + 5);

            // Calcular días transcurridos
            const diasTranscurridos = Math.floor((today - fechaRadicacion) / (1000 * 60 * 60 * 24));

            // Sanitizar datos para evitar problemas en CSV
            const asunto = `Asunto del documento ${i} - ${tipos[Math.floor(Math.random() * tipos.length)]}`.replace(/"/g, '""');
            const remitente = `Remitente ${i}`.replace(/"/g, '""');

            data.push([
                fechaRadicacion.toLocaleDateString('es-CO'),
                tipos[Math.floor(Math.random() * tipos.length)],
                `RAD-${String(i).padStart(6, '0')}`,
                remitente,
                asunto,
                dependencias[Math.floor(Math.random() * dependencias.length)],
                estados[Math.floor(Math.random() * estados.length)],
                fechaVencimiento.toLocaleDateString('es-CO'),
                usuarios[Math.floor(Math.random() * usuarios.length)],
                diasTranscurridos
            ]);
        }

        console.log(`Reporte personalizado generado: ${numRegistros} registros para el período ${fechaDesde} - ${fechaHasta}`);
        return data;

    } catch (error) {
        // Error silencioso en producción por seguridad
        // Retornar datos mínimos en caso de error
        return [
            ['Fecha', 'Tipo', 'Número Radicado', 'Estado', 'Error'],
            [new Date().toLocaleDateString('es-CO'), 'Error', 'N/A', 'Error al generar datos', 'Error interno']
        ];
    }
}

/**
 * Inicializar gráficos (placeholder para futuras implementaciones)
 */
function initializeCharts() {
    // Placeholder para gráficos con Chart.js o similar
    // Charts initialization placeholder
}

/**
 * Mostrar notificación
 */
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Obtener fecha actual en formato YYYY-MM-DD
 */
function getCurrentDate() {
    return new Date().toISOString().split('T')[0];
}

// Exportar funciones para uso global
window.ReportesModule = {
    initialize: initializeReportesModule,
    exportReport: handleExportReport,
    generateCustomReport: handleCustomReport
};
