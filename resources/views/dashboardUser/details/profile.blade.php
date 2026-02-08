@push('styles')
<style>
    .profile-header-card {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8B7669 100%);
        color: white;
        border-radius: 12px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
    }

    .profile-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        border: 4px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
    }

    .profile-info-card {
        background-color: var(--color-light);
        border-radius: 10px;
        transition: transform 0.2s ease;
    }

    .profile-info-card:hover {
        transform: translateY(-2px);
    }

    .profile-info-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background-color: rgba(160, 138, 122, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .profile-info-icon i {
        font-size: 1.25rem;
        color: var(--color-primary);
    }

    .profile-info-label {
        color: var(--color-secondary);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .profile-info-value {
        color: var(--color-text);
        font-size: 1rem;
        font-weight: 500;
    }

    .profile-stat-card {
        background-color: var(--color-light);
        border-radius: 10px;
        text-align: center;
        padding: 1.25rem;
    }

    .profile-stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--color-primary);
    }

    .profile-stat-label {
        font-size: 0.85rem;
        color: var(--color-secondary);
        font-weight: 500;
    }

    .service-badge {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .next-appointment-card {
        border-left: 4px solid var(--color-primary);
        background-color: var(--color-light);
        border-radius: 0 10px 10px 0;
    }
    
    /* ===== ESTILOS RESPONSIVE MÓVIL - PERFIL ===== */
    @media (max-width: 768px) {
        .profile-header-card .card-body {
            padding: 0.75rem !important;
        }
        
        .profile-avatar, .profile-avatar-placeholder {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
            border-width: 3px;
        }
        
        .profile-header-card h3 {
            font-size: 1.05rem;
        }
        
        .profile-header-card p {
            font-size: 0.75rem;
        }
        
        .service-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
        
        /* Estadísticas compactas */
        .profile-stat-card {
            padding: 0.6rem;
        }
        
        .profile-stat-number {
            font-size: 1.2rem;
        }
        
        .profile-stat-label {
            font-size: 0.65rem;
        }
        
        /* Cards de info compactas */
        .profile-info-card {
            padding: 0.6rem !important;
        }
        
        .profile-info-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
        }
        
        .profile-info-icon i {
            font-size: 0.95rem;
        }
        
        .profile-info-label {
            font-size: 0.65rem;
        }
        
        .profile-info-value {
            font-size: 0.82rem;
        }
        
        /* Próxima cita compacta */
        .next-appointment-card {
            padding: 0.6rem !important;
        }
        
        .next-appointment-card .d-flex.flex-wrap.gap-3 {
            gap: 0.35rem !important;
            flex-direction: column;
        }
        
        .next-appointment-card h6 {
            font-size: 0.85rem;
        }
        
        .next-appointment-card .badge {
            align-self: flex-start;
            font-size: 0.7rem;
        }
        
        /* Sección títulos */
        h5.fw-semibold {
            font-size: 0.95rem;
        }
        
        /* Menos gap en grids */
        .row.g-3 {
            --bs-gutter-y: 0.5rem;
            --bs-gutter-x: 0.5rem;
        }
        
        .row.mb-4 {
            margin-bottom: 0.75rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .profile-avatar, .profile-avatar-placeholder {
            width: 56px;
            height: 56px;
            font-size: 1.35rem;
        }
        
        .profile-header-card h3 {
            font-size: 0.95rem;
        }
        
        .profile-stat-number {
            font-size: 1.05rem;
        }
        
        .profile-info-icon {
            width: 30px;
            height: 30px;
        }
        
        .profile-info-value {
            font-size: 0.78rem;
        }
    }
</style>
@endpush

{{-- Cabecera del perfil con foto --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm profile-header-card">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    {{-- Avatar --}}
                    @if($profile && $profile->profile_image_url)
                        <img src="{{ $profile->profile_image_url }}" alt="Foto de perfil" class="profile-avatar">
                    @else
                        <div class="profile-avatar-placeholder">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-grow-1 text-center text-md-start">
                        <h3 class="mb-1 fw-bold">{{ $user->name }}</h3>
                        <p class="mb-2 opacity-75">
                            <i class="ti ti-mail me-1"></i>{{ $user->email }}
                        </p>
                        @if($profile && $profile->product)
                            <span class="service-badge">
                                <i class="ti ti-briefcase me-1"></i>{{ $profile->product->title }}
                            </span>
                        @endif
                    </div>

                    <div class="text-center text-md-end">
                        <p class="mb-1 opacity-75">
                            <i class="ti ti-calendar me-1"></i>Cliente desde
                        </p>
                        <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Estadísticas rápidas --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-6">
        <div class="profile-stat-card">
            <div class="profile-stat-number">{{ $assignedQuestionnairesCount ?? 0 }}</div>
            <div class="profile-stat-label">Cuestionarios Asignados</div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <div class="profile-stat-card">
            <div class="profile-stat-number">{{ $completedQuestionnairesCount ?? 0 }}</div>
            <div class="profile-stat-label">Cuestionarios Completados</div>
        </div>
    </div>
</div>

{{-- Próxima cita --}}
@if($nextAppointment)
<div class="row mb-4">
    <div class="col-12">
        <h5 class="fw-semibold mb-3" style="color: var(--color-secondary);">
            <i class="ti ti-calendar-event me-2" style="color: var(--color-primary);"></i>Próxima Cita
        </h5>
        <div class="next-appointment-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon" style="width: 50px; height: 50px;">
                    <i class="ti ti-calendar-check" style="font-size: 1.5rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-semibold" style="color: var(--color-secondary);">
                        {{ $nextAppointment->availability->title ?? 'Cita' }}
                    </h6>
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <span>
                            <i class="ti ti-calendar me-1"></i>
                            {{ $nextAppointment->date->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </span>
                        <span>
                            <i class="ti ti-clock me-1"></i>
                            {{ substr($nextAppointment->start_time, 0, 5) }} - {{ substr($nextAppointment->end_time, 0, 5) }}
                        </span>
                    </div>
                </div>
                <span class="badge {{ $nextAppointment->status === 'confirmed' ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                    {{ $nextAppointment->status === 'confirmed' ? 'Confirmada' : 'Pendiente' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Información personal --}}
<div class="row">
    <div class="col-12">
        <h5 class="fw-semibold mb-3" style="color: var(--color-secondary);">
            <i class="ti ti-user me-2" style="color: var(--color-primary);"></i>Información Personal
        </h5>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-phone"></i>
                </div>
                <div>
                    <div class="profile-info-label">Teléfono</div>
                    <div class="profile-info-value">{{ $profile->phone ?? 'No especificado' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-cake"></i>
                </div>
                <div>
                    <div class="profile-info-label">Edad</div>
                    <div class="profile-info-value">
                        @if($profile && $profile->age)
                            {{ $profile->age }} años
                        @else
                            No especificado
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-map-pin"></i>
                </div>
                <div>
                    <div class="profile-info-label">Ciudad</div>
                    <div class="profile-info-value">{{ $profile->city ?? 'No especificado' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-briefcase"></i>
                </div>
                <div>
                    <div class="profile-info-label">Profesión</div>
                    <div class="profile-info-value">{{ $profile->profession ?? 'No especificado' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Información de Estilo --}}
<div class="row">
    <div class="col-12">
        <h5 class="fw-semibold mb-3" style="color: var(--color-secondary);">
            <i class="ti ti-palette me-2" style="color: var(--color-primary);"></i>Información de Estilo
        </h5>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-shopping-bag"></i>
                </div>
                <div>
                    <div class="profile-info-label">Servicio Contratado</div>
                    <div class="profile-info-value">
                        @if($profile && $profile->product)
                            {{ $profile->product->title }}
                        @else
                            No asignado
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-droplet"></i>
                </div>
                <div>
                    <div class="profile-info-label">Colorimetría</div>
                    <div class="profile-info-value">{{ $profile->colorimetry->name ?? 'No asignada' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-body-scan"></i>
                </div>
                <div>
                    <div class="profile-info-label">Morfología</div>
                    <div class="profile-info-value">{{ $profile->morphology ?? 'No especificado' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="profile-info-card card border-0 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-info-icon">
                    <i class="ti ti-shirt"></i>
                </div>
                <div>
                    <div class="profile-info-label">Estilo</div>
                    <div class="profile-info-value">{{ $profile->style ?? 'No definido' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>