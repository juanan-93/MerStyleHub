<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita - MerStyleHub</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --color-base: #ECE9E2;
            --color-white: #FFFFFF;
            --color-primary: #A08A7A;
            --color-primary-dark: #8f7668;
            --color-secondary: #343434;
            --color-light: #F5F3F0;
            --color-border: #D9D4CE;
            --color-text-muted: #6C757D;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--color-secondary);
            background-color: var(--color-base);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }

        /* ========== NAVBAR ========== */
        .navbar-landing {
            background-color: var(--color-white);
            transition: all 0.3s ease;
            padding: 0.75rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1050;
        }

        @media (min-width: 576px) {
            .navbar-landing {
                padding: 0.875rem 0;
            }
        }

        @media (min-width: 992px) {
            .navbar-landing {
                padding: 1rem 0;
            }
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            transition: all 0.3s ease;
        }

        @media (min-width: 576px) {
            .navbar-brand img {
                height: 60px;
            }
        }

        @media (min-width: 992px) {
            .navbar-brand img {
                height: 80px;
            }
        }

        /* Mejorar toggle en móvil */
        .navbar-toggler {
            padding: 0.5rem;
            border: none;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: var(--color-secondary);
            transition: all 0.3s ease;
            border-radius: 8px;
            z-index: 1060;
        }

        .navbar-toggler:hover {
            background: var(--color-light);
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        .navbar-toggler:active {
            background: var(--color-border);
        }

        .navbar-toggler i {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        /* Overlay para cerrar menú al tocar fuera */
        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .menu-overlay.show {
            display: block;
            opacity: 1;
        }

        /* ========== MENÚ MÓVIL HAMBURGUESA - REDISEÑO COMPLETO ========== */
        @media (max-width: 991px) {
            /* Contenedor principal del menú slide-in */
            .navbar-collapse {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                height: 100dvh; /* Dynamic viewport height para móviles */
                background: var(--color-white);
                box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
                z-index: 1055;
                overflow-y: auto;
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch;
                transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                
                /* Flexbox para organizar contenido verticalmente */
                display: flex !important;
                flex-direction: column;
                justify-content: flex-start;
                align-items: stretch;
                
                /* Padding con safe area */
                padding: 0;
                padding-top: env(safe-area-inset-top, 0px);
                padding-bottom: env(safe-area-inset-bottom, 0px);
                box-sizing: border-box;
            }

            .navbar-collapse.show {
                right: 0;
            }

            /* Header del menú con botón cerrar */
            .mobile-menu-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.25rem;
                border-bottom: 1px solid var(--color-border);
                flex-shrink: 0;
                min-height: 60px;
                background: var(--color-light);
            }

            .mobile-menu-header .menu-title {
                font-family: 'Playfair Display', serif;
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--color-secondary);
                margin: 0;
            }

            /* Botón cerrar (X) */
            .mobile-menu-close {
                width: 44px;
                height: 44px;
                min-width: 44px;
                min-height: 44px;
                border: none;
                background: var(--color-white);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--color-secondary);
                cursor: pointer;
                transition: all 0.2s ease;
                flex-shrink: 0;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            .mobile-menu-close:hover,
            .mobile-menu-close:active {
                background: var(--color-primary);
                color: var(--color-white);
            }

            .mobile-menu-close i {
                font-size: 1.25rem;
                line-height: 1;
            }

            /* Contenedor de navegación */
            .navbar-nav {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                padding: 1rem 1.25rem;
                margin: 0;
                list-style: none;
                flex: 1 1 auto;
                gap: 0.5rem;
            }

            /* Items de navegación */
            .nav-item {
                width: 100%;
                margin: 0;
            }

            /* Links de navegación */
            .nav-link-custom {
                display: flex !important;
                align-items: center;
                justify-content: flex-start;
                width: 100%;
                min-height: 52px;
                padding: 0.875rem 1rem !important;
                margin: 0;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 500;
                color: var(--color-secondary) !important;
                text-decoration: none;
                background: transparent;
                transition: all 0.2s ease;
                box-sizing: border-box;
            }

            .nav-link-custom:hover,
            .nav-link-custom:active {
                background: var(--color-light);
                color: var(--color-primary) !important;
            }

            .nav-link-custom.active {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
                color: var(--color-white) !important;
                font-weight: 600;
            }

            /* Contenedor de botones de acción */
            .navbar-collapse .d-flex.gap-2 {
                display: flex !important;
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                gap: 0.75rem !important;
                padding: 1.25rem;
                margin-top: auto;
                border-top: 1px solid var(--color-border);
                background: var(--color-light);
                flex-shrink: 0;
            }

            /* Botones dentro del menú */
            .navbar-collapse .d-flex.gap-2 .btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 100%;
                min-height: 50px;
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
                font-weight: 600;
                border-radius: 50px;
                text-align: center;
                box-sizing: border-box;
            }

            /* Mostrar botón de Registro en móvil */
            .navbar-collapse .d-none.d-lg-inline-block {
                display: flex !important;
            }

            /* Prevenir scroll del body cuando menú está abierto */
            body.menu-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
                height: 100%;
            }
        }

        .nav-link-custom {
            color: var(--color-secondary) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }

        @media (min-width: 992px) {
            .nav-link-custom {
                font-size: 1rem;
            }
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: var(--color-primary) !important;
        }

        .btn-primary-custom {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-white);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 576px) {
            .btn-primary-custom {
                padding: 0.5rem 1.25rem;
                font-size: 0.9rem;
            }
        }

        @media (min-width: 992px) {
            .btn-primary-custom {
                padding: 0.6rem 1.5rem;
                font-size: 1rem;
            }
        }

        .btn-primary-custom:hover {
            background-color: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
            color: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(160, 138, 122, 0.3);
        }

        .btn-outline-custom {
            background-color: transparent;
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 576px) {
            .btn-outline-custom {
                padding: 0.5rem 1.25rem;
                font-size: 0.9rem;
            }
        }

        @media (min-width: 992px) {
            .btn-outline-custom {
                padding: 0.6rem 1.5rem;
                font-size: 1rem;
            }
        }

        .btn-outline-custom:hover {
            background-color: var(--color-primary);
            color: var(--color-white);
        }

        /* ========== HERO SECTION ========== */
        .booking-hero {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 375px) {
            .booking-hero {
                padding: 2.25rem 0;
            }
        }

        @media (min-width: 576px) {
            .booking-hero {
                padding: 3rem 0;
            }
        }

        @media (min-width: 992px) {
            .booking-hero {
                padding: 4rem 0;
            }
        }

        .booking-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .booking-hero h1 {
            color: var(--color-white);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
        }

        @media (min-width: 375px) {
            .booking-hero h1 {
                font-size: 1.75rem;
                margin-bottom: 0.75rem;
            }
        }

        @media (min-width: 576px) {
            .booking-hero h1 {
                font-size: 2.25rem;
            }
        }

        @media (min-width: 992px) {
            .booking-hero h1 {
                font-size: 2.75rem;
                margin-bottom: 1rem;
            }
        }

        .booking-hero p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
            margin: 0;
            position: relative;
            line-height: 1.5;
        }

        @media (min-width: 375px) {
            .booking-hero p {
                font-size: 0.95rem;
            }
        }

        @media (min-width: 576px) {
            .booking-hero p {
                font-size: 1rem;
            }
        }

        @media (min-width: 992px) {
            .booking-hero p {
                font-size: 1.15rem;
            }
        }

        /* ========== MAIN CONTENT ========== */
        .booking-main {
            flex: 1;
            padding: 1.5rem 0;
            background: linear-gradient(180deg, var(--color-light) 0%, var(--color-base) 100%);
        }

        @media (min-width: 375px) {
            .booking-main {
                padding: 2rem 0;
            }
        }

        @media (min-width: 576px) {
            .booking-main {
                padding: 2.5rem 0;
            }
        }

        @media (min-width: 992px) {
            .booking-main {
                padding: 4rem 0;
            }
        }

        .booking-card {
            background: var(--color-white);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        @media (min-width: 576px) {
            .booking-card {
                border-radius: 20px;
                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            }
        }

        @media (min-width: 992px) {
            .booking-card {
                border-radius: 24px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            }
        }

        /* Steps indicator */
        .booking-steps-wrapper {
            background: var(--color-light);
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--color-border);
        }

        @media (min-width: 576px) {
            .booking-steps-wrapper {
                padding: 1.25rem 1.5rem;
            }
        }

        @media (min-width: 992px) {
            .booking-steps-wrapper {
                padding: 1.5rem 2rem;
            }
        }

        .booking-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0;
            max-width: 500px;
            margin: 0 auto;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-muted);
            font-size: 0.8rem;
            font-weight: 500;
        }

        @media (min-width: 576px) {
            .step-item {
                gap: 0.75rem;
                font-size: 0.9rem;
            }
        }

        .step-item.active {
            color: var(--color-primary);
        }

        .step-item.completed {
            color: var(--color-primary);
        }

        .step-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--color-white);
            border: 2px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
        }

        @media (min-width: 375px) {
            .step-number {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }
        }

        @media (min-width: 576px) {
            .step-number {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
        }

        .step-item.active .step-number {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-white);
            box-shadow: 0 4px 15px rgba(160, 138, 122, 0.4);
            transform: scale(1.1);
        }

        .step-item.completed .step-number {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-white);
        }

        /* Checkmark para steps completados */
        .step-item.completed .step-number::after {
            content: '✓';
            position: absolute;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .step-item.completed .step-number span {
            display: none;
        }

        /* Animación suave de pulse para step activo */
        @keyframes stepPulse {
            0%, 100% { box-shadow: 0 4px 15px rgba(160, 138, 122, 0.4); }
            50% { box-shadow: 0 4px 20px rgba(160, 138, 122, 0.6); }
        }

        .step-item.active .step-number {
            animation: stepPulse 2s ease-in-out infinite;
        }

        .step-divider {
            width: 30px;
            height: 2px;
            background: var(--color-border);
            margin: 0 0.5rem;
            border-radius: 3px;
            transition: background 0.3s ease;
        }

        @media (min-width: 375px) {
            .step-divider {
                width: 40px;
                height: 3px;
                margin: 0 0.75rem;
            }
        }

        @media (min-width: 576px) {
            .step-divider {
                width: 50px;
                margin: 0 1rem;
            }
        }

        @media (min-width: 992px) {
            .step-divider {
                width: 60px;
            }
        }

        .step-divider.active {
            background: var(--color-primary);
        }

        /* Ocultar texto de steps en móviles muy pequeños */
        @media (max-width: 374px) {
            .step-item span:not(.step-number) {
                display: none;
            }
        }

        /* ========== BOOKING BODY ========== */
        .booking-body {
            padding: 1rem;
        }

        @media (min-width: 375px) {
            .booking-body {
                padding: 1.25rem;
            }
        }

        @media (min-width: 576px) {
            .booking-body {
                padding: 1.5rem;
            }
        }

        @media (min-width: 768px) {
            .booking-body {
                padding: 2rem;
            }
        }

        @media (min-width: 992px) {
            .booking-body {
                padding: 3rem;
            }
        }

        .selection-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 375px) {
            .selection-grid {
                gap: 1.75rem;
            }
        }

        @media (min-width: 576px) {
            .selection-grid {
                gap: 2rem;
            }
        }

        @media (min-width: 992px) {
            .selection-grid {
                grid-template-columns: 1.6fr 1fr;
                gap: 3rem;
            }
        }

        /* ========== CALENDAR SECTION ========== */
        .calendar-section {
            background: var(--color-light);
            border-radius: 16px;
            padding: 1rem;
        }

        @media (min-width: 375px) {
            .calendar-section {
                padding: 1.25rem;
            }
        }

        @media (min-width: 576px) {
            .calendar-section {
                padding: 1.5rem;
                border-radius: 18px;
            }
        }

        @media (min-width: 992px) {
            .calendar-section {
                padding: 2rem;
                border-radius: 20px;
            }
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .section-header .icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .section-header h3 {
            font-size: 1rem;
            margin: 0;
            color: var(--color-secondary);
        }

        .section-header p {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            margin: 0;
            display: none;
        }

        @media (min-width: 375px) {
            .section-header p {
                display: block;
            }
        }

        @media (min-width: 576px) {
            .section-header {
                gap: 0.75rem;
                margin-bottom: 1.25rem;
            }
            .section-header .icon {
                width: 42px;
                height: 42px;
                font-size: 1.15rem;
                border-radius: 12px;
            }
            .section-header h3 {
                font-size: 1.1rem;
            }
            .section-header p {
                font-size: 0.8rem;
            }
        }

        @media (min-width: 992px) {
            .section-header {
                margin-bottom: 1.5rem;
            }
            .section-header .icon {
                width: 48px;
                height: 48px;
                font-size: 1.3rem;
                border-radius: 14px;
            }
            .section-header h3 {
                font-size: 1.25rem;
            }
            .section-header p {
                font-size: 0.85rem;
            }
        }

        .inline-calendar-wrapper {
            background: var(--color-white);
            border-radius: 12px;
            padding: 0.75rem;
            border: 1px solid var(--color-border);
        }

        @media (min-width: 375px) {
            .inline-calendar-wrapper {
                padding: 1rem;
                border-radius: 14px;
            }
        }

        @media (min-width: 576px) {
            .inline-calendar-wrapper {
                padding: 1.25rem;
                border-radius: 16px;
            }
        }

        @media (min-width: 992px) {
            .inline-calendar-wrapper {
                padding: 2rem;
                border-radius: 20px;
            }
        }

        /* ========== FLATPICKR CUSTOMIZATION ========== */
        .flatpickr-calendar {
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            width: 100% !important;
            font-family: 'Inter', sans-serif !important;
        }

        .flatpickr-innerContainer {
            width: 100%;
        }

        .flatpickr-rContainer {
            width: 100%;
        }

        .flatpickr-days {
            width: 100% !important;
        }

        .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .flatpickr-months {
            padding: 0 0.5rem 1rem;
        }

        .flatpickr-months .flatpickr-month {
            color: var(--color-secondary) !important;
            fill: var(--color-secondary) !important;
            height: 45px;
        }

        .flatpickr-current-month {
            font-size: 1.1rem;
            font-weight: 600;
            padding-top: 6px;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 600 !important;
            font-family: 'Playfair Display', serif !important;
            font-size: 1.1rem;
        }

        @media (min-width: 576px) {
            .flatpickr-months .flatpickr-month {
                height: 50px;
            }
            .flatpickr-current-month {
                font-size: 1.25rem;
                padding-top: 8px;
            }
            .flatpickr-current-month .flatpickr-monthDropdown-months {
                font-size: 1.25rem;
            }
        }

        @media (min-width: 992px) {
            .flatpickr-months .flatpickr-month {
                height: 60px;
            }
            .flatpickr-current-month {
                font-size: 1.5rem;
                padding-top: 10px;
            }
            .flatpickr-current-month .flatpickr-monthDropdown-months {
                font-size: 1.5rem;
            }
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            padding: 12px;
        }

        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: var(--color-primary);
        }

        .flatpickr-weekdays {
            margin-top: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--color-border);
            width: 100%;
        }

        .flatpickr-weekday {
            color: var(--color-primary) !important;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            flex-basis: 14.2857%;
            max-width: none;
        }

        @media (min-width: 576px) {
            .flatpickr-weekdays {
                margin-top: 0.75rem;
                padding: 0.75rem 0;
            }
            .flatpickr-weekday {
                font-size: 0.85rem;
            }
        }

        @media (min-width: 992px) {
            .flatpickr-weekday {
                font-size: 0.95rem;
            }
        }

        .flatpickr-day {
            border-radius: 10px;
            font-weight: 500;
            color: var(--color-secondary);
            transition: all 0.2s ease;
            height: 40px;
            line-height: 38px;
            font-size: 0.9rem;
            margin: 0;
            max-width: none;
            flex-basis: 14.2857%;
            box-sizing: border-box;
        }

        /* Móvil pequeño (320px - 374px) */
        @media (min-width: 375px) {
            .flatpickr-day {
                height: 44px;
                line-height: 42px;
                font-size: 0.95rem;
            }
        }

        /* Móvil grande (375px - 575px) */
        @media (min-width: 576px) {
            .flatpickr-day {
                height: 50px;
                line-height: 48px;
                font-size: 1rem;
                border-radius: 12px;
            }
        }

        /* Tablet (768px+) */
        @media (min-width: 768px) {
            .flatpickr-day {
                height: 52px;
                line-height: 50px;
                font-size: 1.05rem;
            }
        }

        /* Desktop (992px+) */
        @media (min-width: 992px) {
            .flatpickr-day {
                height: 48px;
                line-height: 46px;
                font-size: 1rem;
                border-radius: 12px;
            }
        }

        .flatpickr-day:hover {
            background: var(--color-light) !important;
            border-color: var(--color-primary) !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected.today {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%) !important;
            border-color: var(--color-primary) !important;
            color: var(--color-white) !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3);
        }

        /* Día actual sin seleccionar */
        .flatpickr-day.today:not(.selected) {
            border: 2px solid var(--color-primary) !important;
            background: var(--color-light) !important;
            color: var(--color-primary) !important;
            font-weight: 700;
        }

        .flatpickr-day.flatpickr-disabled {
            color: var(--color-border) !important;
        }

        /* Dot para días con disponibilidad */
        .flatpickr-day.has-availability { 
            position: relative; 
        }

        .flatpickr-day.has-availability::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background: var(--color-primary);
            border-radius: 50%;
        }

        @media (min-width: 576px) {
            .flatpickr-day.has-availability::after {
                bottom: 6px;
                width: 6px;
                height: 6px;
            }
        }

        @media (min-width: 992px) {
            .flatpickr-day.has-availability::after {
                bottom: 8px;
                width: 8px;
                height: 8px;
            }
        }

        .flatpickr-day.selected.has-availability::after {
            background: var(--color-white);
        }

        /* Ocultar días otros meses */
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            visibility: hidden !important;
        }

        /* ========== TIME SECTION ========== */
        .time-section {
            background: var(--color-light);
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 375px) {
            .time-section {
                padding: 1.25rem;
            }
        }

        @media (min-width: 576px) {
            .time-section {
                padding: 1.5rem;
                border-radius: 18px;
            }
        }

        @media (min-width: 992px) {
            .time-section {
                padding: 2rem;
                border-radius: 20px;
            }
        }

        .time-selection {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: none;
            overflow-y: visible;
            padding-right: 0;
        }

        @media (min-width: 576px) {
            .time-selection {
                gap: 1.25rem;
            }
        }

        @media (min-width: 992px) {
            .time-selection {
                gap: 1.5rem;
                max-height: 500px;
                overflow-y: auto;
                padding-right: 0.5rem;
            }
        }

        .time-selection::-webkit-scrollbar {
            width: 6px;
        }

        .time-selection::-webkit-scrollbar-track {
            background: var(--color-border);
            border-radius: 6px;
        }

        .time-selection::-webkit-scrollbar-thumb {
            background: var(--color-primary);
            border-radius: 6px;
        }

        .time-period {
            background: var(--color-white);
            border-radius: 12px;
            padding: 0.875rem;
            border: 1px solid var(--color-border);
        }

        @media (min-width: 375px) {
            .time-period {
                padding: 1rem;
            }
        }

        @media (min-width: 576px) {
            .time-period {
                padding: 1.125rem;
                border-radius: 14px;
            }
        }

        @media (min-width: 992px) {
            .time-period {
                padding: 1.25rem;
                border-radius: 16px;
            }
        }

        .time-period-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--color-text-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .time-period-label i {
            font-size: 0.95rem;
            color: var(--color-primary);
        }

        @media (min-width: 576px) {
            .time-period-label {
                font-size: 0.75rem;
                letter-spacing: 0.75px;
                margin-bottom: 0.875rem;
                gap: 0.5rem;
            }
            .time-period-label i {
                font-size: 1rem;
            }
        }

        @media (min-width: 992px) {
            .time-period-label {
                font-size: 0.8rem;
                letter-spacing: 1px;
                margin-bottom: 1rem;
            }
            .time-period-label i {
                font-size: 1.1rem;
            }
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        @media (min-width: 375px) {
            .time-slots {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.5rem;
            }
        }

        @media (min-width: 576px) {
            .time-slots {
                gap: 0.625rem;
            }
        }

        @media (min-width: 992px) {
            .time-slots {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
        }

        .time-slot {
            background: var(--color-light);
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 0.625rem 0.375rem;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            color: var(--color-secondary);
            /* Mejorar touch target para móvil */
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 375px) {
            .time-slot {
                font-size: 0.85rem;
                padding: 0.75rem 0.5rem;
            }
        }

        @media (min-width: 576px) {
            .time-slot {
                font-size: 0.9rem;
                border-radius: 11px;
            }
        }

        @media (min-width: 992px) {
            .time-slot {
                font-size: 0.95rem;
                padding: 0.875rem 0.5rem;
                border-radius: 12px;
            }
        }

        .time-slot:hover {
            border-color: var(--color-primary);
            background: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.15);
        }

        .time-slot.selected {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            border-color: var(--color-primary);
            color: var(--color-white);
            box-shadow: 0 6px 20px rgba(160, 138, 122, 0.4);
            transform: translateY(-2px);
        }

        /* No slots message */
        .no-slots-message {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--color-text-muted);
            background: var(--color-white);
            border-radius: 16px;
            border: 2px dashed var(--color-border);
        }

        .no-slots-message i {
            font-size: 3rem;
            color: var(--color-border);
            margin-bottom: 1rem;
            display: block;
        }

        .no-slots-message p {
            margin: 0;
            font-size: 0.95rem;
        }

        /* Loading */
        .loading-spinner {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 3px solid var(--color-border);
            border-top-color: var(--color-primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Form Hidden */
        #client-form {
            display: none !important;
        }

        /* ========== MODAL ========== */
        .mobile-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(52, 52, 52, 0.7);
            backdrop-filter: blur(8px);
            z-index: 1050;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .mobile-modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--color-white);
            border-radius: 24px;
            padding: 0;
            width: 100%;
            max-width: 480px;
            max-height: 90vh;
            overflow: hidden;
            position: relative;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.35s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header-custom {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            padding: 2rem 2.5rem;
            text-align: center;
            position: relative;
        }

        .modal-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .modal-header-custom h5 {
            color: var(--color-white);
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .modal-header-custom p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin: 0;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            font-size: 1.25rem;
            color: var(--color-white);
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
            z-index: 1;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body-custom {
            padding: 2rem 2.5rem 2.5rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Appointment info card */
        .appointment-info-card {
            background: var(--color-light);
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--color-border);
        }

        .appointment-info-card .icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .appointment-info-card .details {
            flex: 1;
        }

        .appointment-info-card .details .date {
            font-weight: 700;
            color: var(--color-secondary);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .appointment-info-card .details .time {
            color: var(--color-primary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Modal form */
        .modal-form .form-group {
            margin-bottom: 1.25rem;
        }

        .modal-form .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--color-secondary);
            margin-bottom: 0.5rem;
            display: block;
        }

        .modal-form .form-control {
            border: 2px solid var(--color-border);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: var(--color-light);
        }

        .modal-form .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(160, 138, 122, 0.15);
            outline: none;
            background: var(--color-white);
        }

        .modal-form .form-control::placeholder {
            color: var(--color-text-muted);
        }

        .btn-confirm {
            background: linear-gradient(135deg, var(--color-secondary) 0%, #454545 100%);
            color: var(--color-white);
            border: none;
            padding: 1.125rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1rem;
        }

        .btn-confirm:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(160, 138, 122, 0.35);
        }

        .btn-confirm:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Modal responsive - Móvil pequeño (320px) */
        @media (max-width: 374px) {
            .mobile-modal {
                padding: 0.5rem;
            }
            .modal-content {
                max-width: 100%;
                border-radius: 16px;
                max-height: 95vh;
            }
            .modal-header-custom {
                padding: 1rem 1rem 1.25rem;
            }
            .modal-header-custom h5 {
                font-size: 1.25rem;
            }
            .modal-header-custom p {
                font-size: 0.85rem;
            }
            .modal-close {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                top: 0.75rem;
                right: 0.75rem;
            }
            .modal-body-custom {
                padding: 1rem;
                max-height: 65vh;
            }
            .appointment-info-card {
                padding: 0.875rem;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
            }
            .appointment-info-card .icon {
                width: 44px;
                height: 44px;
                font-size: 1.25rem;
                border-radius: 10px;
            }
            .appointment-info-card .details .date {
                font-size: 0.9rem;
            }
            .appointment-info-card .details .time {
                font-size: 0.8rem;
            }
            .modal-form .form-group {
                margin-bottom: 1rem;
            }
            .modal-form .form-label {
                font-size: 0.7rem;
            }
            .modal-form .form-control {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }
            .btn-confirm {
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
                gap: 0.5rem;
            }
        }

        /* Modal responsive - Móvil mediano (375px - 767px) */
        @media (min-width: 375px) and (max-width: 767px) {
            .modal-content {
                max-width: 95%;
                border-radius: 20px;
            }
            .modal-header-custom {
                padding: 1.25rem 1.5rem 1.5rem;
            }
            .modal-header-custom h5 {
                font-size: 1.4rem;
            }
            .modal-body-custom {
                padding: 1.25rem;
            }
        }

        /* ========== FOOTER ========== */
        .footer {
            background-color: var(--color-secondary);
            color: var(--color-white);
            padding: 2rem 0 1rem;
        }

        @media (min-width: 576px) {
            .footer {
                padding: 2.5rem 0 1.25rem;
            }
        }

        @media (min-width: 992px) {
            .footer {
                padding: 3rem 0 1.5rem;
            }
        }

        .footer h5 {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-white);
        }

        @media (min-width: 576px) {
            .footer h5 {
                font-size: 0.9rem;
                margin-bottom: 1.25rem;
            }
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.375rem;
        }

        @media (min-width: 576px) {
            .footer-links li {
                margin-bottom: 0.5rem;
            }
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.8rem;
            display: inline-block;
            padding: 0.125rem 0;
        }

        @media (min-width: 576px) {
            .footer-links a {
                font-size: 0.9rem;
                padding: 0.25rem 0;
            }
        }

        .footer-links a:hover {
            color: var(--color-primary);
        }

        .social-icons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
        }

        @media (min-width: 576px) {
            .social-icons {
                gap: 0.5rem;
            }
        }

        .social-icons a {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            margin-right: 0;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        @media (min-width: 576px) {
            .social-icons a {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }
        }

        .social-icons a:hover {
            background: var(--color-primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            margin-top: 1.5rem;
        }

        @media (min-width: 576px) {
            .footer-bottom {
                padding-top: 1.25rem;
                margin-top: 1.75rem;
            }
        }

        @media (min-width: 992px) {
            .footer-bottom {
                padding-top: 1.5rem;
                margin-top: 2rem;
            }
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0;
            font-size: 0.75rem;
        }

        @media (min-width: 576px) {
            .footer-bottom p {
                font-size: 0.85rem;
            }
        }

        /* Safe area para dispositivos con notch */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .footer {
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
        }

        .social-icons a:hover {
            background: var(--color-primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        /* ========== CHECK APPOINTMENT SECTION ========== */
        .check-appointment-card {
            background: var(--color-white);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--color-border);
        }

        @media (min-width: 576px) {
            .check-appointment-card {
                padding: 25px;
            }
        }

        .check-appointment-card h4 {
            font-size: 1rem;
            color: var(--color-secondary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .check-appointment-card h4 i {
            color: var(--color-primary);
        }

        .check-appointment-card p {
            font-size: 0.85rem;
            color: var(--color-text-muted);
            margin-bottom: 15px;
        }

        .check-email-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .check-email-form input {
            flex: 1;
            min-width: 200px;
            padding: 10px 14px;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .check-email-form input:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        .check-email-form button {
            padding: 10px 20px;
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .check-email-form button:hover {
            background: var(--color-primary-dark);
        }

        .check-email-form button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .existing-appointment-info {
            display: none;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid var(--color-primary);
        }

        .existing-appointment-info.show {
            display: block;
        }

        .existing-appointment-info .appointment-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .existing-appointment-info .appointment-detail:last-of-type {
            margin-bottom: 15px;
        }

        .existing-appointment-info .appointment-detail i {
            color: var(--color-primary);
            font-size: 1.1rem;
        }

        .existing-appointment-info .appointment-detail span {
            font-size: 0.9rem;
            color: var(--color-secondary);
        }

        .btn-cancel-appointment {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-cancel-appointment:hover {
            background: #c82333;
            color: white;
        }

        .no-appointment-msg {
            display: none;
            background: #d4edda;
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 15px;
            color: #155724;
            font-size: 0.9rem;
        }

        .no-appointment-msg.show {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .no-appointment-msg i {
            font-size: 1.1rem;
        }

        /* ========== CLOUDFLARE TURNSTILE ========== */
        .turnstile-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 4px;
        }

        .turnstile-wrapper iframe {
            max-width: 100% !important;
        }

        #turnstile-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .turnstile-error {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 6px;
            margin-bottom: 0;
            text-align: center;
        }

        /* Ajuste en móviles pequeños para que el widget no desborde */
        @media (max-width: 374px) {
            #turnstile-container {
                transform: scale(0.85);
                transform-origin: center;
            }
        }
    </style>
</head>
<body>

    <!-- ========== OVERLAY PARA MENÚ MÓVIL ========== -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- ========== NAVBAR ========== -->
    <nav class="navbar navbar-expand-lg navbar-landing">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logos/edit.png') }}" alt="MerStyleHub">
            </a>
            <button class="navbar-toggler" type="button" id="navbarToggler" aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menú de navegación">
                <i class="ti ti-menu-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Header del menú móvil -->
                <div class="mobile-menu-header d-lg-none">
                    <span class="menu-title">Menú</span>
                    <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Cerrar menú">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <ul class="navbar-nav ms-auto me-lg-4">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ url('/') }}#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ url('/') }}#nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="{{ route('calendar.index') }}">Reservar Cita</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-custom">Iniciar Sesión</a>
                    
                </div>
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION ========== -->
    <section class="booking-hero">
        <div class="container text-center">
            <h1>{{ __('Reserva tu Cita') }}</h1>
            <p>{{ __('Elige el día y la hora que mejor te convenga para tu sesión personalizada') }}</p>
        </div>
    </section>

    <!-- ========== MAIN BOOKING SECTION ========== -->
    <main class="booking-main">
        <div class="container">
            <!-- Check Existing Appointment Card -->
            <div class="check-appointment-card">
                <h4><i class="ti ti-calendar-search"></i> ¿Ya tienes una cita reservada?</h4>
                <p>Introduce tu email para consultar o cancelar tu cita existente</p>
                <div class="check-email-form">
                    <input type="email" id="check-email-input" placeholder="tu@email.com">
                    <button type="button" id="check-email-btn">
                        <i class="ti ti-search"></i>
                        Consultar
                    </button>
                </div>
                <div class="existing-appointment-info" id="existing-appointment-info">
                    <div class="appointment-detail">
                        <i class="ti ti-calendar"></i>
                        <span id="existing-date">--</span>
                    </div>
                    <div class="appointment-detail">
                        <i class="ti ti-clock"></i>
                        <span id="existing-time">--</span>
                    </div>
                    <a href="#" class="btn-cancel-appointment" id="btn-cancel-existing">
                        <i class="ti ti-calendar-x"></i>
                        Cancelar cita
                    </a>
                </div>
                <div class="no-appointment-msg" id="no-appointment-msg">
                    <i class="ti ti-check"></i>
                    <span>No tienes ninguna cita reservada. ¡Puedes reservar una nueva!</span>
                </div>
            </div>

            <div class="booking-card">
                <!-- Steps -->
                <div class="booking-steps-wrapper">
                    <div class="booking-steps">
                        <div class="step-item active" id="step-1">
                            <span class="step-number"><span>1</span></span>
                            <span>Fecha</span>
                        </div>
                        <div class="step-divider" id="divider-1"></div>
                        <div class="step-item" id="step-2">
                            <span class="step-number"><span>2</span></span>
                            <span>Hora</span>
                        </div>
                        <div class="step-divider" id="divider-2"></div>
                        <div class="step-item" id="step-3">
                            <span class="step-number"><span>3</span></span>
                            <span>Datos</span>
                        </div>
                    </div>
                </div>

                <!-- Booking Body -->
                <div class="booking-body">
                    <div class="selection-grid">
                        <!-- Calendar Section -->
                        <div class="calendar-section">
                            <div class="section-header">
                                <div class="icon">
                                    <i class="ti ti-calendar"></i>
                                </div>
                                <div>
                                    <h3>{{ __('Selecciona una fecha') }}</h3>
                                    <p>{{ __('Los días con punto tienen disponibilidad') }}</p>
                                </div>
                            </div>
                            <div class="inline-calendar-wrapper">
                                <div id="calendar"></div>
                            </div>
                        </div>
                        
                        <!-- Time Section -->
                        <div class="time-section">
                            <div class="section-header">
                                <div class="icon">
                                    <i class="ti ti-clock"></i>
                                </div>
                                <div>
                                    <h3>{{ __('Elige tu horario') }}</h3>
                                    <p>{{ __('Horarios disponibles para el día seleccionado') }}</p>
                                </div>
                            </div>
                            
                            <div class="time-selection" id="time-selection-container">
                                <div id="morning-section" style="display: none;">
                                    <div class="time-period">
                                        <p class="time-period-label">
                                            <i class="ti ti-sun"></i> {{ __('Mañana') }}
                                        </p>
                                        <div class="time-slots" id="slots-morning"></div>
                                    </div>
                                </div>

                                <div id="afternoon-section" style="display: none;">
                                    <div class="time-period">
                                        <p class="time-period-label">
                                            <i class="ti ti-sunset-2"></i> {{ __('Tarde') }}
                                        </p>
                                        <div class="time-slots" id="slots-afternoon"></div>
                                    </div>
                                </div>

                                <div id="no-slots-msg" class="no-slots-message">
                                    <i class="ti ti-calendar-event"></i>
                                    <p>{{ __('Selecciona una fecha en el calendario para ver los horarios disponibles') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="client-form" class="row g-3">
                    @csrf
                </form>
            </div>
        </div>
    </main>

    <!-- ========== MODAL ========== -->
    <div class="mobile-modal" id="mobile-modal">
        <div class="modal-content">
            <button class="modal-close" id="modal-close">
                <i class="ti ti-x"></i>
            </button>
            
            <div class="modal-header-custom">
                <h5>{{ __('Completar Reserva') }}</h5>
                <p>{{ __('Un paso más para confirmar tu cita') }}</p>
            </div>
            
            <div class="modal-body-custom">
                <!-- Info de la cita seleccionada -->
                <div class="appointment-info-card">
                    <div class="icon">
                        <i class="ti ti-calendar-check"></i>
                    </div>
                    <div class="details">
                        <div class="date" id="selected-date-info">--</div>
                        <div class="time" id="selected-time-info">--</div>
                    </div>
                </div>

                <form id="mobile-client-form" class="modal-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">{{ __('Nombre completo') }} *</label>
                        <input type="text" name="client_name" class="form-control" placeholder="Tu nombre y apellidos" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Email') }} *</label>
                        <input type="email" name="client_email" class="form-control" placeholder="tu@email.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Teléfono') }}</label>
                        <input type="tel" name="client_phone" class="form-control" placeholder="+34 600 000 000">
                    </div>

                    <!-- Cloudflare Turnstile -->
                    <div class="form-group turnstile-wrapper">
                        <div id="turnstile-container"></div>
                        <p class="turnstile-error" id="turnstile-error" style="display: none;">Por favor, completa la verificación de seguridad</p>
                    </div>

                    <button type="button" class="btn-confirm" id="mobile-btn-confirm">
                        <i class="ti ti-check"></i>
                        {{ __('Confirmar Reserva') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== FOOTER ========== -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <img src="{{ asset('images/logos/edit.png') }}" alt="MerStyleHub" height="80" class="mb-3">
                    <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.7; font-size: 0.9rem;">
                        Tu aliada en el camino hacia una imagen auténtica y un estilo que te represente.
                    </p>
                    <div class="social-icons mt-3">
                        <a href="https://www.instagram.com/merstylehub?igsh=MW9vYnR6YWxhY2wwZQ=="><i class="ti ti-brand-instagram"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h5>Servicios</h5>
                    <ul class="footer-links">
                        <li><a href="#">Colorimetría</a></li>
                        <li><a href="#">Asesoría de Imagen</a></li>
                        <li><a href="#">Personal Shopper</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5>Enlaces</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#servicios">Servicios</a></li>
                        <li><a href="{{ url('/') }}#nosotros">Nosotros</a></li>
                        <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <h5>Contacto</h5>
                    <ul class="footer-links">
                        <li>
                            <i class="ti ti-mail me-2" style="color: var(--color-primary);"></i>
                            <a href="mailto:hola@merstylehub.com">info@merstylehub.es</a>
                        </li>
                        
                        <li>
                            <i class="ti ti-map-pin me-2" style="color: var(--color-primary);"></i>
                            <span style="color: rgba(255, 255, 255, 0.7);">Palma, España</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p>© {{ date('Y') }} MerStyleHub. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- ========== SCRIPTS ========== -->
    <script>
        // Flag global para saber cuándo Turnstile está listo
        var turnstileReady = false;
        var turnstilePendingInit = false;
        function onTurnstileLoad() {
            turnstileReady = true;
            // Si el modal ya estaba abierto esperando, inicializar ahora
            if (turnstilePendingInit) {
                turnstilePendingInit = false;
                if (typeof initTurnstileWidget === 'function') {
                    initTurnstileWidget();
                }
            }
        }
    </script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=onTurnstileLoad" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== ELEMENTOS DEL DOM (cachear referencias) ==========
            const DOM = {
                slotsMorning: document.getElementById('slots-morning'),
                slotsAfternoon: document.getElementById('slots-afternoon'),
                morningSec: document.getElementById('morning-section'),
                afternoonSec: document.getElementById('afternoon-section'),
                noSlotsMsg: document.getElementById('no-slots-msg'),
                mobileModal: document.getElementById('mobile-modal'),
                mobileClientForm: document.getElementById('mobile-client-form'),
                mobileBtnConfirm: document.getElementById('mobile-btn-confirm'),
                modalClose: document.getElementById('modal-close'),
                selectedDateInfo: document.getElementById('selected-date-info'),
                selectedTimeInfo: document.getElementById('selected-time-info'),
                step1: document.getElementById('step-1'),
                step2: document.getElementById('step-2'),
                step3: document.getElementById('step-3'),
                divider1: document.getElementById('divider-1'),
                divider2: document.getElementById('divider-2')
            };

            // ========== ESTADO DE LA APLICACIÓN ==========
            const state = {
                selectedDate: null,
                selectedSlot: null,
                availableDates: new Set(), // Set para búsqueda O(1)
                slotsCache: new Map(),     // Caché de slots por fecha
                isLoadingSlots: false
            };

            // ========== CONFIGURACIÓN ==========
            const CONFIG = {
                CACHE_DURATION: 5 * 60 * 1000, // 5 minutos de caché
                CSRF_TOKEN: '{{ csrf_token() }}'
            };

            // ========== UTILIDADES ==========
            const formatDate = (date) => {
                const d = date instanceof Date ? date : new Date(date);
                return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            };

            const todayStr = formatDate(new Date());

            // ========== ACTUALIZAR PASOS ==========
            function updateSteps(currentStep) {
                const { step1, step2, step3, divider1, divider2 } = DOM;
                
                // Reset rápido
                step1.className = step2.className = step3.className = 'step-item';
                divider1.className = divider2.className = 'step-divider';

                if (currentStep >= 1) step1.classList.add(currentStep > 1 ? 'completed' : 'active');
                if (currentStep >= 2) {
                    step2.classList.add(currentStep > 2 ? 'completed' : 'active');
                    divider1.classList.add('active');
                }
                if (currentStep >= 3) {
                    step3.classList.add('active');
                    divider2.classList.add('active');
                }
            }

            // ========== CARGAR SLOTS CON CACHÉ ==========
            async function loadSlots(date) {
                if (state.isLoadingSlots) return;
                
                // Verificar caché
                const cached = state.slotsCache.get(date);
                if (cached && (Date.now() - cached.timestamp < CONFIG.CACHE_DURATION)) {
                    renderSlots(cached.slots);
                    return;
                }

                state.isLoadingSlots = true;
                showLoading();

                try {
                    const res = await fetch(`/calendar/available-slots/${date}`);
                    if (!res.ok) throw new Error('Network error');
                    
                    const slots = await res.json();
                    
                    // Guardar en caché
                    state.slotsCache.set(date, { slots, timestamp: Date.now() });
                    
                    renderSlots(slots);
                } catch (e) {
                    showError();
                    console.error('Error loading slots:', e);
                } finally {
                    state.isLoadingSlots = false;
                }
            }

            // ========== MOSTRAR ESTADOS ==========
            function showLoading() {
                DOM.noSlotsMsg.innerHTML = '<span class="loading-spinner"></span> Cargando...';
                DOM.noSlotsMsg.style.display = 'block';
                DOM.morningSec.style.display = 'none';
                DOM.afternoonSec.style.display = 'none';
            }

            function showError() {
                DOM.noSlotsMsg.innerHTML = '<i class="ti ti-alert-circle"></i><p>Error al cargar. Inténtalo de nuevo.</p>';
            }

            function showNoSlots() {
                DOM.noSlotsMsg.innerHTML = '<i class="ti ti-calendar-off"></i><p>No hay horarios disponibles para este día.</p>';
            }

            // ========== RENDERIZAR SLOTS (optimizado) ==========
            function renderSlots(slots) {
                if (!slots || slots.length === 0) {
                    showNoSlots();
                    return;
                }

                DOM.noSlotsMsg.style.display = 'none';
                
                // Usar DocumentFragment para mejor rendimiento
                const morningFrag = document.createDocumentFragment();
                const afternoonFrag = document.createDocumentFragment();
                
                let hasMorning = false, hasAfternoon = false;

                slots.forEach(slot => {
                    const hour = parseInt(slot.start.split(':')[0]);
                    const el = createSlotElement(slot);
                    
                    if (hour < 14) {
                        morningFrag.appendChild(el);
                        hasMorning = true;
                    } else {
                        afternoonFrag.appendChild(el);
                        hasAfternoon = true;
                    }
                });

                // Limpiar y añadir de una sola vez
                DOM.slotsMorning.innerHTML = '';
                DOM.slotsAfternoon.innerHTML = '';
                
                if (hasMorning) {
                    DOM.slotsMorning.appendChild(morningFrag);
                    DOM.morningSec.style.display = 'block';
                } else {
                    DOM.morningSec.style.display = 'none';
                }
                
                if (hasAfternoon) {
                    DOM.slotsAfternoon.appendChild(afternoonFrag);
                    DOM.afternoonSec.style.display = 'block';
                } else {
                    DOM.afternoonSec.style.display = 'none';
                }
            }

            // ========== CREAR ELEMENTO SLOT ==========
            function createSlotElement(slot) {
                const el = document.createElement('div');
                el.className = 'time-slot';
                el.textContent = slot.start;
                el.dataset.availabilityId = slot.availability_id;
                el.dataset.start = slot.start;
                el.dataset.end = slot.end;
                
                el.onclick = () => selectSlot(el, slot);
                return el;
            }

            // ========== SELECCIONAR SLOT ==========
            function selectSlot(element, slot) {
                // Deseleccionar anterior
                const prev = document.querySelector('.time-slot.selected');
                if (prev) prev.classList.remove('selected');
                
                element.classList.add('selected');
                state.selectedSlot = slot;
                updateSteps(3);

                // Formatear fecha
                const dateObj = new Date(state.selectedDate + 'T12:00:00');
                const dateFormatted = dateObj.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                DOM.selectedDateInfo.textContent = dateFormatted.charAt(0).toUpperCase() + dateFormatted.slice(1);
                DOM.selectedTimeInfo.textContent = `a las ${slot.start}`;
                DOM.mobileModal.classList.add('show');
                document.body.style.overflow = 'hidden';

                // Inicializar/resetear Turnstile
                initTurnstile();
            }

            // ========== MODAL HANDLERS ==========
            function closeModal() {
                DOM.mobileModal.classList.remove('show');
                document.body.style.overflow = '';
                updateSteps(2);
            }

            DOM.modalClose.onclick = closeModal;

            DOM.mobileModal.onclick = (e) => {
                if (e.target === DOM.mobileModal) closeModal();
            };

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && DOM.mobileModal.classList.contains('show')) closeModal();
            });

            // ========== CLOUDFLARE TURNSTILE ==========
            let turnstileWidgetId = null;
            let turnstileToken = null;

            // Función global accesible desde el callback onload
            window.initTurnstileWidget = function() {
                if (!turnstileReady || typeof turnstile === 'undefined') {
                    // Marcar como pendiente; se ejecutará cuando onTurnstileLoad() dispare
                    turnstilePendingInit = true;
                    return;
                }
                try {
                    if (turnstileWidgetId !== null) {
                        turnstile.reset(turnstileWidgetId);
                        return;
                    }
                    turnstileWidgetId = turnstile.render('#turnstile-container', {
                        sitekey: '{{ config("services.turnstile.site_key") }}',
                        theme: 'light',
                        callback: function(token) {
                            turnstileToken = token;
                            document.getElementById('turnstile-error').style.display = 'none';
                        },
                        'expired-callback': function() {
                            turnstileToken = null;
                        },
                        'error-callback': function(errorCode) {
                            console.warn('Turnstile error:', errorCode);
                            turnstileToken = null;
                        }
                    });
                } catch (e) {
                    console.error('Turnstile render error:', e);
                }
            };

            function initTurnstile() {
                window.initTurnstileWidget();
            }

            // ========== CONFIRMAR RESERVA ==========
            async function confirmBooking(form, button) {
                const formData = new FormData(form);
                if (!formData.get('client_name') || !formData.get('client_email')) {
                    return Swal.fire({
                        title: 'Campos requeridos',
                        text: 'Por favor completa tu nombre y email',
                        icon: 'warning',
                        confirmButtonColor: '#A08A7A'
                    });
                }

                // Verificar Turnstile
                if (!turnstileToken) {
                    document.getElementById('turnstile-error').style.display = 'block';
                    return Swal.fire({
                        title: 'Verificación requerida',
                        text: 'Por favor completa la verificación de seguridad antes de continuar.',
                        icon: 'warning',
                        confirmButtonColor: '#A08A7A'
                    });
                }

                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="loading-spinner" style="margin-right: 0; width: 20px; height: 20px;"></span> Reservando...';

                try {
                    const res = await fetch('/calendar/book', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CONFIG.CSRF_TOKEN,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            availability_id: state.selectedSlot.availability_id,
                            date: state.selectedDate,
                            start_time: state.selectedSlot.start,
                            client_name: formData.get('client_name'),
                            client_email: formData.get('client_email'),
                            client_phone: formData.get('client_phone'),
                            'cf-turnstile-response': turnstileToken
                        })
                    });
                    const data = await res.json();

                    if (data.success) {
                        closeModal();
                        // Invalidar caché de la fecha reservada
                        state.slotsCache.delete(state.selectedDate);
                        
                        Swal.fire({
                            title: '¡Reserva Confirmada!',
                            text: 'Te hemos enviado un email con los detalles de tu cita. ¡Te esperamos!',
                            icon: 'success',
                            confirmButtonColor: '#A08A7A',
                            confirmButtonText: 'Perfecto'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'No se pudo completar la reserva',
                            icon: 'error',
                            confirmButtonColor: '#A08A7A'
                        });
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                } catch (e) {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la reserva.',
                        icon: 'error',
                        confirmButtonColor: '#A08A7A'
                    });
                } finally {
                    // Resetear Turnstile para siguiente intento
                    turnstileToken = null;
                    if (turnstileWidgetId !== null && typeof turnstile !== 'undefined') {
                        turnstile.reset(turnstileWidgetId);
                    }
                }
            }

            DOM.mobileBtnConfirm.onclick = () => confirmBooking(DOM.mobileClientForm, DOM.mobileBtnConfirm);

            // ========== INICIALIZAR CALENDARIO ==========
            async function initCalendar() {
                // Cargar fechas disponibles y slots del día actual en PARALELO
                const [datesRes, slotsRes] = await Promise.all([
                    fetch('/calendar/available-dates').then(r => r.json()).catch(() => []),
                    fetch(`/calendar/available-slots/${todayStr}`).then(r => r.json()).catch(() => [])
                ]);

                // Guardar fechas en Set para búsqueda O(1)
                state.availableDates = new Set(datesRes);
                
                // Cachear slots del día actual
                state.slotsCache.set(todayStr, { slots: slotsRes, timestamp: Date.now() });

                // Inicializar Flatpickr
                flatpickr("#calendar", {
                    inline: true,
                    locale: "es",
                    minDate: "today",
                    defaultDate: todayStr,
                    dateFormat: "Y-m-d",
                    monthSelectorType: "static",
                    prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                    nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                    onDayCreate: (dObj, dStr, fp, dayElem) => {
                        const dateStr = formatDate(dayElem.dateObj);
                        if (state.availableDates.has(dateStr)) {
                            dayElem.classList.add('has-availability');
                        }
                    },
                    onChange: (selectedDates, dateStr) => {
                        state.selectedDate = dateStr;
                        state.selectedSlot = null;
                        updateSteps(2);
                        loadSlots(dateStr);
                    },
                    onReady: () => {
                        // Renderizar slots del día actual inmediatamente (ya los tenemos)
                        state.selectedDate = todayStr;
                        updateSteps(2);
                        renderSlots(slotsRes);
                    }
                });
            }

            // ========== INICIAR APLICACIÓN ==========
            initCalendar();

            // ========== CHECK EXISTING APPOINTMENT ==========
            const checkEmailInput = document.getElementById('check-email-input');
            const checkEmailBtn = document.getElementById('check-email-btn');
            const existingAppointmentInfo = document.getElementById('existing-appointment-info');
            const noAppointmentMsg = document.getElementById('no-appointment-msg');
            const existingDate = document.getElementById('existing-date');
            const existingTime = document.getElementById('existing-time');
            const btnCancelExisting = document.getElementById('btn-cancel-existing');

            checkEmailBtn.addEventListener('click', async function() {
                const email = checkEmailInput.value.trim();
                if (!email) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Email requerido',
                        text: 'Por favor, introduce tu email',
                        confirmButtonColor: '#A08A7A'
                    });
                    return;
                }

                checkEmailBtn.disabled = true;
                checkEmailBtn.innerHTML = '<span class="loading-spinner" style="width: 16px; height: 16px;"></span>';

                try {
                    const res = await fetch('/calendar/check-appointment', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CONFIG.CSRF_TOKEN,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email })
                    });
                    
                    const data = await res.json();

                    if (data.has_appointment) {
                        existingDate.textContent = data.appointment.date;
                        existingTime.textContent = `${data.appointment.time}h`;
                        btnCancelExisting.href = data.appointment.cancel_url;
                        existingAppointmentInfo.classList.add('show');
                        noAppointmentMsg.classList.remove('show');
                    } else {
                        existingAppointmentInfo.classList.remove('show');
                        noAppointmentMsg.classList.add('show');
                    }
                } catch (e) {
                    console.error('Error checking appointment:', e);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo verificar. Inténtalo de nuevo.',
                        confirmButtonColor: '#A08A7A'
                    });
                } finally {
                    checkEmailBtn.disabled = false;
                    checkEmailBtn.innerHTML = '<i class="ti ti-search"></i> Consultar';
                }
            });

            // Permitir buscar con Enter
            checkEmailInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    checkEmailBtn.click();
                }
            });

            // ========== MENÚ MÓVIL HAMBURGUESA ==========
            const navbarToggler = document.getElementById('navbarToggler');
            const navbarCollapse = document.getElementById('navbarNav');
            const menuOverlay = document.getElementById('menuOverlay');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            // Función para abrir menú
            function openMobileMenu() {
                navbarCollapse.classList.add('show');
                menuOverlay.classList.add('show');
                document.body.classList.add('menu-open');
                navbarToggler.setAttribute('aria-expanded', 'true');
            }

            // Función para cerrar menú
            function closeMobileMenu() {
                navbarCollapse.classList.remove('show');
                menuOverlay.classList.remove('show');
                document.body.classList.remove('menu-open');
                navbarToggler.setAttribute('aria-expanded', 'false');
            }

            // Toggle del menú con el botón hamburguesa
            navbarToggler.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (navbarCollapse.classList.contains('show')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });

            // Cerrar con botón X
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }

            // Cerrar al hacer click en overlay
            menuOverlay.addEventListener('click', closeMobileMenu);

            // Cerrar al seleccionar un enlace del menú
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeMobileMenu();
                    }
                });
            });

            // Cerrar con tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && navbarCollapse.classList.contains('show')) {
                    closeMobileMenu();
                }
            });

            // Cerrar menú si se cambia a desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992 && navbarCollapse.classList.contains('show')) {
                    closeMobileMenu();
                }
            });
        });
    </script>

</body>
</html>
