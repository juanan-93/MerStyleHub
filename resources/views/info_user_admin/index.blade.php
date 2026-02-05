@extends('layouts.app', ['title' => 'Información del Cliente'])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboardAdmin.index') }}">
            <i class="ti ti-home me-1"></i>Dashboard
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="ti ti-user me-1"></i>Información del Cliente
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

        .tabs-container .nav-tabs .nav-link {
            color: var(--color-secondary);
        }

        .tabs-container .tab-pane {
            min-height: 220px;
        }

        .info-label {
            color: var(--color-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: var(--color-text);
            font-size: 1rem;
        }

        .user-header-card {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8B7669 100%);
            color: white;
        }

        .avatar-large {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 3px solid white;
        }
    </style>
@endpush

@section('content')
    {{-- Encabezado del Usuario --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm user-header-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="avatar-large rounded-circle d-flex align-items-center justify-content-center">
                            <span class="fw-bold" style="font-size: 2rem;">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-bold">{{ $user->name ?? 'Sin nombre' }}</h3>
                            <p class="mb-1"><i class="ti ti-mail me-2"></i>{{ $user->email ?? 'Sin email' }}</p>
                            <p class="mb-0"><i class="ti ti-calendar me-2"></i>Cliente desde {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="text-end">
                            @if($profile && $profile->product)
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="ti ti-briefcase me-1"></i>{{ $profile->product->title }}
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="ti ti-briefcase-off me-1"></i>Sin servicio asignado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sistema de Tabs --}}
    <div class="tabs-container">
        {{-- Botones tabs --}}
        <ul class="nav nav-tabs" id="userInfoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                    <i class="ti ti-info-circle me-2"></i>Información General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="questionnaires-tab" data-bs-toggle="tab" data-bs-target="#questionnaires" type="button" role="tab" aria-controls="questionnaires" aria-selected="false">
                    <i class="ti ti-clipboard-list me-2"></i>Cuestionarios
                </button>
            </li>
        </ul>

        {{-- Contenido de los tabs --}}
        <div class="tab-content p-4 border border-top-0 bg-white">
            {{-- Tab: Información General --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                @include('info_user_admin.details.profile')
            </div>

            {{-- Tab: Cuestionarios --}}
            <div class="tab-pane fade" id="questionnaires" role="tabpanel" aria-labelledby="questionnaires-tab">
                @include('info_user_admin.details.questionnaire')
            </div>
        </div>
    </div>

    {{-- Botón volver --}}
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('dashboardAdmin.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Volver al Dashboard
            </a>
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
                const tabButton = document.querySelector(`#userInfoTabs button[data-bs-target="#${activeTab}"]`);
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
            }
            
            // Guardar el tab activo en la URL cuando cambia
            document.querySelectorAll('#userInfoTabs button[data-bs-toggle="tab"]').forEach(function(tabBtn) {
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
