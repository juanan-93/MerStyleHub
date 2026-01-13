<div class="d-flex flex-column h-100 bg-white">
    <!-- Logo -->
    <div class="p-3 border-bottom text-center d-none d-lg-block">
        <img src="{{ asset('images/logos/logoheader.png') }}" alt="Logo" height="50" class="img-fluid">
    </div>
    
    <div class="p-3">
        
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded {{ request()->routeIs('home') ? 'active' : '' }}" 
                   href="{{ route('home') }}" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="{{ __('Dashboard') }}"
                   style="color: var(--color-secondary);">
                    <i class="ti ti-home sidebar-icon" style="font-size: 1.25rem;"></i>
                    <span class="ms-2">{{ __('Dashboard') }}</span>
                </a>
            </li>
            
            <!-- Clientes -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   title="{{ __('Clientes') }}"
                   style="color: var(--color-secondary);">
                    <i class="ti ti-users sidebar-icon" style="font-size: 1.25rem;"></i>
                    <span class="ms-2">{{ __('Clientes') }}</span>
                </a>
            </li>
            
            <!-- Productos -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   title="{{ __('Productos') }}"
                   style="color: var(--color-secondary);">
                    <i class="ti ti-package sidebar-icon" style="font-size: 1.25rem;"></i>
                    <span class="ms-2">{{ __('Productos') }}</span>
                </a>
            </li>
            
            <!-- Pedidos -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   title="{{ __('Pedidos') }}"
                   style="color: var(--color-secondary);">
                    <i class="ti ti-shopping-cart sidebar-icon" style="font-size: 1.25rem;"></i>
                    <span class="ms-2">{{ __('Pedidos') }}</span>
                </a>
            </li>

            <!-- Separador -->
            <li class="nav-item my-2">
                <hr class="text-secondary opacity-25">
            </li>
            
            <!-- Reportes -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   title="{{ __('Reportes') }}"
                   style="color: var(--color-secondary);">
                    <i class="ti ti-chart-bar sidebar-icon" style="font-size: 1.25rem;"></i>
                    <span class="ms-2">{{ __('Reportes') }}</span>
                </a>
            </li>
            
            <!-- Configuración -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   title="{{ __('Configuración') }}"
                   style="color: var(--color-secondary);">
                    <i class="ti ti-settings sidebar-icon" style="font-size: 1.25rem;"></i>
                    <span class="ms-2">{{ __('Configuración') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

