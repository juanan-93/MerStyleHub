<div class="d-flex flex-column h-100 bg-white">
    <!-- Logo -->
    <div class="p-3 border-bottom text-center d-none d-lg-block">
        <img src="{{ asset('images/logos/logoheader.png') }}" alt="Logo" height="50" class="img-fluid">
    </div>
    
    <div class="p-3">
        
        <ul class="nav flex-column sidebar-nav">

            @role('customer')
            
                <!-- Dashboard User -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('dashboardUser.*') ? 'active' : '' }}" 
                    href="{{ route('dashboardUser.index') }}" 
                    title="{{ __('Mi Panel') }}">
                        <i class="ti ti-home sidebar-icon"></i>
                        <span class="ms-2">{{ __('Mi Panel') }}</span>
                    </a>
                </li>

                <!-- Cuestionarios -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('user-questionnaire.*') ? 'active' : '' }}" 
                    href="{{ route('user-questionnaire.index') }}" 
                    title="{{ __('Cuestionarios') }}">
                        <i class="ti ti-clipboard-list sidebar-icon"></i>
                        <span class="ms-2">{{ __('Cuestionarios') }}</span>
                    </a>
                </li>

                <!-- Buzon de entrada chat/mensajes -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('chat-user.*') ? 'active' : '' }}" 
                    href="{{ route('chat-user.index') }}" 
                    title="{{ __('Mensajes') }}">
                        <i class="ti ti-send sidebar-icon"></i>
                        <span class="ms-2">{{ __('Mensajes') }}</span>
                    </a>
                </li>

            @endrole

            @role('admin')

                <!-- Dashboard -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('dashboardAdmin.*') ? 'active' : '' }}" 
                    href="{{ route('dashboardAdmin.index') }}" 
                    title="{{ __('Dashboard') }}">
                        <i class="ti ti-home sidebar-icon"></i>
                        <span class="ms-2">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <!-- Buzon de entrada chat/mensajes -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('chat-admin.*') ? 'active' : '' }}" 
                    href="{{ route('chat-admin.index') }}" 
                    title="{{ __('Mensajes') }}">
                        <i class="ti ti-send sidebar-icon"></i>
                        <span class="ms-2">{{ __('Mensajes') }}</span>
                    </a>
                </li>
                
                <!-- Clientes -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                    href="{{ route('users.index') }}" 
                    title="{{ __('Clientes') }}">
                        <i class="ti ti-users sidebar-icon"></i>
                        <span class="ms-2">{{ __('Clientes') }}</span>
                    </a>
                </li>
                
                <!--Calendario -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('admin_appointments.*') ? 'active' : '' }}" 
                    href="{{ route('admin_appointments.index') }}" 
                    title="{{ __('Citas') }}">
                        <i class="ti ti-calendar sidebar-icon"></i>
                        <span class="ms-2">{{ __('Citas') }}</span>
                    </a>
                </li>
                
                <!-- Productos -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                    href="{{ route('products.index') }}" 
                    title="{{ __('Productos') }}">
                        <i class="ti ti-shopping-cart sidebar-icon"></i>
                        <span class="ms-2">{{ __('Productos') }}</span>
                    </a>
                </li>
                
                <!-- Cuestionarios -->
                <li class="nav-item mb-1">
                    <a class="nav-link sidebar-link d-flex align-items-center rounded {{ request()->routeIs('questionnaire.*') ? 'active' : '' }}" 
                    href="{{ route('questionnaire.index') }}" 
                    title="{{ __('Cuestionarios') }}">
                        <i class="ti ti-clipboard-list sidebar-icon"></i>
                        <span class="ms-2">{{ __('Cuestionarios') }}</span>
                    </a>
                </li>

            @endrole
            
            <!-- Separador -->
            <li class="nav-item my-2">
                <hr class="text-secondary opacity-25">
            </li>
            

        </ul>
    </div>
</div>

<style>
    /* Sidebar Navigation Styles */
    .sidebar-nav .sidebar-link {
        color: var(--color-secondary);
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .sidebar-nav .sidebar-link .sidebar-icon {
        font-size: 1.25rem;
        transition: all 0.2s ease;
    }
    
    /* Hover State */
    .sidebar-nav .sidebar-link:hover {
        background-color: var(--color-light);
        color: var(--color-primary);
        border-color: var(--color-border);
    }
    
    .sidebar-nav .sidebar-link:hover .sidebar-icon {
        color: var(--color-primary);
        transform: scale(1.1);
    }
    
    /* Active State */
    .sidebar-nav .sidebar-link.active {
        background-color: var(--color-primary);
        color: var(--color-white) !important;
        border-color: var(--color-primary);
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(160, 138, 122, 0.3);
    }
    
    .sidebar-nav .sidebar-link.active .sidebar-icon {
        color: var(--color-white);
    }
    
    .sidebar-nav .sidebar-link.active:hover {
        background-color: #8f7668;
        border-color: #8f7668;
        color: var(--color-white) !important;
    }
    
    .sidebar-nav .sidebar-link.active:hover .sidebar-icon {
        color: var(--color-white);
        transform: scale(1.1);
    }
</style>

