@php
    $questionnaires = Auth::user()->questionnaires()
        ->withPivot(['status', 'assigned_at', 'completed_at'])
        ->orderByRaw("CASE WHEN questionnaire_user.status = 'pending' THEN 0 ELSE 1 END")
        ->orderBy('questionnaire_user.assigned_at', 'desc')
        ->get();
    
    $pendingCount = $questionnaires->where('pivot.status', 'pending')->count();
    $completedCount = $questionnaires->where('pivot.status', 'completed')->count();
@endphp

<div class="questionnaires-section">
    <!-- Header de la sección -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-semibold mb-1" style="color: var(--color-secondary);">
                <i class="ti ti-clipboard-list me-2" style="color: var(--color-primary);"></i>Mis Cuestionarios
            </h5>
            <p class="text-muted small mb-0">Completa los cuestionarios asignados por tu consultora</p>
        </div>
        <div class="d-flex gap-2">
            @if($pendingCount > 0)
                <span class="badge bg-warning text-dark">
                    <i class="ti ti-clock me-1"></i>{{ $pendingCount }} pendiente{{ $pendingCount > 1 ? 's' : '' }}
                </span>
            @endif
            @if($completedCount > 0)
                <span class="badge bg-success">
                    <i class="ti ti-check me-1"></i>{{ $completedCount }} completado{{ $completedCount > 1 ? 's' : '' }}
                </span>
            @endif
        </div>
    </div>

    @if($questionnaires->isEmpty())
        <!-- Sin cuestionarios -->
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="ti ti-clipboard-off" style="font-size: 4rem; color: var(--color-border);"></i>
            </div>
            <h5 class="text-muted">No tienes cuestionarios asignados</h5>
            <p class="text-muted mb-0">Cuando tu consultora te asigne un cuestionario, aparecerá aquí.</p>
        </div>
    @else
        <!-- Listado de cuestionarios -->
        <div class="row g-3">
            @foreach($questionnaires as $questionnaire)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 questionnaire-card {{ $questionnaire->pivot->status === 'completed' ? 'border-success-subtle' : 'border-warning-subtle' }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-semibold mb-0" style="color: var(--color-secondary);">
                                    {{ Str::limit($questionnaire->title, 40) }}
                                </h6>
                                @if($questionnaire->pivot->status === 'completed')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="ti ti-check"></i>
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="ti ti-clock"></i>
                                    </span>
                                @endif
                            </div>
                            
                            @if($questionnaire->description)
                                <p class="text-muted small mb-2">{{ Str::limit($questionnaire->description, 80) }}</p>
                            @endif
                            
                            <div class="d-flex flex-wrap gap-2 small text-muted mb-3">
                                <span>
                                    <i class="ti ti-list me-1"></i>{{ $questionnaire->questions_count ?? $questionnaire->questions->count() }} preguntas
                                </span>
                                <span class="text-muted">•</span>
                                <span>
                                    <i class="ti ti-calendar me-1"></i>{{ \Carbon\Carbon::parse($questionnaire->pivot->assigned_at)->format('d/m/Y') }}
                                </span>
                            </div>
                            
                            @if($questionnaire->pivot->status === 'completed')
                                <a href="{{ route('user-questionnaire.responses', $questionnaire->id) }}" 
                                   class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="ti ti-eye me-1"></i> Ver respuestas
                                </a>
                            @else
                                <a href="{{ route('user-questionnaire.show', $questionnaire->id) }}" 
                                   class="btn btn-sm btn-primary-custom w-100">
                                    <i class="ti ti-edit me-1"></i> Responder
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Enlace a ver todos -->
        @if($questionnaires->count() > 6)
            <div class="text-center mt-4">
                <a href="{{ route('user-questionnaire.index') }}" class="btn btn-outline-primary">
                    Ver todos los cuestionarios <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    @endif
</div>

<style>
    .questionnaire-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-left-width: 3px !important;
    }
    
    .questionnaire-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .questionnaire-card.border-success-subtle {
        border-left-color: var(--bs-success) !important;
    }
    
    .questionnaire-card.border-warning-subtle {
        border-left-color: var(--bs-warning) !important;
    }
</style>
