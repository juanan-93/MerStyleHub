<div class="row">
    <div class="col-12">
        <h5 class="fw-semibold mb-4" style="color: var(--color-secondary);">
            <i class="ti ti-clipboard-list me-2" style="color: var(--color-primary);"></i>Cuestionarios Asignados
        </h5>
    </div>

    @forelse($assignedQuestionnaires as $assignment)
        <div class="col-12 mb-3">
            <div class="card border-0" style="background-color: var(--color-light);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ti ti-file-text me-2" style="color: var(--color-primary); font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                                        {{ $assignment->questionnaire->title ?? 'Sin título' }}
                                    </h6>
                                    @if($assignment->questionnaire->description)
                                        <small class="text-muted">{{ Str::limit($assignment->questionnaire->description, 80) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="info-label">Estado</div>
                            <div>
                                @if($assignment->status === 'completed')
                                    <span class="badge bg-success">
                                        <i class="ti ti-check me-1"></i>Completado
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="ti ti-clock me-1"></i>Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3 text-end">
                            <div class="info-label mb-2">Asignado</div>
                            <small class="text-muted">{{ $assignment->assigned_at ? $assignment->assigned_at->format('d/m/Y') : 'N/A' }}</small>
                            
                            @if($assignment->status === 'completed' && $assignment->completed_at)
                                <div class="mt-2">
                                    <div class="info-label">Completado</div>
                                    <small class="text-muted">{{ $assignment->completed_at->format('d/m/Y') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($assignment->status === 'completed')
                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="{{ route('info-user-admin.questionnaire-responses', [$user->id, $assignment->id]) }}" 
                                   class="btn btn-sm" 
                                   style="background-color: var(--color-primary); color: white;">
                                    <i class="ti ti-eye me-1"></i>Ver Respuestas
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="ti ti-clipboard-off mb-3" style="font-size: 3rem; color: var(--color-border);"></i>
                <h6 class="text-muted">Sin cuestionarios asignados</h6>
                <p class="text-muted small">Este usuario no tiene cuestionarios asignados todavía.</p>
            </div>
        </div>
    @endforelse
</div>
