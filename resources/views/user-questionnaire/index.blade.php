@extends('layouts.app', ['title' => 'Mis Cuestionarios'])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboardUser.index') }}">
            <i class="ti ti-layout-dashboard me-1"></i>{{ __('Mi Panel') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ __('Mis Cuestionarios') }}</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--color-secondary);">
                        <i class="ti ti-clipboard-list me-2" style="color: var(--color-primary);"></i>Mis Cuestionarios
                    </h2>
                    <p class="text-muted mb-0">Completa los cuestionarios asignados por tu consultora</p>
                </div>
                <a href="{{ route('dashboardUser.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Volver al Panel
                </a>
            </div>
        </div>
    </div>

    {{-- Mensajes de éxito/error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="ti ti-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Listado de Cuestionarios -->
    @if($questionnaires->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-clipboard-off" style="font-size: 4rem; color: var(--color-border);"></i>
                </div>
                <h5 class="text-muted">No tienes cuestionarios asignados</h5>
                <p class="text-muted mb-0">Cuando tu consultora te asigne un cuestionario, aparecerá aquí.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($questionnaires as $questionnaire)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100 questionnaire-card {{ $questionnaire->pivot->status === 'completed' ? 'border-success' : '' }}">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                                {{ $questionnaire->title }}
                            </h5>
                            @if($questionnaire->pivot->status === 'completed')
                                <span class="badge bg-success">
                                    <i class="ti ti-check me-1"></i>Completado
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="ti ti-clock me-1"></i>Pendiente
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($questionnaire->description)
                                <p class="text-muted small mb-3">{{ Str::limit($questionnaire->description, 120) }}</p>
                            @endif
                            
                            <div class="d-flex flex-column gap-2 small text-muted mb-3">
                                <div>
                                    <i class="ti ti-list me-1"></i>
                                    {{ $questionnaire->questions_count ?? $questionnaire->questions->count() }} preguntas
                                </div>
                                <div>
                                    <i class="ti ti-calendar me-1"></i>
                                    Asignado: {{ \Carbon\Carbon::parse($questionnaire->pivot->assigned_at)->format('d/m/Y') }}
                                </div>
                                @if($questionnaire->pivot->status === 'completed' && $questionnaire->pivot->completed_at)
                                    <div>
                                        <i class="ti ti-check me-1"></i>
                                        Completado: {{ \Carbon\Carbon::parse($questionnaire->pivot->completed_at)->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top py-3">
                            @if($questionnaire->pivot->status === 'completed')
                                <a href="{{ route('user-questionnaire.responses', $questionnaire->id) }}" 
                                   class="btn btn-outline-secondary w-100">
                                    <i class="ti ti-eye me-1"></i> Ver mis respuestas
                                </a>
                            @else
                                <a href="{{ route('user-questionnaire.show', $questionnaire->id) }}" 
                                   class="btn btn-primary-custom w-100">
                                    <i class="ti ti-edit me-1"></i> Responder cuestionario
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .questionnaire-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .questionnaire-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .questionnaire-card.border-success {
        border-left: 4px solid var(--bs-success) !important;
    }
</style>
@endpush
