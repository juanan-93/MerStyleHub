<aside class="bg-white shadow-sm d-none d-lg-block" style="width: 250px; min-height: calc(100vh - 64px);">
    <div class="p-3">
        <h6 class="text-uppercase fw-bold mb-3" style="color: #A08A7A; font-size: 0.75rem; letter-spacing: 0.5px;">
            {{ __('Menu') }}
        </h6>
        
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded {{ request()->routeIs('home') ? 'active' : '' }}" 
                   href="{{ route('home') }}" 
                   style="color: #343434; {{ request()->routeIs('home') ? 'background-color: #ECE9E2;' : '' }}">
                    <i class="ti ti-home me-2"></i>
                    {{ __('Home') }}
                </a>
            </li>
            
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   style="color: #343434;">
                    <i class="ti ti-users me-2"></i>
                    {{ __('Customers') }}
                </a>
            </li>
            
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center rounded" 
                   href="#" 
                   style="color: #343434;">
                    <i class="ti ti-settings me-2"></i>
                    {{ __('Settings') }}
                </a>
            </li>
        </ul>
    </div>
</aside>
