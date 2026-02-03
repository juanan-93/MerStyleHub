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
                            id="cuestionarios-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#cuestionarios" 
                            type="button" 
                            role="tab" 
                            aria-controls="cuestionarios" 
                            aria-selected="false">
                        <i class="ti ti-clipboard-list" style="font-size: 1.25rem;"></i>
                        <span>Mis Cuestionarios</span>
                        @php
                            $pendingQuestionnaires = Auth::user()->questionnaires()
                                ->wherePivot('status', 'pending')
                                ->count();
                        @endphp
                        @if($pendingQuestionnaires > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $pendingQuestionnaires }}</span>
                        @endif
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
                
                <!-- Tab Cuestionarios -->
                <div class="tab-pane fade" id="cuestionarios" role="tabpanel" aria-labelledby="cuestionarios-tab">
                    @include('dashboardUser.details.questionnaires')
                </div>
                
                <!-- Tab Perfil -->
                <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">
                    @include('dashboardUser.details.profile')
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
        // Aquí puedes inicializar el calendario u otras funcionalidades
        console.log('Dashboard cargado');
    });
</script>
@endpush