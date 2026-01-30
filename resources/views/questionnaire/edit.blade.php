@extends('layouts.app', ['title' => __('Editar Cuestionario')])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('questionnaire.index') }}">
            <i class="ti ti-clipboard-list me-1"></i>{{ __('Cuestionarios') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ __('Editar') }}</li>
@endsection

@section('content')
{{-- Mensajes de error y éxito --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong><i class="ti ti-alert-circle me-2"></i>{{ __('Por favor corrige los siguientes errores:') }}</strong>
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

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form id="questionnaireForm" method="POST" action="{{ route('questionnaire.update', $questionnaire->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        {{-- Columna Principal --}}
        <div class="col-12 col-lg-8">
            {{-- Card Información General --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                        <i class="ti ti-info-circle me-2" style="color: var(--color-primary);"></i>{{ __('Información del Cuestionario') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold">{{ __('Título del Cuestionario') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title', $questionnaire->title) }}"
                                   placeholder="Ej: Cuestionario de Colorimetría" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">{{ __('Descripción') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Describe brevemente el propósito de este cuestionario...">{{ old('description', $questionnaire->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Preguntas --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                        <i class="ti ti-list-check me-2" style="color: var(--color-primary);"></i>{{ __('Preguntas') }}
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-primary-custom dropdown-toggle" type="button" id="addQuestionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-plus me-1"></i> {{ __('Añadir Pregunta') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="addQuestionDropdown">
                            <li>
                                <a class="dropdown-item py-2" href="#" data-question-type="info">
                                    <i class="ti ti-info-circle me-2" style="color: #fd7e14;"></i>
                                    <span class="fw-medium">{{ __('Instrucciones') }}</span>
                                    <small class="d-block text-muted ms-4">Texto informativo para el usuario</small>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2" href="#" data-question-type="test">
                                    <i class="ti ti-list-check me-2" style="color: #17a2b8;"></i>
                                    <span class="fw-medium">{{ __('Tipo Test') }}</span>
                                    <small class="d-block text-muted ms-4">Múltiples opciones de respuesta</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" data-question-type="text">
                                    <i class="ti ti-text-caption me-2" style="color: #28a745;"></i>
                                    <span class="fw-medium">{{ __('Tipo Texto') }}</span>
                                    <small class="d-block text-muted ms-4">Respuesta en área de texto</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" data-question-type="select">
                                    <i class="ti ti-select me-2" style="color: #6f42c1;"></i>
                                    <span class="fw-medium">{{ __('Tipo Select') }}</span>
                                    <small class="d-block text-muted ms-4">Seleccionar de una lista</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" data-question-type="file">
                                    <i class="ti ti-upload me-2" style="color: #e83e8c;"></i>
                                    <span class="fw-medium">{{ __('Subir Archivo') }}</span>
                                    <small class="d-block text-muted ms-4">Permite subir imágenes o documentos</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Contenedor de Preguntas --}}
                    <div id="questionsContainer">
                        {{-- Estado vacío (oculto si hay preguntas) --}}
                        <div id="emptyQuestionsState" class="text-center py-5" style="{{ $questionnaire->questions->count() > 0 ? 'display: none;' : '' }}">
                            <div class="mb-3">
                                <i class="ti ti-clipboard-text" style="font-size: 3rem; color: var(--color-border);"></i>
                            </div>
                            <h6 class="text-muted mb-2">{{ __('No hay preguntas añadidas') }}</h6>
                            <p class="text-muted small mb-0">{{ __('Usa el botón "Añadir Pregunta" para comenzar a crear tu cuestionario') }}</p>
                        </div>

                        {{-- Preguntas existentes --}}
                        @foreach($questionnaire->questions->sortBy('order') as $index => $question)
                            @php
                                $questionId = $question->id;
                                $typeColors = [
                                    'test' => '#17a2b8',
                                    'text' => '#28a745',
                                    'select' => '#6f42c1',
                                    'file' => '#e83e8c',
                                    'info' => '#fd7e14'
                                ];
                                $typeLabels = [
                                    'test' => 'Tipo Test',
                                    'text' => 'Tipo Texto',
                                    'select' => 'Tipo Select',
                                    'file' => 'Subir Archivo',
                                    'info' => 'Instrucciones'
                                ];
                                $typeIcons = [
                                    'test' => 'ti-list-check',
                                    'text' => 'ti-text-caption',
                                    'select' => 'ti-select',
                                    'file' => 'ti-upload',
                                    'info' => 'ti-info-circle'
                                ];
                            @endphp

                            @if($question->type === 'info')
                                {{-- Bloque Informativo --}}
                                <div class="question-card mb-3" data-question-id="existing_{{ $questionId }}" data-question-type="info">
                                    <div class="card border" style="border-color: #fd7e14 !important; border-radius: 10px; border-width: 2px;">
                                        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 8px 8px 0 0; background-color: #fff3e6;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge" style="background-color: #fd7e14;">
                                                    <i class="ti ti-info-circle me-1"></i>Instrucciones
                                                </span>
                                                <span class="text-muted small">Bloque #<span class="question-number">{{ $index + 1 }}</span></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                                    <i class="ti ti-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                                    <i class="ti ti-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3" style="background-color: #fffaf5;">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][id]" value="{{ $questionId }}">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][type]" value="info">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small" style="color: #fd7e14;">
                                                    <i class="ti ti-message-circle me-1"></i>Título de las instrucciones
                                                </label>
                                                <input type="text" class="form-control" name="questions[existing_{{ $questionId }}][text]" 
                                                       value="{{ old("questions.existing_{$questionId}.text", $question->text) }}"
                                                       placeholder="Ej: Instrucciones para las fotografías">
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-semibold small" style="color: #fd7e14;">
                                                    <i class="ti ti-file-text me-1"></i>Contenido de las instrucciones
                                                </label>
                                                <textarea class="form-control info-content" name="questions[existing_{{ $questionId }}][options][]" rows="4" 
                                                          placeholder="Escribe las instrucciones detalladas...">{{ old("questions.existing_{$questionId}.options.0", $question->options->first()?->text) }}</textarea>
                                            </div>
                                            <div class="mt-3 p-2 rounded" style="background-color: #fff; border: 1px solid #fde3ce;">
                                                <small class="text-muted d-flex align-items-center gap-2">
                                                    <i class="ti ti-bulb" style="color: #fd7e14;"></i>
                                                    Este bloque mostrará información al usuario, no requiere respuesta
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($question->type === 'test')
                                {{-- Pregunta Tipo Test --}}
                                <div class="question-card mb-3" data-question-id="existing_{{ $questionId }}" data-question-type="test">
                                    <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge" style="background-color: #17a2b8;">
                                                    <i class="ti ti-list-check me-1"></i>Tipo Test
                                                </span>
                                                <span class="text-muted small">Pregunta #<span class="question-number">{{ $index + 1 }}</span></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                                    <i class="ti ti-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                                    <i class="ti ti-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][id]" value="{{ $questionId }}">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][type]" value="test">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="questions[existing_{{ $questionId }}][text]" 
                                                       value="{{ old("questions.existing_{$questionId}.text", $question->text) }}"
                                                       placeholder="Escribe tu pregunta aquí...">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold small">Opciones de respuesta</label>
                                                <div class="options-container">
                                                    @foreach($question->options as $optIndex => $option)
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text bg-white border-end-0">
                                                                <i class="ti ti-circle text-muted" style="font-size: 0.8rem;"></i>
                                                            </span>
                                                            <input type="text" class="form-control border-start-0" 
                                                                   name="questions[existing_{{ $questionId }}][options][]" 
                                                                   value="{{ old("questions.existing_{$questionId}.options.{$optIndex}", $option->text) }}"
                                                                   placeholder="Opción {{ $optIndex + 1 }}">
                                                            <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                    @if($question->options->count() < 2)
                                                        @for($i = $question->options->count(); $i < 2; $i++)
                                                            <div class="input-group mb-2">
                                                                <span class="input-group-text bg-white border-end-0">
                                                                    <i class="ti ti-circle text-muted" style="font-size: 0.8rem;"></i>
                                                                </span>
                                                                <input type="text" class="form-control border-start-0" 
                                                                       name="questions[existing_{{ $questionId }}][options][]" 
                                                                       placeholder="Opción {{ $i + 1 }}">
                                                                <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>
                                                        @endfor
                                                    @endif
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-add-option" data-icon="circle">
                                                    <i class="ti ti-plus me-1"></i>Añadir opción
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($question->type === 'text')
                                {{-- Pregunta Tipo Texto --}}
                                <div class="question-card mb-3" data-question-id="existing_{{ $questionId }}" data-question-type="text">
                                    <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge" style="background-color: #28a745;">
                                                    <i class="ti ti-text-caption me-1"></i>Tipo Texto
                                                </span>
                                                <span class="text-muted small">Pregunta #<span class="question-number">{{ $index + 1 }}</span></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                                    <i class="ti ti-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                                    <i class="ti ti-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][id]" value="{{ $questionId }}">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][type]" value="text">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="questions[existing_{{ $questionId }}][text]" 
                                                       value="{{ old("questions.existing_{$questionId}.text", $question->text) }}"
                                                       placeholder="Escribe tu pregunta aquí...">
                                            </div>
                                            <div class="p-3 rounded" style="background-color: var(--color-light); border: 1px dashed var(--color-border);">
                                                <small class="text-muted d-flex align-items-center gap-2">
                                                    <i class="ti ti-info-circle"></i>
                                                    El usuario responderá con texto libre en un área de texto
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($question->type === 'select')
                                {{-- Pregunta Tipo Select --}}
                                <div class="question-card mb-3" data-question-id="existing_{{ $questionId }}" data-question-type="select">
                                    <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge" style="background-color: #6f42c1;">
                                                    <i class="ti ti-select me-1"></i>Tipo Select
                                                </span>
                                                <span class="text-muted small">Pregunta #<span class="question-number">{{ $index + 1 }}</span></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                                    <i class="ti ti-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                                    <i class="ti ti-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][id]" value="{{ $questionId }}">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][type]" value="select">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="questions[existing_{{ $questionId }}][text]" 
                                                       value="{{ old("questions.existing_{$questionId}.text", $question->text) }}"
                                                       placeholder="Escribe tu pregunta aquí...">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Opciones del select</label>
                                                <div class="options-container">
                                                    @foreach($question->options as $optIndex => $option)
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text bg-white border-end-0">
                                                                <i class="ti ti-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                                                            </span>
                                                            <input type="text" class="form-control border-start-0 option-input" 
                                                                   name="questions[existing_{{ $questionId }}][options][]" 
                                                                   value="{{ old("questions.existing_{$questionId}.options.{$optIndex}", $option->text) }}"
                                                                   placeholder="Opción {{ $optIndex + 1 }}">
                                                            <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                                                <i class="ti ti-x"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                    @if($question->options->count() < 2)
                                                        @for($i = $question->options->count(); $i < 2; $i++)
                                                            <div class="input-group mb-2">
                                                                <span class="input-group-text bg-white border-end-0">
                                                                    <i class="ti ti-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                                                                </span>
                                                                <input type="text" class="form-control border-start-0 option-input" 
                                                                       name="questions[existing_{{ $questionId }}][options][]" 
                                                                       placeholder="Opción {{ $i + 1 }}">
                                                                <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            </div>
                                                        @endfor
                                                    @endif
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-add-option" data-icon="chevron-right">
                                                    <i class="ti ti-plus me-1"></i>Añadir opción
                                                </button>
                                            </div>
                                            <div class="mb-3 p-3 rounded" style="background-color: var(--color-light); border: 1px solid var(--color-border);">
                                                <div class="form-check">
                                                    <input class="form-check-input allow-other-checkbox" type="checkbox" 
                                                           name="questions[existing_{{ $questionId }}][allow_other_option]" value="1" 
                                                           id="allowOther_existing_{{ $questionId }}"
                                                           {{ $question->allow_other_option ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold small" for="allowOther_existing_{{ $questionId }}">
                                                        <i class="ti ti-text-plus me-1" style="color: var(--color-primary);"></i>
                                                        Permitir respuesta alternativa
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-1 ms-4">
                                                    Si el usuario no encuentra una opción adecuada, podrá escribir su propia respuesta
                                                </small>
                                            </div>
                                            <div class="select-preview p-3 rounded" style="background-color: #f8f4ff; border: 1px dashed #6f42c1;">
                                                <label class="form-label fw-semibold small d-flex align-items-center gap-2 mb-2" style="color: #6f42c1;">
                                                    <i class="ti ti-eye"></i>Vista previa del Select
                                                </label>
                                                <select class="form-select select2-preview" data-question-id="existing_{{ $questionId }}" style="width: 100%;">
                                                    <option value="" disabled selected>Selecciona una opción...</option>
                                                    @foreach($question->options as $optIndex => $option)
                                                        <option value="{{ $optIndex + 1 }}">{{ $option->text }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($question->type === 'file')
                                {{-- Pregunta Tipo Archivo --}}
                                <div class="question-card mb-3" data-question-id="existing_{{ $questionId }}" data-question-type="file">
                                    <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge" style="background-color: #e83e8c;">
                                                    <i class="ti ti-upload me-1"></i>Subir Archivo
                                                </span>
                                                <span class="text-muted small">Pregunta #<span class="question-number">{{ $index + 1 }}</span></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                                    <i class="ti ti-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                                    <i class="ti ti-arrow-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][id]" value="{{ $questionId }}">
                                            <input type="hidden" name="questions[existing_{{ $questionId }}][type]" value="file">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="questions[existing_{{ $questionId }}][text]" 
                                                       value="{{ old("questions.existing_{$questionId}.text", $question->text) }}"
                                                       placeholder="Ej: Sube una foto de cuerpo entero...">
                                            </div>
                                            <div class="p-3 rounded" style="background-color: #fdf2f8; border: 1px dashed #e83e8c;">
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <i class="ti ti-photo" style="font-size: 2rem; color: #e83e8c;"></i>
                                                    <div>
                                                        <small class="fw-semibold d-block" style="color: #e83e8c;">Vista previa del campo de subida</small>
                                                        <small class="text-muted">El usuario podrá subir imágenes o documentos</small>
                                                    </div>
                                                </div>
                                                <div class="border rounded p-3 bg-white text-center">
                                                    <i class="ti ti-cloud-upload" style="font-size: 1.5rem; color: #ccc;"></i>
                                                    <small class="d-block text-muted mt-1">Arrastra archivos aquí o haz clic para seleccionar</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Lateral --}}
        <div class="col-12 col-lg-4">
            {{-- Card Configuración --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                        <i class="ti ti-settings me-2" style="color: var(--color-primary);"></i>{{ __('Configuración') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">{{ __('Estado') }}</label>
                        <select class="form-select select2-status" id="status" name="status" style="width: 100%;">
                            <option value="active" data-icon="ti-circle-check" data-color="#28a745" {{ $questionnaire->status == 'active' ? 'selected' : '' }}>{{ __('Activo') }}</option>
                            <option value="inactive" data-icon="ti-circle-x" data-color="#dc3545" {{ $questionnaire->status == 'inactive' ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                        </select>
                        <small class="text-muted">{{ __('Los cuestionarios inactivos no serán visibles para los usuarios') }}</small>
                    </div>
                </div>
            </div>

            {{-- Card Resumen --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                        <i class="ti ti-chart-bar me-2" style="color: var(--color-primary);"></i>{{ __('Resumen') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">{{ __('Total elementos') }}</span>
                        <span class="fw-bold" id="totalQuestions">{{ $questionnaire->questions->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <i class="ti ti-info-circle" style="color: #fd7e14;"></i>{{ __('Instrucciones') }}
                        </span>
                        <span class="fw-semibold" id="infoQuestions">{{ $questionnaire->questions->where('type', 'info')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <i class="ti ti-list-check" style="color: #17a2b8;"></i>{{ __('Tipo Test') }}
                        </span>
                        <span class="fw-semibold" id="testQuestions">{{ $questionnaire->questions->where('type', 'test')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <i class="ti ti-text-caption" style="color: #28a745;"></i>{{ __('Tipo Texto') }}
                        </span>
                        <span class="fw-semibold" id="textQuestions">{{ $questionnaire->questions->where('type', 'text')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <i class="ti ti-select" style="color: #6f42c1;"></i>{{ __('Tipo Select') }}
                        </span>
                        <span class="fw-semibold" id="selectQuestions">{{ $questionnaire->questions->where('type', 'select')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted d-flex align-items-center gap-1">
                            <i class="ti ti-upload" style="color: #e83e8c;"></i>{{ __('Subir Archivo') }}
                        </span>
                        <span class="fw-semibold" id="fileQuestions">{{ $questionnaire->questions->where('type', 'file')->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Card Acciones --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        <i class="ti ti-device-floppy me-1"></i> {{ __('Guardar Cambios') }}
                    </button>
                    <a href="{{ route('questionnaire.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="ti ti-arrow-left me-1"></i> {{ __('Cancelar') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    /* Estilos del formulario */
    .form-control, .form-select {
        border-color: var(--color-border);
        color: var(--color-secondary);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem rgba(160, 138, 122, 0.25);
    }
    
    .form-control::placeholder {
        color: #999;
    }
    
    /* Card de preguntas */
    .question-card .card {
        transition: all 0.2s ease;
    }
    
    .question-card .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .question-card .card-header {
        background-color: var(--color-light) !important;
    }
    
    /* Botones del dropdown */
    .dropdown-item:hover {
        background-color: var(--color-light);
    }
    
    .dropdown-item:active {
        background-color: var(--color-primary);
        color: white;
    }
    
    /* Input groups para opciones */
    .input-group .form-control:focus {
        z-index: 1;
    }
    
    .input-group .btn-outline-danger {
        border-color: var(--color-border);
        color: #999;
    }
    
    .input-group .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    /* Botón añadir opción */
    .btn-add-option {
        border-style: dashed;
    }
    
    /* Badges de tipo de pregunta */
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    /* Animación para nuevas preguntas */
    .question-card {
        animation: fadeInUp 0.3s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive */
    @media (max-width: 991px) {
        .col-lg-4 {
            order: -1;
        }
    }
    
    /* Select2 Custom Styling - Corporativo */
    .select2-container--default .select2-selection--single {
        border: 1px solid var(--color-border) !important;
        border-radius: 8px !important;
        height: auto !important;
        padding: 0 !important;
        background-color: var(--color-white) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 8px 12px !important;
        color: var(--color-secondary) !important;
        line-height: 1.5 !important;
        font-size: 0.9rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 8px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--color-primary) transparent transparent !important;
        margin-top: -2px !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15) !important;
    }

    .select2-dropdown {
        border: 1px solid var(--color-border) !important;
        border-radius: 8px !important;
        background-color: var(--color-white) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-container--default .select2-results__option {
        padding: 10px 12px !important;
        color: var(--color-secondary) !important;
        font-size: 0.9rem !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(160, 138, 122, 0.15) !important;
        color: var(--color-primary) !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(160, 138, 122, 0.1) !important;
        color: var(--color-primary) !important;
        font-weight: 500 !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border-color: var(--color-border);
        border-radius: 4px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--color-primary);
        outline: none;
    }

    /* Select2 en la preview */
    .select-preview .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCounter = {{ $questionnaire->questions->count() * 1000 }}; // Start high to avoid conflicts with existing IDs
    const questionsContainer = document.getElementById('questionsContainer');
    const emptyState = document.getElementById('emptyQuestionsState');
    
    // Inicializar Select2 para el select de estado
    if (typeof $.fn.select2 !== 'undefined') {
        // Función para formatear las opciones con iconos
        function formatStatusOption(option) {
            if (!option.id) return option.text;
            
            const icon = $(option.element).data('icon');
            const color = $(option.element).data('color');
            
            return $('<span class="d-flex align-items-center gap-2">' +
                '<i class="ti ' + icon + '" style="color: ' + color + '; font-size: 1.1rem;"></i>' +
                '<span>' + option.text + '</span>' +
                '</span>');
        }
        
        $('.select2-status').select2({
            minimumResultsForSearch: Infinity,
            width: '100%',
            templateResult: formatStatusOption,
            templateSelection: formatStatusOption
        });

        // Inicializar Select2 para preguntas de tipo select existentes
        $('.select2-preview').each(function() {
            $(this).select2({
                placeholder: 'Selecciona una opción...',
                allowClear: true,
                width: '100%'
            });
        });
    }
    
    // Plantillas de preguntas
    const templates = {
        test: `
            <div class="question-card mb-3" data-question-id="__ID__" data-question-type="test">
                <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background-color: #17a2b8;">
                                <i class="ti ti-list-check me-1"></i>Tipo Test
                            </span>
                            <span class="text-muted small">Pregunta #<span class="question-number">__NUM__</span></span>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                <i class="ti ti-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                <i class="ti ti-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="questions[__ID__][text]" placeholder="Escribe tu pregunta aquí...">
                            <input type="hidden" name="questions[__ID__][type]" value="test">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Opciones de respuesta</label>
                            <div class="options-container">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-circle text-muted" style="font-size: 0.8rem;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" name="questions[__ID__][options][]" placeholder="Opción 1">
                                    <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-circle text-muted" style="font-size: 0.8rem;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" name="questions[__ID__][options][]" placeholder="Opción 2">
                                    <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-add-option" data-icon="circle">
                                <i class="ti ti-plus me-1"></i>Añadir opción
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `,
        text: `
            <div class="question-card mb-3" data-question-id="__ID__" data-question-type="text">
                <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background-color: #28a745;">
                                <i class="ti ti-text-caption me-1"></i>Tipo Texto
                            </span>
                            <span class="text-muted small">Pregunta #<span class="question-number">__NUM__</span></span>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                <i class="ti ti-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                <i class="ti ti-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="questions[__ID__][text]" placeholder="Escribe tu pregunta aquí...">
                            <input type="hidden" name="questions[__ID__][type]" value="text">
                        </div>
                        <div class="p-3 rounded" style="background-color: var(--color-light); border: 1px dashed var(--color-border);">
                            <small class="text-muted d-flex align-items-center gap-2">
                                <i class="ti ti-info-circle"></i>
                                El usuario responderá con texto libre en un área de texto
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `,
        select: `
            <div class="question-card mb-3" data-question-id="__ID__" data-question-type="select">
                <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background-color: #6f42c1;">
                                <i class="ti ti-select me-1"></i>Tipo Select
                            </span>
                            <span class="text-muted small">Pregunta #<span class="question-number">__NUM__</span></span>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                <i class="ti ti-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                <i class="ti ti-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="questions[__ID__][text]" placeholder="Escribe tu pregunta aquí...">
                            <input type="hidden" name="questions[__ID__][type]" value="select">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Opciones del select</label>
                            <div class="options-container">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 option-input" name="questions[__ID__][options][]" placeholder="Opción 1">
                                    <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="ti ti-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 option-input" name="questions[__ID__][options][]" placeholder="Opción 2">
                                    <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-add-option" data-icon="chevron-right">
                                <i class="ti ti-plus me-1"></i>Añadir opción
                            </button>
                        </div>
                        <div class="mb-3 p-3 rounded" style="background-color: var(--color-light); border: 1px solid var(--color-border);">
                            <div class="form-check">
                                <input class="form-check-input allow-other-checkbox" type="checkbox" name="questions[__ID__][allow_other_option]" value="1" id="allowOther__ID__">
                                <label class="form-check-label fw-semibold small" for="allowOther__ID__">
                                    <i class="ti ti-text-plus me-1" style="color: var(--color-primary);"></i>
                                    Permitir respuesta alternativa
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1 ms-4">
                                Si el usuario no encuentra una opción adecuada, podrá escribir su propia respuesta
                            </small>
                        </div>
                        <div class="select-preview p-3 rounded" style="background-color: #f8f4ff; border: 1px dashed #6f42c1;">
                            <label class="form-label fw-semibold small d-flex align-items-center gap-2 mb-2" style="color: #6f42c1;">
                                <i class="ti ti-eye"></i>Vista previa del Select
                            </label>
                            <select class="form-select select2-preview" data-question-id="__ID__" style="width: 100%;">
                                <option value="" disabled selected>Selecciona una opción...</option>
                                <option value="1">Opción 1</option>
                                <option value="2">Opción 2</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `,
        file: `
            <div class="question-card mb-3" data-question-id="__ID__" data-question-type="file">
                <div class="card border" style="border-color: var(--color-border) !important; border-radius: 10px;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 10px 10px 0 0;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background-color: #e83e8c;">
                                <i class="ti ti-upload me-1"></i>Subir Archivo
                            </span>
                            <span class="text-muted small">Pregunta #<span class="question-number">__NUM__</span></span>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                <i class="ti ti-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                <i class="ti ti-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Texto de la pregunta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="questions[__ID__][text]" placeholder="Ej: Sube una foto de cuerpo entero...">
                            <input type="hidden" name="questions[__ID__][type]" value="file">
                        </div>
                        <div class="p-3 rounded" style="background-color: #fdf2f8; border: 1px dashed #e83e8c;">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <i class="ti ti-photo" style="font-size: 2rem; color: #e83e8c;"></i>
                                <div>
                                    <small class="fw-semibold d-block" style="color: #e83e8c;">Vista previa del campo de subida</small>
                                    <small class="text-muted">El usuario podrá subir imágenes o documentos</small>
                                </div>
                            </div>
                            <div class="border rounded p-3 bg-white text-center">
                                <i class="ti ti-cloud-upload" style="font-size: 1.5rem; color: #ccc;"></i>
                                <small class="d-block text-muted mt-1">Arrastra archivos aquí o haz clic para seleccionar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,
        info: `
            <div class="question-card mb-3" data-question-id="__ID__" data-question-type="info">
                <div class="card border" style="border-color: #fd7e14 !important; border-radius: 10px; border-width: 2px;">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3" style="border-radius: 8px 8px 0 0; background-color: #fff3e6;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background-color: #fd7e14;">
                                <i class="ti ti-info-circle me-1"></i>Instrucciones
                            </span>
                            <span class="text-muted small">Bloque #<span class="question-number">__NUM__</span></span>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-up" title="Mover arriba">
                                <i class="ti ti-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 btn-move-down" title="Mover abajo">
                                <i class="ti ti-arrow-down"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-delete-question" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3" style="background-color: #fffaf5;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small" style="color: #fd7e14;">
                                <i class="ti ti-message-circle me-1"></i>Título de las instrucciones
                            </label>
                            <input type="text" class="form-control" name="questions[__ID__][text]" placeholder="Ej: Instrucciones para las fotografías">
                            <input type="hidden" name="questions[__ID__][type]" value="info">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small" style="color: #fd7e14;">
                                <i class="ti ti-file-text me-1"></i>Contenido de las instrucciones
                            </label>
                            <textarea class="form-control info-content" name="questions[__ID__][options][]" rows="4" placeholder="Escribe las instrucciones detalladas que verá el usuario...&#10;&#10;Por ejemplo:&#10;- La foto debe ser de cuerpo entero&#10;- Con buena iluminación natural&#10;- Fondo neutro preferiblemente"></textarea>
                        </div>
                        <div class="mt-3 p-2 rounded" style="background-color: #fff; border: 1px solid #fde3ce;">
                            <small class="text-muted d-flex align-items-center gap-2">
                                <i class="ti ti-bulb" style="color: #fd7e14;"></i>
                                Este bloque mostrará información al usuario, no requiere respuesta
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `
    };
    
    // Añadir pregunta desde dropdown (solo los elementos del menú)
    document.querySelectorAll('.dropdown-menu [data-question-type]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.questionType;
            addQuestion(type);
        });
    });
    
    function addQuestion(type) {
        questionCounter++;
        const template = templates[type]
            .replace(/__ID__/g, questionCounter)
            .replace(/__NUM__/g, getVisibleQuestionsCount() + 1);
        
        // Ocultar estado vacío
        emptyState.style.display = 'none';
        
        // Añadir pregunta
        questionsContainer.insertAdjacentHTML('beforeend', template);
        
        // Inicializar Select2 si es tipo select
        if (type === 'select') {
            initSelect2ForQuestion(questionCounter);
        }
        
        // Actualizar contadores
        updateCounters();
        
        // Scroll a la nueva pregunta
        const newQuestion = questionsContainer.querySelector(`[data-question-id="${questionCounter}"]`);
        if (newQuestion) {
            newQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    // Inicializar Select2 para una pregunta específica
    function initSelect2ForQuestion(questionId) {
        const selectElement = questionsContainer.querySelector(`select[data-question-id="${questionId}"]`);
        if (selectElement && typeof $.fn.select2 !== 'undefined') {
            $(selectElement).select2({
                placeholder: 'Selecciona una opción...',
                allowClear: true,
                width: '100%'
            });
        }
    }
    
    // Actualizar opciones del Select2 preview
    function updateSelect2Options(questionCard) {
        const selectElement = questionCard.querySelector('.select2-preview');
        if (!selectElement) return;
        
        const inputs = questionCard.querySelectorAll('.option-input');
        const questionId = selectElement.dataset.questionId;
        
        // Destruir Select2 existente
        if ($(selectElement).hasClass('select2-hidden-accessible')) {
            $(selectElement).select2('destroy');
        }
        
        // Limpiar opciones existentes
        selectElement.innerHTML = '<option value="" disabled selected>Selecciona una opción...</option>';
        
        // Añadir nuevas opciones basadas en los inputs
        inputs.forEach((input, index) => {
            const value = input.value.trim() || `Opción ${index + 1}`;
            const option = document.createElement('option');
            option.value = index + 1;
            option.textContent = value;
            selectElement.appendChild(option);
        });
        
        // Reinicializar Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $(selectElement).select2({
                placeholder: 'Selecciona una opción...',
                allowClear: true,
                width: '100%'
            });
        }
    }
    
    // Delegación de eventos para botones dinámicos
    questionsContainer.addEventListener('click', function(e) {
        // Eliminar pregunta
        if (e.target.closest('.btn-delete-question')) {
            const card = e.target.closest('.question-card');
            Swal.fire({
                title: '¿Eliminar pregunta?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    card.remove();
                    updateQuestionNumbers();
                    updateCounters();
                    
                    // Mostrar estado vacío si no hay preguntas
                    if (getVisibleQuestionsCount() === 0) {
                        emptyState.style.display = 'block';
                    }
                }
            });
        }
        
        // Añadir opción
        if (e.target.closest('.btn-add-option')) {
            const btn = e.target.closest('.btn-add-option');
            const container = btn.previousElementSibling;
            const questionCard = btn.closest('.question-card');
            const questionId = questionCard.dataset.questionId;
            const questionType = questionCard.dataset.questionType;
            const icon = btn.dataset.icon || 'circle';
            const optionCount = container.querySelectorAll('.input-group').length + 1;
            
            const optionInputClass = questionType === 'select' ? 'option-input' : '';
            
            const optionHtml = `
                <div class="input-group mb-2">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="ti ti-${icon} text-muted" style="font-size: 0.8rem;"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ${optionInputClass}" name="questions[${questionId}][options][]" placeholder="Opción ${optionCount}">
                    <button type="button" class="btn btn-outline-danger border-start-0 btn-remove-option">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', optionHtml);
            
            // Actualizar Select2 preview si es tipo select
            if (questionType === 'select') {
                updateSelect2Options(questionCard);
            }
        }
        
        // Eliminar opción
        if (e.target.closest('.btn-remove-option')) {
            const inputGroup = e.target.closest('.input-group');
            const container = inputGroup.parentElement;
            const questionCard = inputGroup.closest('.question-card');
            const questionType = questionCard.dataset.questionType;
            
            // Mantener al menos 2 opciones
            if (container.querySelectorAll('.input-group').length > 2) {
                inputGroup.remove();
                
                // Actualizar Select2 preview si es tipo select
                if (questionType === 'select') {
                    updateSelect2Options(questionCard);
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Mínimo requerido',
                    text: 'Debes tener al menos 2 opciones',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
        
        // Mover arriba
        if (e.target.closest('.btn-move-up')) {
            const card = e.target.closest('.question-card');
            const prev = card.previousElementSibling;
            if (prev && prev.classList.contains('question-card')) {
                card.parentNode.insertBefore(card, prev);
                updateQuestionNumbers();
            }
        }
        
        // Mover abajo
        if (e.target.closest('.btn-move-down')) {
            const card = e.target.closest('.question-card');
            const next = card.nextElementSibling;
            if (next && next.classList.contains('question-card')) {
                card.parentNode.insertBefore(next, card);
                updateQuestionNumbers();
            }
        }
    });
    
    // Event listener para actualizar Select2 cuando el usuario escribe en los inputs de opciones
    questionsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('option-input')) {
            const questionCard = e.target.closest('.question-card');
            if (questionCard && questionCard.dataset.questionType === 'select') {
                // Debounce para no actualizar en cada tecla
                clearTimeout(questionCard.updateTimeout);
                questionCard.updateTimeout = setTimeout(() => {
                    updateSelect2Options(questionCard);
                }, 300);
            }
        }
    });
    
    function getVisibleQuestionsCount() {
        return questionsContainer.querySelectorAll('.question-card').length;
    }
    
    function updateQuestionNumbers() {
        const questions = questionsContainer.querySelectorAll('.question-card');
        questions.forEach((q, index) => {
            q.querySelector('.question-number').textContent = index + 1;
        });
    }
    
    function updateCounters() {
        const total = getVisibleQuestionsCount();
        const info = questionsContainer.querySelectorAll('[data-question-type="info"]').length;
        const test = questionsContainer.querySelectorAll('[data-question-type="test"]').length;
        const text = questionsContainer.querySelectorAll('[data-question-type="text"]').length;
        const select = questionsContainer.querySelectorAll('[data-question-type="select"]').length;
        const file = questionsContainer.querySelectorAll('[data-question-type="file"]').length;
        
        document.getElementById('totalQuestions').textContent = total;
        document.getElementById('infoQuestions').textContent = info;
        document.getElementById('testQuestions').textContent = test;
        document.getElementById('textQuestions').textContent = text;
        document.getElementById('selectQuestions').textContent = select;
        document.getElementById('fileQuestions').textContent = file;
    }
    
    // Validación del formulario
    document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const title = document.getElementById('title').value.trim();
        const questionsCount = getVisibleQuestionsCount();
        
        if (!title) {
            Swal.fire({
                icon: 'error',
                title: 'Campo requerido',
                text: 'El título del cuestionario es obligatorio'
            });
            return;
        }
        
        if (questionsCount === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Sin preguntas',
                text: 'Debes añadir al menos una pregunta al cuestionario'
            });
            return;
        }
        
        // Enviar el formulario al servidor
        this.submit();
    });
});
</script>
@endpush
