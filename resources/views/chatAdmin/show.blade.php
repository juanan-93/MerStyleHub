@extends('layouts.app', ['title' => 'Chat - ' . $conversation->customer->name])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('chat-admin.index') }}" class="text-decoration-none">
            <i class="ti ti-messages me-1"></i>{{ __('Mensajes') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ $conversation->customer->name }}</li>
@endsection

@section('content')
<div class="container-fluid py-3">
    <div class="row" style="height: calc(100vh - 140px); min-height: 600px;">
        <!-- Panel izquierdo: Lista de conversaciones -->
        <div class="col-md-4 col-lg-3 h-100 d-none d-md-block">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-header bg-white border-bottom p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0" style="color: var(--color-secondary);">Conversaciones</h6>
                        <a href="{{ route('chat-admin.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill" title="Ver todas">
                            <i class="ti ti-list"></i>
                        </a>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control bg-light border-start-0" placeholder="Buscar cliente..." id="searchConversation">
                    </div>
                </div>
                <div class="card-body p-0 overflow-auto flex-grow-1">
                    @foreach($conversations as $conv)
                        <a href="{{ route('chat-admin.show', $conv->id) }}" 
                           class="conversation-item d-flex align-items-center p-3 text-decoration-none border-bottom {{ $conversation->id === $conv->id ? 'active' : '' }}"
                           data-name="{{ strtolower($conv->customer->name) }}">
                            <div class="position-relative flex-shrink-0">
                                @if($conv->customer->customerProfile && $conv->customer->customerProfile->profile_image)
                                    <img src="{{ Storage::url($conv->customer->customerProfile->profile_image) }}" 
                                         class="rounded-circle" width="44" height="44" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--color-primary), #8f7668); color: white; font-weight: 600; font-size: 0.85rem;">
                                        {{ strtoupper(substr($conv->customer->name, 0, 1)) }}
                                    </div>
                                @endif
                                @php $unread = $conv->unreadMessagesFor(Auth::id()); @endphp
                                @if($unread > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        {{ $unread > 9 ? '9+' : $unread }}
                                    </span>
                                @endif
                            </div>
                            <div class="ms-3 overflow-hidden flex-grow-1">
                                <h6 class="mb-0 fw-semibold text-truncate" style="color: var(--color-secondary); font-size: 0.85rem;">
                                    {{ $conv->customer->name }}
                                </h6>
                                @if($conv->lastMessage)
                                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.75rem;">
                                        @if($conv->lastMessage->sender_id === Auth::id()) <span>Tú: </span> @endif
                                        {{ Str::limit(strip_tags($conv->lastMessage->body ?? 'Archivo adjunto'), 30) }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Panel derecho: Chat activo -->
        <div class="col-md-8 col-lg-9 h-100">
            @include('chatAdmin.partials.chat-window', ['conversation' => $conversation])
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
    document.getElementById('searchConversation')?.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.conversation-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
