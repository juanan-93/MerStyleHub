@extends('layouts.app', ['title' => 'Mensajes'])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-messages me-1"></i>
        {{ __('Mensajes') }}
    </li>
@endsection

@section('content')
<div class="container-fluid py-3">
    <div class="row" style="height: calc(100vh - 140px); min-height: 600px;">
        <!-- Panel izquierdo: Lista de conversaciones -->
        <div class="col-md-4 col-lg-3 h-100">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <!-- Buscar / Nueva conversación -->
                <div class="card-header bg-white border-bottom p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0" style="color: var(--color-secondary);">Conversaciones</h6>
                        <button class="btn btn-sm rounded-pill" style="background-color: var(--color-primary); color: white;" 
                                data-bs-toggle="modal" data-bs-target="#newConversationModal" title="Nueva conversación">
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control bg-light border-start-0" placeholder="Buscar cliente..." id="searchConversation">
                    </div>
                </div>
                
                <!-- Lista de conversaciones -->
                <div class="card-body p-0 overflow-auto flex-grow-1" id="conversationsList">
                    @forelse($conversations as $conv)
                        <a href="{{ route('chat-admin.show', $conv->id) }}" 
                           class="conversation-item d-flex align-items-center p-3 text-decoration-none border-bottom {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }}"
                           data-name="{{ strtolower($conv->customer->name) }}">
                            <!-- Avatar -->
                            <div class="position-relative flex-shrink-0">
                                @if($conv->customer->customerProfile && $conv->customer->customerProfile->profile_image)
                                    <img src="{{ Storage::url($conv->customer->customerProfile->profile_image) }}" 
                                         class="rounded-circle" width="48" height="48" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--color-primary), #8f7668); color: white; font-weight: 600;">
                                        {{ strtoupper(substr($conv->customer->name, 0, 1)) }}
                                    </div>
                                @endif
                                @php $unread = $conv->unreadMessagesFor(Auth::id()); @endphp
                                @if($unread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        {{ $unread > 9 ? '9+' : $unread }}
                                    </span>
                                @endif
                            </div>
                            <!-- Info -->
                            <div class="ms-3 overflow-hidden flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-semibold text-truncate" style="color: var(--color-secondary); font-size: 0.9rem;">
                                        {{ $conv->customer->name }}
                                    </h6>
                                    @if($conv->lastMessage)
                                        <small class="text-muted ms-2 flex-shrink-0" style="font-size: 0.7rem;">
                                            {{ $conv->lastMessage->created_at->format('H:i') }}
                                        </small>
                                    @endif
                                </div>
                                @if($conv->lastMessage)
                                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.8rem;">
                                        @if($conv->lastMessage->sender_id === Auth::id())
                                            <span class="text-muted">Tú: </span>
                                        @endif
                                        @if($conv->lastMessage->hasAttachment() && !$conv->lastMessage->body)
                                            <i class="ti ti-paperclip"></i> Archivo adjunto
                                        @else
                                            {{ Str::limit(strip_tags($conv->lastMessage->body), 40) }}
                                        @endif
                                    </p>
                                @else
                                    <p class="mb-0 text-muted fst-italic" style="font-size: 0.8rem;">Sin mensajes aún</p>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <i class="ti ti-messages-off" style="font-size: 3rem; color: var(--color-border);"></i>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.9rem;">No hay conversaciones</p>
                            <p class="text-muted" style="font-size: 0.8rem;">Inicia una nueva conversación</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Panel derecho: Chat -->
        <div class="col-md-8 col-lg-9 h-100">
            @if(isset($conversation))
                @include('chatAdmin.partials.chat-window', ['conversation' => $conversation])
            @else
                <!-- Estado vacío -->
                <div class="card border-0 shadow-sm h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-4" 
                             style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(160,138,122,0.1), rgba(160,138,122,0.2));">
                            <i class="ti ti-messages" style="font-size: 3rem; color: var(--color-primary);"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--color-secondary);">MerStyleHub Chat</h4>
                        <p class="text-muted">Selecciona una conversación o inicia una nueva</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Nueva conversación -->
<div class="modal fade" id="newConversationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, var(--color-primary), #8f7668);">
                <h5 class="modal-title text-white fw-bold">
                    <i class="ti ti-message-plus me-2"></i>Nueva Conversación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('chat-admin.start') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Seleccionar cliente</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($customersWithoutChat ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    @if(empty($customersWithoutChat) || (isset($customersWithoutChat) && $customersWithoutChat->isEmpty()))
                        <div class="alert alert-info mb-3">
                            <i class="ti ti-info-circle me-1"></i>
                            Todos los clientes ya tienen una conversación activa.
                        </div>
                    @endif
                    <div class="text-end">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn text-white" style="background-color: var(--color-primary);">
                            <i class="ti ti-message-plus me-1"></i>Iniciar Chat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Layout principal */
    .chat-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    /* Panel de conversaciones */
    .conversations-panel {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .conversation-item {
        transition: all 0.2s ease;
        color: var(--color-secondary);
        border-left: 3px solid transparent;
    }
    
    .conversation-item:hover {
        background-color: var(--color-light);
        border-left-color: var(--color-border);
    }
    
    .conversation-item.active {
        background-color: rgba(160, 138, 122, 0.15);
        border-left: 3px solid var(--color-primary) !important;
    }
    
    /* Panel vacío */
    .empty-state-panel {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border-radius: 12px;
    }
    
    /* Mejoras responsive */
    @media (max-width: 768px) {
        .conversations-panel {
            border-radius: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Filtrar conversaciones por nombre
    document.getElementById('searchConversation')?.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.conversation-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
