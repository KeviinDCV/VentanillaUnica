/**
 * UniRadic - Carga de archivos para formularios modales
 * Maneja drag & drop y vista previa de archivos en modales de radicación
 */

class ModalFileUpload {
    constructor() {
        this.maxFileSize = 10 * 1024 * 1024; // 10MB
        this.allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
        this.allowedExtensions = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png'];
        
        this.initializeAll();
    }

    initializeAll() {
        // Inicializar para formulario de entrada
        this.initializeModal('modal', 'documento_modal');
        
        // Inicializar para formulario interno
        this.initializeModal('modal-interno', 'documento_modal_interno');
        
        // Inicializar para formulario de salida
        this.initializeModal('modal-salida', 'documento_modal_salida');
    }

    initializeModal(modalSuffix, inputId) {
        const dropZone = document.getElementById(`drop-zone-${modalSuffix}`);
        const fileInput = document.getElementById(inputId);
        const filePreview = document.getElementById(`file-preview-${modalSuffix}`);
        const removeButton = document.getElementById(`remove-file-${modalSuffix}`);

        if (!dropZone || !fileInput) {
            console.log(`Modal ${modalSuffix} elements not found, skipping initialization`);
            return;
        }

        // Eventos de drag & drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.highlight(dropZone), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.unhighlight(dropZone), false);
        });

        dropZone.addEventListener('drop', (e) => this.handleDrop(e, modalSuffix), false);

        // Evento de selección de archivo
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleFile(e.target.files[0], modalSuffix);
            }
        });

        // Evento de remover archivo
        if (removeButton) {
            removeButton.addEventListener('click', () => this.removeFile(modalSuffix));
        }
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight(element) {
        element.classList.add('border-uniradical-blue', 'bg-blue-50');
        element.classList.remove('border-gray-300');
    }

    unhighlight(element) {
        element.classList.remove('border-uniradical-blue', 'bg-blue-50');
        element.classList.add('border-gray-300');
    }

    handleDrop(e, modalSuffix) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            this.handleFile(files[0], modalSuffix);
        }
    }

    handleFile(file, modalSuffix) {
        // Validar tipo de archivo
        if (!this.isValidFileType(file)) {
            this.showError('Tipo de archivo no permitido. Solo se permiten: PDF, Word, JPG, PNG');
            return;
        }

        // Validar tamaño
        if (file.size > this.maxFileSize) {
            this.showError('El archivo es demasiado grande. Tamaño máximo: 10MB');
            return;
        }

        // Actualizar input file
        const fileInput = document.getElementById(modalSuffix === 'modal' ? 'documento_modal' : 
                                                modalSuffix === 'modal-interno' ? 'documento_modal_interno' : 
                                                'documento_modal_salida');
        
        if (fileInput) {
            // Crear un nuevo FileList con el archivo
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }

        // Mostrar vista previa
        this.showFilePreview(file, modalSuffix);
    }

    isValidFileType(file) {
        const fileName = file.name.toLowerCase();
        const fileType = file.type.toLowerCase();
        
        // Verificar por tipo MIME
        if (this.allowedTypes.includes(fileType)) {
            return true;
        }
        
        // Verificar por extensión como fallback
        return this.allowedExtensions.some(ext => fileName.endsWith(ext));
    }

    showFilePreview(file, modalSuffix) {
        const dropZoneContent = document.getElementById(`drop-zone-content-${modalSuffix}`);
        const filePreview = document.getElementById(`file-preview-${modalSuffix}`);
        const fileName = document.getElementById(`file-name-${modalSuffix}`);
        const fileSize = document.getElementById(`file-size-${modalSuffix}`);

        if (dropZoneContent && filePreview && fileName && fileSize) {
            // Ocultar contenido original y mostrar vista previa
            dropZoneContent.classList.add('hidden');
            filePreview.classList.remove('hidden');

            // Actualizar información del archivo
            fileName.textContent = file.name;
            fileSize.textContent = this.formatFileSize(file.size);
        }
    }

    removeFile(modalSuffix) {
        const dropZoneContent = document.getElementById(`drop-zone-content-${modalSuffix}`);
        const filePreview = document.getElementById(`file-preview-${modalSuffix}`);
        const fileInput = document.getElementById(modalSuffix === 'modal' ? 'documento_modal' : 
                                                modalSuffix === 'modal-interno' ? 'documento_modal_interno' : 
                                                'documento_modal_salida');

        if (dropZoneContent && filePreview) {
            // Mostrar contenido original y ocultar vista previa
            dropZoneContent.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }

        if (fileInput) {
            fileInput.value = '';
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    showError(message) {
        // Crear notificación de error
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.modalFileUpload = new ModalFileUpload();
    console.log('Modal File Upload initialized');
});

// Reinicializar cuando se abra un modal
document.addEventListener('modalOpened', function() {
    if (window.modalFileUpload) {
        window.modalFileUpload.initializeAll();
    }
});

// Exportar para uso global
window.ModalFileUpload = ModalFileUpload;
