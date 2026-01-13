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
                
                <!-- Notificaciones -->
                <li class="nav-item">
                    <button class="btn btn-link text-decoration-none position-relative" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notificaciones" style="color: var(--color-secondary);">
                        <i class="ti ti-bell" style="font-size: 1.3rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill" style="font-size: 0.6rem;">3</span>
                    </button>
                </li>

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
                </li>
            </ul>
        </div>
    </div>
</nav>

