<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <!-- Header del Calendario -->
    <div class="card-header bg-white py-4 border-bottom d-flex flex-column gap-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-primary-custom">
                    <i class="ti ti-calendar me-2"></i>{{ __('Calendario de Reservas') }}
                </h4>
                <p class="text-muted small mb-0">{{ __('Gestiona y visualiza todas tus citas programadas') }}</p>
            </div>
            
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Buscador rápido -->
                <div class="input-group input-group-sm shadow-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="ti ti-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="{{ __('Buscar cliente o servicio...') }}">
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm px-3 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-filter me-1"></i>{{ __('Filtros') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><h6 class="dropdown-header">{{ __('Estado de Cita') }}</h6></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><span class="dot bg-warning"></span>{{ __('Pendientes') }}</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><span class="dot bg-success"></span>{{ __('Confirmadas') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">{{ __('Limpiar filtros') }}</a></li>
                    </ul>
                </div>

                <!-- Botón Hoy -->
                <button class="btn btn-primary-custom btn-sm px-4 rounded-pill shadow-sm">
                    {{ __('Hoy') }}
                </button>
            </div>
        </div>

        <!-- Toolbar de Navegación y Leyenda -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pt-2">
            <div class="d-flex align-items-center gap-3">
                <!-- Navegación -->
                <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                    <button class="btn btn-icon btn-sm rounded-circle hover-bg-white border-0">
                        <i class="ti ti-chevron-left text-secondary"></i>
                    </button>
                    <span class="px-3 fw-bold text-secondary" style="min-width: 140px; text-align: center;">
                        Enero 2026
                    </span>
                    <button class="btn btn-icon btn-sm rounded-circle hover-bg-white border-0">
                        <i class="ti ti-chevron-right text-secondary"></i>
                    </button>
                </div>

                <!-- Selectores de vista -->
                <div class="btn-group shadow-sm" role="group">
                    <button type="button" class="btn btn-outline-primary-custom btn-sm px-3 active">{{ __('Mes') }}</button>
                    <button type="button" class="btn btn-outline-primary-custom btn-sm px-3">{{ __('Semana') }}</button>
                    <button type="button" class="btn btn-outline-primary-custom btn-sm px-3">{{ __('Día') }}</button>
                </div>
            </div>

            <!-- Leyenda de Estados (Desktop Only para evitar saturar) -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-warning"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Pendiente') }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-success"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Confirmada') }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-info"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Finalizada') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuerpo del Calendario -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 calendar-grid">
                <thead>
                    <tr class="bg-light">
                        @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                            <th class="text-center py-3 border-0">
                                <span class="text-uppercase small fw-bold tracking-wider text-muted">{{ __($dia) }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Simulación de cuadrícula de calendario
                        $rows = 5;
                        $cols = 7;
                        $dayCounter = 1;
                        $prevMonthDays = 29; // Diciembre termina en 31, Lun=29, Mar=30, Mie=31
                        $startOffset = 3; // Empieza en Miércoles (0-indexed base Lunes) -> Lun, Mar, Mie es el 1
                    @endphp

                    @for ($r = 0; $r < $rows; $r++)
                        <tr>
                            @for ($c = 0; $c < $cols; $c++)
                                @php
                                    $cellId = ($r * 7) + $c;
                                    $isCurrentMonth = $cellId >= $startOffset && $dayCounter <= 31;
                                    $displayDay = 0;
                                    
                                    if ($cellId < $startOffset) {
                                        $displayDay = $prevMonthDays + $cellId;
                                    } elseif ($dayCounter <= 31) {
                                        $displayDay = $dayCounter++;
                                    } else {
                                        $displayDay = $dayCounter++ - 31;
                                    }
                                    
                                    $isToday = $isCurrentMonth && $displayDay == 24; // Hoy es 24 de Enero 2026 según el contexto
                                @endphp
                                <td class="calendar-cell p-2 {{ !$isCurrentMonth ? 'bg-light bg-opacity-50 grayscale' : '' }}" style="height: 140px; vertical-align: top; width: 14.28%;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="day-number fw-bold {{ $isToday ? 'today-badge' : ($isCurrentMonth ? 'text-secondary' : 'text-muted opacity-50') }}">
                                            {{ $displayDay }}
                                        </span>
                                        @if($isCurrentMonth)
                                            <button class="btn btn-link btn-sm p-0 text-muted opacity-25 hover-opacity-100 transition">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Ejemplo de Citas Maquetadas para Administración -->
                                    <div class="appointment-list d-flex flex-column gap-1 overflow-hidden" style="max-height: 100px;">
                                        @if($isCurrentMonth && $displayDay == 24)
                                            <!-- Cita Confirmada -->
                                            <div class="appointment-card confirmed small d-flex align-items-center justify-content-between px-2" title="Confirmada: 10:00 - Corte de Cabello (Ana García)">
                                                <div class="d-flex align-items-center gap-1 text-truncate">
                                                    <span class="dot bg-success"></span>
                                                    <strong class="time">10:00</strong>
                                                    <span class="client text-truncate">Ana García</span>
                                                </div>
                                                <i class="ti ti-check text-success ms-1 d-none d-md-block"></i>
                                            </div>
                                            <!-- Cita Pendiente -->
                                            <div class="appointment-card pending small d-flex align-items-center justify-content-between px-2" title="Pendiente: 12:30 - Manicura (Luis Torres)">
                                                <div class="d-flex align-items-center gap-1 text-truncate">
                                                    <span class="dot bg-warning"></span>
                                                    <strong class="time">12:30</strong>
                                                    <span class="client text-truncate">Luis Torres</span>
                                                </div>
                                                <i class="ti ti-alert-circle text-warning ms-1 d-none d-md-block"></i>
                                            </div>
                                        @endif

                                        @if($isCurrentMonth && $displayDay == 25)
                                            <!-- Cita Finalizada -->
                                            <div class="appointment-card finished small d-flex align-items-center px-2" title="Finalizada: 16:00 - Carla Ruiz">
                                                <span class="dot bg-info"></span>
                                                <strong class="time ms-1">16:00</strong>
                                                <span class="client text-truncate ms-1">Carla Ruiz</span>
                                            </div>
                                        @endif

                                        @if($isCurrentMonth && $displayDay == 20)
                                            <!-- Cita Cancelada -->
                                            <div class="appointment-card cancelled small d-flex align-items-center px-2 opacity-50" title="Cancelada: 09:15">
                                                <span class="dot bg-danger"></span>
                                                <strong class="time ms-1">09:15</strong>
                                                <span class="client text-truncate ms-1 text-decoration-line-through">Corte</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    :root {
        --color-primary-cal: #A08A7A;
        --color-secondary-cal: #343434;
        --color-light-cal: #F5F3F0;
        --color-border-cal: #D9D4CE;
    }

    .calendar-grid {
        table-layout: fixed;
        border-collapse: collapse;
    }

    .calendar-cell {
        border: 1px solid var(--color-border-cal) !important;
        transition: all 0.2s ease;
    }

    .calendar-cell:hover:not(.bg-light) {
        background-color: #fffaf7;
        box-shadow: inset 0 0 10px rgba(160, 138, 122, 0.05);
    }

    .day-number {
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
    }

    .today-badge {
        background-color: var(--color-primary-cal);
        color: white !important;
        box-shadow: 0 2px 4px rgba(160, 138, 122, 0.3);
    }

    /* Estilos de Citas */
    .appointment-card {
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        white-space: nowrap;
        background-color: var(--color-white);
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .appointment-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .appointment-card.confirmed {
        border-left: 3px solid #28a745;
        background-color: #f0fff4;
        color: #155724;
    }

    .appointment-card.pending {
        border-left: 3px solid #ffc107;
        background-color: #fffbeb;
        color: #856404;
    }

    .appointment-card.finished {
        border-left: 3px solid #0dcaf0;
        background-color: #f0faff;
        color: #055160;
    }

    .appointment-card.cancelled {
        border-left: 3px solid #dc3545;
        background-color: #fff5f5;
        color: #721c24;
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .time {
        font-weight: 700;
        color: inherit;
    }

    .client {
        font-weight: 500;
    }

    /* Helpers de UI */
    .text-primary-custom {
        color: var(--color-primary-cal) !important;
    }

    .btn-primary-custom {
        background-color: var(--color-primary-cal);
        border-color: var(--color-primary-cal);
        color: white;
    }

    .btn-primary-custom:hover {
        background-color: #8c786a;
        border-color: #8c786a;
        color: white;
    }

    .btn-outline-primary-custom {
        color: var(--color-primary-cal);
        border-color: var(--color-primary-cal);
    }

    .btn-outline-primary-custom:hover, 
    .btn-outline-primary-custom.active {
        background-color: var(--color-primary-cal);
        color: white;
    }

    .hover-bg-white:hover {
        background-color: white !important;
    }

    .grayscale {
        filter: grayscale(1);
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }
</style>
