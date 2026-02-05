@extends('layouts.app', ['title' => 'Respuestas del Cuestionario'])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboardAdmin.index') }}">
            <i class="ti ti-home me-1"></i>Dashboard
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('info-user-admin.show', $user->id) }}?tab=questionnaires">
            <i class="ti ti-user me-1"></i>{{ $user->name }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="ti ti-clipboard-check me-1"></i>Respuestas
    </li>
@endsection

@push('styles')
    <style>
        .response-card {
            background-color: var(--color-light);
            border-left: 4px solid var(--color-primary);
        }

        .question-number {
            width: 40px;
            height: 40px;
            background-color: var(--color-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .response-text {
            color: var(--color-text);
            font-size: 1rem;
            padding: 0.75rem 1rem;
            background-color: white;
            border-radius: 0.375rem;
            border: 1px solid var(--color-border);
        }

        .info-label {
            color: var(--color-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .header-questionnaire {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8B7669 100%);
            color: white;
        }
    </style>
@endpush

@section('content')
    {{-- Encabezado del Cuestionario --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm header-questionnaire">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <h3 class="mb-2 fw-bold">{{ $questionnaireUser->questionnaire->title }}</h3>
                            @if($questionnaireUser->questionnaire->description)
                                <p class="mb-3 opacity-75">{{ $questionnaireUser->questionnaire->description }}</p>
                            @endif
                            <div class="d-flex gap-4">
                                <div>
                                    <small class="opacity-75">Usuario:</small>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                                <div>
                                    <small class="opacity-75">Completado:</small>
                                    <strong>{{ $questionnaireUser->completed_at ? $questionnaireUser->completed_at->format('d/m/Y H:i') : 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-success px-3 py-2">
                            <i class="ti ti-check me-1"></i>Completado
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Respuestas --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header border-bottom" style="background-color: var(--color-white);">
                    <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                        <i class="ti ti-messages me-2" style="color: var(--color-primary);"></i>Respuestas del Usuario
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $questions = $questionnaireUser->questionnaire->questions;
                        $responses = $questionnaireUser->responses->keyBy('question_id');
                    @endphp

                    @forelse($questions as $index => $question)
                        <div class="response-card card border-0 mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex gap-3 mb-3">
                                    <div class="question-number">{{ $index + 1 }}</div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-2" style="color: var(--color-secondary);">
                                            {{ $question->text }}
                                        </h6>
                                        <span class="badge badge-sm" style="background-color: rgba(160, 138, 122, 0.2); color: var(--color-primary);">
                                            {{ ucfirst($question->type) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="ms-5 ps-2">
                                    <div class="info-label mb-2">Respuesta:</div>
                                    @php
                                        $response = $responses->get($question->id);
                                    @endphp

                                    @if($response)
                                        @if($question->type === 'text')
                                            <div class="response-text">
                                                {{ $response->text_response ?? 'Sin respuesta' }}
                                            </div>
                                        @elseif($question->type === 'select' || $question->type === 'test')
                                            <div class="response-text">
                                                @if($response->selectedOption)
                                                    <i class="ti ti-check-circle me-2" style="color: var(--color-primary);"></i>
                                                    {{ $response->selectedOption->text }}
                                                @else
                                                    <span class="text-muted">Sin respuesta</span>
                                                @endif
                                            </div>
                                        @elseif($question->type === 'info')
                                            <div class="response-text">
                                                <i class="ti ti-info-circle me-2" style="color: var(--color-primary);"></i>
                                                {{ $response->text_response ?? 'Información leída' }}
                                            </div>
                                        @else
                                            <div class="response-text text-muted">
                                                Tipo de pregunta no soportado
                                            </div>
                                        @endif
                                    @else
                                        <div class="response-text text-muted">
                                            Sin respuesta registrada
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ti ti-message-off mb-3" style="font-size: 3rem; color: var(--color-border);"></i>
                            <h6 class="text-muted">Sin preguntas</h6>
                            <p class="text-muted small">Este cuestionario no tiene preguntas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Botón volver --}}
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('info-user-admin.show', $user->id) }}?tab=questionnaires" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Volver a Cuestionarios
            </a>
        </div>
    </div>
@endsection
