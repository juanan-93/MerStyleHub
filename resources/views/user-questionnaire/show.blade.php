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
        {{-- DEBUG: Mostrando action URL --}}
        @php 
            $actionUrl = route('user-questionnaire.store', $questionnaire->id);
            \Log::info('Form action URL: ' . $actionUrl);
        @endphp
        <form action="{{ $actionUrl }}" method="POST" id="questionnaireForm" enctype="multipart/form-data">
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
                                            <select class="form-select-cute question-field select2-field" 
                                                    name="question_{{ $question->id }}" 
                                                    id="question_{{ $question->id }}"
                                                    data-placeholder="Selecciona una opción..."
                                                    @if($question->allow_other_option) data-allow-other="true" @endif>
                                                <option value=""></option>
                                                @foreach($question->options as $option)
                                                    <option value="{{ $option->id }}" 
                                                        {{ (old('question_'.$question->id) == $option->id || ($existingResponse && $existingResponse->question_option_id == $option->id)) ? 'selected' : '' }}>
                                                        {{ $option->text }}
                                                    </option>
                                                @endforeach
                                                @if($question->allow_other_option)
                                                    <option value="other" {{ old('question_'.$question->id) == 'other' ? 'selected' : '' }}>Otro...</option>
                                                @endif
                                            </select>
                                        </div>
                                        @if($question->allow_other_option)
                                            <textarea class="form-control other-textarea mt-3 select-other-input" 
                                                   id="select_other_input_{{ $question->id }}"
                                                   name="question_{{ $question->id }}_other"
                                                   rows="3"
                                                   placeholder="Especifica tu respuesta..."
                                                   style="{{ old('question_'.$question->id) != 'other' ? 'display: none;' : '' }}">{{ old('question_'.$question->id.'_other', ($existingResponse && $existingResponse->question_option_id === null ? $existingResponse->text_response : '')) }}</textarea>
                                        @endif
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
                                        {{-- Tipo archivo múltiple --}}
                                        <div class="file-container">
                                            <div class="file-upload-card file-dropzone" 
                                                 data-input="question_{{ $question->id }}"
                                                 onclick="document.getElementById('question_{{ $question->id }}').click()">
                                                <div class="file-icon">
                                                    <i class="ti ti-cloud-upload"></i>
                                                </div>
                                                <span class="file-text">Arrastra tus archivos aquí o haz clic para seleccionar</span>
                                                <small class="file-hint">JPG, PNG, PDF (máx. 5MB por archivo) · Puedes subir varios</small>
                                            </div>
                                            <input type="file" 
                                                   class="question-field file-input-hidden" 
                                                   name="question_{{ $question->id }}[]" 
                                                   id="question_{{ $question->id }}"
                                                   accept="image/*,.pdf"
                                                   multiple>
                                            <div class="file-list mt-3" id="fileList_{{ $question->id }}"></div>
                                        </div>
                                        @break

                                    @default
                                        {{-- Tipo por defecto --}}
                                        <div class="text-container">
                                            <input type="text" 
                                                   class="form-control-cute question-field" 
                                                   name="question_{{ $question->id }}" 
                                                   id="question_{{ $question->id }}"
                                                   placeholder="Escribe aquí..."
                                                   value="{{ old('question_'.$question->id, $existingResponse?->text_response) }}">
                                        </div>
                                @endswitch
                                </div>
                                
                                {{-- Navegación integrada en la pregunta --}}
                                <div class="question-nav">
                                    <button type="button" class="btn-nav btn-arrow btn-prev-q" title="Anterior">
                                        <i class="ti ti-chevron-left"></i>
                                    </button>
                                    
                                    <button type="button" class="btn-nav-next btn-continue-q">
                                        <span>Continuar</span>
                                        <i class="ti ti-arrow-right"></i>
                                    </button>
                                    
                                    <button type="button" class="btn-nav btn-arrow btn-next-q" title="Siguiente">
                                        <i class="ti ti-chevron-right"></i>
                                    </button>
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
                                <button type="button" class="btn-back-final" id="backToQuestionsBtn">
                                    <i class="ti ti-arrow-left"></i>
                                    <span>Volver</span>
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
        </form>
    @endif
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
        max-width: 1000px;
        padding: 40px 20px 120px;
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
        max-width: 900px;
        margin: 0 auto;
        padding: 32px;
        background: var(--tf-white);
        border-radius: 12px;
    }
    
    .question-number {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }
    
    .number-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-size: 16px;
        font-weight: 400;
        color: var(--tf-text-light);
    }
    
    .number-badge .num {
        font-size: 23px;
        font-weight: 700;
        color: var(--tf-primary);
    }
    
    .number-badge i {
        font-size: 10px;
        color: var(--tf-text-light);
    }
    
    .question-text {
        font-size: 17px;
        font-weight: 400;
        color: var(--tf-text);
        line-height: 1.7;
        margin-bottom: 0;
    }
    
    .question-required {
        color: #E53935;
        font-size: 17px;
        font-weight: 400;
    }
    
    .question-input {
        margin-top: 32px;
    }
    
    /* ===== OPTIONS - Typeform Style ===== */
    .options-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .option-card {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        background: var(--tf-option-bg);
        border: 1px solid var(--tf-option-border);
        border-radius: 6px;
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
    
    /* ===== INPUT LABEL ===== */
    .input-label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        color: var(--tf-text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    /* ===== TEXT CONTAINER ===== */
    .text-container {
        background: #FAFAFA;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid var(--tf-border);
        transition: all 0.2s ease;
    }
    
    .text-container:focus-within {
        border-color: var(--tf-primary);
        background: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.1);
    }
    
    /* ===== INPUTS - Typeform Style ===== */
    .form-control-cute {
        padding: 12px 0;
        font-size: 16px;
        font-family: var(--font-main);
        border-radius: 0;
        border: none;
        border-bottom: 2px solid var(--tf-border);
        background: transparent;
        transition: all 0.2s ease;
        width: 100%;
        color: var(--tf-text);
    }
    
    .form-control-cute:focus {
        border-bottom-color: var(--tf-primary);
        box-shadow: none;
        outline: none;
    }
    
    .form-control-cute::placeholder {
        color: #AAAAAA;
        font-size: 15px;
    }
    
    textarea.form-control-cute {
        min-height: 120px;
        resize: none;
        font-size: 16px;
        line-height: 1.6;
    }
    
    /* ===== SELECT CONTAINER ===== */
    .select-container {
        background: #FAFAFA;
        border-radius: 8px;
        padding: 16px 20px;
        border: 1px solid var(--tf-border);
        transition: all 0.2s ease;
    }
    
    .select-container:focus-within {
        border-color: var(--tf-primary);
        background: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.1);
    }
    
    .form-select-cute {
        padding: 8px 0;
        font-size: 16px;
        font-family: var(--font-main);
        border: none;
        background: transparent;
        width: 100%;
        color: var(--tf-text);
    }
    
    .form-select-cute:focus {
        box-shadow: none;
        outline: none;
    }
    
    /* ===== SELECT2 STYLES ===== */
    .select2-container--default .select2-selection--single {
        border: 1px solid var(--tf-border) !important;
        border-radius: 8px !important;
        height: auto !important;
        padding: 0 !important;
        background-color: var(--tf-white) !important;
    }
    
    .select2-container--default .select2-selection--single:focus {
        outline: none;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--tf-primary) !important;
        box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15) !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--tf-text) !important;
        font-size: 16px !important;
        line-height: 1.5 !important;
        padding: 10px 14px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #999999 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 10px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: var(--tf-primary) transparent transparent transparent !important;
    }
    
    .select2-dropdown {
        border: 1px solid var(--tf-border) !important;
        border-radius: 8px !important;
        background-color: var(--tf-white) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12) !important;
        margin-top: 4px;
        z-index: 99999 !important;
    }
    
    .select2-container--open {
        z-index: 99999 !important;
    }
    
    .select2-container--default .select2-results__option {
        padding: 12px 16px !important;
        font-size: 15px !important;
        color: var(--tf-text) !important;
        transition: all 0.15s ease;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(160, 138, 122, 0.15) !important;
        color: var(--tf-text) !important;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(160, 138, 122, 0.1) !important;
        color: var(--tf-primary) !important;
        font-weight: 500 !important;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid var(--tf-border) !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--tf-primary) !important;
        outline: none !important;
    }
    
    .other-text-input,
    .other-textarea {
        border: 1px solid var(--tf-border);
        border-radius: 8px;
        padding: 12px 14px;
        font-family: var(--font-main);
        width: 100%;
        transition: all 0.2s ease;
        font-size: 15px;
        background: var(--tf-white);
        resize: none;
    }
    
    .other-text-input:focus,
    .other-textarea:focus {
        border-color: var(--tf-primary);
        box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15);
        outline: none;
    }
    
    /* ===== FILE UPLOAD - Typeform Style ===== */
    .file-upload-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 24px;
        background: #FAFAFA;
        border: 2px dashed var(--tf-border);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .file-upload-card:hover {
        border-color: var(--tf-primary);
        background: rgba(160, 138, 122, 0.05);
    }
    
    .file-upload-card.drag-over {
        border-color: var(--tf-primary);
        background-color: rgba(160, 138, 122, 0.12);
        border-style: solid;
        transform: scale(1.01);
    }
    
    .file-upload-card.drag-over .file-icon {
        transform: scale(1.15);
        background: var(--tf-primary);
    }
    
    .file-upload-card.drag-over .file-icon i {
        color: white;
    }
    
    .file-upload-card.has-file {
        border-color: var(--tf-primary);
        background-color: rgba(160, 138, 122, 0.08);
        border-style: solid;
    }
    
    .file-input-hidden {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
        pointer-events: none;
    }
    
    .file-upload-card .file-icon {
        width: 56px;
        height: 56px;
        background: rgba(160, 138, 122, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }
    
    .file-upload-card .file-icon i {
        font-size: 24px;
        color: var(--tf-primary);
        transition: all 0.2s ease;
    }
    
    .file-text {
        font-weight: 500;
        color: var(--tf-text);
        margin-bottom: 4px;
        font-size: 16px;
        text-align: center;
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
    
    .file-name:empty {
        display: none;
    }
    
    /* Lista de archivos múltiples */
    .file-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .file-list:empty {
        display: none;
    }
    
    .file-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: rgba(160, 138, 122, 0.08);
        border-radius: 8px;
        border: 1px solid rgba(160, 138, 122, 0.15);
    }
    
    .file-item-icon {
        width: 36px;
        height: 36px;
        background: var(--tf-primary);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .file-item-icon i {
        font-size: 18px;
        color: white;
    }
    
    .file-item-info {
        flex: 1;
        min-width: 0;
    }
    
    .file-item-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--tf-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .file-item-size {
        font-size: 12px;
        color: var(--tf-text-light);
    }
    
    .file-item-remove {
        width: 28px;
        height: 28px;
        border: none;
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    
    .file-item-remove:hover {
        background: #dc3545;
        color: white;
    }
    
    .file-item-remove i {
        font-size: 14px;
    }
    
    .file-counter {
        text-align: center;
        font-size: 13px;
        color: var(--tf-text-light);
        margin-top: 8px;
    }
    
    .file-counter strong {
        color: var(--tf-primary);
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
        max-width: 400px;
        margin: 0 auto;
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .btn-back-final {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: 500;
        font-family: var(--font-main);
        color: var(--tf-text-light);
        background: transparent;
        border: 1px solid var(--tf-border);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-back-final:hover {
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
    
    /* ===== NAVIGATION - Integrada en la pregunta ===== */
    .question-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 32px;
        gap: 12px;
    }
    
    .btn-nav.btn-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        font-family: var(--font-main);
        color: var(--tf-text);
        background: transparent;
        border: 1px solid var(--tf-border);
        transition: all 0.15s ease;
        cursor: pointer;
    }
    
    .btn-nav.btn-arrow:hover:not(:disabled) {
        border-color: var(--tf-primary);
        color: var(--tf-primary);
        background: rgba(160, 138, 122, 0.05);
    }
    
    .btn-nav.btn-arrow:disabled,
    .btn-nav.btn-arrow.hidden {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .btn-nav.btn-arrow.invisible {
        visibility: hidden;
    }
    
    .btn-nav.btn-arrow i {
        font-size: 20px;
    }
    
    .btn-nav-next {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 32px;
        border-radius: 50px;
        font-weight: 600;
        font-family: var(--font-main);
        font-size: 15px;
        color: white;
        background: var(--tf-primary);
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
        min-width: 160px;
    }
    
    .btn-nav-next:hover:not(:disabled) {
        background: #8a7668;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3);
    }
    
    .btn-nav-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-nav-next i {
        font-size: 18px;
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
        
        .question-wrapper {
            max-width: 100%;
            padding: 20px;
        }
        
        .question-text {
            font-size: 15px;
            line-height: 1.6;
        }
        
        .question-required {
            font-size: 15px;
        }
        
        .option-card {
            padding: 12px 14px;
        }
        
        .option-text {
            font-size: 14px;
        }
        
        .text-container,
        .select-container {
            padding: 16px;
        }
        
        .form-control-cute {
            font-size: 15px;
        }
        
        .questionnaire-nav {
            padding: 12px 16px;
            gap: 8px;
        }
        
        .btn-nav-next {
            flex: 1;
            min-width: auto;
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .btn-nav.btn-arrow {
            width: 40px;
            height: 40px;
        }
        
        .btn-nav.btn-arrow i {
            font-size: 18px;
        }
        
        .final-icon {
            width: 64px;
            height: 64px;
        }
        
        .final-icon i {
            font-size: 28px;
        }
        
        .final-title {
            font-size: 20px;
        }
        
        .completed-card {
            padding: 32px 24px;
        }
    }
    
    /* ===== TABLET ===== */
    @media (min-width: 577px) and (max-width: 768px) {
        .slide-content {
            max-width: 95%;
        }
    }
    
    /* ===== DESKTOP ===== */
    @media (min-width: 769px) {
        .header-logo {
            padding: 20px 32px;
        }
        
        .slide-content {
            max-width: 1100px;
            padding: 60px 32px 120px;
        }
        
        .question-wrapper {
            max-width: 1000px;
        }
        
        .questionnaire-nav {
            padding: 20px 32px;
        }
        
        .question-text {
            font-size: 22px;
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
    
    // Track de preguntas respondidas (para permitir navegación hacia atrás)
    let maxAnsweredSlide = -1;
    
    const progressBar = document.getElementById('progressBar');
    const form = document.getElementById('questionnaireForm');
    
    // Inicializar Select2 para los selectores
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2-field').each(function() {
            $(this).select2({
                width: '100%',
                allowClear: true,
                minimumResultsForSearch: 5,
                dropdownParent: $('body'),
                placeholder: $(this).data('placeholder') || 'Selecciona una opción...'
            });
        });
        
        // Escuchar cambios de Select2 para actualizar estado del botón y manejar "Otro"
        $('.select2-field').on('change', function() {
            const selectId = $(this).attr('id');
            const selectedValue = $(this).val();
            const otherInput = document.getElementById('select_other_input_' + selectId.replace('question_', ''));
            
            if (otherInput) {
                if (selectedValue === 'other') {
                    otherInput.style.display = 'block';
                    otherInput.focus();
                } else {
                    otherInput.style.display = 'none';
                    otherInput.value = '';
                }
            }
            
            updateCurrentSlideNav();
        });
    }
    
    // Inicializar navegación para cada slide
    initializeSlideNavigation();
    updateProgress();
    updateCurrentSlideNav();
    
    // Inicializar navegación de cada slide
    function initializeSlideNavigation() {
        slides.forEach((slide, index) => {
            const prevBtn = slide.querySelector('.btn-prev-q');
            const nextBtn = slide.querySelector('.btn-next-q');
            const continueBtn = slide.querySelector('.btn-continue-q');
            
            // Botón anterior (flecha izquierda)
            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (index > 0) {
                        goToSlide(index - 1);
                    }
                });
            }
            
            // Botón siguiente (flecha derecha) - para navegar adelante entre preguntas respondidas
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Permitir ir adelante si estás dentro del rango respondido
                    if (maxAnsweredSlide >= index && index < totalSlides - 1) {
                        goToSlide(index + 1);
                    }
                });
            }
            
            // Botón Continuar - avanzar después de responder
            if (continueBtn) {
                continueBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isRequired = slide.dataset.required === 'true';
                    const type = slide.dataset.type;
                    
                    // Si es slide final, no hacer nada
                    if (type === 'final') return;
                    
                    // Validar si es requerido
                    if (isRequired && !isSlideAnswered(slide)) {
                        shakeButton(continueBtn);
                        return;
                    }
                    
                    // Marcar esta pregunta como respondida
                    if (index > maxAnsweredSlide) {
                        maxAnsweredSlide = index;
                    }
                    
                    // Avanzar a la siguiente
                    if (index < totalSlides - 1) {
                        goToSlide(index + 1);
                    }
                });
            }
        });
    }
    
    // Ir a un slide específico
    function goToSlide(index) {
        if (index < 0 || index >= totalSlides) return;
        
        // No permitir saltar a preguntas no respondidas (excepto la siguiente inmediata)
        if (index > maxAnsweredSlide + 1) return;
        
        const currentEl = slides[currentSlide];
        const nextEl = slides[index];
        
        // Animación de salida
        currentEl.classList.remove('active');
        if (index > currentSlide) {
            currentEl.classList.add('exit-left');
        }
        
        // Cambiar índice
        currentSlide = index;
        
        // Animación de entrada
        setTimeout(() => {
            slides.forEach(s => s.classList.remove('exit-left'));
            nextEl.classList.add('active');
            updateProgress();
            updateCurrentSlideNav();
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
    
    // Actualizar navegación del slide actual
    function updateCurrentSlideNav() {
        const currentEl = slides[currentSlide];
        const prevBtn = currentEl.querySelector('.btn-prev-q');
        const nextBtn = currentEl.querySelector('.btn-next-q');
        const continueBtn = currentEl.querySelector('.btn-continue-q');
        const type = currentEl.dataset.type;
        const isRequired = currentEl.dataset.required === 'true';
        
        // Botón anterior - invisible si es el primero
        if (prevBtn) {
            if (currentSlide === 0) {
                prevBtn.classList.add('invisible');
            } else {
                prevBtn.classList.remove('invisible');
            }
        }
        
        // Botón siguiente (flecha) - visible si puedes navegar adelante
        // Aparece cuando: no estás en el último slide Y (hay slides respondidos adelante O puedes ir al siguiente)
        if (nextBtn) {
            const isFinalSlide = type === 'final';
            const canNavigateForward = !isFinalSlide && currentSlide < maxAnsweredSlide + 1 && currentSlide < totalQuestions;
            
            if (canNavigateForward && maxAnsweredSlide >= currentSlide) {
                nextBtn.classList.remove('invisible', 'hidden');
                nextBtn.disabled = false;
            } else {
                nextBtn.classList.add('invisible');
                nextBtn.disabled = true;
            }
        }
        
        // Botón Continuar
        if (continueBtn) {
            if (type === 'final') {
                continueBtn.style.display = 'none';
            } else {
                continueBtn.style.display = 'flex';
                const btnText = continueBtn.querySelector('span');
                if (btnText) {
                    btnText.textContent = currentSlide === totalQuestions - 1 ? 'Finalizar' : 'Continuar';
                }
                
                // Habilitar/deshabilitar según respuesta
                if (type === 'info' || !isRequired) {
                    continueBtn.disabled = false;
                } else {
                    continueBtn.disabled = !isSlideAnswered(currentEl);
                }
            }
        }
    }
    
    // Verificar si el slide está respondido
    function isSlideAnswered(slideEl) {
        const type = slideEl.dataset.type;
        
        if (type === 'info' || type === 'final') return true;
        
        const inputs = slideEl.querySelectorAll('.question-field');
        let answered = false;
        
        inputs.forEach(input => {
            if (input.type === 'radio' || input.type === 'checkbox') {
                if (input.checked) answered = true;
            } else if (input.type === 'file') {
                // Verificar en fileStorage
                const questionId = input.id?.replace('question_', '');
                if (questionId && typeof fileStorage !== 'undefined' && fileStorage[questionId] && fileStorage[questionId].length > 0) {
                    answered = true;
                } else if (input.files && input.files.length > 0) {
                    answered = true;
                }
            } else if (input.tagName === 'SELECT') {
                if (input.value) answered = true;
            } else {
                if (input.value.trim()) answered = true;
            }
        });
        
        return answered;
    }
    
    // Escuchar cambios en los inputs (NO auto-avanzar)
    document.querySelectorAll('.question-field').forEach(input => {
        const eventType = (input.type === 'radio' || input.type === 'checkbox' || input.type === 'file' || input.tagName === 'SELECT') 
            ? 'change' 
            : 'input';
            
        input.addEventListener(eventType, function() {
            // Solo actualizar el estado del botón, NO auto-avanzar
            updateCurrentSlideNav();
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
    
    // ===== DRAG AND DROP PARA ARCHIVOS MÚLTIPLES =====
    
    // Objeto para almacenar archivos por pregunta
    const fileStorage = {};
    
    // Prevenir que el navegador abra archivos arrastrados en cualquier parte
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    document.addEventListener('drop', function(e) {
        e.preventDefault();
    });
    
    // Tipos de archivo válidos - incluir todos los formatos comunes de imagen
    const validTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml',
        'image/heic', 'image/heif', // iPhone
        'application/pdf'
    ];
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    
    // Función para verificar si es imagen por extensión (backup)
    function isValidFileType(file) {
        // Primero verificar por MIME type
        if (validTypes.includes(file.type)) return true;
        
        // Si el MIME type está vacío o no reconocido, verificar por extensión
        const ext = file.name.split('.').pop().toLowerCase();
        const validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'heic', 'heif', 'pdf'];
        return validExtensions.includes(ext);
    }
    
    // Manejar las zonas de drop
    document.querySelectorAll('.file-dropzone').forEach(dropzone => {
        const inputId = dropzone.dataset.input;
        const fileInput = document.getElementById(inputId);
        const questionId = inputId.split('_').pop();
        const fileListEl = document.getElementById('fileList_' + questionId);
        
        // Inicializar storage para esta pregunta
        fileStorage[questionId] = [];
        
        // Drag enter
        dropzone.addEventListener('dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('drag-over');
        });
        
        // Drag over
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('drag-over');
        });
        
        // Drag leave
        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('drag-over');
        });
        
        // Drop
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('drag-over');
            
            const files = Array.from(e.dataTransfer.files);
            addFilesToQuestion(questionId, files, fileInput, fileListEl, dropzone);
        });
    });
    
    // Manejar cambio en input file (click normal)
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const questionId = this.id.split('_').pop();
            const fileListEl = document.getElementById('fileList_' + questionId);
            const dropzone = document.querySelector('[data-input="' + this.id + '"]');
            
            if (this.files.length > 0) {
                const files = Array.from(this.files);
                addFilesToQuestion(questionId, files, this, fileListEl, dropzone);
            }
        });
    });
    
    // Función para agregar archivos a una pregunta
    function addFilesToQuestion(questionId, newFiles, fileInput, fileListEl, dropzone) {
        let addedCount = 0;
        let errors = [];
        
        newFiles.forEach(file => {
            // Validar tipo usando función mejorada
            if (!isValidFileType(file)) {
                errors.push(`"${file.name}" - tipo no válido (solo imágenes y PDF)`);
                return;
            }
            
            // Validar tamaño
            if (file.size > maxFileSize) {
                errors.push(`"${file.name}" - excede 5MB (${(file.size / 1024 / 1024).toFixed(2)}MB)`);
                return;
            }
            
            // Verificar si ya existe
            const exists = fileStorage[questionId].some(f => f.name === file.name && f.size === file.size);
            if (exists) {
                errors.push(`"${file.name}" - ya agregado`);
                return;
            }
            
            // Agregar al storage
            fileStorage[questionId].push(file);
            addedCount++;
        });
        
        // Mostrar errores si hay
        if (errors.length > 0) {
            alert('Algunos archivos no se pudieron agregar:\n\n' + errors.join('\n'));
        }
        
        // Actualizar el input file con todos los archivos
        updateFileInput(questionId, fileInput);
        
        // Actualizar la lista visual
        renderFileList(questionId, fileListEl, fileInput, dropzone);
        
        // Actualizar navegación
        if (addedCount > 0) {
            updateCurrentSlideNav();
        }
    }
    
    // Función para actualizar el input file
    function updateFileInput(questionId, fileInput) {
        const dataTransfer = new DataTransfer();
        fileStorage[questionId].forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;
    }
    
    // Función para renderizar la lista de archivos
    function renderFileList(questionId, fileListEl, fileInput, dropzone) {
        if (!fileListEl) return;
        
        const files = fileStorage[questionId];
        
        if (files.length === 0) {
            fileListEl.innerHTML = '';
            if (dropzone) dropzone.classList.remove('has-file');
            return;
        }
        
        if (dropzone) dropzone.classList.add('has-file');
        
        let html = '';
        files.forEach((file, index) => {
            const icon = file.type.startsWith('image/') ? 'ti-photo' : 'ti-file-text';
            const fileSize = formatFileSize(file.size);
            
            html += `
                <div class="file-item" data-index="${index}">
                    <div class="file-item-icon">
                        <i class="ti ${icon}"></i>
                    </div>
                    <div class="file-item-info">
                        <div class="file-item-name">${escapeHtml(file.name)}</div>
                        <div class="file-item-size">${fileSize}</div>
                    </div>
                    <button type="button" class="file-item-remove" onclick="removeFile('${questionId}', ${index})" title="Eliminar">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            `;
        });
        
        html += `<div class="file-counter"><strong>${files.length}</strong> archivo${files.length !== 1 ? 's' : ''} seleccionado${files.length !== 1 ? 's' : ''}</div>`;
        
        fileListEl.innerHTML = html;
    }
    
    // Función para eliminar un archivo
    window.removeFile = function(questionId, index) {
        fileStorage[questionId].splice(index, 1);
        
        const fileInput = document.getElementById('question_' + questionId);
        const fileListEl = document.getElementById('fileList_' + questionId);
        const dropzone = document.querySelector('[data-input="question_' + questionId + '"]');
        
        updateFileInput(questionId, fileInput);
        renderFileList(questionId, fileListEl, fileInput, dropzone);
        
        // Actualizar navegación
        updateCurrentSlideNav();
    };
    
    // Función para formatear tamaño de archivo
    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
    
    // Función para escapar HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Shake del botón si no está respondido
    function shakeButton(btn) {
        if (btn) {
            btn.classList.add('shake');
            setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }
    
    // Botón volver (desde slide final)
    const backToQuestionsBtn = document.getElementById('backToQuestionsBtn');
    if (backToQuestionsBtn) {
        backToQuestionsBtn.addEventListener('click', function() {
            if (maxAnsweredSlide >= 0) {
                goToSlide(maxAnsweredSlide);
            } else if (currentSlide > 0) {
                goToSlide(currentSlide - 1);
            }
        });
    }
    
    // Confirmación al enviar
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('=== FORMULARIO SUBMIT ===');
            console.log('Form action:', form.action);
            console.log('Form data:', new FormData(form));
            
            // Mostrar todos los campos
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
            
            if (!confirm('¿Estás seguro de enviar tus respuestas? Una vez enviadas no podrás modificarlas.')) {
                e.preventDefault();
                return;
            }
            
            console.log('Usuario confirmó, enviando formulario...');
        });
    }
    
    // Navegación con teclado
    document.addEventListener('keydown', function(e) {
        const currentEl = slides[currentSlide];
        const prevBtn = currentEl.querySelector('.btn-prev-q');
        const continueBtn = currentEl.querySelector('.btn-continue-q');
        
        if (e.key === 'Enter') {
            if (document.activeElement.tagName !== 'TEXTAREA' && continueBtn && !continueBtn.disabled) {
                e.preventDefault();
                continueBtn.click();
            }
        } else if (e.key === 'ArrowLeft') {
            if (prevBtn && !prevBtn.classList.contains('invisible')) {
                e.preventDefault();
                prevBtn.click();
            }
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
