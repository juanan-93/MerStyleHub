@extends('layouts.questionnaire', ['title' => $questionnaire->title])

@section('content')
<div class="questionnaire-fullscreen" id="questionnaireApp">
    {{-- Header con barra de progreso --}}
    <div class="header-top">
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBar" role="progressbar" aria-valuemin="0" aria-valuemax="{{ $questionnaire->questions->count() }}" style="width: 0%;"></div>
        </div>
        <div class="header-logo">
            <span class="logo-text">MerStyleHub</span>
            <a href="{{ route('user-questionnaire.index') }}" class="header-exit" title="Salir">
                <i class="ti ti-x"></i>
            </a>
        </div>
    </div>

    {{-- Ya completado --}}
    @if($assignment->isCompleted())
        <div class="completed-container">
            <div class="completed-card">
                <div class="completed-icon">
                    <i class="ti ti-check"></i>
                </div>
                <h3 class="completed-title">¡Completado!</h3>
                <p class="completed-text">Ya has respondido este cuestionario con éxito.</p>
                <a href="{{ route('user-questionnaire.responses', $questionnaire->id) }}" class="btn-view-responses">
                    <i class="ti ti-eye me-2"></i>Ver mis respuestas
                </a>
            </div>
        </div>
    @else
        {{-- Formulario de Cuestionario --}}
        <form action="{{ route('user-questionnaire.store', $questionnaire->id) }}" method="POST" id="questionnaireForm">
            @csrf
            
            {{-- Slides de preguntas --}}
            <div class="slides-wrapper" id="slidesWrapper">
                @foreach($questionnaire->questions as $index => $question)
                    <div class="slide {{ $loop->first ? 'active' : '' }}" data-slide="{{ $loop->index }}" data-required="{{ $question->required ? 'true' : 'false' }}" data-type="{{ $question->type }}">
                        <div class="slide-content">
                            <div class="question-wrapper">
                                {{-- Número de pregunta --}}
                                <div class="question-number">
                                    <span class="number-badge">
                                        <span class="num">{{ $loop->iteration }}</span>
                                        <i class="ti ti-arrow-right"></i>
                                    </span>
                                </div>
                                
                                {{-- Texto de la pregunta --}}
                                <h2 class="question-text">
                                    {{ $question->text }}
                                    @if($question->required)
                                        <span class="question-required">*</span>
                                    @endif
                                </h2>

                                {{-- Contenido según tipo --}}
                                <div class="question-input">
                                    @php
                                        $existingResponse = $existingResponses[$question->id] ?? null;
                                    @endphp

                                    @switch($question->type)
                                    @case('info')
                                        {{-- Tipo informativo --}}
                                        @if($question->options->isNotEmpty())
                                            <div class="info-box">
                                                <ul class="info-list">
                                                    @foreach($question->options as $option)
                                                        <li>{{ $option->text }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <input type="hidden" name="question_{{ $question->id }}" value="read" class="question-field">
                                        @endif
                                        @break

                                    @case('test')
                                        {{-- Tipo test - opciones grandes --}}
                                        <div class="options-container">
                                            @foreach($question->options as $optionIndex => $option)
                                                <label class="option-card" for="question_{{ $question->id }}_option_{{ $option->id }}">
                                                    <div class="option-key">
                                                        <span class="key-letter">{{ chr(65 + $optionIndex) }}</span>
                                                    </div>
                                                    <input class="option-input question-field" 
                                                           type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           id="question_{{ $question->id }}_option_{{ $option->id }}"
                                                           value="{{ $option->id }}"
                                                           {{ (old('question_'.$question->id) == $option->id || ($existingResponse && $existingResponse->question_option_id == $option->id)) ? 'checked' : '' }}>
                                                    <span class="option-text">{{ $option->text }}</span>
                                                </label>
                                            @endforeach
                                            
                                            @if($question->allow_other_option)
                                                <label class="option-card option-other" for="question_{{ $question->id }}_other">
                                                    <input class="option-input question-field other-option" 
                                                           type="radio" 
                                                           name="question_{{ $question->id }}" 
                                                           id="question_{{ $question->id }}_other"
                                                           value="other"
                                                           data-target="other_input_{{ $question->id }}"
                                                           {{ old('question_'.$question->id) == 'other' ? 'checked' : '' }}>
                                                    <span class="option-text">Otro...</span>
                                                    <span class="option-check">
                                                        <i class="ti ti-check"></i>
                                                    </span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control other-text-input mt-3" 
                                                       id="other_input_{{ $question->id }}"
                                                       name="question_{{ $question->id }}_other"
                                                       placeholder="Especifica tu respuesta..."
                                                       value="{{ old('question_'.$question->id.'_other', ($existingResponse && $existingResponse->question_option_id === null ? $existingResponse->text_response : '')) }}"
                                                       style="{{ old('question_'.$question->id) != 'other' ? 'display: none;' : '' }}">
                                            @endif
                                        </div>
                                        @break

                                    @case('select')
                                        {{-- Tipo select --}}
                                        <div class="select-container">
                                            <select class="form-select-cute question-field" 
                                                    name="question_{{ $question->id }}" 
                                                    id="question_{{ $question->id }}">
                                                <option value="">Selecciona una opción...</option>
                                                @foreach($question->options as $option)
                                                    <option value="{{ $option->id }}" 
                                                        {{ (old('question_'.$question->id) == $option->id || ($existingResponse && $existingResponse->question_option_id == $option->id)) ? 'selected' : '' }}>
                                                        {{ $option->text }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @break

                                    @case('text')
                                        {{-- Tipo texto --}}
                                        <div class="text-container">
                                            <textarea class="form-control-cute question-field" 
                                                      name="question_{{ $question->id }}" 
                                                      id="question_{{ $question->id }}"
                                                      rows="4" 
                                                      placeholder="Escribe tu respuesta aquí...">{{ old('question_'.$question->id, $existingResponse?->text_response) }}</textarea>
                                        </div>
                                        @break

                                    @case('file')
                                        {{-- Tipo archivo --}}
                                        <div class="file-container">
                                            <label class="file-upload-card" for="question_{{ $question->id }}">
                                                <div class="file-icon">
                                                    <i class="ti ti-cloud-upload"></i>
                                                </div>
                                                <span class="file-text">Toca para subir archivo</span>
                                                <small class="file-hint">JPG, PNG, PDF (máx. 5MB)</small>
                                            </label>
                                            <input type="file" 
                                                   class="question-field" 
                                                   name="question_{{ $question->id }}" 
                                                   id="question_{{ $question->id }}"
                                                   style="display: none;">
                                            <div class="file-name mt-2" id="fileName_{{ $question->id }}"></div>
                                        </div>
                                        @break

                                    @default
                                        {{-- Tipo por defecto --}}
                                        <div class="text-container">
                                            <input type="text" 
                                                   class="form-control-cute question-field" 
                                                   name="question_{{ $question->id }}" 
                                                   id="question_{{ $question->id }}"
                                                   placeholder="Tu respuesta..."
                                                   value="{{ old('question_'.$question->id, $existingResponse?->text_response) }}">
                                        </div>
                                @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Slide final de confirmación --}}
                <div class="slide" data-slide="{{ $questionnaire->questions->count() }}" data-type="final">
                    <div class="slide-content">
                        <div class="final-content">
                            <div class="final-icon">
                                <i class="ti ti-check"></i>
                            </div>
                            <h2 class="final-title">¡Listo!</h2>
                            <p class="final-text">Revisa tus respuestas antes de enviar.</p>
                            
                            <div class="final-actions">
                                <button type="button" class="btn-review" id="reviewBtn">
                                    <i class="ti ti-eye"></i>
                                    <span>Revisar</span>
                                </button>
                                <button type="submit" class="btn-submit" id="submitBtn">
                                    <span>Enviar</span>
                                    <i class="ti ti-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navegación fija inferior --}}
            <div class="questionnaire-nav">
                <button type="button" class="btn-nav btn-prev" id="prevBtn" style="visibility: hidden;">
                    <i class="ti ti-chevron-up"></i>
                </button>
                <button type="button" class="btn-nav" id="downBtn">
                    <i class="ti ti-chevron-down"></i>
                </button>
                
                <button type="button" class="btn-nav-next" id="nextBtn">
                    <span>OK</span>
                    <i class="ti ti-check"></i>
                </button>
            </div>
        </form>
    @endif
