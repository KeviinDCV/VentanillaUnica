<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Acceso al Sistema</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpg" href="{{ asset('images/LogoHospital.jpg') }}">
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('images/LogoHospital.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .hospital-bg {
            background-image: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('{{ asset('images/hospital.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .login-container {
            height: 100vh;
            min-height: 100vh;
            max-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(5, 45, 162, 0.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        .logo-container {
            max-width: 100%;
            height: auto;
        }

        /* FORMULARIO PROFESIONAL COMPACTO */
        .login-form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            box-shadow:
                0 20px 40px rgba(5, 45, 162, 0.1),
                0 8px 16px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 380px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .login-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #052da2, #0ea5e9, #052da2);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 200% 0; }
            50% { background-position: -200% 0; }
        }

        /* INPUTS PROFESIONALES SIN BORDE DOBLE */
        .input-modern {
            border: 2px solid rgba(203, 213, 225, 0.6);
            border-radius: 12px;
            background: rgba(248, 250, 252, 0.8);
            padding: 14px 16px 14px 48px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 15px;
            line-height: 1.4;
            width: 100%;
            position: relative;
            z-index: 1;
            font-weight: 400;

            /* ELIMINAR OUTLINE DEL NAVEGADOR */
            outline: none !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .input-modern:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: #052da2;

            /* ELIMINAR OUTLINE COMPLETAMENTE */
            outline: none !important;
            outline-offset: 0 !important;

            /* SOLO SOMBRA PERSONALIZADA */
            box-shadow:
                0 0 0 3px rgba(5, 45, 162, 0.12),
                0 4px 12px rgba(5, 45, 162, 0.15);
            transform: translateY(-1px);
        }

        .input-modern:valid:not(:placeholder-shown) {
            border-color: #10b981;
            background: rgba(240, 253, 244, 0.8);
            outline: none !important;
        }

        .input-modern::placeholder {
            color: #64748b;
            font-weight: 400;
        }

        /* ELIMINAR OUTLINE EN TODOS LOS ESTADOS */
        .input-modern:focus-visible {
            outline: none !important;
        }

        .input-modern:active {
            outline: none !important;
        }

        /* CONTENEDOR DE INPUT PROFESIONAL */
        .input-container {
            position: relative;
            margin-bottom: 1.25rem;
        }

        /* ICONOS SVG PROFESIONALES */
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 2;
            transition: all 0.3s ease;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            transition: all 0.3s ease;
        }

        .input-container:focus-within .input-icon {
            color: #052da2;
            transform: translateY(-50%) scale(1.1);
        }

        .input-container:focus-within .input-icon svg {
            transform: scale(1.1);
        }

        /* BOTÓN MOSTRAR/OCULTAR CONTRASEÑA CON ICONOS SVG */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            transition: all 0.3s ease;
            z-index: 3;
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;

            /* ELIMINAR OUTLINE */
            outline: none !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: #052da2;
            background: rgba(5, 45, 162, 0.1);
            transform: translateY(-50%) scale(1.1);
        }

        .password-toggle:hover svg {
            transform: scale(1.1);
        }

        .password-toggle:focus {
            outline: none !important;
            color: #052da2;
            background: rgba(5, 45, 162, 0.15);
            box-shadow: 0 0 0 2px rgba(5, 45, 162, 0.2);
        }

        .password-toggle:active {
            transform: translateY(-50%) scale(0.95);
        }

        .password-toggle:active svg {
            transform: scale(0.9);
        }

        /* AJUSTAR PADDING DEL INPUT CUANDO HAY BOTÓN DE TOGGLE */
        .input-container.has-toggle .input-modern {
            padding-right: 56px;
        }

        /* ANIMACIÓN DEL ICONO SVG DE TOGGLE */
        .password-toggle svg {
            transition: all 0.3s ease;
        }

        .password-toggle.toggled svg {
            transform: scale(1.1);
        }

        /* TÍTULO DEL FORMULARIO PROFESIONAL */
        .form-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .form-title p {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 400;
            margin: 0;
        }

        .form-divider {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #052da2, #0ea5e9);
            margin: 0.75rem auto;
            border-radius: 2px;
        }

        /* BOTÓN DE LOGIN CON ANIMACIÓN HOSPITALARIA ÚNICA */
        .btn-login {
            background: linear-gradient(135deg, #052da2 0%, #0369a1 100%);
            color: white;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            box-shadow:
                0 4px 12px rgba(5, 45, 162, 0.25),
                0 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.025em;
            margin-top: 0.5rem;

            /* ELIMINAR OUTLINE */
            outline: none !important;
        }

        /* ANIMACIÓN HOSPITALARIA ÚNICA - PULSO MÉDICO */
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 20%;
            width: 4px;
            height: 60%;
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scaleY(0);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 20%;
            width: 4px;
            height: 60%;
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scaleY(0);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.1s;
            border-radius: 2px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #041f7a 0%, #0284c7 100%);
            transform: translateY(-2px);
            box-shadow:
                0 8px 24px rgba(5, 45, 162, 0.35),
                0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* ACTIVAR ANIMACIÓN PULSO EN HOVER */
        .btn-login:hover::before {
            transform: translateY(-50%) scaleY(1);
            animation: medicalPulse 1.2s ease-in-out infinite;
        }

        .btn-login:hover::after {
            transform: translateY(-50%) scaleY(1);
            animation: medicalPulse 1.2s ease-in-out infinite 0.3s;
        }

        /* KEYFRAMES PARA PULSO MÉDICO */
        @keyframes medicalPulse {
            0%, 100% {
                transform: translateY(-50%) scaleY(1);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-50%) scaleY(1.5);
                opacity: 0.6;
            }
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow:
                0 4px 12px rgba(5, 45, 162, 0.25),
                0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-login:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-login:disabled::before,
        .btn-login:disabled::after {
            display: none;
        }

        /* ELIMINAR OUTLINE EN FOCUS */
        .btn-login:focus {
            outline: none !important;
        }

        .btn-login:focus-visible {
            outline: none !important;
            box-shadow:
                0 4px 12px rgba(5, 45, 162, 0.25),
                0 2px 4px rgba(0, 0, 0, 0.1),
                0 0 0 3px rgba(5, 45, 162, 0.2);
        }

        /* Estados de loading */
        .btn-login.loading {
            pointer-events: none;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* CHECKBOX PROFESIONAL SIN OUTLINE */
        .checkbox-modern {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            flex-shrink: 0;

            /* ELIMINAR OUTLINE */
            outline: none !important;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .checkbox-modern:checked {
            background: #052da2;
            border-color: #052da2;
            transform: scale(1.05);
            outline: none !important;
        }

        .checkbox-modern:checked::after {
            content: '✓';
            position: absolute;
            top: -1px;
            left: 3px;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .checkbox-modern:hover {
            border-color: #052da2;
            box-shadow: 0 0 0 3px rgba(5, 45, 162, 0.1);
            outline: none !important;
        }

        .checkbox-modern:focus {
            outline: none !important;
            border-color: #052da2;
            box-shadow: 0 0 0 3px rgba(5, 45, 162, 0.15);
        }

        .checkbox-modern:focus-visible {
            outline: none !important;
        }

        /* REMEMBER ME SECTION */
        .remember-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
            padding: 12px 16px;
            background: rgba(248, 250, 252, 0.5);
            border-radius: 10px;
            border: 1px solid rgba(203, 213, 225, 0.5);
            transition: all 0.3s ease;
        }

        .remember-section:hover {
            background: rgba(248, 250, 252, 0.8);
            border-color: rgba(5, 45, 162, 0.2);
        }

        .remember-label {
            font-size: 14px;
            color: #475569;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        /* MENSAJES DE ERROR CON MEJOR CONTRASTE */
        .error-message {
            color: #b91c1c;
            font-size: 13px;
            margin-top: 8px;
            padding: 10px 14px;
            background: linear-gradient(135deg, rgba(254, 242, 242, 0.95) 0%, rgba(252, 231, 231, 0.9) 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid #dc2626;
            border-radius: 8px;
            animation: slideInError 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
        }

        .error-message::before {
            content: '⚠️';
            font-size: 14px;
            flex-shrink: 0;
        }

        @keyframes slideInError {
            from {
                opacity: 0;
                transform: translateY(-12px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ESTADOS DE VALIDACIÓN CON CONTRASTE PERFECTO */
        .input-modern.error {
            border-color: #dc2626 !important;
            background: rgba(254, 242, 242, 0.95) !important;
            animation: gentleShake 0.6s ease-in-out;
            box-shadow:
                0 0 0 3px rgba(220, 38, 38, 0.1),
                0 2px 8px rgba(220, 38, 38, 0.15) !important;

            /* ASEGURAR TEXTO OSCURO EN TODOS LOS CASOS */
            color: #1f2937 !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        .input-modern.error::placeholder {
            color: #dc2626 !important;
            opacity: 0.8 !important;
            -webkit-text-fill-color: #dc2626 !important;
        }



        /* FORZAR TEXTO OSCURO EN TODOS LOS ESTADOS DE ERROR */
        .input-modern.error,
        .input-modern.error:focus,
        .input-modern.error:active,
        .input-modern.error[type="text"],
        .input-modern.error[type="password"] {
            color: #1f2937 !important;
            background: rgba(254, 242, 242, 0.95) !important;
            -webkit-text-fill-color: #1f2937 !important;
            -webkit-text-security: none !important;
        }

        /* ASEGURAR CONTRASTE EN CAMPO DE CONTRASEÑA VISIBLE */
        input[type="text"].input-modern.error {
            color: #1f2937 !important;
            -webkit-text-fill-color: #1f2937 !important;
            text-security: none !important;
            -webkit-text-security: none !important;
        }

        /* CONTRASTE PERFECTO EN ESTADO NORMAL */
        .input-modern {
            color: #1f2937 !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        .input-modern:focus {
            color: #1f2937 !important;
            -webkit-text-fill-color: #1f2937 !important;
        }



        /* ANIMACIÓN SHAKE MÁS SUAVE */
        @keyframes gentleShake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-3px); }
            40% { transform: translateX(3px); }
            60% { transform: translateX(-2px); }
            80% { transform: translateX(2px); }
        }

        /* ICONO DE ERROR EN INPUT */
        .input-container.error .input-icon {
            color: #dc2626 !important;
            animation: errorPulse 0.5s ease-in-out;
        }



        /* ESTADOS DEL BOTÓN DE TOGGLE SEGÚN VALIDACIÓN */
        .input-container.error .password-toggle {
            color: #dc2626 !important;
        }

        .input-container.error .password-toggle:hover {
            color: #b91c1c !important;
            background: rgba(220, 38, 38, 0.1) !important;
        }



        @keyframes errorPulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
        }



        /* MICRO-INTERACCIONES */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group:hover .input-modern {
            border-bottom-color: #9ca3af;
        }



        /* EFECTOS DE FOCUS MEJORADOS */
        .input-modern:focus {
            border-bottom-color: #052da2;
            outline: none;
            background: rgba(5, 45, 162, 0.03);
            box-shadow: 0 1px 0 0 #052da2, 0 4px 12px rgba(5, 45, 162, 0.15);
            transform: translateY(-1px);
        }

        /* Ripple effect para botones */
        .btn-login {
            position: relative;
            overflow: hidden;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* MEJORAS DE ACCESIBILIDAD */
        .input-modern:focus-visible {
            outline: 2px solid #052da2;
            outline-offset: 2px;
        }

        .btn-login:focus-visible {
            outline: 2px solid #052da2;
            outline-offset: 2px;
        }

        /* Indicadores de estado para lectores de pantalla */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* RESPONSIVIDAD OPTIMIZADA SIN SCROLL */
        @media (max-width: 768px) {
            .login-form-container {
                padding: 1.5rem;
                margin: 1rem;
                max-width: calc(100vw - 2rem);
            }

            .input-modern {
                font-size: 16px; /* Evita zoom en iOS */
                padding: 12px 16px 12px 44px;
            }

            .input-container {
                margin-bottom: 1rem;
            }

            .btn-login {
                padding: 14px 24px;
                font-size: 16px;
                margin-top: 0.25rem;
            }

            .form-title h2 {
                font-size: 1.5rem;
            }

            .form-title {
                margin-bottom: 1.5rem;
            }

            .remember-section {
                margin: 1rem 0;
                padding: 10px 14px;
            }

            .error-message {
                font-size: 13px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 480px) {
            .login-form-container {
                padding: 1.25rem;
                margin: 0.75rem;
            }

            .input-modern {
                padding: 11px 14px 11px 40px;
                font-size: 16px;
            }

            .input-container {
                margin-bottom: 0.875rem;
            }

            .btn-login {
                padding: 13px 20px;
                margin-top: 0.25rem;
            }

            .form-title {
                margin-bottom: 1.25rem;
            }

            .form-title h2 {
                font-size: 1.375rem;
            }

            .remember-section {
                margin: 0.875rem 0;
                padding: 8px 12px;
            }

            .input-icon {
                left: 12px;
                width: 16px;
                height: 16px;
            }

            .input-icon svg {
                width: 16px;
                height: 16px;
            }

            .password-toggle {
                right: 12px;
                width: 28px;
                height: 28px;
            }

            .password-toggle svg {
                width: 18px;
                height: 18px;
            }

            .input-container.has-toggle .input-modern {
                padding-right: 48px;
            }
        }

        /* ALTURA MÁXIMA PARA EVITAR SCROLL */
        @media (max-height: 700px) {
            .login-form-container {
                padding: 1rem;
                margin: 0.5rem;
            }

            .form-title {
                margin-bottom: 1rem;
            }

            .form-title h2 {
                font-size: 1.375rem;
                margin-bottom: 0.25rem;
            }

            .input-container {
                margin-bottom: 0.75rem;
            }

            .remember-section {
                margin: 0.75rem 0;
                padding: 8px 12px;
            }

            .btn-login {
                margin-top: 0.25rem;
                padding: 12px 20px;
            }
        }

        @media (max-height: 600px) {
            .login-form-container {
                padding: 0.875rem;
                margin: 0.25rem;
            }

            .form-title {
                margin-bottom: 0.875rem;
            }

            .input-container {
                margin-bottom: 0.625rem;
            }

            .remember-section {
                margin: 0.625rem 0;
            }
        }

        /* Modo oscuro (si se implementa en el futuro) */
        @media (prefers-color-scheme: dark) {
            .input-modern {
                color: #f9fafb;
                border-bottom-color: #4b5563;
            }

            .input-modern:focus {
                background: rgba(5, 45, 162, 0.1);
            }

            .label-floating {
                color: #d1d5db;
            }

            .input-modern:focus + .label-floating,
            .input-modern:valid:not(:placeholder-shown) + .label-floating {
                color: #60a5fa;
            }
        }

        /* Animaciones reducidas para usuarios que las prefieren */
        @media (prefers-reduced-motion: reduce) {
            .input-modern,
            .btn-login,
            .label-floating,
            .input-container::after {
                transition: none;
                animation: none;
            }

            .error-message {
                animation: none;
            }
        }

        /* Estados de carga mejorados */
        .form-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .form-loading .input-modern {
            background: #f9fafb;
            cursor: not-allowed;
        }

        /* Tooltips para ayuda contextual */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #1f2937;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1000;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 0.5rem;
                height: 100vh;
                min-height: 100vh;
                max-height: 100vh;
            }

            .hospital-bg {
                background-attachment: scroll;
            }

            .login-card {
                max-height: 95vh;
                padding: 1.5rem;
            }
        }

        @media (max-height: 700px) {
            .login-container {
                align-items: center;
                padding: 0.5rem;
            }

            .login-card {
                max-height: 95vh;
                padding: 1rem;
            }
        }

        @media (max-height: 600px) {
            .login-card {
                max-height: 98vh;
                padding: 0.75rem;
            }
        }

        /* Efectos específicos para la integración del logo con el fondo del hospital */
        .hospital-bg .logo-hospital-integrated,
        .hospital-bg .logo-hospital-mobile {
            /* Mejorar la integración con el fondo específico del hospital */
            mix-blend-mode: multiply;
            filter:
                brightness(0.94)
                contrast(1.12)
                saturate(1.05)
                drop-shadow(0 4px 12px rgba(5, 45, 162, 0.15))
                drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* Efecto de integración mejorado para el contenedor del logo */
        .hospital-bg .logo-container-integrated {
            position: relative;
            overflow: hidden;
        }

        .hospital-bg .logo-container-integrated::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            right: -10%;
            bottom: -10%;
            background: radial-gradient(ellipse, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            z-index: -1;
            filter: blur(8px);
        }

        /* Optimización para diferentes fondos */
        @media (max-width: 768px) {
            .hospital-bg .logo-hospital-mobile {
                mix-blend-mode: multiply;
                filter:
                    brightness(0.96)
                    contrast(1.08)
                    saturate(1.03)
                    drop-shadow(0 2px 8px rgba(5, 45, 162, 0.12));
            }
        }

        /* EFECTOS CSS SIMPLIFICADOS - FORMA RECTANGULAR GARANTIZADA */
        .logo-hospital-integrated-direct {
            /* Eliminación del fondo blanco - MÉTODO PRINCIPAL */
            mix-blend-mode: multiply !important;

            /* Filtros para integración visual */
            filter: brightness(0.90) contrast(1.18) saturate(1.10) drop-shadow(0 3px 8px rgba(5, 45, 162, 0.15)) !important;

            /* ELIMINAR TODAS LAS MÁSCARAS - Causan distorsión */
            -webkit-mask: none !important;
            mask: none !important;

            /* Clip-path rectangular con bordes muy sutiles */
            clip-path: inset(2px 2px 2px 2px round 4px) !important;

            /* Transiciones suaves */
            transition: all 0.3s ease !important;

            /* Preservar forma y proporciones EXACTAS */
            object-fit: contain !important;
            object-position: center !important;

            /* Optimización de renderizado */
            will-change: filter, transform !important;
            transform: translateZ(0) !important;

            /* Asegurar forma rectangular */
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
        }

        .logo-hospital-integrated-direct:hover {
            /* Efectos hover sin máscaras - Solo filtros y transform */
            filter: brightness(0.95) contrast(1.22) saturate(1.12) drop-shadow(0 4px 12px rgba(5, 45, 162, 0.20)) !important;
            transform: translateZ(0) scale(1.01) !important;

            /* Clip-path ligeramente más suave en hover */
            clip-path: inset(1px 1px 1px 1px round 6px) !important;

            /* Sin máscaras que puedan distorsionar */
            -webkit-mask: none !important;
            mask: none !important;
        }

        .logo-container-integrated-direct {
            position: relative !important;
            display: inline-block !important;
            padding: 0.75rem !important;

            /* FORMA RECTANGULAR - NO circular */
            border-radius: 8px !important;

            /* Backdrop filter sutil */
            backdrop-filter: blur(1px) brightness(1.05) !important;
            -webkit-backdrop-filter: blur(1px) brightness(1.05) !important;

            /* Fondo rectangular sutil */
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%) !important;

            /* Sombra rectangular del contenedor */
            box-shadow: 0 4px 16px rgba(5, 45, 162, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;

            /* Asegurar que no distorsione el contenido */
            overflow: visible !important;
        }

        .logo-container-integrated-direct::before {
            content: '' !important;
            position: absolute !important;
            top: -5px !important;
            left: -5px !important;
            right: -5px !important;
            bottom: -5px !important;

            /* Fondo rectangular simple sin distorsión */
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 100%) !important;
            border-radius: 10px !important;
            z-index: -1 !important;
            filter: blur(2px) !important;
            opacity: 0.4 !important;
        }

        /* Eliminar ::after que puede causar distorsión */
        .logo-container-integrated-direct::after {
            display: none !important;
        }

        /* Versión móvil - SIN MÁSCARAS */
        .logo-hospital-mobile-direct {
            mix-blend-mode: multiply !important;
            filter: brightness(0.92) contrast(1.14) saturate(1.08) drop-shadow(0 2px 6px rgba(5, 45, 162, 0.12)) !important;

            /* ELIMINAR todas las máscaras problemáticas */
            -webkit-mask: none !important;
            mask: none !important;

            /* Clip-path muy sutil para móvil */
            clip-path: inset(1px 1px 1px 1px round 3px) !important;

            /* Preservar forma rectangular exacta */
            object-fit: contain !important;
            object-position: center !important;

            /* Transiciones suaves */
            transition: all 0.3s ease !important;
            transform: translateZ(0) !important;

            /* Asegurar dimensiones correctas */
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;
        }

        /* Fallback para navegadores sin soporte de mix-blend-mode */
        @supports not (mix-blend-mode: multiply) {
            .logo-hospital-integrated-direct,
            .logo-hospital-mobile-direct {
                mix-blend-mode: normal !important;
                filter: brightness(0.82) contrast(1.35) saturate(1.20) drop-shadow(0 4px 12px rgba(5, 45, 162, 0.25)) !important;
                opacity: 0.88 !important;
                background: rgba(255, 255, 255, 0.1) !important;
            }
        }

        /* Fallback para navegadores sin soporte de clip-path */
        @supports not (clip-path: inset(1px)) {
            .logo-hospital-integrated-direct,
            .logo-hospital-mobile-direct {
                clip-path: none !important;
                border-radius: 6px !important;
                overflow: hidden !important;
            }
        }

        /* Método alternativo usando solo border-radius para máxima compatibilidad */
        .logo-container-simple {
            position: relative !important;
            overflow: hidden !important;
            border-radius: 8px !important;
            background: rgba(255, 255, 255, 0.05) !important;
            padding: 0.5rem !important;
        }

        .logo-simple {
            mix-blend-mode: multiply !important;
            filter: brightness(0.88) contrast(1.20) saturate(1.12) !important;
            border-radius: 4px !important;
            width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }

        /* Media queries responsivos */
        @media (max-width: 768px) {
            .logo-container-integrated-direct {
                padding: 0.75rem !important;
                backdrop-filter: blur(1px) brightness(1.05) !important;
            }
        }

        /* Forzar aplicación de efectos SIMPLIFICADOS - SIN MÁSCARAS */
        .hospital-bg .logo-hospital-integrated-direct,
        body .logo-hospital-integrated-direct {
            mix-blend-mode: multiply !important;
            filter: brightness(0.90) contrast(1.18) saturate(1.10) drop-shadow(0 3px 8px rgba(5, 45, 162, 0.15)) !important;

            /* ELIMINAR todas las máscaras que causan distorsión */
            -webkit-mask: none !important;
            mask: none !important;

            /* Clip-path rectangular muy sutil */
            clip-path: inset(2px 2px 2px 2px round 4px) !important;

            /* Asegurar forma rectangular */
            object-fit: contain !important;
            object-position: center !important;
        }

        .hospital-bg .logo-hospital-mobile-direct,
        body .logo-hospital-mobile-direct {
            mix-blend-mode: multiply !important;
            filter: brightness(0.92) contrast(1.14) saturate(1.08) drop-shadow(0 2px 6px rgba(5, 45, 162, 0.12)) !important;

            /* ELIMINAR todas las máscaras que causan distorsión */
            -webkit-mask: none !important;
            mask: none !important;

            /* Clip-path rectangular muy sutil para móvil */
            clip-path: inset(1px 1px 1px 1px round 3px) !important;

            /* Asegurar forma rectangular */
            object-fit: contain !important;
            object-position: center !important;
        }

        /* Asegurar que los contenedores de emergencia no tengan efectos */
        .hospital-bg .container-emergency,
        body .container-emergency {
            background: transparent !important;
            background-color: transparent !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }

        /* Forzar transparencia total en logos de emergencia */
        .hospital-bg .logo-emergency,
        body .logo-emergency {
            mix-blend-mode: multiply !important;
            filter: brightness(0.92) contrast(1.25) saturate(1.18) !important;
            box-shadow: none !important;
            text-shadow: none !important;
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            border-radius: 0 !important;
        }

        /* Animación sutil */
        @keyframes logoFloatDirect {
            0%, 100% {
                transform: translateZ(0) translateY(0px) !important;
            }
            50% {
                transform: translateZ(0) translateY(-2px) !important;
            }
        }

        .logo-hospital-integrated-direct {
            animation: logoFloatDirect 6s ease-in-out infinite !important;
        }

        /* Desactivar animaciones si se prefiere movimiento reducido */
        @media (prefers-reduced-motion: reduce) {
            .logo-emergency {
                animation: none !important;
                transition: none !important;
            }
        }

        /* REGLAS ADICIONALES PARA ELIMINAR SOMBRAS RESIDUALES */
        .logo-emergency,
        .logo-emergency:hover,
        .logo-emergency:focus,
        .logo-emergency:active {
            /* Eliminar TODAS las posibles sombras */
            box-shadow: none !important;
            text-shadow: none !important;
            filter: brightness(0.92) contrast(1.25) saturate(1.18) !important;

            /* Eliminar TODOS los fondos */
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;

            /* Eliminar TODOS los bordes */
            border: none !important;
            border-radius: 0 !important;
            outline: none !important;

            /* Asegurar transparencia total */
            opacity: 1 !important;
        }

        /* Eliminar efectos de pseudo-elementos que puedan crear sombras */
        .container-emergency::before,
        .container-emergency::after,
        .logo-emergency::before,
        .logo-emergency::after {
            display: none !important;
            content: none !important;
        }

        /* REGLA ESPECÍFICA PARA ELIMINAR DROP-SHADOW */
        img.logo-emergency {
            /* Filtros SIN drop-shadow */
            filter: brightness(0.92) contrast(1.25) saturate(1.18) !important;

            /* Asegurar que no hay drop-shadow de ninguna fuente */
            -webkit-filter: brightness(0.92) contrast(1.25) saturate(1.18) !important;

            /* Eliminar cualquier sombra CSS */
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
            -moz-box-shadow: none !important;

            /* Eliminar text-shadow */
            text-shadow: none !important;
            -webkit-text-shadow: none !important;
            -moz-text-shadow: none !important;
        }

        /* Regla específica para el hover sin drop-shadow */
        img.logo-emergency:hover {
            filter: brightness(0.95) contrast(1.28) saturate(1.20) !important;
            -webkit-filter: brightness(0.95) contrast(1.28) saturate(1.20) !important;
            box-shadow: none !important;
            text-shadow: none !important;
        }

        /* VERSIÓN DE EMERGENCIA - TRANSPARENCIA TOTAL SIN SOMBRAS */
        .logo-emergency {
            /* Solo mix-blend-mode para eliminar fondo blanco */
            mix-blend-mode: multiply !important;

            /* Filtros optimizados para transparencia total - SIN DROP-SHADOW */
            filter: brightness(0.92) contrast(1.25) saturate(1.18) !important;

            /* Sin máscaras, sin clip-path - Solo forma natural */
            -webkit-mask: none !important;
            mask: none !important;
            clip-path: none !important;

            /* ELIMINAR TODAS LAS SOMBRAS */
            box-shadow: none !important;
            text-shadow: none !important;

            /* Preservar forma rectangular EXACTA */
            object-fit: contain !important;
            object-position: center !important;
            width: 100% !important;
            height: auto !important;
            max-width: 100% !important;

            /* Sin bordes que puedan crear sombras */
            border: none !important;
            border-radius: 0 !important;

            /* Sin fondos que puedan crear efectos grisáceos */
            background: transparent !important;
            background-color: transparent !important;

            /* Transición simple */
            transition: filter 0.3s ease !important;
        }

        .logo-emergency:hover {
            /* Mantener los mismos filtros que el estado normal */
            filter: brightness(0.95) contrast(1.28) saturate(1.20) !important;

            /* Asegurar que no hay sombras en hover */
            box-shadow: none !important;
            text-shadow: none !important;

            /* Sin fondos */
            background: transparent !important;
            background-color: transparent !important;
        }

        .container-emergency {
            position: relative !important;
            display: inline-block !important;

            /* ELIMINAR padding que puede crear efectos visuales */
            padding: 0 !important;

            /* ELIMINAR border-radius que puede crear sombras */
            border-radius: 0 !important;

            /* ELIMINAR fondo completamente */
            background: transparent !important;
            background-color: transparent !important;

            /* ELIMINAR todas las sombras del contenedor */
            box-shadow: none !important;

            /* ELIMINAR bordes */
            border: none !important;

            /* ELIMINAR backdrop-filter que puede crear efectos grisáceos */
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
    </style>
</head>

<body class="hospital-bg">
    <div class="login-container">
        <div class="w-full max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

                <!-- Sección del Logo del Hospital -->
                <div class="hidden lg:flex flex-col items-center justify-center p-8">
                    <div class="container-emergency mb-6">
                        <img src="{{ asset('images/LogoHospital.jpg') }}"
                             alt="Logo Hospital"
                             class="logo-emergency w-full max-w-md h-auto">
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-light text-gray-800 mb-2">UniRadic</h1>
                        <p class="text-gray-600 font-light">Sistema de Gestión Documental</p>
                        <p class="text-gray-500 text-sm font-light mt-1">Ventanilla Única Hospitalaria</p>
                    </div>
                </div>

                <!-- Sección del Formulario de Login Profesional -->
                <div class="flex items-center justify-center p-2">
                    <div class="login-form-container">

                        <!-- Logo móvil -->
                        <div class="lg:hidden text-center mb-4">
                            <div class="container-emergency inline-block mb-3">
                                <img src="{{ asset('images/LogoHospital.jpg') }}"
                                     alt="Logo Hospital"
                                     class="logo-emergency w-32 h-auto mx-auto">
                            </div>
                        </div>

                        <!-- Título del formulario profesional -->
                        <div class="form-title">
                            <h2>Acceso al Sistema</h2>
                            <div class="form-divider"></div>
                            <p>Ingresa tus credenciales para continuar</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-6" :status="session('status')" />

                        <!-- Mensajes de error y advertencia -->
                        @if (session('error'))
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm">
                                {{ session('warning') }}
                            </div>
                        @endif

                        @if (session('message'))
                            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 text-sm">
                                {{ session('message') }}
                            </div>
                        @endif

                        <!-- Formulario de Login Mejorado -->
                        <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6" novalidate>
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group">
                                <div class="input-container">
                                    <div class="input-icon">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 7L10.94 11.94C11.59 12.39 12.41 12.39 13.06 11.94L20 7M4 6H20C20.55 6 21 6.45 21 7V17C21 17.55 20.55 18 20 18H4C3.45 18 3 17.55 3 17V7C3 6.45 3.45 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <input id="email"
                                           type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus
                                           autocomplete="username"
                                           class="input-modern"
                                           placeholder="correo@hospital.com"
                                           aria-describedby="email-error">
                                </div>
                                @error('email')
                                    <div class="error-message" id="email-error" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <div class="input-container has-toggle">
                                    <div class="input-icon">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 11H5C3.89543 11 3 11.8954 3 13V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V13C21 11.8954 20.1046 11 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <input id="password"
                                           type="password"
                                           name="password"
                                           required
                                           autocomplete="current-password"
                                           class="input-modern"
                                           placeholder="Contraseña"
                                           aria-describedby="password-error">
                                    <button type="button"
                                            class="password-toggle"
                                            id="password-toggle"
                                            aria-label="Mostrar contraseña"
                                            title="Mostrar contraseña">
                                        <svg id="show-password-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                            <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <svg id="hide-password-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.94 17.94C16.2306 19.243 14.1491 19.9649 12 20C5 20 1 12 1 12C2.24389 9.68192 4.028 7.66607 6.17 6.17M9.9 4.24C10.5883 4.0789 11.2931 3.99836 12 4C19 4 23 12 23 12C22.393 13.1356 21.6691 14.2048 20.84 15.19M14.12 14.12C13.8454 14.4148 13.5141 14.6512 13.1462 14.8151C12.7782 14.9791 12.3809 15.0673 11.9781 15.0744C11.5753 15.0815 11.1749 15.0074 10.8016 14.8565C10.4283 14.7056 10.0887 14.4811 9.80385 14.1962C9.51900 13.9113 9.29449 13.5717 9.14359 13.1984C8.99269 12.8251 8.91855 12.4247 8.92563 12.0219C8.93271 11.6191 9.02091 11.2218 9.18488 10.8538C9.34884 10.4858 9.58525 10.1546 9.88 9.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-message" id="password-error" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="remember-section">
                                <input id="remember_me"
                                       type="checkbox"
                                       name="remember"
                                       value="1"
                                       class="checkbox-modern"
                                       aria-describedby="remember-description">
                                <label for="remember_me" class="remember-label">
                                    Recordar sesión
                                </label>
                                <span class="sr-only" id="remember-description">
                                    Mantener la sesión iniciada en este dispositivo
                                </span>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit"
                                        class="btn-login"
                                        id="login-button"
                                        aria-describedby="login-status">
                                    <span class="button-text">Iniciar Sesión</span>
                                </button>
                                <div class="sr-only" id="login-status" aria-live="polite"></div>
                            </div>

                            <!-- Mensaje de estado general -->
                            <div id="form-status" class="sr-only" aria-live="polite"></div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript del formulario ahora se maneja desde resources/js/login-form.js -->

    <!-- Script para prevenir navegación hacia atrás -->
    <script>
        // Prevenir navegación hacia atrás después de acceder al login
        (function() {
            // Reemplazar el estado actual del historial
            if (window.history && window.history.pushState) {
                // Agregar una entrada al historial para prevenir el botón atrás
                window.history.pushState(null, null, window.location.href);

                // Manejar el evento popstate (botón atrás/adelante)
                window.addEventListener('popstate', function(event) {
                    // Prevenir la navegación hacia atrás
                    window.history.pushState(null, null, window.location.href);

                    // Mostrar mensaje opcional
                    console.log('Navegación hacia atrás bloqueada desde página de login');
                });
            }

            // Limpiar cualquier dato de sesión del lado del cliente
            try {
                // Limpiar localStorage relacionado con sesiones
                const keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && (key.includes('session') || key.includes('auth') || key.includes('uniradical'))) {
                        keysToRemove.push(key);
                    }
                }
                keysToRemove.forEach(key => localStorage.removeItem(key));

                // Limpiar sessionStorage
                sessionStorage.clear();
            } catch (e) {
                // Silenciar errores de storage
                console.debug('Error limpiando storage:', e);
            }
        })();

        // Prevenir cache de la página
        window.addEventListener('beforeunload', function() {
            // Forzar recarga desde servidor en próxima visita
            if (window.performance && window.performance.navigation.type === 1) {
                // Página fue recargada
                window.location.reload(true);
            }
        });

        // Manejar evento pageshow para páginas cargadas desde cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Página cargada desde cache, forzar recarga
                window.location.reload(true);
            }
        });
    </script>

    <style>
        /* Animación inicial del formulario */
        #loginForm {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</body>
</html>
