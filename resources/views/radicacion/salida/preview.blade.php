<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - Radicado de Salida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .preview-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header h2 {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f8fafc;
            color: #2563eb;
            padding: 8px 12px;
            border-left: 4px solid #2563eb;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-item.full-width {
            grid-column: 1 / -1;
        }
        .info-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 3px;
        }
        .info-value {
            color: #6b7280;
            padding: 5px 0;
        }
        .sticker-section {
            border: 2px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            background-color: #f8fafc;
        }
        .sticker-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            text-align: center;
        }
        .sticker-item {
            padding: 8px;
            border: 1px solid #d1d5db;
            background: white;
        }
        .sticker-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .sticker-value {
            font-weight: bold;
            color: #111827;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background-color: #2563eb;
            color: white;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .preview-container {
                box-shadow: none;
                padding: 20px;
            }
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <!-- Encabezado -->
        <div class="header">
            <h1>SISTEMA UNIRADICAL</h1>
            <h2>DOCUMENTO DE SALIDA - VISTA PREVIA</h2>
        </div>

        <!-- Información del Radicado -->
        <div class="sticker-section">
            <div class="sticker-grid">
                <div class="sticker-item">
                    <div class="sticker-label">NÚMERO DE RADICADO</div>
                    <div class="sticker-value">PENDIENTE</div>
                </div>
                <div class="sticker-item">
                    <div class="sticker-label">TIPO</div>
                    <div class="sticker-value">SALIDA</div>
                </div>
                <div class="sticker-item">
                    <div class="sticker-label">FECHA</div>
                    <div class="sticker-value">{{ date('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Información del Destinatario -->
        <div class="section">
            <div class="section-title">INFORMACIÓN DEL DESTINATARIO</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nombre Completo:</div>
                    <div class="info-value">{{ $datosPreview['nombre_destinatario'] }}</div>
                </div>
                @if(isset($datosPreview['tipo_documento_destinatario']) && isset($datosPreview['numero_documento_destinatario']))
                <div class="info-item">
                    <div class="info-label">Identificación:</div>
                    <div class="info-value">{{ $datosPreview['tipo_documento_destinatario'] }} {{ $datosPreview['numero_documento_destinatario'] }}</div>
                </div>
                @endif
                @if(isset($datosPreview['telefono_destinatario']) && $datosPreview['telefono_destinatario'])
                <div class="info-item">
                    <div class="info-label">Teléfono:</div>
                    <div class="info-value">{{ $datosPreview['telefono_destinatario'] }}</div>
                </div>
                @endif
                @if(isset($datosPreview['email_destinatario']) && $datosPreview['email_destinatario'])
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $datosPreview['email_destinatario'] }}</div>
                </div>
                @endif
                @if(isset($datosPreview['direccion_destinatario']) && $datosPreview['direccion_destinatario'])
                <div class="info-item full-width">
                    <div class="info-label">Dirección:</div>
                    <div class="info-value">{{ $datosPreview['direccion_destinatario'] }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-label">Ciudad:</div>
                    <div class="info-value">{{ $datosPreview['ciudad_destinatario'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Departamento:</div>
                    <div class="info-value">{{ $datosPreview['departamento_destinatario'] }}</div>
                </div>
            </div>
        </div>

        <!-- Información de la Dependencia Origen -->
        <div class="section">
            <div class="section-title">DEPENDENCIA DE ORIGEN</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Dependencia:</div>
                    <div class="info-value">{{ $datosPreview['dependencia_origen']->nombre }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Funcionario:</div>
                    <div class="info-value">{{ $datosPreview['funcionario_remitente'] }}</div>
                </div>
                @if(isset($datosPreview['cargo_remitente']) && $datosPreview['cargo_remitente'])
                <div class="info-item full-width">
                    <div class="info-label">Cargo:</div>
                    <div class="info-value">{{ $datosPreview['cargo_remitente'] }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Información del Documento -->
        <div class="section">
            <div class="section-title">INFORMACIÓN DEL DOCUMENTO</div>
            <div class="info-grid">
                <div class="info-item full-width">
                    <div class="info-label">TRD (Serie/Subserie):</div>
                    <div class="info-value">
                        {{ $datosPreview['trd']['codigo'] }} - {{ $datosPreview['trd']['serie'] }} / {{ $datosPreview['trd']['subserie'] }}
                    </div>
                </div>
                <div class="info-item full-width">
                    <div class="info-label">Asunto:</div>
                    <div class="info-value">{{ $datosPreview['asunto'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipo de Comunicación:</div>
                    <div class="info-value">{{ $datosPreview['tipo_comunicacion'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Número de Folios:</div>
                    <div class="info-value">{{ $datosPreview['numero_folios'] }}</div>
                </div>
                @if(isset($datosPreview['medio_envio']) && $datosPreview['medio_envio'])
                <div class="info-item">
                    <div class="info-label">Medio de Envío:</div>
                    <div class="info-value">{{ ucfirst(str_replace('_', ' ', $datosPreview['medio_envio'])) }}</div>
                </div>
                @endif
                @if(isset($datosPreview['tipo_anexo']) && $datosPreview['tipo_anexo'])
                <div class="info-item">
                    <div class="info-label">Tipo de Anexo:</div>
                    <div class="info-value">{{ ucfirst($datosPreview['tipo_anexo']) }}</div>
                </div>
                @endif
                @if(isset($datosPreview['requiere_acuse_recibo']) && $datosPreview['requiere_acuse_recibo'])
                <div class="info-item">
                    <div class="info-label">Requiere Acuse de Recibo:</div>
                    <div class="info-value">{{ $datosPreview['requiere_acuse_recibo'] ? 'SÍ' : 'NO' }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Observaciones -->
        @if(isset($datosPreview['observaciones']) && $datosPreview['observaciones'])
        <div class="section">
            <div class="section-title">OBSERVACIONES</div>
            <div class="info-value" style="padding: 10px; background-color: #f9fafb; border-radius: 4px;">
                {{ $datosPreview['observaciones'] }}
            </div>
        </div>
        @endif

        <!-- Usuario que Radica -->
        <div class="section">
            <div class="section-title">INFORMACIÓN DE RADICACIÓN</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Usuario que Radica:</div>
                    <div class="info-value">{{ $datosPreview['usuario_radica'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Radicación:</div>
                    <div class="info-value">{{ date('d/m/Y H:i:s') }}</div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="actions">
            <button onclick="window.print()" class="btn btn-primary">Imprimir Vista Previa</button>
            <button onclick="window.close()" class="btn btn-secondary">Cerrar</button>
        </div>
    </div>
</body>
</html>
