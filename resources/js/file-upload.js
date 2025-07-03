/**
 * UniRadic - Sistema de Carga de Archivos con Drag & Drop
 * Manejo avanzado de archivos para digitalización de documentos
 */

class FileUploadManager {
    constructor() {
        this.maxFileSize = 10 * 1024 * 1024; // 10MB
        this.allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/jpg', 
            'image/png'
        ];
        this.allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        this.currentFile = null;
        
        this.init();
    }

    init() {
        this.setupDropZone();
        this.setupFileInput();
        this.setupRemoveButton();
    }

    setupDropZone() {
        const dropZone = document.getElementById('drop-zone');
        if (!dropZone) return;

        // Prevenir comportamiento por defecto
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
            document.body.addEventListener(eventName, this.preventDefaults, false);
        });

        // Efectos visuales
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.highlight(dropZone), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.unhighlight(dropZone), false);
        });

        // Manejar drop
        dropZone.addEventListener('drop', (e) => this.handleDrop(e), false);
    }

    setupFileInput() {
        const fileInput = document.getElementById('documento');
        if (!fileInput) return;

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleFile(e.target.files[0]);
            }
        });
    }

    setupRemoveButton() {
        const removeButton = document.getElementById('remove-file');
        if (!removeButton) return;

        removeButton.addEventListener('click', () => this.removeFile());
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

    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            this.handleFile(files[0]);
        }
    }

    handleFile(file) {
        // Validar archivo
        const validation = this.validateFile(file);
        if (!validation.valid) {
            this.showError(validation.message);
            return;
        }

        this.currentFile = file;
        this.showProgress();
        
        // Simular procesamiento
        this.processFile(file).then(() => {
            this.hideProgress();
            this.showPreview(file);
            this.updateFileInput(file);
        }).catch(error => {
            this.hideProgress();
            this.showError('Error al procesar el archivo: ' + error.message);
        });
    }

    validateFile(file) {
        // Validar tamaño
        if (file.size > this.maxFileSize) {
            return {
                valid: false,
                message: `El archivo es demasiado grande. Tamaño máximo: ${this.formatFileSize(this.maxFileSize)}`
            };
        }

        // Validar tipo
        const extension = file.name.split('.').pop().toLowerCase();
        if (!this.allowedExtensions.includes(extension)) {
            return {
                valid: false,
                message: `Tipo de archivo no permitido. Formatos válidos: ${this.allowedExtensions.join(', ')}`
            };
        }

        // Validar MIME type
        if (!this.allowedTypes.includes(file.type)) {
            return {
                valid: false,
                message: 'Tipo de archivo no válido'
            };
        }

        return { valid: true };
    }

    processFile(file) {
        return new Promise((resolve, reject) => {
            // Simular procesamiento con progreso
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    resolve();
                }
                this.updateProgress(progress);
            }, 200);
        });
    }

    showProgress() {
        document.getElementById('drop-zone-content').classList.add('hidden');
        document.getElementById('upload-progress').classList.remove('hidden');
    }

    hideProgress() {
        document.getElementById('drop-zone-content').classList.remove('hidden');
        document.getElementById('upload-progress').classList.add('hidden');
    }

    updateProgress(percent) {
        const progressBar = document.getElementById('progress-bar');
        if (progressBar) {
            progressBar.style.width = `${percent}%`;
        }
    }

    showPreview(file) {
        const preview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const fileType = document.getElementById('file-type');
        const fileIcon = document.getElementById('file-icon');
        const imagePreview = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');

        if (!preview) return;

        // Actualizar información del archivo
        fileName.textContent = file.name;
        fileSize.textContent = this.formatFileSize(file.size);
        fileType.textContent = file.type;

        // Mostrar icono apropiado
        fileIcon.innerHTML = this.getFileIcon(file);

        // Mostrar vista previa de imagen si es aplicable
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }

        preview.classList.remove('hidden');
    }

    updateFileInput(file) {
        const fileInput = document.getElementById('documento');
        if (fileInput) {
            // Crear un nuevo DataTransfer para actualizar el input
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
        }
    }

    removeFile() {
        this.currentFile = null;
        
        // Limpiar input
        const fileInput = document.getElementById('documento');
        if (fileInput) {
            fileInput.value = '';
        }

        // Ocultar preview
        const preview = document.getElementById('file-preview');
        if (preview) {
            preview.classList.add('hidden');
        }

        // Limpiar imagen preview
        const imagePreview = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');
        if (imagePreview && previewImage) {
            imagePreview.classList.add('hidden');
            previewImage.src = '';
        }
    }

    getFileIcon(file) {
        const extension = file.name.split('.').pop().toLowerCase();
        
        switch (extension) {
            case 'pdf':
                return `<svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>`;
            case 'doc':
            case 'docx':
                return `<svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>`;
            case 'jpg':
            case 'jpeg':
            case 'png':
                return `<svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>`;
            default:
                return `<svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>`;
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
        if (window.UniRadicNotifications) {
            window.UniRadicNotifications.error('Error de Archivo', message);
        } else {
            alert(message);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('drop-zone')) {
        new FileUploadManager();
    }
});

// Exportar para uso global
window.FileUploadManager = FileUploadManager;
