/**
 * UniRadic - Sistema de Radicación
 * JavaScript para formularios de radicación
 */

// Funciones para Radicación de Entrada
window.RadicacionEntrada = {
    init() {
        this.initEventListeners();
        this.initValidations();
    },

    initEventListeners() {
        // Tipo de remitente - escuchar cambios en los radio buttons
        const tipoRemitenteRadios = document.querySelectorAll('input[name="tipo_remitente"]');
        tipoRemitenteRadios.forEach(radio => {
            radio.addEventListener('change', () => RadicacionEntrada.toggleTipoRemitente());
        });

        // Autocompletado de remitente por documento
        const numeroDocumento = document.getElementById('numero_documento');
        if (numeroDocumento) {
            numeroDocumento.addEventListener('blur', this.buscarRemitente);
        }

        // Tipo de comunicación (físico/verbal)
        const tipoComunicacion = document.getElementById('tipo_comunicacion');
        if (tipoComunicacion) {
            tipoComunicacion.addEventListener('change', this.toggleTipoComunicacion);
        }

        // TRD
        const trdSelect = document.getElementById('trd_id');
        if (trdSelect) {
            trdSelect.addEventListener('change', () => RadicacionEntrada.showTrdInfo());
        }

        // Archivo
        const documentoInput = document.getElementById('documento');
        if (documentoInput) {
            documentoInput.addEventListener('change', () => RadicacionEntrada.showFilePreview());
        }

        // Botón de previsualización
        const btnPreview = document.getElementById('btn-preview');

        if (btnPreview) {
            btnPreview.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                RadicacionEntrada.mostrarPreview();
            });
        }

        // Formulario - Sin validaciones para debugging
        const form = document.getElementById('radicacionEntradaForm');
        if (form) {
            console.log('Event listener agregado al formulario de entrada');
        }
    },

    toggleTipoRemitente() {
        const tipoRemitenteChecked = document.querySelector('input[name="tipo_remitente"]:checked');
        const tipoRemitente = tipoRemitenteChecked ? tipoRemitenteChecked.value : '';
        const camposRegistrado = document.getElementById('campos-registrado');

        // Verificar si el elemento existe (puede no existir en formularios de modal)
        if (!camposRegistrado) {
            console.log('Elemento campos-registrado no encontrado, saltando toggleTipoRemitente');
            return;
        }

        if (tipoRemitente === 'registrado') {
            // Mostrar campos específicos para remitente registrado (tipo y número de documento)
            camposRegistrado.style.display = 'block';
            RadicacionEntrada.setRequiredFields(camposRegistrado, true);
        } else if (tipoRemitente === 'anonimo') {
            // Ocultar campos específicos para remitente anónimo
            camposRegistrado.style.display = 'none';
            RadicacionEntrada.setRequiredFields(camposRegistrado, false);
        } else {
            // No hay selección, ocultar campos específicos
            camposRegistrado.style.display = 'none';
            RadicacionEntrada.setRequiredFields(camposRegistrado, false);
        }
    },

    setRequiredFields(container, required) {
        const inputs = container.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (required) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
    },

    async buscarRemitente() {
        const numeroDocumento = document.getElementById('numero_documento').value.trim();

        if (!numeroDocumento) {
            return;
        }

        try {
            const response = await fetch(`/radicacion/entrada/buscar-remitente?numero_documento=${encodeURIComponent(numeroDocumento)}`);
            const data = await response.json();

            if (data.found) {
                // Llenar automáticamente los campos
                document.getElementById('tipo_documento').value = data.data.tipo_documento || '';
                document.getElementById('nombre_completo').value = data.data.nombre_completo || '';
                document.getElementById('telefono').value = data.data.telefono || '';
                document.getElementById('email').value = data.data.email || '';
                document.getElementById('direccion').value = data.data.direccion || '';
                // Usar las nuevas funciones para establecer ciudad y departamento
                if (typeof window.setCiudadDepartamento === 'function') {
                    window.setCiudadDepartamento(
                        'departamento_id',
                        'ciudad_id',
                        data.data.departamento_id,
                        data.data.ciudad_id
                    );
                }
                document.getElementById('entidad').value = data.data.entidad || '';

                // Mostrar mensaje de éxito
                window.UniRadicNotifications.success(
                    'Remitente Encontrado',
                    'Los datos del remitente han sido cargados automáticamente desde registros anteriores'
                );
            }
        } catch (error) {
            console.error('Error al buscar remitente:', error);
            window.UniRadicNotifications.error(
                'Error de Conexión',
                'No se pudo verificar la información del remitente. Verifique su conexión a internet.'
            );
        }
    },

    showMessage(message, type = 'info', title = '') {
        // Usar el sistema de notificaciones personalizado
        const notificationTitle = title || (
            type === 'success' ? 'Éxito' :
            type === 'error' ? 'Error' :
            type === 'warning' ? 'Advertencia' :
            'Información'
        );

        window.UniRadicNotifications.show({
            type: type,
            title: notificationTitle,
            message: message,
            duration: 4000
        });
    },

    toggleTipoComunicacion() {
        const tipoComunicacion = document.getElementById('tipo_comunicacion').value;
        const numeroFolios = document.getElementById('numero_folios');
        const observaciones = document.getElementById('observaciones');
        const documentoInput = document.getElementById('documento');

        if (tipoComunicacion === 'verbal') {
            // Para comunicación verbal, establecer valores automáticos
            numeroFolios.value = 1;
            numeroFolios.readOnly = true;

            // Generar plantilla automática para observaciones
            const fecha = new Date().toLocaleDateString('es-CO');
            const hora = new Date().toLocaleTimeString('es-CO');
            const plantilla = `COMUNICACIÓN VERBAL RECIBIDA\n\nFecha: ${fecha}\nHora: ${hora}\n\nResumen de la comunicación:\n[El funcionario debe completar este campo con el resumen de la comunicación verbal recibida]\n\nFuncionario que recibe: ${document.querySelector('meta[name="user-name"]')?.content || '[Nombre del funcionario]'}`;

            observaciones.value = plantilla;
            observaciones.rows = 8;

            // Hacer opcional el documento para comunicación verbal
            if (documentoInput) {
                documentoInput.removeAttribute('required');
            }

            window.UniRadicNotifications.info(
                'Plantilla Generada',
                'Se ha generado automáticamente la plantilla para comunicación verbal. Complete el resumen de la comunicación recibida.'
            );
        } else {
            // Para comunicación física, restaurar valores normales
            numeroFolios.readOnly = false;
            observaciones.rows = 3;

            // Hacer requerido el documento para comunicación física
            if (documentoInput) {
                documentoInput.setAttribute('required', 'required');
            }

            // Limpiar plantilla si había una
            if (observaciones.value.includes('COMUNICACIÓN VERBAL RECIBIDA')) {
                observaciones.value = '';
            }
        }
    },

    mostrarPreview() {
        try {
            const form = document.getElementById('radicacionEntradaForm');

            if (!form) {
                window.UniRadicNotifications.error(
                    'Error del Sistema',
                    'No se pudo encontrar el formulario. Recargue la página e intente nuevamente.'
                );
                return;
            }

            const formData = new FormData(form);

            // Validar campos requeridos para preview
            const camposRequeridos = ['nombre_completo', 'trd_id', 'dependencia_destino_id'];
            let camposFaltantes = [];

            camposRequeridos.forEach(campo => {
                const valor = formData.get(campo);
                if (!valor || valor.trim() === '') {
                    camposFaltantes.push(campo);
                }
            });

            if (camposFaltantes.length > 0) {
                window.UniRadicNotifications.warning(
                    'Campos Requeridos',
                    'Complete los campos obligatorios (Nombre del remitente, TRD y Dependencia destino) para generar la previsualización.'
                );
                return;
            }

            // Crear formulario temporal para envío
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = '/radicacion/entrada/preview';
            tempForm.target = '_blank';
            tempForm.style.display = 'none';

            // Agregar token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                window.UniRadicNotifications.error(
                    'Error de Seguridad',
                    'No se pudo obtener el token de seguridad. Recargue la página e intente nuevamente.'
                );
                return;
            }

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            tempForm.appendChild(csrfInput);

            // Copiar datos del formulario
            for (let [key, value] of formData.entries()) {
                if (key !== 'documento') { // Excluir archivo para preview
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    tempForm.appendChild(input);
                }
            }

            // Enviar formulario
            document.body.appendChild(tempForm);
            tempForm.submit();
            document.body.removeChild(tempForm);

        } catch (error) {
            window.UniRadicNotifications.error(
                'Error Inesperado',
                'Ocurrió un error al generar la previsualización. Intente nuevamente.'
            );
        }
    },

    showTrdInfo() {
        const trdSelect = document.getElementById('trd_id');
        const trdInfo = document.getElementById('trd-info');
        const selectedOption = trdSelect.options[trdSelect.selectedIndex];

        if (selectedOption.value) {
            document.getElementById('trd-codigo').textContent = selectedOption.dataset.codigo || '';
            document.getElementById('trd-serie').textContent = selectedOption.dataset.serie || '';
            document.getElementById('trd-subserie').textContent = selectedOption.dataset.subserie || 'N/A';
            document.getElementById('trd-asunto').textContent = selectedOption.dataset.asunto || '';
            trdInfo.classList.remove('hidden');
        } else {
            trdInfo.classList.add('hidden');
        }
    },

    showFilePreview() {
        const fileInput = document.getElementById('documento');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = RadicacionEntrada.formatFileSize(file.size);
            filePreview.classList.remove('hidden');
        } else {
            filePreview.classList.add('hidden');
        }
    },

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    validateForm(e) {
        console.log('RadicacionEntrada.validateForm - Iniciando validación');

        const tipoRemitenteChecked = document.querySelector('input[name="tipo_remitente"]:checked');
        console.log('Tipo remitente seleccionado:', tipoRemitenteChecked ? tipoRemitenteChecked.value : 'ninguno');

        if (!tipoRemitenteChecked) {
            console.log('RadicacionEntrada.validateForm - Validación fallida: no hay tipo de remitente');
            e.preventDefault();
            window.UniRadicNotifications.warning(
                'Campo Requerido',
                'Debe seleccionar el tipo de remitente (Anónimo o Registrado)'
            );
            return false;
        }

        console.log('RadicacionEntrada.validateForm - Validación exitosa, permitiendo envío');

        // Mostrar indicador de carga
        const submitBtn = e.target.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Procesando...';
        }

        return true;
    },

    initValidations() {
        // Inicializar estado al cargar la página
        this.toggleTipoRemitente();
    }
};

