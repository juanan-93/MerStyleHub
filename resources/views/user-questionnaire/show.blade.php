@extends('layouts.app', ['title' => $questionnaire->title])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboardUser.index') }}">
            <i class="ti ti-layout-dashboard me-1"></i>{{ __('Mi Panel') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('user-questionnaire.index') }}">
            <i class="ti ti-clipboard-list me-1"></i>{{ __('Mis Cuestionarios') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ Str::limit($questionnaire->title, 30) }}</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--color-secondary);">
                        <i class="ti ti-clipboard-list me-2" style="color: var(--color-primary);"></i>{{ $questionnaire->title }}
                    </h2>
                    @if($questionnaire->description)
                        <p class="text-muted mb-0">{{ $questionnaire->description }}</p>
                    @endif
                </div>
                <a href="{{ route('user-questionnaire.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong><i class="ti ti-alert-circle me-2"></i>Por favor corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    {{-- Ya completado --}}
    @if($assignment->isCompleted())
        <div class="alert alert-success mb-4">
            <i class="ti ti-check me-2"></i>
            <strong>Este cuestionario ya ha sido completado.</strong>
            <a href="{{ route('user-questionnaire.responses', $questionnaire->id) }}" class="alert-link ms-2">
                Ver mis respuestas
            </a>
        </div>
    @endif

    <!-- Formulario de Cuestionario -->
    <form action="{{ route('user-questionnaire.store', $questionnaire->id) }}" method="POST" id="questionnaireForm">
        @csrf
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        <i class="ti ti-list me-1"></i>{{ $questionnaire->questions->count() }} preguntas
                    </span>
                    <span class="text-muted small">
                        <span class="text-danger">*</span> Campo obligatorio
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                @foreach($questionnaire->questions as $index => $question)
                    <div class="question-block mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex gap-3 mb-3">
                            <div class="question-number flex-shrink-0">
                                <span class="badge rounded-circle d-flex align-items-center justify-content-center" 
                                      style="width: 32px; height: 32px; background-color: var(--color-primary); color: white;">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label fw-semibold mb-0" style="color: var(--color-secondary);">
                                    {{ $question->text }}
                                    @if($question->required)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                
                                @if($question->type === 'info')
                                    <p class="text-muted small mt-1 mb-0">
                                        <i class="ti ti-info-circle me-1"></i>Información importante
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="ms-5">
                            @php
                                $existingResponse = $existingResponses[$question->id] ?? null;
                            @endphp

                            @switch($question->type)
                                @case('info')
                                    {{-- Tipo informativo - mostrar las opciones como información --}}
                                    @if($question->options->isNotEmpty())
                                        <div class="alert alert-light border mb-0">
                                            <ul class="mb-0 ps-3">
                                                @foreach($question->options as $option)
                                                    <li class="mb-1">{{ $option->text }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        {{-- Campo oculto para marcar como leído --}}
                                        <input type="hidden" name="question_{{ $question->id }}" value="read">
                                    @endif
                                    @break

                                @case('test')
                                    {{-- Tipo test - radio buttons --}}
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($question->options as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="question_{{ $question->id }}" 
                                                       id="question_{{ $question->id }}_option_{{ $option->id }}"
                                                       value="{{ $option->id }}"
                                                       {{ (old('question_'.$question->id) == $option->id || ($existingResponse && $existingResponse->question_option_id == $option->id)) ? 'checked' : '' }}
                                                       {{ $question->required ? 'required' : '' }}>
                                                <label class="form-check-label" for="question_{{ $question->id }}_option_{{ $option->id }}">
                                                    {{ $option->text }}
                                                </label>
                                            </div>
                                        @endforeach
                                        
                                        @if($question->allow_other_option)
                                            <div class="form-check">
                                                <input class="form-check-input other-option" 
                                                       type="radio" 
                                                       name="question_{{ $question->id }}" 
                                                       id="question_{{ $question->id }}_other"
                                                       value="other"
                                                       data-target="other_input_{{ $question->id }}"
                                                       {{ old('question_'.$question->id) == 'other' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="question_{{ $question->id }}_other">
                                                    Otro:
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-sm mt-1 other-input" 
                                                       id="other_input_{{ $question->id }}"
                                                       name="question_{{ $question->id }}_other"
                                                       placeholder="Especifica tu respuesta..."
                                                       value="{{ old('question_'.$question->id.'_other', ($existingResponse && $existingResponse->question_option_id === null ? $existingResponse->text_response : '')) }}"
                                                       style="max-width: 300px; {{ old('question_'.$question->id) != 'other' ? 'display: none;' : '' }}">
                                            </div>
                                        @endif
                                    </div>
                                    @break

                                @case('select')
                                    {{-- Tipo select - dropdown --}}
                                    <select class="form-select" 
                                            name="question_{{ $question->id }}" 
                                            id="question_{{ $question->id }}"
                                            {{ $question->required ? 'required' : '' }}
                                            style="max-width: 400px;">
                                        <option value="">Selecciona una opción...</option>
                                        @foreach($question->options as $option)
                                            <option value="{{ $option->id }}" 
                                                {{ (old('question_'.$question->id) == $option->id || ($existingResponse && $existingResponse->question_option_id == $option->id)) ? 'selected' : '' }}>
                                                {{ $option->text }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @break

                                @case('text')
                                    {{-- Tipo texto - textarea --}}
                                    <textarea class="form-control" 
                                              name="question_{{ $question->id }}" 
                                              id="question_{{ $question->id }}"
                                              rows="3" 
                                              placeholder="Escribe tu respuesta aquí..."
                                              {{ $question->required ? 'required' : '' }}>{{ old('question_'.$question->id, $existingResponse?->text_response) }}</textarea>
                                    @break

                                @case('file')
                                    {{-- Tipo archivo --}}
                                    <input type="file" 
                                           class="form-control" 
                                           name="question_{{ $question->id }}" 
                                           id="question_{{ $question->id }}"
                                           {{ $question->required ? 'required' : '' }}
                                           style="max-width: 400px;">
                                    <small class="text-muted d-block mt-1">
                                        <i class="ti ti-upload me-1"></i>Formatos permitidos: JPG, PNG, PDF (máx. 5MB)
                                    </small>
                                    @break

                                @default
                                    {{-- Tipo por defecto - texto --}}
                                    <input type="text" 
                                           class="form-control" 
                                           name="question_{{ $question->id }}" 
                                           id="question_{{ $question->id }}"
                                           placeholder="Tu respuesta..."
                                           value="{{ old('question_'.$question->id, $existingResponse?->text_response) }}"
                                           {{ $question->required ? 'required' : '' }}
                                           style="max-width: 400px;">
                            @endswitch
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('user-questionnaire.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x me-1"></i> Cancelar
                    </a>
                    @if(!$assignment->isCompleted())
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="ti ti-check me-1"></i> Enviar respuestas
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .question-block {
        transition: background-color 0.2s ease;
    }
    
    .question-block:hover {
        background-color: rgba(160, 138, 122, 0.03);
        margin-left: -1rem;
        margin-right: -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0.5rem;
    }
    
    .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }
    
    .form-check-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.25rem rgba(160, 138, 122, 0.25);
    }
    
    .form-select:focus,
    .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.25rem rgba(160, 138, 122, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar campos "Otro" en preguntas tipo test
    document.querySelectorAll('.other-option').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const targetId = this.dataset.target;
            const otherInput = document.getElementById(targetId);
            if (otherInput) {
                otherInput.style.display = this.checked ? 'block' : 'none';
                if (this.checked) {
                    otherInput.focus();
                }
            }
        });
    });
    
    // Ocultar campos "otro" cuando se selecciona otra opción
    document.querySelectorAll('input[type="radio"]:not(.other-option)').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const name = this.name;
            const otherRadio = document.querySelector(`input[name="${name}"].other-option`);
            if (otherRadio) {
                const targetId = otherRadio.dataset.target;
                const otherInput = document.getElementById(targetId);
                if (otherInput) {
                    otherInput.style.display = 'none';
                }
            }
        });
    });
    
    // Confirmación antes de enviar
    document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
        if (!confirm('¿Estás seguro de enviar tus respuestas? Una vez enviadas no podrás modificarlas.')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
