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
            min-width: 40px;
            min-height: 40px;
            flex-shrink: 0;
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

        /* ===== Previsualización de archivos ===== */
        .file-preview-card {
            width: 160px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--color-border);
            background: white;
            transition: all 0.2s ease;
        }

        .file-preview-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .file-preview-link {
            display: block;
            width: 100%;
            height: 120px;
            overflow: hidden;
            background: var(--color-light);
        }

        .file-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .file-preview-card:hover .file-preview-img {
            transform: scale(1.05);
        }

        .file-preview-name {
            padding: 0.5rem 0.6rem;
            font-size: 0.75rem;
            color: var(--color-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border-top: 1px solid var(--color-border);
        }

        .file-download-card {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1rem;
            background: white;
            border: 1px solid var(--color-border);
            border-radius: 10px;
            text-decoration: none;
            color: var(--color-secondary);
            transition: all 0.2s ease;
        }

        .file-download-card:hover {
            border-color: var(--color-primary);
            box-shadow: 0 2px 10px rgba(160, 138, 122, 0.15);
            color: var(--color-secondary);
        }

        .file-download-name {
            font-size: 0.85rem;
            font-weight: 500;
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
                    <div class="d-flex flex-column align-items-end gap-2">
                        <span class="badge bg-success px-3 py-2">
                            <i class="ti ti-check me-1"></i>Completado
                        </span>
                        <a href="{{ route('info-user-admin.questionnaire-responses.pdf', [$user->id, $questionnaireUser->id]) }}" 
                           class="btn btn-sm px-3 py-1"
                           style="background-color: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(4px);">
                            <i class="ti ti-file-type-pdf me-1"></i>Exportar PDF
                        </a>
                    </div>
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
                                        @elseif($question->type === 'file')
                                            @php
                                                $files = json_decode($response->text_response, true);
                                            @endphp
                                            @if(is_array($files) && count($files) > 0)
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach($files as $file)
                                                        @php
                                                            $isImage = str_starts_with($file['mime'] ?? '', 'image/');
                                                            $fileUrl = asset('storage/' . $file['path']);
                                                        @endphp
                                                        @if($isImage)
                                                            <div class="file-preview-card">
                                                                <a href="{{ $fileUrl }}" target="_blank" class="file-preview-link">
                                                                    <img src="{{ $fileUrl }}" alt="{{ $file['name'] ?? 'Imagen' }}" class="file-preview-img">
                                                                </a>
                                                                <div class="file-preview-name" title="{{ $file['name'] ?? '' }}">
                                                                    <i class="ti ti-photo me-1"></i>{{ Str::limit($file['name'] ?? 'Imagen', 25) }}
                                                                </div>
                                                            </div>
                                                        @else
                                                            <a href="{{ $fileUrl }}" target="_blank" class="file-download-card">
                                                                <i class="ti ti-file-download" style="font-size: 1.5rem; color: var(--color-primary);"></i>
                                                                <span class="file-download-name">{{ Str::limit($file['name'] ?? 'Archivo', 30) }}</span>
                                                                @if(isset($file['size']))
                                                                    <small class="text-muted">{{ number_format($file['size'] / 1024, 0) }} KB</small>
                                                                @endif
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="response-text text-muted">Sin archivos adjuntos</div>
                                            @endif
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
