/**
 * Funcionalidad para manejar la cascada departamento-ciudad en formularios de radicación de entrada
 */

document.addEventListener('DOMContentLoaded', function() {
    initDepartamentoCiudadCascade();
});

function initDepartamentoCiudadCascade() {
    const departamentoSelect = document.getElementById('departamento_id');
    const ciudadSelect = document.getElementById('ciudad_id');
    const departamentoNombreInput = document.getElementById('departamento_nombre');
    const ciudadNombreInput = document.getElementById('ciudad_nombre');

    if (!departamentoSelect || !ciudadSelect) {
        console.log('Elementos de departamento/ciudad no encontrados');
        return;
    }

    // Manejar cambio de departamento
    departamentoSelect.addEventListener('change', function() {
        const departamentoId = this.value;
        const departamentoOption = this.options[this.selectedIndex];
        
        // Actualizar campo oculto con el nombre del departamento
        if (departamentoNombreInput) {
            departamentoNombreInput.value = departamentoOption.dataset.nombre || '';
        }

        // Limpiar ciudad
        ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
        ciudadSelect.disabled = true;
        
        if (ciudadNombreInput) {
            ciudadNombreInput.value = '';
        }

        if (departamentoId) {
            // Cargar ciudades del departamento seleccionado
            cargarCiudadesPorDepartamento(departamentoId);
        } else {
            // No hay departamento seleccionado
            ciudadSelect.innerHTML = '<option value="">Primero seleccione un departamento...</option>';
            ciudadSelect.disabled = true;
        }
    });

    // Manejar cambio de ciudad
    ciudadSelect.addEventListener('change', function() {
        const ciudadOption = this.options[this.selectedIndex];
        
        // Actualizar campo oculto con el nombre de la ciudad
        if (ciudadNombreInput) {
            ciudadNombreInput.value = ciudadOption.dataset.nombre || '';
        }
    });

    // Cargar ciudades iniciales si hay un departamento preseleccionado
    if (departamentoSelect.value) {
        cargarCiudadesPorDepartamento(departamentoSelect.value);
    }
}

function cargarCiudadesPorDepartamento(departamentoId) {
    const ciudadSelect = document.getElementById('ciudad_id');
    const ciudadNombreInput = document.getElementById('ciudad_nombre');
    
    if (!ciudadSelect) return;

    // Mostrar estado de carga
    ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
    ciudadSelect.disabled = true;

    // Realizar petición AJAX
    fetch(`/api/ciudades/por-departamento?departamento_id=${departamentoId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar ciudades');
            }
            return response.json();
        })
        .then(ciudades => {
            // Limpiar select
            ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';
            
            // Agregar ciudades
            ciudades.forEach(ciudad => {
                const option = document.createElement('option');
                option.value = ciudad.id;
                option.textContent = ciudad.nombre;
                option.dataset.nombre = ciudad.nombre;
                ciudadSelect.appendChild(option);
            });

            // Habilitar select
            ciudadSelect.disabled = false;

            // Restaurar valor seleccionado si existe
            const valorAnterior = ciudadNombreInput ? ciudadNombreInput.value : '';
            if (valorAnterior) {
                const opcionCorrecta = Array.from(ciudadSelect.options).find(option => 
                    option.dataset.nombre === valorAnterior
                );
                if (opcionCorrecta) {
                    ciudadSelect.value = opcionCorrecta.value;
                }
            }
        })
        .catch(error => {
            console.error('Error al cargar ciudades:', error);
            ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
            ciudadSelect.disabled = true;
            
            // Mostrar mensaje de error al usuario
            showNotification('Error al cargar las ciudades. Por favor, recargue la página.', 'error');
        });
}

// Función auxiliar para mostrar notificaciones
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'success' ? 'bg-green-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;

    // Agregar al DOM
    document.body.appendChild(notification);

    // Remover después de 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Exportar funciones para uso global si es necesario
window.cargarCiudadesPorDepartamento = cargarCiudadesPorDepartamento;
window.initDepartamentoCiudadCascade = initDepartamentoCiudadCascade;
