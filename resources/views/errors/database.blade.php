<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema No Disponible - UniRadic</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/LogoHospital.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ESTILOS ESPECÍFICOS PARA PÁGINA DE ERROR DE BASE DE DATOS */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            box-shadow:
                0 20px 40px rgba(5, 45, 162, 0.1),
                0 8px 16px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 450px;
            width: 90%;
            max-height: 90vh;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #052da2, #0ea5e9, #052da2);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 200% 0; }
            50% { background-position: -200% 0; }
        }

        .hospital-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(5, 45, 162, 0.2);
        }

        .hospital-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .error-icon {
            font-size: 3rem;
            color: #052da2;
            margin-bottom: 1rem;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }

        .error-subtitle {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .error-message {
            background: rgba(248, 250, 252, 0.8);
            border: 1px solid rgba(203, 213, 225, 0.5);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .error-message h3 {
            color: #052da2;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-message ul {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
            padding-left: 1.25rem;
        }

        .error-message li {
            margin-bottom: 0.375rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #052da2 0%, #0369a1 100%);
            color: white;
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(5, 45, 162, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #041f7a 0%, #0284c7 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(5, 45, 162, 0.35);
        }

        .btn-secondary {
            background: rgba(248, 250, 252, 0.8);
            color: #052da2;
            padding: 0.875rem 1.75rem;
            border: 2px solid rgba(5, 45, 162, 0.2);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(5, 45, 162, 0.05);
            border-color: #052da2;
            transform: translateY(-1px);
        }

        .system-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(203, 213, 225, 0.3);
            color: #94a3b8;
            font-size: 0.8rem;
        }

        /* RESPONSIVIDAD */
        @media (max-width: 768px) {
            .error-container {
                padding: 2rem;
                margin: 1rem;
            }

            .error-title {
                font-size: 1.75rem;
            }

            .error-subtitle {
                font-size: 1rem;
            }

            .hospital-logo {
                width: 60px;
                height: 60px;
            }

            .error-icon {
                font-size: 3rem;
            }
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 1.5rem;
            }

            .action-buttons {
                gap: 0.75rem;
            }

            .btn-primary,
            .btn-secondary {
                padding: 0.875rem 1.5rem;
                font-size: 0.95rem;
            }
        }

        /* OPTIMIZACIÓN PARA PANTALLAS CON POCA ALTURA */
        @media (max-height: 700px) {
            .error-container {
                padding: 1.5rem;
                max-height: 85vh;
                overflow-y: auto;
            }

            .hospital-logo {
                width: 50px;
                height: 50px;
                margin-bottom: 0.75rem;
            }

            .error-icon {
                font-size: 2.5rem;
                margin-bottom: 0.75rem;
            }

            .error-title {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .error-subtitle {
                font-size: 0.95rem;
                margin-bottom: 1.25rem;
            }

            .error-message {
                padding: 1rem;
                margin-bottom: 1.25rem;
            }

            .action-buttons {
                margin-top: 1.25rem;
                gap: 0.5rem;
            }

            .system-info {
                margin-top: 1.25rem;
                padding-top: 1.25rem;
                font-size: 0.75rem;
            }
        }

        @media (max-height: 600px) {
            .error-container {
                padding: 1.25rem;
                max-height: 80vh;
            }

            .hospital-logo {
                width: 40px;
                height: 40px;
                margin-bottom: 0.5rem;
            }

            .error-icon {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }

            .error-title {
                font-size: 1.375rem;
                margin-bottom: 0.375rem;
            }

            .error-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1rem;
                line-height: 1.4;
            }

            .error-message {
                padding: 0.875rem;
                margin-bottom: 1rem;
            }

            .error-message h3 {
                font-size: 0.875rem;
                margin-bottom: 0.375rem;
            }

            .error-message ul {
                font-size: 0.825rem;
                line-height: 1.4;
            }

            .error-message li {
                margin-bottom: 0.25rem;
            }

            .action-buttons {
                margin-top: 1rem;
                gap: 0.5rem;
            }

            .btn-primary,
            .btn-secondary {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }

            .system-info {
                margin-top: 1rem;
                padding-top: 1rem;
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Logo del Hospital -->
        <div class="hospital-logo">
            <img src="{{ asset('images/LogoHospital.jpg') }}" alt="Logo Hospital">
        </div>

        <!-- Icono de Error -->
        <div class="error-icon">🏥</div>

        <!-- Título y Subtítulo -->
        <h1 class="error-title">Sistema No Disponible</h1>
        <p class="error-subtitle">
            El sistema UniRadic está experimentando dificultades técnicas temporales y no puede procesar su solicitud en este momento.
        </p>

        <!-- Mensaje de Error Detallado -->
        <div class="error-message">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 9V13M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                ¿Qué puede hacer?
            </h3>
            <ul>
                <li>Espere unos minutos e intente acceder nuevamente</li>
                <li>Verifique su conexión a internet</li>
                <li>Contacte al administrador del sistema si el problema persiste</li>
                <li>Si es urgente, utilice los procedimientos manuales establecidos</li>
            </ul>
        </div>

        <!-- Botones de Acción -->
        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4V20C4 20.5523 4.44772 21 5 21H19C19.5523 21 20 20.5523 20 20V8H16C14.8954 8 14 7.10457 14 6V2H5C4.44772 2 4 2.44772 4 3V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 2L20 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Intentar Nuevamente
            </a>
            
            <button onclick="window.location.reload()" class="btn-secondary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 4V10H7M23 20V14H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M20.49 9C19.9828 7.56678 19.1209 6.28392 17.9845 5.27304C16.8482 4.26216 15.4745 3.55682 13.9917 3.21834C12.5089 2.87986 10.9652 2.91902 9.50481 3.33329C8.04437 3.74757 6.71475 4.52306 5.64 5.58L1 10M23 14L18.36 18.42C17.2853 19.477 15.9556 20.2525 14.4952 20.6667C13.0348 21.081 11.4911 21.1201 10.0083 20.7817C8.52547 20.4432 7.1518 19.7378 6.01547 18.727C4.87913 17.7161 4.01717 16.4332 3.51 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Recargar Página
            </button>
        </div>

        <!-- Información del Sistema -->
        <div class="system-info">
            <p><strong>UniRadic</strong> - Sistema de Gestión Documental Hospitalaria</p>
            <p>Si necesita asistencia inmediata, contacte al departamento de TI</p>
        </div>
    </div>

    <script>
        // Auto-refresh cada 30 segundos
        setTimeout(function() {
            window.location.reload();
        }, 30000);

        // Mostrar tiempo transcurrido
        let startTime = Date.now();
        setInterval(function() {
            let elapsed = Math.floor((Date.now() - startTime) / 1000);
            if (elapsed > 0) {
                document.title = `Sistema No Disponible (${elapsed}s) - UniRadic`;
            }
        }, 1000);
    </script>
</body>
</html>
