<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'MerStyleHub' }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Select2 CSS (para selects mejorados) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <!-- Estilos de Layout -->
    <style>
        :root {
            --color-base: #ECE9E2;
            --color-white: #FFFFFF;
            --color-primary: #A08A7A;
            --color-secondary: #343434;
            --color-light: #F5F3F0;
            --color-border: #D9D4CE;
        }

        body {
            background-color: var(--color-base);
            color: var(--color-secondary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Desktop Transition */
        .sidebar-desktop {
            width: 260px;
            height: 100vh;
            transition: width 0.3s ease;
            flex-shrink: 0;
        }
        .sidebar-desktop.collapsed {
            width: 70px;
        }
        .sidebar-desktop.collapsed .ms-2, 
        .sidebar-desktop.collapsed .sidebar-title {
            display: none;
        }
        .sidebar-desktop.collapsed .nav-link {
            justify-content: center;
        }

        /* Header estilos */
        .navbar {
            background-color: var(--color-white) !important;
            border-bottom: 1px solid var(--color-border);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
            position: relative;
            z-index: 100;
        }
        
        .navbar-brand {
            color: var(--color-secondary) !important;
            font-weight: 700;
            font-size: 1.25rem;
        }

        /* Dropdown en navbar */
        .navbar .dropdown {
            position: static;
        }

        .navbar .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            left: auto;
            margin-top: 0.5rem;
            z-index: 1000;
            min-width: 200px;
        }

        /* Botones personalizados */
        .btn-primary-custom {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-white);
        }
        
        .btn-primary-custom:hover {
            background-color: #8f7668;
            border-color: #8f7668;
            color: var(--color-white);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Main content */
        main {
            background-color: var(--color-base);
        }

        /* Breadcrumbs */
        .breadcrumb {
            background-color: var(--color-white);
        }
        
        .breadcrumb-item {
            color: var(--color-secondary);
        }
        
        .breadcrumb-item.active {
            color: var(--color-primary);
        }
        
        .breadcrumb-item a {
            color: var(--color-primary);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
    </style>
    
    @stack('styles')
</head>
<body class="d-flex flex-row vh-100">
    
    <!-- Sidebar Desktop (Visible en pantallas grandes) -->
    <aside class="sidebar-desktop d-none d-lg-block border-end bg-white h-100 overflow-y-auto" id="desktopSidebar">
        @include('layouts.partials.sidebar')
    </aside>

    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
        
        <!-- Header/Navbar Superior -->
        @include('layouts.partials.header')

        <!-- Sidebar Mobile (Offcanvas nativo de Bootstrap 5) -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel" style="color: var(--color-secondary);">
                    <img src="{{ asset('images/logos/logo.png') }}" alt="Logo" height="24" class="me-2">
                    Menú
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                @include('layouts.partials.sidebar')
            </div>
        </div>

        <!-- Contenido Principal -->
        <main class="flex-grow-1 d-flex flex-column h-100 overflow-y-auto w-100">
            <!-- Breadcrumbs -->
            @if(trim($__env->yieldContent('breadcrumbs')))
                <div class="bg-white border-bottom px-4 py-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>
            @endif

            <!-- Contenido Dinámico -->
            <div class="p-4 flex-grow-1">
                @yield('content')
            </div>

            <!-- Footer (Ahora dentro del flujo principal para estar siempre al fondo) -->
            <div class="mt-auto">
                @include('layouts.partials.footer')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Moment.js (para manejo de fechas) -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/es.js"></script>
    
    <!-- DataTables JS (Bootstrap 5 integration) -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <!-- Select2 (para selects mejorados) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- Chart.js (para gráficas) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Axios (para peticiones HTTP) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Script de utilidades y sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica para colapsar sidebar en Desktop
            const toggleBtn = document.getElementById('desktopSidebarToggle');
            const sidebar = document.getElementById('desktopSidebar');
            
            if(toggleBtn && sidebar) {
                // Restaurar estado
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if(isCollapsed) sidebar.classList.add('collapsed');

                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                });
            }

            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Inicializar popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });

        // Función auxiliar para SweetAlert
        function confirmarAccion(mensaje = '¿Estás seguro?', titulo = 'Confirmar acción') {
            return Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#A08A7A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            });
        }

        // Función para mostrar notificaciones
        function mostrarNotificacion(titulo, mensaje, tipo = 'success') {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: tipo,
                confirmButtonColor: '#A08A7A',
                timer: 3000,
                timerProgressBar: true
            });
        }
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>
