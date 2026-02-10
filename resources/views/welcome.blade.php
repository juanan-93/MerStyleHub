<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MerStyleHub - Tu Estilo, Tu Esencia</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <!-- Animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-base: #ECE9E2;
            --color-white: #FFFFFF;
            --color-primary: #A08A7A;
            --color-primary-dark: #8f7668;
            --color-secondary: #343434;
            --color-light: #F5F3F0;
            --color-border: #D9D4CE;
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
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }

        /* Navbar */
        .navbar-landing {
            background-color: transparent;
            transition: all 0.3s ease;
            padding: 1.25rem 0;
            position: relative;
            z-index: 1050;
        }

        .navbar-landing.scrolled {
            background-color: var(--color-white);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            padding: 0.75rem 0;
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
            background: rgba(160, 138, 122, 0.1);
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        .navbar-toggler:active {
            background: rgba(160, 138, 122, 0.2);
        }

        .navbar-toggler i {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .navbar-landing.scrolled .navbar-toggler {
            background: var(--color-light);
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
        }

        .nav-link-custom:hover {
            color: var(--color-primary) !important;
        }

        /* Botones */
        .btn-primary-custom {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-white);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            /* Mejorar touch target */
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 576px) {
            .btn-primary-custom {
                padding: 0.75rem 1.75rem;
                font-size: 1rem;
            }
        }

        @media (min-width: 992px) {
            .btn-primary-custom {
                padding: 0.75rem 2rem;
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
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @media (min-width: 576px) {
            .btn-outline-custom {
                padding: 0.75rem 1.75rem;
                font-size: 1rem;
            }
        }

        @media (min-width: 992px) {
            .btn-outline-custom {
                padding: 0.75rem 2rem;
            }
        }

        .btn-outline-custom:hover {
            background-color: var(--color-primary);
            color: var(--color-white);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg, var(--color-base) 0%, var(--color-light) 50%, var(--color-white) 100%);
            overflow: hidden;
            padding: 100px 0 3rem;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 70%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(160, 138, 122, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Badge del hero mejorado para móvil */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--color-white);
            color: var(--color-secondary);
            padding: 0.5rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--color-border);
        }

        .hero-badge i {
            color: var(--color-primary);
            font-size: 1rem;
        }

        @media (min-width: 375px) {
            .hero-badge {
                padding: 0.625rem 1rem;
                font-size: 0.8rem;
                margin-bottom: 1.25rem;
            }
        }

        @media (min-width: 576px) {
            .hero-badge {
                padding: 0.75rem 1.25rem;
                font-size: 0.85rem;
                margin-bottom: 1.5rem;
            }
            .hero-badge i {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 992px) {
            .hero-badge {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
                margin-bottom: 1.75rem;
            }
        }

        .hero-title {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 1rem;
            color: var(--color-secondary);
        }

        .hero-subtitle {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        /* Móvil pequeño (375px+) */
        @media (min-width: 375px) {
            .hero-title {
                font-size: 2rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
        }

        /* Móvil grande (576px+) */
        @media (min-width: 576px) {
            .hero-section {
                padding: 120px 0 4rem;
            }
            .hero-title {
                font-size: 2.25rem;
            }
            .hero-subtitle {
                font-size: 1.1rem;
                margin-bottom: 1.75rem;
            }
        }

        /* Tablet (768px+) */
        @media (min-width: 768px) {
            .hero-title {
                font-size: 2.75rem;
            }
            .hero-subtitle {
                font-size: 1.15rem;
                margin-bottom: 2rem;
            }
        }

        /* Desktop (992px+) */
        @media (min-width: 992px) {
            .hero-section {
                padding: 0;
            }
            .hero-title {
                font-size: 3.5rem;
                line-height: 1.2;
                margin-bottom: 1.5rem;
            }
            .hero-subtitle {
                font-size: 1.25rem;
                line-height: 1.8;
            }
        }

        /* Responsive - Floating Cards solo en desktop */
        @media (max-width: 991px) {
            .floating-card {
                display: none;
            }
        }

        /* Hero image container responsive */
        .hero-image-container {
            position: relative;
            margin-top: 2rem;
        }

        @media (min-width: 992px) {
            .hero-image-container {
                margin-top: 0;
            }
        }

        .hero-image {
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 100%;
        }

        @media (min-width: 576px) {
            .hero-image {
                border-radius: 18px;
            }
        }

        @media (min-width: 992px) {
            .hero-image {
                border-radius: 20px;
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
                max-width: 500px;
            }
        }

        .floating-card {
            position: absolute;
            background: var(--color-white);
            padding: 1.25rem 1.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            animation: float 3s ease-in-out infinite;
        }

        .floating-card.card-1 {
            top: 10%;
            left: -10%;
            animation-delay: 0s;
        }

        .floating-card.card-2 {
            bottom: 15%;
            right: -5%;
            animation-delay: 1.5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Services Section */
        .section {
            padding: 3rem 0;
        }

        @media (min-width: 576px) {
            .section {
                padding: 4rem 0;
            }
        }

        @media (min-width: 992px) {
            .section {
                padding: 6rem 0;
            }
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--color-secondary);
        }

        @media (min-width: 576px) {
            .section-title {
                font-size: 1.75rem;
                margin-bottom: 1rem;
            }
        }

        @media (min-width: 992px) {
            .section-title {
                font-size: 2.5rem;
            }
        }

        .section-subtitle {
            font-size: 0.95rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        @media (min-width: 576px) {
            .section-subtitle {
                font-size: 1rem;
                margin: 0 auto 2.5rem;
            }
        }

        @media (min-width: 992px) {
            .section-subtitle {
                font-size: 1.1rem;
                margin: 0 auto 3rem;
            }
        }

        /* ========== CARRUSEL DE SERVICIOS ========== */
        .services-carousel-wrapper {
            position: relative;
            overflow: hidden;
            padding: 1rem 0 2rem;
        }

        .services-carousel-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .service-slide {
            flex: 0 0 88%;
            max-width: 88%;
            padding: 0 0.4rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 576px) {
            .service-slide {
                flex: 0 0 70%;
                max-width: 70%;
                padding: 0 0.75rem;
            }
        }

        @media (min-width: 768px) {
            .service-slide {
                flex: 0 0 50%;
                max-width: 50%;
                padding: 0 1rem;
            }
        }

        @media (min-width: 992px) {
            .service-slide {
                flex: 0 0 33.333%;
                max-width: 33.333%;
                padding: 0 1rem;
            }
        }

        .service-card {
            background: var(--color-white);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--color-border);
            height: 100%;
            opacity: 0.6;
            transform: scale(0.92);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @media (min-width: 576px) {
            .service-card {
                padding: 2rem;
                border-radius: 18px;
            }
        }

        @media (min-width: 992px) {
            .service-card {
                padding: 2.5rem;
                border-radius: 20px;
            }
        }

        .service-slide.active .service-card {
            opacity: 1;
            transform: scale(1);
            border-color: var(--color-primary);
            box-shadow: 0 20px 60px rgba(160, 138, 122, 0.2);
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--color-white);
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        @media (min-width: 576px) {
            .service-icon {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
                border-radius: 16px;
                margin: 0 auto 1.25rem;
            }
        }

        @media (min-width: 992px) {
            .service-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
                border-radius: 20px;
                margin: 0 auto 1.5rem;
            }
        }

        .service-card h4 {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            color: var(--color-secondary);
        }

        @media (min-width: 576px) {
            .service-card h4 {
                font-size: 1.15rem;
            }
        }

        @media (min-width: 992px) {
            .service-card h4 {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }
        }

        .service-card .service-desc {
            color: #666;
            line-height: 1.7;
            font-size: 0.875rem;
            text-align: left;
            margin-bottom: 0;
        }

        @media (min-width: 576px) {
            .service-card .service-desc {
                font-size: 0.925rem;
                line-height: 1.75;
            }
        }

        /* Navegación del carrusel */
        .carousel-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .carousel-btn {
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
            border-radius: 50%;
            border: 2px solid var(--color-primary);
            background: var(--color-white);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .carousel-btn:hover {
            background: var(--color-primary);
            color: var(--color-white);
            transform: scale(1.05);
        }

        .carousel-counter {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--color-secondary);
            min-width: 60px;
            text-align: center;
        }

        .carousel-counter .current {
            font-weight: 700;
            color: var(--color-primary);
            font-size: 1.1rem;
        }

        /* About Section */
        .about-section {
            background-color: var(--color-white);
        }

        .about-image {
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 576px) {
            .about-image {
                border-radius: 18px;
            }
        }

        @media (min-width: 992px) {
            .about-image {
                border-radius: 20px;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            }
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }

        @media (min-width: 576px) {
            .feature-item {
                margin-bottom: 1.5rem;
            }
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            background: var(--color-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-right: 0.75rem;
        }

        @media (min-width: 576px) {
            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                border-radius: 12px;
                margin-right: 1rem;
            }
        }

        .feature-item h5 {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        @media (min-width: 576px) {
            .feature-item h5 {
                font-size: 1rem;
            }
        }

        .feature-item p {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 0;
            line-height: 1.5;
        }

        @media (min-width: 576px) {
            .feature-item p {
                font-size: 0.9rem;
            }
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 576px) {
            .cta-section {
                padding: 4rem 0;
            }
        }

        @media (min-width: 992px) {
            .cta-section {
                padding: 5rem 0;
            }
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-section h2 {
            color: var(--color-white);
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        @media (min-width: 576px) {
            .cta-section h2 {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
        }

        @media (min-width: 992px) {
            .cta-section h2 {
                font-size: 2.5rem;
            }
        }

        .cta-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        @media (min-width: 576px) {
            .cta-section p {
                font-size: 1rem;
                margin-bottom: 1.75rem;
            }
        }

        @media (min-width: 992px) {
            .cta-section p {
                font-size: 1.1rem;
                margin-bottom: 2rem;
            }
        }

        .btn-white {
            background-color: var(--color-white);
            color: var(--color-primary);
            padding: 0.875rem 1.75rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            min-height: 44px;
        }

        @media (min-width: 576px) {
            .btn-white {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }

        @media (min-width: 992px) {
            .btn-white {
                padding: 1rem 2.5rem;
            }
        }

        .btn-white:hover {
            background-color: var(--color-secondary);
            color: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        .footer {
            background-color: var(--color-secondary);
            color: var(--color-white);
            padding: 2.5rem 0 1.5rem;
        }

        @media (min-width: 576px) {
            .footer {
                padding: 3rem 0 1.5rem;
            }
        }

        @media (min-width: 992px) {
            .footer {
                padding: 4rem 0 2rem;
            }
        }

        .footer h5 {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--color-white);
        }

        @media (min-width: 576px) {
            .footer h5 {
                font-size: 1rem;
                margin-bottom: 1.25rem;
            }
        }

        @media (min-width: 992px) {
            .footer h5 {
                margin-bottom: 1.5rem;
            }
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        @media (min-width: 576px) {
            .footer-links li {
                margin-bottom: 0.75rem;
            }
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.85rem;
            /* Mejorar touch target */
            display: inline-block;
            padding: 0.25rem 0;
        }

        @media (min-width: 576px) {
            .footer-links a {
                font-size: 0.9rem;
            }
        }

        .footer-links a:hover {
            color: var(--color-primary);
        }

        .social-icons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--color-white);
            margin-right: 0;
            transition: all 0.3s ease;
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

        @media (min-width: 992px) {
            .footer-bottom {
                padding-top: 2rem;
                margin-top: 3rem;
            }
        }

        .footer-bottom p {
            font-size: 0.8rem;
        }

        @media (min-width: 576px) {
            .footer-bottom p {
                font-size: 0.85rem;
            }
        }

        /* Scroll animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mejoras adicionales para touch en móviles */
        @media (hover: none) and (pointer: coarse) {
            /* Eliminar efectos hover en dispositivos táctiles */
            .btn-primary-custom:hover,
            .btn-outline-custom:hover,
            .btn-white:hover {
                transform: none;
            }
        }

        /* Safe area para dispositivos con notch */
        @supports (padding-top: env(safe-area-inset-top)) {
            .navbar-landing {
                padding-top: max(1.25rem, env(safe-area-inset-top));
            }
            .footer {
                padding-bottom: max(2rem, env(safe-area-inset-bottom));
            }
        }
    </style>
</head>
<body>
    <!-- Overlay para menú móvil -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logos/logo.png') }}" alt="MerStyleHub">
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
                        <a class="nav-link nav-link-custom" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#nosotros">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ route('calendar.index') }}">Reservar Cita</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="hero-badge animate__animated animate__fadeInDown">
                        <i class="ti ti-sparkles"></i> Asesoría de Imagen Personalizada
                    </span>
                    <h1 class="hero-title animate__animated animate__fadeInUp">
                        Descubre tu <span style="color: var(--color-primary);">mejor versión</span> con estilo único
                    </h1>
                    <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                        Te ayudamos a encontrar tu estilo personal, potenciar tu imagen y sentirte segura cada día. 
                        Colorimetría, asesoría personalizada y mucho más.
                    </p>
                    <div class="d-flex flex-wrap gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="{{ route('calendar.index') }}" class="btn btn-primary-custom btn-lg">
                            <i class="ti ti-calendar me-2"></i>Reservar Cita
                        </a>
                        <a href="#servicios" class="btn btn-outline-custom btn-lg">
                            Ver Servicios
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-container text-center animate__animated animate__fadeInRight">
                        <div style="height: 500px; border-radius: 20px; overflow: hidden;">
                            <img src="{{ asset('images/landing/mer.jpeg') }}" alt="Mercedes - Asesora de Imagen" class="hero-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center top; border-radius: 20px;">
                        </div>
                        
                        <!-- Floating Cards -->
                        <div class="floating-card card-1">
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 45px; height: 45px; background: linear-gradient(135deg, #F8D7DA, #F5C6CB, #E8B4B8, #D4A5A5); border-radius: 50%;"></div>
                                <div class="text-start">
                                    <small class="text-muted d-block">Tu paleta</small>
                                    <strong>Colorimetría</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="floating-card card-2">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-palette me-2" style="color: var(--color-primary); font-size: 1.5rem;"></i>
                                <div class="text-start">
                                    <strong class="d-block">100% personalizado</strong>
                                    <small class="text-muted">a tu medida</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section" id="servicios">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-light px-3 py-2 rounded-pill mb-3" style="color: var(--color-primary);">
                    Nuestros Servicios
                </span>
                <h2 class="section-title fade-up">Todo lo que necesitas para brillar</h2>
                <p class="section-subtitle fade-up">Servicios personalizados diseñados para potenciar tu imagen y estilo personal</p>
            </div>
            
            @php
                $icons = ['ti-palette', 'ti-hanger', 'ti-diamond', 'ti-shirt', 'ti-brush', 'ti-shopping-bag', 'ti-walk', 'ti-calendar-event'];
            @endphp

            <div class="services-carousel-wrapper fade-up">
                <div class="services-carousel-track" id="servicesTrack">
                    @foreach($products as $index => $product)
                    <div class="service-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="ti {{ $icons[$index % count($icons)] }}"></i>
                            </div>
                            <h4>{{ $product->title }}</h4>
                            <p class="service-desc">{!! strip_tags($product->description) !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="carousel-nav">
                <button class="carousel-btn" id="carouselPrev" aria-label="Servicio anterior">
                    <i class="ti ti-chevron-left"></i>
                </button>
                <div class="carousel-counter">
                    <span class="current" id="carouselCurrent">1</span> / <span id="carouselTotal">{{ $products->count() }}</span>
                </div>
                <button class="carousel-btn" id="carouselNext" aria-label="Siguiente servicio">
                    <i class="ti ti-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section" id="nosotros">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="fade-up">
                        <div style="height: 450px; border-radius: 20px; overflow: hidden;">
                            <img src="{{ asset('images/landing/Gemini_Generated_Image_a5p17pa5p17pa5p1.png') }}" alt="Asesoría de imagen - MerStyleHub" class="about-image" style="width: 100%; height: 100%; object-fit: cover; object-position: center; border-radius: 20px;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="badge bg-light px-3 py-2 rounded-pill mb-3" style="color: var(--color-primary);">
                        Sobre Nosotros
                    </span>
                    <h2 class="section-title mb-4 fade-up">Tu imagen, nuestra pasión</h2>
                    <p class="mb-4 fade-up" style="color: #666; line-height: 1.8;">
                        En MerStyleHub creemos que cada persona tiene un estilo único esperando ser descubierto. 
                        Con más de 10 años de experiencia en asesoría de imagen, hemos ayudado a cientos de mujeres 
                        a sentirse más seguras y auténticas.
                    </p>
                    
                    <div class="fade-up">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="ti ti-certificate"></i>
                            </div>
                            <div>
                                <h5>Profesionales Certificadas</h5>
                                <p>Formación continua en las últimas tendencias y técnicas de imagen.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="ti ti-heart"></i>
                            </div>
                            <div>
                                <h5>Enfoque Personalizado</h5>
                                <p>Cada asesoría es única, adaptada a tus necesidades y estilo de vida.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="ti ti-messages"></i>
                            </div>
                            <div>
                                <h5>Acompañamiento Continuo</h5>
                                <p>No te dejamos sola, seguimos contigo en tu proceso de transformación.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container position-relative">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="fade-up">¿Lista para descubrir tu mejor versión?</h2>
                    <p class="fade-up">Agenda tu primera cita y comienza tu transformación. El primer paso hacia una imagen que refleje quien realmente eres.</p>
                    <a href="{{ route('calendar.index') }}" class="btn btn-white btn-lg fade-up">
                        <i class="ti ti-calendar-event me-2"></i>Reservar Ahora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <img src="{{ asset('images/logos/logo.png') }}" alt="MerStyleHub" height="40" class="mb-3" style="filter: brightness(0) invert(1);">
                    <p style="color: rgba(255, 255, 255, 0.7); line-height: 1.8;">
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
                        <li><a href="#">Análisis de Armario</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5>Enlaces</h5>
                    <ul class="footer-links">
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="{{ route('calendar.index') }}">Reservar Cita</a></li>
                        <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
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
                <p style="color: rgba(255, 255, 255, 0.5); margin-bottom: 0;">
                    © {{ date('Y') }} MerStyleHub. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Banner de Cookies --}}
    @include('partials.cookie-banner')
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Fade up animation on scroll
        const fadeUpElements = document.querySelectorAll('.fade-up');
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        fadeUpElements.forEach(el => observer.observe(el));

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

        // ========== CARRUSEL DE SERVICIOS ==========
        (function() {
            const track = document.getElementById('servicesTrack');
            const slides = document.querySelectorAll('.service-slide');
            const prevBtn = document.getElementById('carouselPrev');
            const nextBtn = document.getElementById('carouselNext');
            const counterCurrent = document.getElementById('carouselCurrent');
            const totalSlides = slides.length;
            let currentIndex = 0;
            let startX = 0;
            let isDragging = false;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let autoplayInterval = null;
            const AUTOPLAY_DELAY = 5000;

            function getSlideWidth() {
                const w = window.innerWidth;
                if (w >= 992) return 33.333;
                if (w >= 768) return 50;
                if (w >= 576) return 70;
                return 88;
            }

            function getOffset() {
                const slideW = getSlideWidth();
                return (100 - slideW) / 2;
            }

            function updateCarousel(animate) {
                if (animate !== false) {
                    track.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                } else {
                    track.style.transition = 'none';
                }

                const slideW = getSlideWidth();
                const offset = getOffset();
                const translateX = -(currentIndex * slideW) + offset;
                track.style.transform = `translateX(${translateX}%)`;
                prevTranslate = translateX;

                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === currentIndex);
                });

                counterCurrent.textContent = currentIndex + 1;
            }

            function goTo(index) {
                // Loop infinito
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                currentIndex = index;
                updateCarousel();
            }

            function startAutoplay() {
                stopAutoplay();
                autoplayInterval = setInterval(() => {
                    goTo(currentIndex + 1);
                }, AUTOPLAY_DELAY);
            }

            function stopAutoplay() {
                if (autoplayInterval) {
                    clearInterval(autoplayInterval);
                    autoplayInterval = null;
                }
            }

            prevBtn.addEventListener('click', () => {
                stopAutoplay();
                goTo(currentIndex - 1);
                startAutoplay();
            });

            nextBtn.addEventListener('click', () => {
                stopAutoplay();
                goTo(currentIndex + 1);
                startAutoplay();
            });

            // Touch/drag support
            const wrapper = track.parentElement;

            function touchStart(e) {
                isDragging = true;
                startX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
                track.style.transition = 'none';
                stopAutoplay();
            }

            function touchMove(e) {
                if (!isDragging) return;
                const currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
                const diff = currentX - startX;
                const wrapperWidth = wrapper.offsetWidth;
                const percentMoved = (diff / wrapperWidth) * 100;
                track.style.transform = `translateX(${prevTranslate + percentMoved}%)`;
                currentTranslate = prevTranslate + percentMoved;
            }

            function touchEnd() {
                if (!isDragging) return;
                isDragging = false;
                const moved = currentTranslate - prevTranslate;
                const threshold = getSlideWidth() / 4;

                if (moved < -threshold) {
                    goTo(currentIndex + 1);
                } else if (moved > threshold) {
                    goTo(currentIndex - 1);
                } else {
                    updateCarousel();
                }
                startAutoplay();
            }

            wrapper.addEventListener('touchstart', touchStart, { passive: true });
            wrapper.addEventListener('touchmove', touchMove, { passive: true });
            wrapper.addEventListener('touchend', touchEnd);

            wrapper.addEventListener('mousedown', touchStart);
            wrapper.addEventListener('mousemove', touchMove);
            wrapper.addEventListener('mouseup', touchEnd);
            wrapper.addEventListener('mouseleave', () => {
                if (isDragging) touchEnd();
            });

            // Pausar autoplay al hover en desktop
            const section = document.getElementById('servicios');
            section.addEventListener('mouseenter', stopAutoplay);
            section.addEventListener('mouseleave', startAutoplay);

            // Keyboard support
            document.addEventListener('keydown', function(e) {
                const rect = section.getBoundingClientRect();
                const visible = rect.top < window.innerHeight && rect.bottom > 0;
                if (!visible) return;

                if (e.key === 'ArrowLeft') { stopAutoplay(); goTo(currentIndex - 1); startAutoplay(); }
                if (e.key === 'ArrowRight') { stopAutoplay(); goTo(currentIndex + 1); startAutoplay(); }
            });

            // Update on resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => updateCarousel(false), 100);
            });

            // Pausar cuando la sección no es visible (IntersectionObserver)
            const visibilityObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        startAutoplay();
                    } else {
                        stopAutoplay();
                    }
                });
            }, { threshold: 0.3 });
            visibilityObserver.observe(section);

            // Initial position
            updateCarousel(false);
        })();
    </script>
</body>
</html>
