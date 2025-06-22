// Manejo de fecha y hora para Colombia
class ColombiaTime {
    constructor() {
        this.timezone = 'America/Bogota';
        this.init();
    }

    init() {
        // Actualizar hora cada segundo
        this.updateTime();
        setInterval(() => {
            this.updateTime();
        }, 1000);
    }

    /**
     * Obtener fecha y hora actual de Colombia
     */
    getNow() {
        return new Date().toLocaleString('es-CO', {
            timeZone: this.timezone,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
    }

    /**
     * Obtener solo la hora actual de Colombia
     */
    getTime() {
        return new Date().toLocaleString('es-CO', {
            timeZone: this.timezone,
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    }

    /**
     * Obtener solo la fecha actual de Colombia
     */
    getDate() {
        return new Date().toLocaleString('es-CO', {
            timeZone: this.timezone,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }

    /**
     * Formatear fecha para dashboard (dd/mm/yyyy hh:mm)
     */
    getFormatted() {
        const now = new Date();
        const options = {
            timeZone: this.timezone,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        };
        
        const formatted = now.toLocaleString('es-CO', options);
        // Convertir de dd/mm/yyyy, hh:mm:ss a dd/mm/yyyy hh:mm
        return formatted.replace(/(\d{2}\/\d{2}\/\d{4}),?\s*(\d{2}:\d{2}).*/, '$1 $2');
    }

    /**
     * Obtener nombre del día en español
     */
    getDayName() {
        return new Date().toLocaleDateString('es-CO', {
            timeZone: this.timezone,
            weekday: 'long'
        });
    }

    /**
     * Obtener nombre del mes en español
     */
    getMonthName() {
        return new Date().toLocaleDateString('es-CO', {
            timeZone: this.timezone,
            month: 'long'
        });
    }

    /**
     * Actualizar elementos en la página con la hora actual
     */
    updateTime() {
        // Actualizar hora en el dashboard principal
        const timeElement = document.querySelector('[data-colombia-time]');
        if (timeElement) {
            timeElement.textContent = this.getTime();
        }

        // Actualizar fecha y hora completa
        const dateTimeElement = document.querySelector('[data-colombia-datetime]');
        if (dateTimeElement) {
            dateTimeElement.textContent = this.getFormatted();
        }

        // Actualizar solo fecha
        const dateElement = document.querySelector('[data-colombia-date]');
        if (dateElement) {
            dateElement.textContent = this.getDate();
        }

        // Actualizar elementos con clase específica
        const timeElements = document.querySelectorAll('.colombia-time');
        timeElements.forEach(element => {
            element.textContent = this.getTime();
        });

        const dateTimeElements = document.querySelectorAll('.colombia-datetime');
        dateTimeElements.forEach(element => {
            element.textContent = this.getFormatted();
        });
    }

    /**
     * Verificar si es horario laboral (8:00 AM - 6:00 PM)
     */
    isWorkingHours() {
        const now = new Date();
        const colombiaTime = new Date(now.toLocaleString('en-US', { timeZone: this.timezone }));
        const hour = colombiaTime.getHours();
        return hour >= 8 && hour < 18;
    }

    /**
     * Obtener saludo según la hora
     */
    getGreeting() {
        const now = new Date();
        const colombiaTime = new Date(now.toLocaleString('en-US', { timeZone: this.timezone }));
        const hour = colombiaTime.getHours();

        if (hour >= 5 && hour < 12) {
            return 'Buenos días';
        } else if (hour >= 12 && hour < 18) {
            return 'Buenas tardes';
        } else {
            return 'Buenas noches';
        }
    }

    /**
     * Formatear duración en español
     */
    formatDuration(minutes) {
        if (minutes < 60) {
            return `${minutes} minuto${minutes !== 1 ? 's' : ''}`;
        }
        
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;
        
        let result = `${hours} hora${hours !== 1 ? 's' : ''}`;
        if (remainingMinutes > 0) {
            result += ` y ${remainingMinutes} minuto${remainingMinutes !== 1 ? 's' : ''}`;
        }
        
        return result;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.colombiaTime = new ColombiaTime();
    
    // Agregar información de zona horaria al footer si existe
    const timezoneInfo = document.querySelector('[data-timezone-info]');
    if (timezoneInfo) {
        timezoneInfo.textContent = 'Hora de Colombia (UTC-5)';
    }
});

// Exportar para uso en otros módulos
export default ColombiaTime;
