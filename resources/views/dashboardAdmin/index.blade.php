

@extends('layouts.app', ['title' => 'Dashboard'])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-home me-1"></i>Dashboard
    </li>
@endsection

@push('styles')
    <style>
        .stat-card {
            background: linear-gradient(135deg, var(--color-white) 0%, var(--color-light) 100%);
        }
        
        .stat-card .stat-number {
            color: var(--color-primary);
            font-weight: 700;
            font-size: 2rem;
        }
        
        .stat-card .stat-label {
            color: var(--color-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .tabs-container .nav-tabs .nav-link { color: var(--color-secondary); }
        .tabs-container .tab-pane { min-height: 220px; }
        .placeholder-box { background: var(--color-light); border: 1px dashed var(--color-border, #D9D4CE); padding: 24px; border-radius: 8px; }
    </style>
@endpush

@section('content')
    <div class="tabs-container">
        {{-- Botones tabs  --}}
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Dashboard</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="false">Citas</button>
            </li>
        </ul>

        {{-- Contenido  de los tabs--}}
        <div class="tab-content p-3 border border-top-0 bg-white">
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                @include('dashboardAdmin.details.dashboard')
            </div>

            <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                @include('dashboardAdmin.details.calendar')
            </div>

        </div>
    </div>
  
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Verificar si hay un parámetro tab en la URL
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            
            if (activeTab) {
                // Activar el tab correspondiente
                const tabButton = document.querySelector(`#dashboardTabs button[data-bs-target="#${activeTab}"]`);
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
            }
            
            // Guardar el tab activo en la URL cuando cambia
            document.querySelectorAll('#dashboardTabs button[data-bs-toggle="tab"]').forEach(function(tabBtn) {
                tabBtn.addEventListener('shown.bs.tab', function(e) {
                    const tabId = e.target.getAttribute('data-bs-target').replace('#', '');
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.replaceState({}, '', url);
                });
            });
        });
    </script>
@endpush
