<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicio No Disponible - UniRadic</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/LogoHospital.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ESTILOS ESPECÍFICOS PARA ERROR 503 */
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
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 
                0 25px 50px rgba(5, 45, 162, 0.1),
                0 10px 20px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 90%;
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
            background: linear-gradient(90deg, #dc2626, #ef4444, #dc2626);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 200% 0; }
            50% { background-position: -200% 0; }
        }

        .hospital-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(5, 45, 162, 0.2);
        }

        .hospital-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .error-icon {
            font-size: 4rem;
            color: #dc2626;
            margin-bottom: 1.5rem;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
        }

        .error-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-code {
            font-size: 1.25rem;
            font-weight: 600;
            color: #dc2626;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #052da2 0%, #0369a1 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
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

        .system-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(203, 213, 225, 0.3);
            color: #94a3b8;
            font-size: 0.875rem;
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
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Logo del Hospital -->
        <div class="hospital-logo">
            <img src="{{ asset('images/LogoHospital.jpg') }}" alt="Logo Hospital">
        </div>

        <!-- Icono de Error -->
        <div class="error-icon">⚠️</div>

        <!-- Código de Error -->
        <div class="error-code">Error 503</div>

        <!-- Título y Subtítulo -->
        <h1 class="error-title">Servicio No Disponible</h1>
        <p class="error-subtitle">
            El sistema UniRadic está temporalmente fuera de servicio debido a mantenimiento o sobrecarga del servidor.
        </p>

        <!-- Botones de Acción -->
        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver al Inicio
            </a>
        </div>

        <!-- Información del Sistema -->
        <div class="system-info">
            <p><strong>UniRadic</strong> - Sistema de Gestión Documental Hospitalaria</p>
            <p>Por favor, intente nuevamente en unos minutos</p>
        </div>
    </div>

    <script nonce="{{ session('csp_nonce', 'default-nonce') }}">
        // Auto-refresh cada 60 segundos para error 503
        setTimeout(function() {
            window.location.reload();
        }, 60000);
    </script>
</body>
</html>
