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
                    <div class="row">
                        <!-- Calendario -->
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <div class="card border h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="ti ti-calendar-event me-2"></i>Calendario de Citas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="calendar-container" style="min-height: 400px;">
                                        <!-- Aquí se renderizará el calendario -->
                                        <div class="text-center py-5 text-muted">
                                            <i class="ti ti-calendar-time" style="font-size: 4rem; opacity: 0.3;"></i>
                                            <p class="mt-3">Cargando calendario...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Próximas Citas -->
                        <div class="col-lg-4">
                            <div class="card border h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="ti ti-clock me-2"></i>Próximas Citas
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <!-- Ejemplo de cita -->
                                        <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 45px; height: 45px; background-color: var(--color-primary); color: white;">
                                                <i class="ti ti-scissors"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Consulta de Estilo</h6>
                                                <small class="text-muted">
                                                    <i class="ti ti-calendar me-1"></i>30 Ene, 2026
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="ti ti-clock me-1"></i>10:00 - 11:00
                                                </small>
                                            </div>
                                            <span class="badge rounded-pill" style="background-color: var(--color-primary);">
                                                Confirmada
                                            </span>
                                        </div>
                                        
                                        <!-- Estado vacío -->
                                        {{-- 
                                        <div class="text-center py-5 text-muted">
                                            <i class="ti ti-calendar-off" style="font-size: 3rem; opacity: 0.3;"></i>
                                            <p class="mt-2 mb-0">No tienes citas próximas</p>
                                            <a href="{{ route('calendar.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                                Reservar Cita
                                            </a>
                                        </div>
                                        --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Perfil -->
                <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="ti ti-user-circle me-2"></i>Información Personal
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" 
                                             style="width: 100px; height: 100px; background-color: var(--color-primary); color: white; font-size: 2.5rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Nombre Completo</label>
                                            <p class="fw-medium">{{ Auth::user()->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Email</label>
                                            <p class="fw-medium">{{ Auth::user()->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Miembro desde</label>
                                            <p class="fw-medium">{{ Auth::user()->created_at->format('d M, Y') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Estado</label>
                                            <p class="fw-medium">
                                                <span class="badge bg-success">Activo</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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