// Funciones para Radicación Interna
window.RadicacionInterna = {
    init() {
        this.initEventListeners();
        this.initValidations();
    },

    initEventListeners() {
        // TRD
        const trdSelect = document.getElementById('trd_id');
        if (trdSelect) {
            trdSelect.addEventListener('change', () => RadicacionInterna.showTrdInfo());
        }

        // Archivo
        const documentoInput = document.getElementById('documento');
        if (documentoInput) {
            documentoInput.addEventListener('change', () => RadicacionInterna.showFilePreview());
        }

        // Dependencias
        const dependenciaOrigen = document.getElementById('dependencia_origen_id');
        const dependenciaDestino = document.getElementById('dependencia_destino_id');
        if (dependenciaOrigen) {
            dependenciaOrigen.addEventListener('change', () => RadicacionInterna.validateDependencias());
        }
        if (dependenciaDestino) {
            dependenciaDestino.addEventListener('change', () => RadicacionInterna.validateDependencias());
        }

        // Requiere respuesta - Manejar tanto select como radio buttons
        const requiereRespuestaSelect = document.getElementById('requiere_respuesta');
        const requiereRespuestaRadios = document.querySelectorAll('input[name="requiere_respuesta"]');

        if (requiereRespuestaSelect) {
            console.log('🔧 Configurando select requiere_respuesta');
            requiereRespuestaSelect.addEventListener('change', () => RadicacionInterna.toggleFechaLimiteSelect());
        }

        if (requiereRespuestaRadios.length > 0) {
            console.log('🔧 Configurando radio buttons requiere_respuesta');
            requiereRespuestaRadios.forEach(radio => {
                radio.addEventListener('change', () => RadicacionInterna.toggleFechaLimite());
            });
        }

        // Botón de previsualización
        const btnPreviewInterno = document.getElementById('btn-preview-interno');
        if (btnPreviewInterno) {
            btnPreviewInterno.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                RadicacionInterna.mostrarPreview();
            });
        }

        // Formulario
        const form = document.getElementById('radicacionInternaForm');
        if (form) {
            console.log('Configurando event listener del formulario interno');
            form.addEventListener('submit', (e) => RadicacionInterna.validateForm(e));
        }
    },

    showTrdInfo() {
        RadicacionEntrada.showTrdInfo(); // Reutilizar función
    },

    showFilePreview() {
        RadicacionEntrada.showFilePreview(); // Reutilizar función
    },

    toggleFechaLimite() {
        const requiereRespuesta = document.querySelector('input[name="requiere_respuesta"]:checked');
        const fechaLimiteContainer = document.getElementById('fecha-limite-container');
        const fechaLimiteInput = document.getElementById('fecha_limite_respuesta');

        if (requiereRespuesta && requiereRespuesta.value === '1') {
            fechaLimiteContainer.style.display = 'block';
            // NO AGREGAR REQUIRED - se valida en el servidor
        } else {
            fechaLimiteContainer.style.display = 'none';
            fechaLimiteInput.value = '';
        }
    },

    toggleFechaLimiteSelect() {
        const requiereRespuestaSelect = document.getElementById('requiere_respuesta');
        const fechaLimiteContainer = document.getElementById('fecha-limite-container');
        const fechaLimiteInput = document.getElementById('fecha_limite_respuesta');

        console.log('🔧 toggleFechaLimiteSelect - Valor:', requiereRespuestaSelect ? requiereRespuestaSelect.value : 'no encontrado');

        if (requiereRespuestaSelect && fechaLimiteContainer && fechaLimiteInput) {
            if (requiereRespuestaSelect.value === 'si') {
                // Usar style.display en lugar de clases para mayor compatibilidad
                fechaLimiteContainer.style.display = 'block';
                fechaLimiteContainer.classList.remove('hidden');
                fechaLimiteInput.disabled = false;
                fechaLimiteInput.removeAttribute('disabled');
                console.log('✅ Campo fecha límite mostrado y habilitado');
                console.log('  - Container display:', fechaLimiteContainer.style.display);
                console.log('  - Container classes:', fechaLimiteContainer.className);
                console.log('  - Input disabled:', fechaLimiteInput.disabled);
            } else {
                // Ocultar usando ambos métodos
                fechaLimiteContainer.style.display = 'none';
                fechaLimiteContainer.classList.add('hidden');
                fechaLimiteInput.value = '';
                fechaLimiteInput.disabled = true;
                console.log('✅ Campo fecha límite oculto y deshabilitado');
            }
        } else {
            console.error('❌ No se encontraron elementos para fecha límite');
            console.error('  - Select:', !!requiereRespuestaSelect);
            console.error('  - Container:', !!fechaLimiteContainer);
            console.error('  - Input:', !!fechaLimiteInput);
        }
    },

    validateDependencias() {
        const origenElement = document.getElementById('dependencia_origen_id');
        const destinoElement = document.getElementById('dependencia_destino_id');

        if (!origenElement || !destinoElement) {
            console.warn('Elementos de dependencia no encontrados');
            return true; // No validar si los elementos no existen
        }

        const origen = origenElement.value;
        const destino = destinoElement.value;

        if (origen && destino && origen === destino) {
            console.log('❌ Dependencias iguales detectadas');
            alert('La dependencia de origen y destino deben ser diferentes');
            destinoElement.value = '';
            return false;
        }
        console.log('✅ Validación de dependencias exitosa');
        return true;
    },

    validateForm(e) {
        console.log('🔧 RadicacionInterna.validateForm - Iniciando validación');

        if (!RadicacionInterna.validateDependencias()) {
            console.log('❌ Validación de dependencias falló');
            e.preventDefault();
            return false;
        }

        // Cambiar para usar select en lugar de radio button
        const requiereRespuestaSelect = document.getElementById('requiere_respuesta');

        if (!requiereRespuestaSelect || !requiereRespuestaSelect.value) {
            console.log('❌ Campo requiere_respuesta no válido');
            e.preventDefault();
            alert('Debe indicar si el documento requiere respuesta');
            return false;
        }

        // Cambiar comparación para usar 'si' en lugar de '1'
        if (requiereRespuestaSelect.value === 'si') {
            const fechaLimiteInput = document.getElementById('fecha_limite_respuesta');
            const fechaLimiteContainer = document.getElementById('fecha-limite-container');

            console.log('🔧 Verificando fecha límite:');
            console.log('  - Input encontrado:', !!fechaLimiteInput);
            console.log('  - Container encontrado:', !!fechaLimiteContainer);
            console.log('  - Container hidden:', fechaLimiteContainer ? fechaLimiteContainer.classList.contains('hidden') : 'N/A');
            console.log('  - Input value:', fechaLimiteInput ? fechaLimiteInput.value : 'N/A');
            console.log('  - Input disabled:', fechaLimiteInput ? fechaLimiteInput.disabled : 'N/A');

            if (!fechaLimiteInput || !fechaLimiteInput.value) {
                console.log('❌ Fecha límite requerida pero no proporcionada');
                e.preventDefault();
                alert('Debe especificar la fecha límite de respuesta cuando se requiere respuesta');
                return false;
            }
        }

        console.log('✅ Validación exitosa, enviando formulario');
        console.log('🔧 Datos del formulario antes del envío:');

        // Log de todos los campos del formulario
        const formData = new FormData(e.target);
        for (let [key, value] of formData.entries()) {
            console.log(`  - ${key}: ${value}`);
        }

        // Mostrar indicador de carga
        const submitBtn = e.target.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Procesando...';
        }

        console.log('🔧 Formulario enviándose a:', e.target.action);
        console.log('🔧 Método:', e.target.method);

        return true;
    },

    mostrarPreview() {
        try {
            const form = document.getElementById('radicacionInternaForm');

            if (!form) {
                window.UniRadicNotifications.error(
                    'Error del Sistema',
                    'No se pudo encontrar el formulario. Recargue la página e intente nuevamente.'
                );
                return;
            }

            const formData = new FormData(form);

            // Validar campos requeridos para preview
            const camposRequeridos = [
                'funcionario_remitente',
                'dependencia_origen_id',
                'trd_id',
                'dependencia_destino_id',
                'asunto',
                'tipo_comunicacion',
                'prioridad'
            ];
            let camposFaltantes = [];

            camposRequeridos.forEach(campo => {
                const valor = formData.get(campo);
                if (!valor || valor.trim() === '') {
                    camposFaltantes.push(campo);
                }
            });

            if (camposFaltantes.length > 0) {
                window.UniRadicNotifications.warning(
                    'Campos Requeridos',
                    'Complete los campos obligatorios (Funcionario remitente, Dependencia origen, TRD, Dependencia destino, Asunto, Tipo de comunicación y Prioridad) para generar la previsualización.'
                );
                return;
            }

            // Crear formulario temporal para envío
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = '/radicacion/interna/preview';
            tempForm.target = '_blank';
            tempForm.style.display = 'none';

            // Agregar token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                window.UniRadicNotifications.error(
                    'Error de Seguridad',
                    'No se pudo obtener el token de seguridad. Recargue la página e intente nuevamente.'
                );
                return;
            }

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            tempForm.appendChild(csrfInput);

            // Copiar datos del formulario
            for (let [key, value] of formData.entries()) {
                if (key !== 'documento') { // Excluir archivo para preview
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    tempForm.appendChild(input);
                }
            }

            // Enviar formulario
            document.body.appendChild(tempForm);
            tempForm.submit();
            document.body.removeChild(tempForm);

        } catch (error) {
            window.UniRadicNotifications.error(
                'Error Inesperado',
                'Ocurrió un error al generar la previsualización. Intente nuevamente.'
            );
        }
    },

    initValidations() {
        // Inicializar estado para radio buttons (si existen)
        this.toggleFechaLimite();

        // Inicializar estado para select (si existe)
        this.toggleFechaLimiteSelect();

        console.log('🔧 RadicacionInterna - Validaciones inicializadas');
    }
};

