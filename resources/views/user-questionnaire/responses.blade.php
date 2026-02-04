@extends('layouts.app', ['title' => 'Mis Respuestas - ' . $questionnaire->title])

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
    <li class="breadcrumb-item active">{{ Str::limit($questionnaire->title, 20) }}</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--color-secondary);">
                        <i class="ti ti-clipboard-check me-2" style="color: var(--color-primary);"></i>{{ $questionnaire->title }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="ti ti-check me-1"></i>
                        Cuestionario completado el {{ \Carbon\Carbon::parse($assignment->completed_at)->format('d/m/Y \a \l\a\s H:i') }}
                    </p>
                </div>
                <a href="{{ route('user-questionnaire.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Mis Respuestas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                <i class="ti ti-list-check me-2" style="color: var(--color-primary);"></i>Mis Respuestas
            </h5>
            <span class="badge bg-success">
                <i class="ti ti-check me-1"></i>Completado
            </span>
        </div>
        <div class="card-body p-4">
            @foreach($questionnaire->questions->sortBy('order') as $index => $question)
                <div class="question-response mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex gap-3">
                        <div class="question-number flex-shrink-0">
                            <span class="badge rounded-circle d-flex align-items-center justify-content-center" 
                                  style="width: 32px; height: 32px; background-color: var(--color-primary); color: white;">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-2" style="color: var(--color-secondary);">
                                {{ $question->text }}
                            </h6>
                            
                            @php
                                $response = $responses[$question->id] ?? null;
                            @endphp

                            @if($question->type === 'info')
                                {{-- Tipo informativo --}}
                                <div class="alert alert-light border mb-0">
                                    <ul class="mb-0 ps-3">
                                        @foreach($question->options as $option)
                                            <li class="mb-1">{{ $option->text }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="ti ti-eye me-1"></i>Información leída
                                </small>
                            @elseif($response)
                                <div class="response-content p-3 bg-light rounded">
                                    @if($response->selectedOption)
                                        {{-- Respuesta con opción seleccionada --}}
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-circle-check text-success me-2"></i>
                                            <span>{{ $response->selectedOption->text }}</span>
                                        </div>
                                    @elseif($response->text_response)
                                        {{-- Verificar si es tipo archivo --}}
                                        @if($question->type === 'file')
                                            @php
                                                $files = json_decode($response->text_response, true);
                                            @endphp
                                            @if(is_array($files) && count($files) > 0)
                                                <div class="uploaded-files">
                                                    @foreach($files as $file)
                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                            @if(str_starts_with($file['mime'] ?? '', 'image/'))
                                                                <i class="ti ti-photo text-primary me-1"></i>
                                                            @else
                                                                <i class="ti ti-file-text text-primary me-1"></i>
                                                            @endif
                                                            <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-decoration-none">
                                                                {{ $file['name'] }}
                                                            </a>
                                                            <small class="text-muted">({{ number_format($file['size'] / 1024, 1) }} KB)</small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Sin archivos</span>
                                            @endif
                                        @elseif($question->type === 'info' && $response->text_response)
                                            {{-- Múltiples opciones para tipo info --}}
                                            @php
                                                $selectedIds = json_decode($response->text_response, true);
                                            @endphp
                                            @if(is_array($selectedIds))
                                                <ul class="mb-0 ps-0" style="list-style: none;">
                                                    @foreach($question->options->whereIn('id', $selectedIds) as $selectedOption)
                                                        <li class="mb-1">
                                                            <i class="ti ti-circle-check text-success me-2"></i>
                                                            {{ $selectedOption->text }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="mb-0">{{ $response->text_response }}</p>
                                            @endif
                                        @else
                                            <p class="mb-0" style="white-space: pre-wrap;">{{ $response->text_response }}</p>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">Sin respuesta</span>
                                    @endif
                                </div>
                            @else
                                <div class="response-content p-3 bg-light rounded">
                                    <span class="text-muted fst-italic">
                                        <i class="ti ti-minus me-1"></i>Sin respuesta
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer bg-white py-3 text-center">
            <a href="{{ route('user-questionnaire.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Volver a mis cuestionarios
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .response-content {
        border-left: 3px solid var(--color-primary);
    }
    
    .question-response {
        transition: background-color 0.2s ease;
    }
    
    .question-response:hover {
        background-color: rgba(160, 138, 122, 0.03);
        margin-left: -1rem;
        margin-right: -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0.5rem;
    }
</style>
@endpush
