@extends('layouts.app', ['title' => 'Mensajes'])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-messages me-1"></i>
        {{ __('Mensajes') }}
    </li>
@endsection

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        @forelse($conversations as $conv)
            <div class="col-md-6 col-lg-4 mb-3">
                <a href="{{ route('chat-user.show', $conv->id) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm conversation-card h-100">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--color-primary), #8f7668); color: white; font-weight: 600;">
                                {{ strtoupper(substr($conv->admin->name, 0, 1)) }}
                            </div>
                            <div class="overflow-hidden flex-grow-1">
                                <h6 class="mb-1 fw-bold" style="color: var(--color-secondary);">{{ $conv->admin->name }}</h6>
                                @if($conv->lastMessage)
                                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.85rem;">
                                        {{ Str::limit(strip_tags($conv->lastMessage->body ?? 'Archivo adjunto'), 50) }}
                                    </p>
                                @else
                                    <p class="mb-0 text-muted fst-italic" style="font-size: 0.85rem;">Sin mensajes aún</p>
                                @endif
                            </div>
                            @php $unread = $conv->unreadMessagesFor(Auth::id()); @endphp
                            @if($unread > 0)
                                <span class="badge rounded-pill bg-danger ms-2">{{ $unread }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-messages-off" style="font-size: 4rem; color: var(--color-border);"></i>
                        <h5 class="mt-3 fw-bold" style="color: var(--color-secondary);">No tienes conversaciones</h5>
                        <p class="text-muted">Tu asesora se pondrá en contacto contigo pronto.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .conversation-card {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .conversation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush
