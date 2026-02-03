<nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom" style="padding: 0.65rem 0rem;">
    <div class="container-fluid">
        <!-- Botón Toggle Sidebar (Mobile - Offcanvas) -->
        <button class="btn btn-link text-decoration-none me-2 d-lg-none" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#sidebarOffcanvas" 
                aria-controls="sidebarOffcanvas"
                style="color: var(--color-primary);">
            <i class="ti ti-menu-2" style="font-size: 1.5rem;"></i>
        </button>

        <!-- Botón Toggle Sidebar (Desktop - Collapse) -->
        <button class="btn btn-link text-decoration-none me-2 d-none d-lg-block" 
                id="desktopSidebarToggle"
                style="color: var(--color-primary);">
            <i class="ti ti-menu-2" style="font-size: 1.5rem;"></i>
        </button>
        
        <!-- Navbar items (siempre visible) -->
        <div class="ms-auto">
            <ul class="navbar-nav d-flex flex-row align-items-center gap-2">
                
                <!-- Notificaciones Dropdown -->
                @auth
                <li class="nav-item dropdown">
                    <button class="btn btn-link text-decoration-none position-relative" 
                            id="notificationDropdown"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            style="color: var(--color-secondary);">
                        <i class="ti ti-bell" style="font-size: 1.3rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill" 
                              id="notificationBadge"
                              style="font-size: 0.6rem; display: none;">0</span>
                    </button>
                    
                    <!-- Dropdown Menu de Notificaciones -->
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow-lg border-0" 
                         aria-labelledby="notificationDropdown"
                         style="width: 360px; max-width: 95vw;">
                        <!-- Header del dropdown -->
                        <div class="dropdown-header d-flex justify-content-between align-items-center py-3 px-3 border-bottom">
                            <h6 class="mb-0 fw-bold">
                                <i class="ti ti-bell me-1"></i>Notificaciones
                            </h6>
                            <button class="btn btn-sm btn-link text-decoration-none p-0" 
                                    id="markAllReadDropdown"
                                    style="color: var(--color-primary); font-size: 0.8rem;">
                                Marcar todas como leídas
                            </button>
                        </div>
                        
                        <!-- Lista de notificaciones (scrollable) -->
                        <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
                            <div id="notificationItems" class="py-2">
                                <!-- Las notificaciones se cargarán aquí via AJAX -->
                                <div class="text-center py-4" id="notificationLoading">
                                    <div class="spinner-border spinner-border-sm text-muted" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="text-muted small mb-0 mt-2">Cargando notificaciones...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer del dropdown -->
                        <div class="dropdown-footer border-top p-2 text-center">
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-link text-decoration-none w-100" style="color: var(--color-primary);">
                                <i class="ti ti-list me-1"></i>Ver todas las notificaciones
                            </a>
                        </div>
                    </div>
                </li>
                @endauth

                <!-- Mensajes -->
                <li class="nav-item">
                    <button class="btn btn-link text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Mensajes" style="color: var(--color-secondary);">
                        <i class="ti ti-mail" style="font-size: 1.3rem;"></i>
                    </button>
                </li>

                <!-- Separador -->
                <li class="nav-item d-none d-lg-block">
                    <div class="vr" style="height: 1.5rem; margin-top: 0.25rem;"></div>
                </li>
                
                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    @auth
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" style="color: var(--color-secondary);">
                            <div class="rounded-circle" style="width: 32px; height: 32px; background-color: var(--color-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.85rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">{{ Auth::user()->email }}</h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="ti ti-user me-2"></i>Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="ti ti-settings me-2"></i>Configuración
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ti ti-logout me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('login') }}" style="color: var(--color-secondary);">
                            <i class="ti ti-login fs-4"></i>
                            <span>{{ __('Iniciar Sesión') }}</span>
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

