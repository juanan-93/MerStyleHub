@extends('layouts.app', ['title' => 'Chat'])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('chat-user.index') }}" class="text-decoration-none">
            <i class="ti ti-messages me-1"></i>{{ __('Mensajes') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ $conversation->admin->name }}</li>
@endsection

@section('content')
<div class="container-fluid py-3">
    <div class="row" style="height: calc(100vh - 140px); min-height: 500px;">
        <div class="col-12 h-100">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column" id="chatWindow">
                <!-- Header del chat -->
                <div class="card-header bg-white border-bottom p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 42px; height: 42px; background: linear-gradient(135deg, var(--color-primary), #8f7668); color: white; font-weight: 600;">
                            {{ strtoupper(substr($conversation->admin->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: var(--color-secondary);">{{ $conversation->admin->name }}</h6>
                            <small class="text-muted">Tu asesora de imagen</small>
                        </div>
                    </div>
                </div>

                <!-- Área de mensajes -->
                <div class="card-body overflow-auto flex-grow-1 p-3" id="messagesContainer" 
                     style="background-color: #f8f6f3; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23d9d4ce\' fill-opacity=\'0.15\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    
                    @php $lastDate = null; @endphp
                    @foreach($conversation->messages as $message)
                        @php 
                            $messageDate = $message->created_at->format('Y-m-d');
                            $isMe = $message->sender_id === Auth::id();
                        @endphp
                        
                        {{-- Separador de fecha --}}
                        @if($lastDate !== $messageDate)
                            <div class="text-center my-3">
                                <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(160,138,122,0.15); color: var(--color-secondary); font-size: 0.75rem;">
                                    @if($message->created_at->isToday())
                                        Hoy
                                    @elseif($message->created_at->isYesterday())
                                        Ayer
                                    @else
                                        {{ $message->created_at->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                            @php $lastDate = $messageDate; @endphp
                        @endif

                        {{-- Mensaje tipo correo electrónico --}}
                        <div class="email-message mb-4" data-message-id="{{ $message->id }}">
                            {{-- Cabecera del mensaje --}}
                            <div class="email-header p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        {{-- Avatar --}}
                                        <div class="me-3">
                                            @if($isMe)
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-primary), #8f7668); color: white; font-weight: 600; font-size: 0.9rem;">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-secondary), #8f7668); color: white; font-weight: 600; font-size: 0.9rem;">
                                                    A
                                                </div>
                                            @endif
                                        </div>
                                        
                                        {{-- Información del remitente --}}
                                        <div>
                                            <div class="fw-bold" style="color: var(--color-secondary); font-size: 0.95rem;">
                                                {{ $isMe ? 'Tú' : $conversation->admin->name }}
                                            </div>
                                            <div class="text-muted" style="font-size: 0.8rem;">
                                                {{ $message->created_at->format('d M Y - H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Cuerpo del mensaje --}}
                            <div class="email-body p-3">
                                {{-- Adjuntos --}}
                                @if($message->hasAttachment())
                                    <div class="attachment-section mb-3 p-3 rounded" style="background-color: var(--color-light); border-left: 4px solid var(--color-primary);">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ti ti-paperclip me-2" style="color: var(--color-primary); font-size: 1.1rem;"></i>
                                            <span class="fw-semibold" style="color: var(--color-secondary); font-size: 0.9rem;">Archivo adjunto</span>
                                        </div>
                                        @if($message->isImage())
                                            <div class="text-center">
                                                <a href="{{ $message->attachment_url }}" target="_blank" class="d-inline-block">
                                                    <img src="{{ $message->attachment_url }}" class="img-fluid rounded border" 
                                                         style="max-height: 300px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" alt="Imagen adjunta">
                                                </a>
                                                <div class="mt-2">
                                                    <small class="text-muted">{{ $message->attachment_name }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="file-download-card d-flex align-items-center p-3 bg-white rounded border">
                                                <div class="file-icon me-3">
                                                    <i class="ti ti-file-download" style="font-size: 2rem; color: var(--color-primary);"></i>
                                                </div>
                                                <div class="file-details flex-grow-1">
                                                    <div class="fw-semibold mb-1" style="color: var(--color-secondary);">{{ $message->attachment_name }}</div>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <small class="text-muted">{{ $message->formatted_size }}</small>
                                                        <a href="{{ $message->attachment_url }}" target="_blank" download="{{ $message->attachment_name }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-download me-1"></i>Descargar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                {{-- Contenido del mensaje --}}
                                @if($message->body)
                                    <div class="message-content" style="font-size: 0.95rem; line-height: 1.6; color: var(--color-secondary);">
                                        {!! $message->body !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Área de composición de correo -->
                <div class="card-footer bg-white border-top p-4">
                    <form action="{{ route('chat-user.send', $conversation->id) }}" method="POST" enctype="multipart/form-data" id="chatForm">
                        @csrf
                        
                        {{-- Preview del archivo adjunto --}}
                        <div id="filePreview" class="d-none mb-3">
                            <div class="attachment-preview p-3 rounded" style="background-color: var(--color-light); border: 1px solid var(--color-border);">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-paperclip me-2" style="color: var(--color-primary); font-size: 1.2rem;"></i>
                                        <div>
                                            <div class="fw-semibold mb-1" style="color: var(--color-secondary);">Archivo seleccionado:</div>
                                            <span id="fileName" class="text-muted"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                        <i class="ti ti-x me-1"></i>Quitar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Editor de contenido --}}
                        <div class="compose-content mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-semibold mb-0" style="color: var(--color-secondary);">Tu consulta:</label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="clearBtn" title="Limpiar editor">
                                        <i class="ti ti-eraser me-1"></i>Limpiar
                                    </button>
                                    <label class="btn btn-outline-secondary btn-sm mb-0" title="Adjuntar archivo">
                                        <i class="ti ti-paperclip me-1"></i>Adjuntar
                                        <input type="file" name="attachment" class="d-none" id="fileInput" 
                                               accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar">
                                    </label>
                                </div>
                            </div>
                            <textarea name="body" id="summernoteEditor" placeholder="Escribe tu mensaje aquí..."></textarea>
                        </div>
                        
                        {{-- Botones de acción --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light" onclick="history.back()">
                                <i class="ti ti-arrow-left me-1"></i>Volver
                            </button>
                            <button type="submit" class="btn text-white" style="background-color: var(--color-primary);" id="sendBtn">
                                <i class="ti ti-send me-1"></i>Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilo de correo electrónico */
    .email-message {
        border: 1px solid var(--color-border);
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .email-message:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-1px);
    }
    
    .email-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid var(--color-border);
    }
    
    .email-body {
        background: white;
        min-height: 60px;
    }
    
    .message-content {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .attachment-section {
        transition: all 0.2s ease;
    }
    
    .attachment-section:hover {
        background-color: rgba(var(--color-primary-rgb), 0.05) !important;
    }
    
    .file-download-card {
        transition: all 0.2s ease;
    }
    
    .file-download-card:hover {
        background-color: var(--color-light) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    /* Área de composición */
    .compose-header {
        background: linear-gradient(135deg, var(--color-light) 0%, rgba(var(--color-light-rgb), 0.3) 100%);
    }
    
    .attachment-preview {
        transition: all 0.2s ease;
    }
    
    .attachment-preview:hover {
        background-color: rgba(var(--color-primary-rgb), 0.08) !important;
    }
    
    /* Scrollbar personalizado */
    #messagesContainer::-webkit-scrollbar {
        width: 8px;
    }
    #messagesContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    #messagesContainer::-webkit-scrollbar-thumb {
        background: var(--color-border);
        border-radius: 4px;
    }
    #messagesContainer::-webkit-scrollbar-thumb:hover {
        background: var(--color-secondary);
    }
    
    /* Estilos para Summernote */
    .note-editor.note-frame {
        border: 2px solid var(--color-border) !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    
    .note-toolbar {
        background: linear-gradient(135deg, var(--color-light) 0%, #f8f9fa 100%) !important;
        border-bottom: 1px solid var(--color-border) !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 12px 16px !important;
    }
    
    .note-editable {
        min-height: 120px !important;
        max-height: 200px !important;
        font-size: 0.95rem !important;
        line-height: 1.6 !important;
        padding: 16px !important;
        border-radius: 0 0 12px 12px !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }
    
    .note-editable:focus {
        box-shadow: none !important;
        border-color: var(--color-primary) !important;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    /* Botones */
    .btn {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* ========================================
       ESTILOS RESPONSIVE PARA MÓVILES
       ======================================== */
    
    @media (max-width: 768px) {
        /* Ocultar layout padding y footer en móvil para el chat */
        .p-4.flex-grow-1 {
            padding: 0 !important;
        }
        
        .mt-auto {
            display: none !important; /* Ocultar footer */
        }
        
        /* Breadcrumbs más compactos en móvil */
        .bg-white.border-bottom.px-4.py-3 {
            padding: 0.5rem 1rem !important;
        }
        
        .bg-white.border-bottom.px-4.py-3 .breadcrumb {
            font-size: 0.8rem !important;
        }
        
        /* Contenedor principal - pantalla completa */
        .container-fluid.py-3 {
            padding: 0 !important;
            height: 100% !important;
        }
        
        .container-fluid.py-3 > .row {
            height: calc(100vh - 100px) !important; /* Resta navbar + breadcrumbs */
            min-height: auto !important;
            margin: 0 !important;
        }
        
        .container-fluid.py-3 > .row > .col-12 {
            padding: 0 !important;
            height: 100% !important;
        }
        
        /* Card del chat - sin bordes redondeados */
        #chatWindow {
            border-radius: 0 !important;
            height: 100% !important;
        }
        
        /* Header del chat - más compacto */
        #chatWindow > .card-header {
            padding: 0.75rem 1rem !important;
            position: sticky;
            top: 0;
            z-index: 100;
            border-radius: 0 !important;
        }
        
        #chatWindow > .card-header .rounded-circle {
            width: 36px !important;
            height: 36px !important;
            font-size: 0.85rem !important;
        }
        
        #chatWindow > .card-header .me-3 {
            margin-right: 0.75rem !important;
        }
        
        #chatWindow > .card-header h6 {
            font-size: 0.95rem !important;
        }
        
        #chatWindow > .card-header small {
            font-size: 0.7rem !important;
        }
        
        /* Área de mensajes */
        #messagesContainer {
            padding: 0.75rem !important;
        }
        
        /* Mensajes - estilo más compacto tipo chat */
        .email-message {
            border-radius: 16px !important;
            margin-bottom: 0.75rem !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
        }
        
        .email-message:hover {
            transform: none !important;
        }
        
        .email-header {
            padding: 0.65rem 0.85rem !important;
        }
        
        .email-header .rounded-circle {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.75rem !important;
        }
        
        .email-header .me-3 {
            margin-right: 0.5rem !important;
        }
        
        .email-header .fw-bold {
            font-size: 0.85rem !important;
        }
        
        .email-header .text-muted {
            font-size: 0.7rem !important;
        }
        

        
        .email-body {
            padding: 0.75rem !important;
        }
        
        .message-content {
            font-size: 0.9rem !important;
            line-height: 1.5 !important;
        }
        
        /* Adjuntos en móvil */
        .attachment-section {
            padding: 0.65rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        .attachment-section img {
            max-height: 200px !important;
        }
        
        .file-download-card {
            padding: 0.65rem !important;
        }
        
        .file-download-card .file-icon i {
            font-size: 1.5rem !important;
        }
        
        .file-download-card .btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.5rem !important;
        }
        
        /* Separador de fecha */
        .text-center.my-3 {
            margin: 0.5rem 0 !important;
        }
        
        .text-center.my-3 .badge {
            font-size: 0.7rem !important;
            padding: 0.35rem 0.75rem !important;
        }
        
        /* Área de composición - estilo app móvil */
        #chatWindow > .card-footer {
            padding: 0.75rem !important;
            border-radius: 0 !important;
            position: sticky;
            bottom: 0;
            z-index: 100;
            background: white !important;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        
        .compose-content {
            margin-bottom: 0.5rem !important;
        }
        
        .compose-content .d-flex.align-items-center.justify-content-between {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .compose-content label.form-label {
            font-size: 0.85rem !important;
            display: none; /* Ocultar label en móvil */
        }
        
        .compose-content .d-flex.gap-2 {
            width: 100%;
            justify-content: flex-end;
            margin-bottom: 0.5rem;
        }
        
        .compose-content .btn-sm {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.65rem !important;
        }
        
        /* Summernote en móvil - más compacto */
        .note-editor.note-frame {
            border-radius: 16px !important;
        }
        
        .note-toolbar {
            padding: 6px 8px !important;
            border-radius: 16px 16px 0 0 !important;
            flex-wrap: wrap;
        }
        
        .note-toolbar .note-btn-group {
            margin-right: 3px !important;
        }
        
        .note-toolbar .note-btn {
            font-size: 12px !important;
            padding: 4px 6px !important;
        }
        
        .note-editable {
            min-height: 80px !important;
            max-height: 150px !important;
            padding: 10px !important;
            font-size: 0.9rem !important;
            border-radius: 0 0 16px 16px !important;
        }
        
        /* Botones de acción en móvil */
        .card-footer .d-flex.justify-content-end.gap-2 {
            flex-direction: row;
            gap: 0.5rem !important;
        }
        
        .card-footer .btn-light {
            display: none; /* Ocultar botón volver en móvil, usar breadcrumb/navbar */
        }
        
        .card-footer #sendBtn {
            flex: 1;
            border-radius: 25px !important;
            padding: 0.65rem 1rem !important;
            font-size: 0.9rem !important;
        }
        
        /* Preview de archivo en móvil */
        #filePreview .attachment-preview {
            padding: 0.65rem !important;
        }
        
        #filePreview .btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.5rem !important;
        }
    }
    
    /* Extra small devices (phones < 375px) */
    @media (max-width: 375px) {

        
        .compose-content .btn-sm span,
        .compose-content .btn-sm .me-1 + span {
            display: none;
        }
        
        .note-toolbar .note-btn-group:nth-child(n+4) {
            display: none !important;
        }
    }
    
    /* Landscape mode en móvil */
    @media (max-width: 768px) and (orientation: landscape) {
        .container-fluid.py-3 > .row {
            height: calc(100vh - 56px) !important;
        }
        
        .note-editable {
            min-height: 60px !important;
            max-height: 100px !important;
        }
        
        #messagesContainer {
            max-height: calc(100vh - 200px);
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const removeFile = document.getElementById('removeFile');
    const chatForm = document.getElementById('chatForm');
    const clearBtn = document.getElementById('clearBtn');
    let summernoteEditor;
    
    // Detectar si es móvil
    const isMobile = window.innerWidth <= 768;
    
    // Toolbar según dispositivo
    const toolbarConfig = isMobile ? [
        ['font', ['bold', 'italic', 'underline']],
        ['para', ['ul', 'ol']],
        ['insert', ['link']]
    ] : [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'hr']],
        ['view', ['fullscreen', 'help']]
    ];

    // Inicializar SummerNote
    $('#summernoteEditor').summernote({
        height: isMobile ? 80 : 120,
        focus: !isMobile, // No auto-focus en móvil para evitar teclado
        toolbar: toolbarConfig,
        placeholder: 'Escribe tu mensaje aquí...',
        callbacks: {
            onKeydown: function(e) {
                if (e.ctrlKey && e.keyCode === 13) {
                    e.preventDefault();
                    const content = $('#summernoteEditor').summernote('code').trim();
                    if (content && content !== '<p><br></p>' || fileInput.files.length > 0) {
                        chatForm.submit();
                    }
                }
            },
            onFocus: function() {
                $('.note-editor.note-frame').css('border-color', 'var(--color-primary)');
                // En móvil, hacer scroll al área de composición
                if (isMobile) {
                    setTimeout(() => {
                        document.querySelector('.card-footer').scrollIntoView({ behavior: 'smooth', block: 'end' });
                    }, 300);
                }
            },
            onBlur: function() {
                $('.note-editor.note-frame').css('border-color', 'var(--color-border)');
            }
        }
    });
    summernoteEditor = $('#summernoteEditor');

    // Scroll al final
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }
    scrollToBottom();

    // Botón limpiar
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            summernoteEditor.summernote('reset');
            if (fileInput) {
                fileInput.value = '';
                filePreview.classList.add('d-none');
                filePreview.classList.remove('d-flex');
            }
        });
    }

    // Form submission con SummerNote
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            const content = summernoteEditor.summernote('code').trim();
            if (!content || content === '<p><br></p>') {
                summernoteEditor.summernote('code', '');
            }
            
            // Deshabilitar botón de envío para evitar doble click
            const sendBtn = document.getElementById('sendBtn');
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<i class="ti ti-loader ti-spin me-1"></i>Enviando...';
            }
        });
    }

    // Preview de archivo
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                filePreview.classList.remove('d-none');
                filePreview.classList.add('d-flex');
            }
        });
    }

    if (removeFile) {
        removeFile.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.add('d-none');
            filePreview.classList.remove('d-flex');
        });
    }

    // Polling para nuevos mensajes (cada 5 segundos)
    let lastMessageId = {{ $conversation->messages->last()?->id ?? 0 }};
    const conversationId = {{ $conversation->id }};

    setInterval(async () => {
        try {
            const response = await fetch(`{{ url('/chat-user') }}/${conversationId}/new-messages?last_message_id=${lastMessageId}`);
            const data = await response.json();
            
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                        appendMessage(msg);
                    }
                });
                scrollToBottom();
            }
        } catch (error) {
            console.error('Error polling messages:', error);
        }
    }, 5000);

    function appendMessage(msg) {
        const isMe = msg.sender_id === {{ Auth::id() }};
        
        let attachmentHtml = '';
        if (msg.attachment_path) {
            const isImage = (msg.attachment_type || '').startsWith('image/');
            const fileUrl = `/storage/${msg.attachment_path}`;
            
            if (isImage) {
                attachmentHtml = `<div class="attachment-container">
                    <a href="${fileUrl}" target="_blank">
                        <img src="${fileUrl}" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;" alt="Imagen">
                    </a>
                </div>`;
            } else {
                attachmentHtml = `<div class="attachment-container">
                    <div class="file-attachment d-flex align-items-center p-2 rounded" style="background-color: rgba(255,255,255,0.9); border: 1px solid rgba(0,0,0,0.1);">
                        <div class="file-icon me-2">
                            <i class="ti ti-file-download" style="font-size: 1.2rem; color: var(--color-primary);"></i>
                        </div>
                        <div class="file-info">
                            <a href="${fileUrl}" target="_blank" download="${msg.attachment_name}" 
                               class="fw-semibold text-decoration-none" style="font-size: 0.8rem; color: var(--color-secondary);">
                                ${msg.attachment_name}
                            </a>
                        </div>
                    </div>
                </div>`;
            }
        }
        
        // Formato de correo electrónico
        const bodyHtml = msg.body ? `<div class="message-content" style="font-size: 0.95rem; line-height: 1.6; color: var(--color-secondary);">${msg.body}</div>` : '';
        const senderName = isMe ? 'Tú' : '{{ $conversation->admin->name }}';
        const senderAvatar = isMe ? '{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}' : 'A';
        const avatarBg = isMe ? 'var(--color-primary)' : 'var(--color-secondary)';
        const roleBadge = '';
        const date = new Date(msg.created_at);
        const dateStr = date.toLocaleDateString('es-ES') + ' - ' + date.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});
        
        const html = `<div class="email-message mb-4" data-message-id="${msg.id}">
            <div class="email-header p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, ${avatarBg}, #8f7668); color: white; font-weight: 600; font-size: 0.9rem;">
                                ${senderAvatar}
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold" style="color: var(--color-secondary); font-size: 0.95rem;">
                                ${senderName}
                                ${roleBadge}
                            </div>
                            <div class="text-muted" style="font-size: 0.8rem;">${dateStr}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="email-body p-3">
                ${attachmentHtml}
                ${bodyHtml}
            </div>
        </div>`;
        
        messagesContainer.insertAdjacentHTML('beforeend', html);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
@endpush