// Funciones para Radicación de Salida
window.RadicacionSalida = {
    init() {
        this.initEventListeners();
        this.initValidations();
    },

    initEventListeners() {
        // Tipo de destinatario
        const tipoDestinatario = document.getElementById('tipo_destinatario');
        if (tipoDestinatario) {
            tipoDestinatario.addEventListener('change', () => RadicacionSalida.toggleTipoDestinatario());
        }

        // TRD
        const trdSelect = document.getElementById('trd_id');
        if (trdSelect) {
            trdSelect.addEventListener('change', () => RadicacionSalida.showTrdInfo());
        }

        // Archivo
        const documentoInput = document.getElementById('documento');
        if (documentoInput) {
            documentoInput.addEventListener('change', () => RadicacionSalida.showFilePreview());
        }

        // Botón de previsualización
        const btnPreview = document.getElementById('btn-preview');
        if (btnPreview) {
            btnPreview.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                RadicacionSalida.mostrarPreview();
            });
        }

        // Requiere acuse de recibo
        const requiereAcuseRadios = document.querySelectorAll('input[name="requiere_acuse_recibo"]');
        requiereAcuseRadios.forEach(radio => {
            radio.addEventListener('change', () => RadicacionSalida.toggleFechaLimite());
        });

        // Formulario
        const form = document.getElementById('radicacionSalidaForm');
        if (form) {
            form.addEventListener('submit', (e) => RadicacionSalida.validateForm(e));
        }
    },

    toggleTipoDestinatario() {
        const tipoDestinatarioElement = document.getElementById('tipo_destinatario');
        const campoNit = document.getElementById('campo-nit');

        // Verificar si los elementos existen
        if (!tipoDestinatarioElement) {
            console.log('Elemento tipo_destinatario no encontrado, saltando toggleTipoDestinatario');
            return;
        }

        const tipoDestinatario = tipoDestinatarioElement.value;

        // Manejar campo NIT - solo mostrar/ocultar
        if (campoNit) {
            if (tipoDestinatario === 'persona_juridica' || tipoDestinatario === 'entidad_publica') {
                campoNit.style.display = 'block';
            } else {
                campoNit.style.display = 'none';
            }
        }
    },

    toggleFechaLimite() {
        const requiereAcuse = document.querySelector('input[name="requiere_acuse_recibo"]:checked');
        const fechaLimiteContainer = document.getElementById('fecha-limite-container');
        const fechaLimiteInput = document.getElementById('fecha_limite_respuesta');

        if (requiereAcuse && requiereAcuse.value === '1') {
            fechaLimiteContainer.style.display = 'block';
        } else {
            fechaLimiteContainer.style.display = 'none';
            if (fechaLimiteInput) {
                fechaLimiteInput.value = '';
            }
        }
    },

    setRequiredFields(container, required) {
        const inputs = container.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (required) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
    },

    showTrdInfo() {
        RadicacionEntrada.showTrdInfo(); // Reutilizar función
    },

    showFilePreview() {
        RadicacionEntrada.showFilePreview(); // Reutilizar función
    },

    validateForm(e) {
        const tipoDestinatario = document.getElementById('tipo_destinatario').value;

        if (!tipoDestinatario) {
            e.preventDefault();
            window.UniRadicNotifications.warning(
                'Campo Requerido',
                'Debe seleccionar el tipo de destinatario'
            );
            return false;
        }

        // Mostrar indicador de carga
        const submitBtn = e.target.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Procesando...';

        return true;
    },

    mostrarPreview() {
        try {
            const form = document.getElementById('radicacionSalidaForm');

            if (!form) {
                window.UniRadicNotifications.error(
                    'Error del Sistema',
                    'No se pudo encontrar el formulario. Recargue la página e intente nuevamente.'
                );
                return;
            }

            const formData = new FormData(form);

            // Validar campos requeridos para preview
            const camposRequeridos = [
                'tipo_destinatario',
                'nombre_destinatario',
                'dependencia_origen_id',
                'funcionario_remitente',
                'trd_id',
                'asunto',
                'tipo_comunicacion',
                'prioridad'
            ];
            let camposFaltantes = [];

            camposRequeridos.forEach(campo => {
                const valor = formData.get(campo);
                console.log(`Campo ${campo}: "${valor}"`); // Debug
                if (!valor || valor.trim() === '') {
                    camposFaltantes.push(campo);
                }
            });

            if (camposFaltantes.length > 0) {
                console.log('Campos faltantes:', camposFaltantes); // Debug
                window.UniRadicNotifications.warning(
                    'Campos Requeridos',
                    `Complete los campos obligatorios faltantes: ${camposFaltantes.join(', ')}`
                );
                return;
            }

            // Crear formulario temporal para envío
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = '/radicacion/salida/preview';
            tempForm.target = '_blank';
            tempForm.style.display = 'none';

            // Agregar token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                window.UniRadicNotifications.error(
                    'Error de Seguridad',
                    'No se pudo obtener el token de seguridad. Recargue la página e intente nuevamente.'
                );
                return;
            }

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            tempForm.appendChild(csrfInput);

            // Copiar datos del formulario
            for (let [key, value] of formData.entries()) {
                if (key !== 'documento') { // Excluir archivo para preview
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    tempForm.appendChild(input);
                }
            }

            // Enviar formulario
            document.body.appendChild(tempForm);
            tempForm.submit();
            document.body.removeChild(tempForm);

        } catch (error) {
            window.UniRadicNotifications.error(
                'Error Inesperado',
                'Ocurrió un error al generar la previsualización. Intente nuevamente.'
            );
        }
    },

    initValidations() {
        this.toggleTipoDestinatario();
        this.toggleFechaLimite();
    }
};