</div>

{{-- Modal de revisión --}}
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content modal-review">
            <div class="modal-header">
                <h5 class="modal-title">Revisar respuestas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reviewContent">
                {{-- Se llenará con JavaScript --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ===== VARIABLES ===== */
    :root {
        --tf-primary: #A08A7A;
        --tf-black: #000000;
        --tf-white: #FFFFFF;
        --tf-bg: #FFFFFF;
        --tf-border: rgba(0, 0, 0, 0.14);
        --tf-border-hover: rgba(0, 0, 0, 0.6);
        --tf-text: #191919;
        --tf-text-light: #5E5E5E;
        --tf-option-bg: transparent;
        --tf-option-border: rgba(0, 0, 0, 0.22);
        --font-main: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    /* ===== BASE ===== */
    .questionnaire-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: var(--tf-bg);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: var(--font-main);
    }
    
    /* ===== FORM WRAPPER ===== */
    #questionnaireForm {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        position: relative;
    }
    
    /* ===== HEADER - Typeform Style ===== */
    .header-top {
        position: relative;
        flex-shrink: 0;
    }
    
    .progress-bar-container {
        height: 4px;
        background: var(--tf-border);
        width: 100%;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: var(--tf-primary);
        transition: width 0.4s ease;
        width: 0%;
    }
    
    .header-logo {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .logo-text {
        font-size: 14px;
        font-weight: 600;
        color: var(--tf-text);
        letter-spacing: 0.5px;
    }
    
    .header-exit {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        background: transparent;
        border: 1px solid var(--tf-border);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--tf-text-light);
    }
    
    .header-exit:hover {
        border-color: var(--tf-border-hover);
        color: var(--tf-text);
    }
    
    /* ===== SLIDES ===== */
    .slides-wrapper {
        flex: 1;
        position: relative;
        overflow: hidden;
        min-height: 0;
    }
    
    .slide {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.4s ease;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .slide.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .slide.exit-left {
        opacity: 0;
        transform: translateY(-20px);
    }
    
    .slide-content {
        width: 100%;
        max-width: 720px;
        padding: 40px 24px 120px;
    }
    
    .slide-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* ===== QUESTION WRAPPER - Typeform Style ===== */
    .question-wrapper {
        width: 100%;
    }
    
    .question-number {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .number-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        font-weight: 400;
        color: var(--tf-text);
    }
    
    .number-badge .num {
        font-weight: 500;
    }
    
    .number-badge i {
        font-size: 12px;
        color: var(--tf-text-light);
    }
    
    .question-text {
        font-size: 24px;
        font-weight: 400;
        color: var(--tf-text);
        line-height: 1.4;
        margin-bottom: 8px;
    }
    
    .question-required {
        color: #E53935;
        font-size: 24px;
        font-weight: 400;
    }
    
    .question-input {
        margin-top: 32px;
    }
    
    /* ===== OPTIONS - Typeform Style ===== */
    .options-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .option-card {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: var(--tf-option-bg);
        border: 1px solid var(--tf-option-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
    }
    
    .option-card:hover {
        border-color: var(--tf-border-hover);
        background: rgba(0, 0, 0, 0.02);
    }
    
    .option-card:has(.option-input:checked) {
        border-color: var(--tf-primary);
        background: rgba(160, 138, 122, 0.08);
    }
    
    .option-key {
        width: 24px;
        height: 24px;
        border: 1px solid var(--tf-option-border);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }
    
    .key-letter {
        font-size: 12px;
        font-weight: 500;
        color: var(--tf-text);
    }
    
    .option-card:has(.option-input:checked) .option-key {
        background: var(--tf-primary);
        border-color: var(--tf-primary);
    }
    
    .option-card:has(.option-input:checked) .key-letter {
        color: var(--tf-white);
    }
    
    .option-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .option-text {
        flex: 1;
        font-size: 16px;
        color: var(--tf-text);
        font-weight: 400;
        line-height: 1.4;
    }
    
    /* ===== INFO BOX ===== */
    .info-box {
        background: #F9F9F9;
        border-radius: 8px;
        padding: 20px;
        border-left: 3px solid var(--tf-primary);
        margin-top: 16px;
    }
    
    .info-list {
        margin: 0;
        padding-left: 20px;
    }
    
    .info-list li {
        margin-bottom: 8px;
        color: var(--tf-text);
        line-height: 1.5;
        font-size: 14px;
    }
    
    .info-list li:last-child {
        margin-bottom: 0;
    }
    
    .info-list li::marker {
        color: var(--tf-primary);
    }
    
    /* ===== INPUTS - Typeform Style ===== */
    .form-control-cute,
    .form-select-cute {
        padding: 12px 0;
        font-size: 24px;
        font-family: var(--font-main);
        border-radius: 0;
        border: none;
        border-bottom: 2px solid var(--tf-border);
        background: transparent;
        transition: all 0.2s ease;
        width: 100%;
        color: var(--tf-text);
    }
    
    .form-control-cute:focus,
    .form-select-cute:focus {
        border-bottom-color: var(--tf-primary);
        box-shadow: none;
        outline: none;
    }
    
    .form-control-cute::placeholder {
        color: #AAAAAA;
    }
    
    textarea.form-control-cute {
        min-height: 100px;
        resize: vertical;
        font-size: 18px;
    }
    
    .other-text-input {
        border: none;
        border-bottom: 1px solid var(--tf-border);
        border-radius: 0;
        padding: 8px 0;
        font-family: var(--font-main);
        width: 100%;
        transition: all 0.2s ease;
        font-size: 16px;
        background: transparent;
    }
    
    .other-text-input:focus {
        border-bottom-color: var(--tf-primary);
        box-shadow: none;
        outline: none;
    }
    
    /* ===== FILE UPLOAD - Typeform Style ===== */
    .file-upload-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 24px;
        background: transparent;
        border: 1px dashed var(--tf-option-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .file-upload-card:hover {
        border-color: var(--tf-border-hover);
        background: rgba(0, 0, 0, 0.02);
    }
    
    .file-upload-card .file-icon {
        width: 56px;
        height: 56px;
        background: rgba(160, 138, 122, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    
    .file-upload-card .file-icon i {
        font-size: 24px;
        color: var(--tf-primary);
    }
    
    .file-text {
        font-weight: 500;
        color: var(--tf-text);
        margin-bottom: 4px;
        font-size: 16px;
    }
    
    .file-hint {
        font-size: 14px;
        color: var(--tf-text-light);
    }
    
    .file-name {
        text-align: center;
        font-size: 14px;
        color: var(--tf-primary);
        font-weight: 500;
        margin-top: 12px;
        padding: 8px 16px;
        background: rgba(160, 138, 122, 0.1);
        border-radius: 4px;
    }
    
    /* ===== SLIDE FINAL - Typeform Style ===== */
    .final-content {
        text-align: center;
        padding: 40px 24px;
    }
    
    .final-icon {
        width: 80px;
        height: 80px;
        background: var(--tf-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    
    .final-icon i {
        font-size: 36px;
        color: white;
    }
    
    .final-title {
        font-size: 28px;
        font-weight: 400;
        color: var(--tf-text);
        margin-bottom: 12px;
    }
    
    .final-text {
        color: var(--tf-text-light);
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 32px;
    }
    
    .final-actions {
        max-width: 300px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .btn-review {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: 500;
        font-family: var(--font-main);
        color: var(--tf-text);
        background: transparent;
        border: 1px solid var(--tf-option-border);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-review:hover {
        border-color: var(--tf-border-hover);
        color: var(--tf-text);
    }
    
    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: 500;
        font-family: var(--font-main);
        color: white;
        background: var(--tf-primary);
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-submit:hover {
        background: #8a7668;
    }
    
    /* ===== NAVIGATION - Typeform Style ===== */
    .questionnaire-nav {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 16px 24px;
        background: var(--tf-white);
        gap: 8px;
    }
    
    .btn-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        font-family: var(--font-main);
        color: var(--tf-text);
        background: transparent;
        border: 1px solid var(--tf-border);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    
    .btn-nav:hover {
        border-color: var(--tf-border-hover);
    }
    
    .btn-nav span {
        display: none;
    }
    
    .btn-nav-next {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 500;
        font-family: var(--font-main);
        font-size: 14px;
        color: white;
        background: var(--tf-primary);
        border: none;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    
    .btn-nav-next:hover:not(:disabled) {
        background: #8a7668;
    }
    
    .btn-nav-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* ===== ANIMATIONS ===== */
    .slide.active .question-wrapper {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* ===== MODAL REVIEW - Clean Style ===== */
    #reviewModal .modal-content {
        border: none;
        border-radius: 8px;
        overflow: hidden;
    }
    
    #reviewModal .modal-header {
        background: var(--tf-primary);
        color: white;
        border: none;
        padding: 16px 20px;
    }
    
    #reviewModal .modal-title {
        font-weight: 500;
        font-size: 16px;
    }
    
    #reviewModal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    
    #reviewModal .modal-body {
        padding: 0;
    }
    
    #reviewModal .modal-footer {
        border-top: 1px solid var(--tf-border);
        padding: 12px 20px;
    }
    
    .btn-modal-close {
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        font-family: var(--font-main);
        color: var(--tf-text);
        background: #F5F5F5;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-modal-close:hover {
        background: #EBEBEB;
    }
    
    .review-item {
        padding: 16px 20px;
        border-bottom: 1px solid var(--tf-border);
    }
    
    .review-item:hover {
        background-color: #FAFAFA;
    }
    
    .review-item:last-child {
        border-bottom: none;
    }
    
    .review-question {
        font-weight: 500;
        color: var(--tf-text);
        margin-bottom: 8px;
        font-size: 14px;
        font-family: var(--font-main);
    }
    
    .review-answer {
        color: var(--tf-primary);
        background: rgba(160, 138, 122, 0.1);
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-family: var(--font-main);
    }
    
    .review-answer.empty {
        color: #9E9E9E;
        font-style: italic;
        background: #F5F5F5;
    }
    
    /* ===== COMPLETED STATE ===== */
    .completed-container {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        padding: 40px 24px;
    }
    
    .completed-card {
        text-align: center;
        background: var(--tf-white);
        padding: 48px 32px;
        max-width: 400px;
        width: 100%;
    }
    
    .completed-icon {
        width: 72px;
        height: 72px;
        background: #4CAF50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    
    .completed-icon i {
        font-size: 32px;
        color: white;
    }
    
    .completed-title {
        font-size: 24px;
        font-weight: 400;
        color: var(--tf-text);
        margin-bottom: 8px;
    }
    
    .completed-text {
        color: var(--tf-text-light);
        margin-bottom: 24px;
        font-size: 16px;
    }
    
    .btn-view-responses {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: 500;
        font-family: var(--font-main);
        color: white;
        background: var(--tf-primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-view-responses:hover {
        background: #8a7668;
        color: white;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 576px) {
        .header-logo {
            padding: 12px 16px;
        }
        
        .slide-content {
            padding: 24px 16px 100px;
        }
        
        .question-text {
            font-size: 20px;
        }
        
        .option-card {
            padding: 10px 12px;
        }
        
        .option-text {
            font-size: 14px;
        }
        
        .questionnaire-nav {
            padding: 12px 16px;
        }
        
        .btn-nav-next {
            flex: 1;
            justify-content: center;
        }
        
        .final-icon {
            width: 64px;
            height: 64px;
        }
        
        .final-icon i {
            font-size: 28px;
        }
        
        .final-title {
            font-size: 22px;
        }
        
        .completed-card {
            padding: 32px 24px;
        }
        
        .form-control-cute,
        .form-select-cute {
            font-size: 18px;
        }
    }
    
    /* ===== TABLET ===== */
    @media (min-width: 577px) and (max-width: 768px) {
        .slide-content {
            max-width: 560px;
        }
    }
    
    /* ===== DESKTOP ===== */
    @media (min-width: 769px) {
        .header-logo {
            padding: 20px 32px;
        }
        
        .slide-content {
            max-width: 720px;
            padding: 60px 32px 120px;
        }
        
        .questionnaire-nav {
            padding: 20px 32px;
        }
        
        .question-text {
            font-size: 28px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;
    const totalQuestions = totalSlides - 1; // Excluyendo el slide final
    let currentSlide = 0;
    
    const prevBtn = document.getElementById('prevBtn');
    const downBtn = document.getElementById('downBtn');
    const nextBtn = document.getElementById('nextBtn');
    const progressBar = document.getElementById('progressBar');
    const form = document.getElementById('questionnaireForm');
    const reviewBtn = document.getElementById('reviewBtn');
    const reviewModal = document.getElementById('reviewModal');
    
    // Inicializar
    updateProgress();
    updateNavButtons();
    checkCurrentSlideAnswer();
    
    // Botón anterior (arriba)
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        });
    }
    
    // Botón siguiente (abajo)
    if (downBtn) {
        downBtn.addEventListener('click', function() {
            if (currentSlide < totalSlides - 1) {
                goToSlide(currentSlide + 1);
            }
        });
    }
    
    // Botón OK/siguiente
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentSlide < totalSlides - 1) {
                const currentSlideEl = slides[currentSlide];
                const isRequired = currentSlideEl.dataset.required === 'true';
                const type = currentSlideEl.dataset.type;
                
                // Si es slide final, no avanzar (usar el botón submit)
                if (type === 'final') return;
                
                // Validar si es requerido
                if (isRequired && !isSlideAnswered(currentSlideEl)) {
                    shakeButton();
                    return;
                }
                
                goToSlide(currentSlide + 1);
            }
        });
    }
    
    // Ir a un slide específico
    function goToSlide(index) {
        if (index < 0 || index >= totalSlides) return;
        
        const currentEl = slides[currentSlide];
        const nextEl = slides[index];
        
        // Animación de salida
        currentEl.classList.remove('active');
        currentEl.classList.add(index > currentSlide ? 'exit-left' : '');
        
        // Cambiar índice
        currentSlide = index;
        
        // Animación de entrada
        setTimeout(() => {
            slides.forEach(s => s.classList.remove('exit-left'));
            nextEl.classList.add('active');
            updateProgress();
            updateNavButtons();
            checkCurrentSlideAnswer();
        }, 50);
    }
    
    // Actualizar barra de progreso
    function updateProgress() {
        const progress = totalQuestions > 0 ? ((currentSlide + 1) / totalQuestions) * 100 : 0;
        const displayProgress = Math.min(progress, 100);
        
        if (progressBar) {
            progressBar.style.width = displayProgress + '%';
        }
    }
    
    // Actualizar botones de navegación
    function updateNavButtons() {
        if (prevBtn) {
            prevBtn.style.visibility = currentSlide === 0 ? 'hidden' : 'visible';
        }
        
        if (nextBtn) {
            const currentType = slides[currentSlide].dataset.type;
            if (currentType === 'final') {
                nextBtn.style.display = 'none';
            } else {
                nextBtn.style.display = 'flex';
                const btnText = nextBtn.querySelector('span');
                if (btnText) {
                    btnText.textContent = currentSlide === totalQuestions - 1 ? 'Finalizar' : 'OK';
                }
            }
        }
    }
    
    // Verificar si el slide actual está respondido
    function isSlideAnswered(slideEl) {
        const type = slideEl.dataset.type;
        
        if (type === 'info' || type === 'final') return true;
        
        const inputs = slideEl.querySelectorAll('.question-field');
        let answered = false;
        
        inputs.forEach(input => {
            if (input.type === 'radio' || input.type === 'checkbox') {
                if (input.checked) answered = true;
            } else if (input.type === 'file') {
                if (input.files && input.files.length > 0) answered = true;
            } else if (input.tagName === 'SELECT') {
                if (input.value) answered = true;
            } else {
                if (input.value.trim()) answered = true;
            }
        });
        
        return answered;
    }
    
    // Verificar respuesta del slide actual y habilitar/deshabilitar botón
    function checkCurrentSlideAnswer() {
        const currentEl = slides[currentSlide];
        const isRequired = currentEl.dataset.required === 'true';
        const type = currentEl.dataset.type;
        
        if (type === 'info' || type === 'final' || !isRequired) {
            nextBtn.disabled = false;
        } else {
            nextBtn.disabled = !isSlideAnswered(currentEl);
        }
    }
    
    // Escuchar cambios en los inputs
    document.querySelectorAll('.question-field').forEach(input => {
        const eventType = (input.type === 'radio' || input.type === 'checkbox' || input.type === 'file' || input.tagName === 'SELECT') 
            ? 'change' 
            : 'input';
            
        input.addEventListener(eventType, function() {
            checkCurrentSlideAnswer();
            
            // Auto-avanzar para opciones de test (radio buttons)
            if (input.type === 'radio' && !input.classList.contains('other-option')) {
                setTimeout(() => {
                    if (currentSlide < totalSlides - 1) {
                        goToSlide(currentSlide + 1);
                    }
                }, 300);
            }
        });
    });
    
    // Manejar campos "Otro"
    document.querySelectorAll('.other-option').forEach(radio => {
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
    document.querySelectorAll('input[type="radio"]:not(.other-option)').forEach(radio => {
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
    
    // Manejar archivos
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileNameEl = document.getElementById('fileName_' + this.id.split('_').pop());
            if (fileNameEl && this.files.length > 0) {
                fileNameEl.innerHTML = '<i class="ti ti-file me-1"></i>' + this.files[0].name;
            }
        });
    });
    
    // Shake del botón si no está respondido
    function shakeButton() {
        nextBtn.classList.add('shake');
        setTimeout(() => nextBtn.classList.remove('shake'), 500);
    }
    
    // Botón revisar respuestas
    if (reviewBtn) {
        reviewBtn.addEventListener('click', function() {
            generateReview();
            new bootstrap.Modal(reviewModal).show();
        });
    }
    
    // Generar contenido de revisión
    function generateReview() {
        const reviewContent = document.getElementById('reviewContent');
        let html = '';
        
        slides.forEach((slide, index) => {
            if (slide.dataset.type === 'final') return;
            
            const questionText = slide.querySelector('.question-text')?.textContent?.replace('*', '').trim();
            let answerText = '';
            
            const inputs = slide.querySelectorAll('.question-field');
            inputs.forEach(input => {
                if (input.type === 'radio' && input.checked) {
                    const label = slide.querySelector(`label[for="${input.id}"] .option-text`);
                    answerText = label ? label.textContent : input.value;
                    
                    // Si es "otro", obtener el texto
                    if (input.value === 'other') {
                        const otherInput = document.getElementById(input.dataset.target);
                        if (otherInput && otherInput.value) {
                            answerText = 'Otro: ' + otherInput.value;
                        }
                    }
                } else if (input.tagName === 'SELECT' && input.value) {
                    answerText = input.options[input.selectedIndex].text;
                } else if (input.tagName === 'TEXTAREA' || input.type === 'text') {
                    answerText = input.value;
                } else if (input.type === 'hidden' && input.value === 'read') {
                    answerText = 'Información leída ✓';
                } else if (input.type === 'file' && input.files.length > 0) {
                    answerText = input.files[0].name;
                }
            });
            
            const isEmpty = !answerText || answerText.trim() === '';
            
            html += `
                <div class="review-item">
                    <div class="review-question">${index + 1}. ${questionText}</div>
                    <div class="review-answer ${isEmpty ? 'empty' : ''}">${isEmpty ? 'Sin respuesta' : answerText}</div>
                </div>
            `;
        });
        
        reviewContent.innerHTML = html;
    }
    
    // Confirmación al salir
    if (exitBtn) {
        exitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de salir? Los cambios no guardados se perderán.')) {
                window.location.href = '{{ route("user-questionnaire.index") }}';
            }
        });
    }
    
    // Confirmación al enviar
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de enviar tus respuestas? Una vez enviadas no podrás modificarlas.')) {
                e.preventDefault();
            }
        });
    }
    
    // Navegación con teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowRight' || e.key === 'Enter') {
            if (document.activeElement.tagName !== 'TEXTAREA') {
                nextBtn.click();
            }
        } else if (e.key === 'ArrowLeft') {
            prevBtn.click();
        }
    });
});
</script>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .shake { animation: shake 0.3s ease-in-out; }
</style>
@endpush
