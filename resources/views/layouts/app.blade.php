<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MerStyleHub' }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons (opcional para iconos) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    @stack('styles')
</head>
<body style="background-color: #ECE9E2;">
    
    <!-- Header/Navbar Superior -->
    @include('layouts.partials.header')

    <div class="d-flex">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Contenido Principal -->
        <main class="flex-grow-1">
            <!-- Breadcrumbs -->
            @if(trim($__env->yieldContent('breadcrumbs')))
                <div class="bg-white border-bottom">
                    <div class="container-fluid py-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                @yield('breadcrumbs')
                            </ol>
                        </nav>
                    </div>
                </div>
            @endif

            <!-- Contenido de la página -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (para DataTables y SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>