@auth
<style>
    /* Estilos del dropdown de notificaciones */
    .notification-dropdown {
        border-radius: 12px !important;
    }
    
    .notification-dropdown .dropdown-header {
        background-color: #fafafa;
    }
    
    .notification-item-dropdown {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
        cursor: pointer;
    }
    
    .notification-item-dropdown:hover {
        background-color: #f8f9fa;
    }
    
    .notification-item-dropdown:last-child {
        border-bottom: none;
    }
    
    .notification-item-dropdown.unread {
        background-color: #fef8f4;
        border-left: 3px solid var(--color-primary);
    }
    
    .notification-icon-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .notification-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .notification-list::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .notification-list::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 3px;
    }
    
    /* Colores sutiles para iconos en dropdown */
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-danger-subtle { background-color: #f8d7da !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    .bg-info-subtle { background-color: #cff4fc !important; }
    .bg-primary-subtle { background-color: #e8ddd5 !important; }
    .bg-secondary-subtle { background-color: #e9ecef !important; }
    
    .text-primary-custom { color: var(--color-primary) !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationItems = document.getElementById('notificationItems');
    const notificationLoading = document.getElementById('notificationLoading');
    const markAllReadDropdown = document.getElementById('markAllReadDropdown');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    let notificationsLoaded = false;
    
    // Cargar contador inicial
    loadUnreadCount();
    
    // Cargar notificaciones cuando se abre el dropdown
    notificationDropdown?.addEventListener('shown.bs.dropdown', function() {
        loadNotifications();
    });
    
    // Marcar todas como leídas desde el dropdown
    markAllReadDropdown?.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
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
                // Actualizar UI
                document.querySelectorAll('.notification-item-dropdown.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                updateBadge(0);
            }
        });
    });
    
    function loadUnreadCount() {
        fetch('{{ route("notifications.unreadCount") }}')
            .then(response => response.json())
            .then(data => {
                updateBadge(data.count);
            })
            .catch(error => console.error('Error loading notification count:', error));
    }
    
    function loadNotifications() {
        if (notificationLoading) {
            notificationLoading.style.display = 'block';
        }
        
        fetch('{{ route("notifications.dropdown") }}')
            .then(response => response.json())
            .then(data => {
                if (notificationLoading) {
                    notificationLoading.style.display = 'none';
                }
                
                if (data.notifications.length === 0) {
                    notificationItems.innerHTML = `
                        <div class="text-center py-4">
                            <i class="ti ti-bell-off fs-2 text-muted"></i>
                            <p class="text-muted small mb-0 mt-2">No tienes notificaciones</p>
                        </div>
                    `;
                } else {
                    notificationItems.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item-dropdown d-flex align-items-start gap-2 ${!notification.is_read ? 'unread' : ''}"
                             data-id="${notification.id}"
                             data-url="${notification.action_url || ''}">
                            <div class="notification-icon-sm ${notification.bg_color_class}">
                                <i class="ti ${notification.icon} ${notification.color_class}"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong class="small ${!notification.is_read ? 'text-dark' : 'text-secondary'}" style="font-size: 0.85rem;">
                                        ${notification.title}
                                    </strong>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.3;">
                                    ${notification.message.length > 80 ? notification.message.substring(0, 80) + '...' : notification.message}
                                </p>
                                <small class="text-muted" style="font-size: 0.7rem;">${notification.time_ago}</small>
                            </div>
                        </div>
                    `).join('');
                    
                    // Agregar event listeners a los items
                    document.querySelectorAll('.notification-item-dropdown').forEach(item => {
                        item.addEventListener('click', function() {
                            const id = this.dataset.id;
                            const url = this.dataset.url;
                            
                            // Marcar como leída
                            fetch(`/notifications/${id}/read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            }).then(() => {
                                this.classList.remove('unread');
                                loadUnreadCount();
                            });
                            
                            // Redirigir si hay URL
                            if (url) {
                                window.location.href = url;
                            }
                        });
                    });
                }
                
                updateBadge(data.unread_count);
                notificationsLoaded = true;
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                if (notificationLoading) {
                    notificationLoading.innerHTML = `
                        <div class="text-center py-4">
                            <i class="ti ti-alert-circle fs-2 text-danger"></i>
                            <p class="text-muted small mb-0 mt-2">Error al cargar</p>
                        </div>
                    `;
                }
            });
    }
    
    function updateBadge(count) {
        if (notificationBadge) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.style.display = 'inline';
            } else {
                notificationBadge.style.display = 'none';
            }
        }
    }
    
    // Actualizar contador cada 60 segundos
    setInterval(loadUnreadCount, 60000);
});
</script>
@endauth

