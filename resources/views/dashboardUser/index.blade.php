@extends('layouts.app', ['title' => 'Mi Panel'])

@section('content')
<div class="container-fluid py-4">
    <!-- Header (oculto en landscape móvil) -->
    <div class="row mb-4 d-none d-sm-flex d-md-flex">
        <div class="col-12">
            <h2 class="fw-bold mb-1" style="color: var(--color-secondary);">
                <i class="ti ti-layout-dashboard me-2"></i>Bienvenido, {{ Auth::user()->name }}
            </h2>
            <p class="text-muted mb-0">Gestiona tus citas y servicios desde aquí</p>
        </div>
    </div>
    
    <!-- Header compacto móvil -->
    <div class="d-block d-sm-none mb-2">
        <h5 class="fw-bold mb-0" style="color: var(--color-secondary);">
            <i class="ti ti-layout-dashboard me-2"></i>Hola, {{ Str::before(Auth::user()->name, ' ') }}
        </h5>
    </div>

    <!-- Sistema de Tabs -->
    <div class="card border-0 shadow-sm" id="mainDashboardCard">
        <div class="card-header bg-white border-bottom" id="tabsHeader">
            <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" 
                            id="calendario-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#calendario" 
                            type="button" 
                            role="tab" 
                            aria-controls="calendario" 
                            aria-selected="true">
                        <i class="ti ti-calendar"></i>
                        <span>Citas</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="documentos-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#documentos" 
                            type="button" 
                            role="tab" 
                            aria-controls="documentos" 
                            aria-selected="false">
                        <i class="ti ti-files"></i>
                        <span>Docs</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="perfil-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#perfil" 
                            type="button" 
                            role="tab" 
                            aria-controls="perfil" 
                            aria-selected="false">
                        <i class="ti ti-user"></i>
                        <span>Perfil</span>
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-4">
            <div class="tab-content" id="dashboardTabsContent">
                <!-- Tab Calendario -->
                <div class="tab-pane fade show active" id="calendario" role="tabpanel" aria-labelledby="calendario-tab">
                    @include('dashboardUser.details.calendar')
                </div>
                
                <!-- Tab Perfil -->
                <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">
                    @include('dashboardUser.details.profile')
                </div>
                
                <!-- Tab Documentos -->
                <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
                    @include('dashboardUser.details.document')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        /* ===== ESTILOS BASE TABS ===== */
        #dashboardTabs {
            display: flex;
            border-bottom: 2px solid #e9ecef;
        }
        
        #dashboardTabs .nav-item {
            flex: 1;
        }
        
        #dashboardTabs .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            color: var(--color-secondary, #6c757d);
            border: none !important;
            border-bottom: 3px solid transparent !important;
            border-radius: 0 !important;
            padding: 0.875rem 1rem;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            margin-bottom: -2px;
            text-decoration: none;
            background: transparent !important;
        }
        
        #dashboardTabs .nav-link:hover,
        #dashboardTabs .nav-link:focus {
            color: var(--color-primary, #c99c67);
            background-color: rgba(201, 156, 103, 0.04) !important;
            border-color: transparent !important;
            border-bottom-color: rgba(201, 156, 103, 0.3) !important;
            isolation: auto;
        }
        
        #dashboardTabs .nav-link.active {
            color: var(--color-primary, #c99c67) !important;
            border-color: transparent !important;
            border-bottom-color: var(--color-primary, #c99c67) !important;
            background-color: transparent !important;
            font-weight: 600;
        }
        
        #dashboardTabs .nav-link i {
            font-size: 1.1rem;
        }
        
        /* Card base */
        #mainDashboardCard {
            border-radius: 12px;
            overflow: hidden;
        }
        
        #tabsHeader {
            border-radius: 12px 12px 0 0 !important;
            background: #fff !important;
        }
        
        /* ===== RESPONSIVE MÓVIL ===== */
        @media (max-width: 768px) {
            /* CRÍTICO: Reducir padding del wrapper del layout */
            .p-4.flex-grow-1 {
                padding: 0.5rem !important;
            }
            
            .container-fluid {
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }
            
            .container-fluid.py-4 {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }
            
            /* Card principal sin bordes redondeados */
            #mainDashboardCard {
                border-radius: 8px;
                box-shadow: none;
            }
            
            /* TABS: Estilo segmented control tipo app */
            #tabsHeader {
                padding: 0 !important;
                border-bottom: none !important;
            }
            
            #dashboardTabs {
                width: 100%;
                border-bottom: 2px solid #e9ecef;
                margin: 0;
            }
            
            /* Override completo Bootstrap nav-tabs en móvil */
            #dashboardTabs.nav-tabs {
                border-bottom: 2px solid #e9ecef;
            }
            
            #dashboardTabs.nav-tabs .nav-link {
                flex-direction: column;
                gap: 0.2rem;
                padding: 0.65rem 0.25rem;
                font-size: 0.72rem;
                font-weight: 600;
                text-align: center;
                border: none !important;
                border-bottom: 3px solid transparent !important;
                margin-bottom: -2px;
                background: transparent !important;
                line-height: 1.2;
            }
            
            #dashboardTabs.nav-tabs .nav-link.active {
                border: none !important;
                border-bottom: 3px solid var(--color-primary, #c99c67) !important;
                color: var(--color-primary, #c99c67) !important;
                background: transparent !important;
            }
            
            #dashboardTabs.nav-tabs .nav-link:hover {
                border: none !important;
                border-bottom: 3px solid transparent !important;
            }
            
            #dashboardTabs .nav-link i {
                font-size: 1.2rem;
                display: block;
            }
            
            #dashboardTabs .nav-link span {
                display: block;
                font-size: 0.68rem;
                letter-spacing: 0.02em;
            }
            
            /* Card body: mínimo padding para máximo espacio */
            #mainDashboardCard > .card-body.p-4 {
                padding: 0.5rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .p-4.flex-grow-1 {
                padding: 0.25rem !important;
            }
            
            #dashboardTabs.nav-tabs .nav-link {
                padding: 0.5rem 0.15rem;
                font-size: 0.68rem;
            }
            
            #dashboardTabs .nav-link i {
                font-size: 1.1rem;
            }
            
            #mainDashboardCard > .card-body.p-4 {
                padding: 0.35rem !important;
            }
        }
        
        @media (max-width: 375px) {
            #dashboardTabs.nav-tabs .nav-link {
                padding: 0.45rem 0.1rem;
                font-size: 0.62rem;
            }
            
            #dashboardTabs .nav-link i {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activar tab desde parámetro URL (?tab=documentos, ?tab=perfil, etc.)
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam) {
            const tabButton = document.getElementById(tabParam + '-tab');
            if (tabButton) {
                const bsTab = new bootstrap.Tab(tabButton);
                bsTab.show();
            }
        }
    });
</script>
@endpush