// Funciones para Consultar Radicados
window.ConsultarRadicados = {
    init() {
        this.initEventListeners();
    },

    initEventListeners() {
        // Auto-submit para filtros rápidos
        const filtrosRapidos = document.querySelectorAll('.filtro-rapido');
        filtrosRapidos.forEach(filtro => {
            filtro.addEventListener('change', () => ConsultarRadicados.autoSubmit());
        });

        // Limpiar filtros
        const limpiarBtn = document.getElementById('limpiar-filtros');
        if (limpiarBtn) {
            limpiarBtn.addEventListener('click', () => ConsultarRadicados.limpiarFiltros());
        }
    },

    autoSubmit() {
        const form = document.getElementById('filtros-form');
        if (form) {
            form.submit();
        }
    },

    limpiarFiltros() {
        const form = document.getElementById('filtros-form');
        if (form) {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            form.submit();
        }
    }
};

// Inicialización automática basada en la página actual
document.addEventListener('DOMContentLoaded', function() {
    // Detectar qué módulo inicializar basado en elementos presentes
    if (document.getElementById('radicacionEntradaForm')) {
        RadicacionEntrada.init();

        // Verificación adicional para el botón previsualizar
        setTimeout(() => {
            const btnPreview = document.getElementById('btn-preview');
            if (btnPreview && !btnPreview.hasAttribute('data-initialized')) {
                btnPreview.setAttribute('data-initialized', 'true');
                btnPreview.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    RadicacionEntrada.mostrarPreview();
                });
            }
        }, 100);
    }

    if (document.getElementById('radicacionInternaForm')) {
        RadicacionInterna.init();

        // Verificación adicional para el botón previsualizar interno
        setTimeout(() => {
            const btnPreviewInterno = document.getElementById('btn-preview-interno');
            if (btnPreviewInterno && !btnPreviewInterno.hasAttribute('data-initialized')) {
                btnPreviewInterno.setAttribute('data-initialized', 'true');
                btnPreviewInterno.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    RadicacionInterna.mostrarPreview();
                });
            }
        }, 100);
    }

    if (document.getElementById('radicacionSalidaForm')) {
        RadicacionSalida.init();

        // Verificación adicional para el botón previsualizar salida
        setTimeout(() => {
            const btnPreview = document.getElementById('btn-preview');
            if (btnPreview && !btnPreview.hasAttribute('data-initialized')) {
                btnPreview.setAttribute('data-initialized', 'true');
                btnPreview.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Botón previsualizar clickeado - RadicacionSalida'); // Debug
                    RadicacionSalida.mostrarPreview();
                });
            }
        }, 100);
    }

    if (document.getElementById('filtros-form')) {
        ConsultarRadicados.init();
    }

    // Modal Consultar Radicados
    const btnConsultarRadicados = document.getElementById('btn-consultar-radicados');
    const modalConsultarRadicados = document.getElementById('modal-consultar-radicados');
    const btnCerrarModalConsulta = document.getElementById('btn-cerrar-modal-consulta');

    if (btnConsultarRadicados && modalConsultarRadicados) {
        // Abrir modal
        btnConsultarRadicados.addEventListener('click', function() {
            // Verificar si hay parámetros de búsqueda en la URL
            const currentUrl = new URL(window.location);
            const hasSearchParams = currentUrl.search && currentUrl.search !== '';

            if (hasSearchParams) {
                // Si hay parámetros de búsqueda, limpiar la URL y recargar
                // Asegurarse de que vamos a la ruta base de radicación
                const cleanUrl = currentUrl.origin + '/radicacion?modal=consultar';
                window.location.href = cleanUrl;
            } else {
                // Si no hay parámetros, abrir modal normalmente
                modalConsultarRadicados.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });

        // Cerrar modal con botón X
        if (btnCerrarModalConsulta) {
            btnCerrarModalConsulta.addEventListener('click', function() {
                modalConsultarRadicados.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
        }

        // Cerrar modal al hacer clic fuera del contenido
        modalConsultarRadicados.addEventListener('click', function(e) {
            if (e.target === modalConsultarRadicados) {
                modalConsultarRadicados.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modalConsultarRadicados.classList.contains('hidden')) {
                modalConsultarRadicados.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Verificar si se debe abrir el modal automáticamente
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('modal') === 'consultar') {
            modalConsultarRadicados.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Limpiar el parámetro de la URL sin recargar
            urlParams.delete('modal');
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.replaceState({}, '', newUrl);
        }
    }




});
