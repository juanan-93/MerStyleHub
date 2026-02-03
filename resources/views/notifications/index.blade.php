@extends('layouts.app', ['title' => __('Notificaciones')])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-bell me-1"></i>
        {{ __('Notificaciones') }}
    </li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--color-secondary);">
                    <i class="ti ti-bell me-2"></i>Notificaciones
                </h2>
                <p class="text-muted mb-0">
                    @if($unreadCount > 0)
                        Tienes <strong>{{ $unreadCount }}</strong> notificación{{ $unreadCount > 1 ? 'es' : '' }} sin leer
                    @else
                        Todas las notificaciones leídas
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                @if($unreadCount > 0)
                    <button class="btn btn-outline-secondary" id="markAllReadBtn">
                        <i class="ti ti-checks me-1"></i>Marcar todas como leídas
                    </button>
                @endif
                @if($notifications->where('read_at', '!=', null)->count() > 0)
                    <button class="btn btn-outline-danger" id="clearReadBtn">
                        <i class="ti ti-trash me-1"></i>Limpiar leídas
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
                   class="btn {{ $filter === 'all' ? 'btn-primary-custom' : 'btn-outline-secondary' }}">
                    Todas
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="btn {{ $filter === 'unread' ? 'btn-primary-custom' : 'btn-outline-secondary' }}">
                    <i class="ti ti-circle-dot me-1"></i>Sin leer
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="btn {{ $filter === 'read' ? 'btn-primary-custom' : 'btn-outline-secondary' }}">
                    <i class="ti ti-check me-1"></i>Leídas
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Notificaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="notification-item d-flex align-items-start gap-3 p-3 border-bottom {{ !$notification->isRead() ? 'bg-light-unread' : '' }}"
                             data-notification-id="{{ $notification->id }}">
                            <!-- Icono -->
                            <div class="notification-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $notification->bg_color_class }}"
                                 style="width: 48px; height: 48px;">
                                <i class="ti {{ $notification->icon }} fs-4 {{ $notification->color_class }}"></i>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="notification-content flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-semibold {{ !$notification->isRead() ? 'text-dark' : 'text-secondary' }}">
                                        {{ $notification->title }}
                                        @if(!$notification->isRead())
                                            <span class="badge bg-primary-custom ms-2" style="font-size: 0.65rem;">Nueva</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted flex-shrink-0 ms-2">{{ $notification->time_ago }}</small>
                                </div>
                                <p class="mb-2 text-muted small">{{ $notification->message }}</p>
                                <div class="d-flex gap-2">
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" 
                                           class="btn btn-sm btn-outline-primary-custom notification-action"
                                           data-id="{{ $notification->id }}">
                                            <i class="ti ti-external-link me-1"></i>Ver más
                                        </a>
                                    @endif
                                    @if(!$notification->isRead())
                                        <button class="btn btn-sm btn-outline-secondary mark-read-btn" 
                                                data-id="{{ $notification->id }}">
                                            <i class="ti ti-check me-1"></i>Marcar como leída
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger delete-notification-btn" 
                                            data-id="{{ $notification->id }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ti ti-bell-off fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No hay notificaciones</h5>
                            <p class="text-muted small">
                                @if($filter === 'unread')
                                    No tienes notificaciones sin leer
                                @elseif($filter === 'read')
                                    No tienes notificaciones leídas
                                @else
                                    Cuando tengas notificaciones aparecerán aquí
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>
                
                @if($notifications->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $notifications->appends(['filter' => $filter])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-light-unread {
        background-color: #fef8f4 !important;
        border-left: 3px solid var(--color-primary) !important;
    }
    
    .notification-item {
        transition: all 0.2s ease;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa !important;
    }
    
    .notification-item:last-child {
        border-bottom: none !important;
    }
    
    .btn-primary-custom {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
        color: white;
    }
    
    .btn-primary-custom:hover {
        background-color: #8c786a;
        border-color: #8c786a;
        color: white;
    }
    
    .btn-outline-primary-custom {
        color: var(--color-primary);
        border-color: var(--color-primary);
    }
    
    .btn-outline-primary-custom:hover {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
        color: white;
    }
    
    .bg-primary-custom {
        background-color: var(--color-primary) !important;
    }
    
    .text-primary-custom {
        color: var(--color-primary) !important;
    }
    
    /* Colores sutiles para iconos */
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-danger-subtle { background-color: #f8d7da !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    .bg-info-subtle { background-color: #cff4fc !important; }
    .bg-primary-subtle { background-color: #e8ddd5 !important; }
    .bg-secondary-subtle { background-color: #e9ecef !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Marcar todas como leídas
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        fetch('{{ route("notifications.markAllAsRead") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: 'Todas las notificaciones han sido marcadas como leídas',
                    confirmButtonColor: '#A08A7A',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            }
        });
    });
    
    // Limpiar leídas
    document.getElementById('clearReadBtn')?.addEventListener('click', function() {
        Swal.fire({
            title: '¿Eliminar notificaciones leídas?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#A08A7A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("notifications.destroyAllRead") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Listo!',
                            text: 'Notificaciones leídas eliminadas',
                            confirmButtonColor: '#A08A7A',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });
    
    // Marcar individual como leída
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${id}"]`);
                    item.classList.remove('bg-light-unread');
                    this.remove();
                    updateNotificationBadge();
                }
            });
        });
    });
    
    // Eliminar notificación individual
    document.querySelectorAll('.delete-notification-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: '¿Eliminar notificación?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#A08A7A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`[data-notification-id="${id}"]`).remove();
                            updateNotificationBadge();
                        }
                    });
                }
            });
        });
    });
    
    // Marcar como leída al hacer click en "Ver más"
    document.querySelectorAll('.notification-action').forEach(link => {
        link.addEventListener('click', function(e) {
            const id = this.dataset.id;
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        });
    });
    
    function updateNotificationBadge() {
        fetch('{{ route("notifications.unreadCount") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('#notificationBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            });
    }
});
</script>
@endpush