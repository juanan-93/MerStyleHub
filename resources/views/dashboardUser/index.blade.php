@extends('layouts.app', ['title' => 'Mi Panel'])

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-1" style="color: var(--color-secondary);">
                <i class="ti ti-layout-dashboard me-2"></i>Bienvenido, {{ Auth::user()->name }}
            </h2>
            <p class="text-muted mb-0">Gestiona tus citas y servicios desde aquí</p>
        </div>
    </div>

    <!-- Sistema de Tabs -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center gap-2" 
                            id="calendario-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#calendario" 
                            type="button" 
                            role="tab" 
                            aria-controls="calendario" 
                            aria-selected="true">
                        <i class="ti ti-calendar" style="font-size: 1.25rem;"></i>
                        <span>Mis Citas</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" 
                            id="documentos-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#documentos" 
                            type="button" 
                            role="tab" 
                            aria-controls="documentos" 
                            aria-selected="false">
                        <i class="ti ti-files" style="font-size: 1.25rem;"></i>
                        <span>Mis Documentos</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" 
                            id="perfil-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#perfil" 
                            type="button" 
                            role="tab" 
                            aria-controls="perfil" 
                            aria-selected="false">
                        <i class="ti ti-user" style="font-size: 1.25rem;"></i>
                        <span>Mi Perfil</span>
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
        /* Estilos personalizados para los tabs */
        #dashboardTabs .nav-link {
            color: var(--color-secondary);
            border: none;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        #dashboardTabs .nav-link:hover {
            color: var(--color-primary);
            border-bottom-color: var(--color-border);
        }
        
        #dashboardTabs .nav-link.active {
            color: var(--color-primary);
            border-bottom-color: var(--color-primary);
            background-color: transparent;
        }
        
        #dashboardTabs .nav-link i {
            color: inherit;
        }
        
        .card {
            border-radius: 12px;
        }
        
        .card-header {
            border-radius: 12px 12px 0 0 !important;
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