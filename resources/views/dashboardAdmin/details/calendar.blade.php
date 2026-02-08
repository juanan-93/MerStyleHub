@php
    $calendarData = $calendarData ?? [
        'currentMonth' => now(),
        'monthName' => now()->locale('es')->isoFormat('MMMM YYYY'),
        'daysInMonth' => now()->daysInMonth,
        'firstDayOfWeek' => now()->startOfMonth()->dayOfWeekIso,
        'appointments' => collect(),
        'allSlots' => [],
        'today' => now()->format('Y-m-d'),
        'view' => 'month',
        'weekStart' => now()->startOfWeek()->format('Y-m-d'),
        'weekEnd' => now()->endOfWeek()->format('Y-m-d'),
    ];
    
    $currentMonth = $calendarData['currentMonth'];
    $appointments = $calendarData['appointments'];
    $allSlots = $calendarData['allSlots'] ?? [];
    $today = $calendarData['today'];
    $currentView = $calendarData['view'] ?? 'month';
    $weekStart = \Carbon\Carbon::parse($calendarData['weekStart'] ?? now()->startOfWeek()->format('Y-m-d'));
    $weekEnd = \Carbon\Carbon::parse($calendarData['weekEnd'] ?? now()->endOfWeek()->format('Y-m-d'));
@endphp

<div class="card shadow-sm border-0 animate__animated animate__fadeIn" id="calendarContainer">
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
                    <input type="text" id="searchAppointments" class="form-control border-start-0" placeholder="{{ __('Buscar cliente...') }}">
                </div>

                <div class="dropdown" id="filterDropdown">
                    <button class="btn btn-outline-secondary btn-sm px-3 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="ti ti-filter me-1"></i>{{ __('Filtros') }} <span id="filterCount" class="badge bg-primary-custom ms-1" style="display: none;">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 200px;">
                        <li><h6 class="dropdown-header">{{ __('Estado de Cita') }}</h6></li>
                        <li>
                            <label class="dropdown-item d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                                <input type="checkbox" class="form-check-input filter-checkbox" data-status="available" style="margin: 0;">
                                <span class="dot bg-info"></span>{{ __('Disponibles') }}
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                                <input type="checkbox" class="form-check-input filter-checkbox" data-status="pending" style="margin: 0;">
                                <span class="dot bg-warning"></span>{{ __('Pendientes') }}
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                                <input type="checkbox" class="form-check-input filter-checkbox" data-status="confirmed" style="margin: 0;">
                                <span class="dot bg-success"></span>{{ __('Confirmadas') }}
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                                <input type="checkbox" class="form-check-input filter-checkbox" data-status="cancelled" style="margin: 0;">
                                <span class="dot bg-danger"></span>{{ __('Canceladas') }}
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item d-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                                <input type="checkbox" class="form-check-input filter-checkbox" data-status="blocked" style="margin: 0;">
                                <span class="dot bg-dark"></span>{{ __('Bloqueadas') }}
                            </label>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" id="clearFilters"><i class="ti ti-x me-1"></i>{{ __('Limpiar filtros') }}</a></li>
                    </ul>
                </div>

                <!-- Botón Hoy -->
               <!-- <button class="btn btn-primary-custom btn-sm px-4 rounded-pill shadow-sm" id="goToToday">
                    {{ __('Hoy') }}
                </button>-->
            </div>
        </div>

        <!-- Toolbar de Navegación y Leyenda -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pt-2">
            <div class="d-flex align-items-center gap-3">
                <!-- Toggle Vista Mensual/Semanal -->
                <div class="btn-group shadow-sm" role="group" aria-label="Vista del calendario">
                    <button type="button" class="btn btn-sm {{ $currentView === 'month' ? 'btn-primary-custom active' : 'btn-outline-primary-custom' }}" id="viewMonthBtn" data-view="month">
                        <i class="ti ti-calendar-month me-1"></i>{{ __('Mes') }}
                    </button>
                    <button type="button" class="btn btn-sm {{ $currentView === 'week' ? 'btn-primary-custom active' : 'btn-outline-primary-custom' }}" id="viewWeekBtn" data-view="week">
                        <i class="ti ti-calendar-week me-1"></i>{{ __('Semana') }}
                    </button>
                </div>
                
                <!-- Navegación -->
                <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                    <button class="btn btn-icon btn-sm rounded-circle hover-bg-white border-0" id="prevMonth">
                        <i class="ti ti-chevron-left text-secondary"></i>
                    </button>
                    <span class="px-3 fw-bold text-secondary text-capitalize" id="currentMonthLabel" style="min-width: 200px; text-align: center;">
                        @if($currentView === 'week')
                            {{ $weekStart->locale('es')->isoFormat('D MMM') }} - {{ $weekEnd->locale('es')->isoFormat('D MMM YYYY') }}
                        @else
                            {{ $calendarData['monthName'] }}
                        @endif
                    </span>
                    <button class="btn btn-icon btn-sm rounded-circle hover-bg-white border-0" id="nextMonth">
                        <i class="ti ti-chevron-right text-secondary"></i>
                    </button>
                </div>
            </div>

            <!-- Leyenda de Estados -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-info"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Disponible') }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-warning"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Pendiente') }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-success"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Confirmada') }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-danger"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Cancelada') }}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="dot bg-dark"></span>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ __('Bloqueada') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuerpo del Calendario -->
    <div class="card-body p-0">
        <!-- Vista Mensual -->
        <div class="table-responsive" id="monthView" style="display: {{ $currentView === 'month' ? 'block' : 'none' }};">
            <table class="table table-bordered mb-0 calendar-grid">
                <thead>
                    <tr class="bg-light">
                        @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                            <th class="text-center py-3 border-0">
                                <span class="text-uppercase small fw-bold tracking-wider text-muted">{{ $dia }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="calendarBody">
                    @php
                        $daysInMonth = $calendarData['daysInMonth'];
                        $firstDayOfWeek = $calendarData['firstDayOfWeek'];
                        $startOffset = $firstDayOfWeek - 1;
                        $totalCells = ceil(($startOffset + $daysInMonth) / 7) * 7;
                        $prevMonth = $currentMonth->copy()->subMonth();
                        $prevMonthDays = $prevMonth->daysInMonth;
                        $dayCounter = 1;
                        $nextMonthDay = 1;
                    @endphp

                    @for ($cell = 0; $cell < $totalCells; $cell++)
                        @if ($cell % 7 == 0)
                            <tr>
                        @endif
                        
                        @php
                            if ($cell < $startOffset) {
                                $displayDay = $prevMonthDays - $startOffset + $cell + 1;
                                $isCurrentMonth = false;
                                $cellDate = $prevMonth->copy()->day($displayDay)->format('Y-m-d');
                            } elseif ($dayCounter <= $daysInMonth) {
                                $displayDay = $dayCounter++;
                                $isCurrentMonth = true;
                                $cellDate = $currentMonth->copy()->day($displayDay)->format('Y-m-d');
                            } else {
                                $displayDay = $nextMonthDay++;
                                $isCurrentMonth = false;
                                $cellDate = $currentMonth->copy()->addMonth()->day($displayDay)->format('Y-m-d');
                            }
                            
                            $isToday = $cellDate === $today;
                            $daySlots = $allSlots[$cellDate] ?? [];
                            
                            // Eliminar slots de días/horas pasados del calendario
                            if ($cellDate < $today) {
                                $daySlots = [];
                            } elseif ($isToday) {
                                $daySlots = array_filter($daySlots, function($slot) {
                                    return !\Carbon\Carbon::parse($slot['start_time'])->lte(now());
                                });
                                $daySlots = array_values($daySlots);
                            }
                            
                            $slotsCount = count($daySlots);
                            $availableCount = collect($daySlots)->where('status', 'available')->count();
                            $bookedCount = $slotsCount - $availableCount;
                        @endphp
                        
                        <td class="calendar-cell p-2 {{ !$isCurrentMonth ? 'other-month' : '' }}" 
                            data-date="{{ $cellDate }}"
                            style="height: 130px; vertical-align: top; width: 14.28%;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="day-number fw-bold {{ $isToday ? 'today-badge' : ($isCurrentMonth ? 'text-secondary' : 'text-muted opacity-50') }}">
                                    {{ $displayDay }}
                                </span>
                                @if($slotsCount > 0)
                                    <span class="badge bg-light text-muted small" style="font-size: 0.65rem;">
                                        {{ $availableCount }}/{{ $slotsCount }}
                                    </span>
                                @endif
                            </div>

                            <div class="appointment-list d-flex flex-column gap-1 overflow-auto" style="max-height: 90px;">
                                @foreach($daySlots as $slot)
                                    @php
                                        $statusClass = $slot['status'];
                                        $statusColor = match($slot['status']) {
                                            'available' => 'info',
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                            'cancelled' => 'danger',
                                            'blocked' => 'dark',
                                            default => 'secondary'
                                        };
                                        $displayText = match($slot['status']) {
                                            'available' => 'Disponible',
                                            'blocked' => '🔒 Bloqueado',
                                            default => $slot['client_name'] ? Str::limit($slot['client_name'], 10) : 'Cita'
                                        };
                                        
                                    @endphp
                                    <div class="appointment-card {{ $statusClass }} slot-clickable small d-flex align-items-center justify-content-between px-2" 
                                         data-slot='@json($slot)'
                                         data-date="{{ $cellDate }}"
                                         data-status="{{ $slot['status'] }}"
                                         data-is-past="false"
                                         title="{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $displayText }}">
                                        <div class="d-flex align-items-center gap-1 text-truncate">
                                            <span class="dot bg-{{ $statusColor }}"></span>
                                            <strong class="time">{{ $slot['start_time'] }}</strong>
                                            <span class="client text-truncate">{{ $displayText }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        
                        @if (($cell + 1) % 7 == 0)
                            </tr>
                        @endif
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Vista Semanal -->
        <div class="table-responsive" id="weekView" style="display: {{ $currentView === 'week' ? 'block' : 'none' }};">
            <table class="table table-bordered mb-0 calendar-grid weekly-grid">
                <thead>
                    <tr class="bg-light">
                        <th class="text-center py-3 border-0 hour-column">
                            <span class="text-uppercase small fw-bold tracking-wider text-muted">{{ __('Hora') }}</span>
                        </th>
                        @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $index => $dia)
                            @php
                                $dayDate = $weekStart->copy()->addDays($index);
                                $isToday = $dayDate->format('Y-m-d') === $today;
                            @endphp
                            <th class="text-center py-2 border-0 {{ $isToday ? 'bg-primary-custom bg-opacity-10' : '' }}" data-weekday="{{ $index }}">
                                <span class="text-uppercase small fw-bold tracking-wider text-muted d-block">{{ $dia }}</span>
                                <span class="day-number fw-bold {{ $isToday ? 'today-badge' : 'text-secondary' }}" data-day-number>
                                    {{ $dayDate->day }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="weekCalendarBody">
                    @php
                        // Generar filas por hora (de 8:00 a 20:00)
                        $startHour = 8;
                        $endHour = 20;
                    @endphp
                    @for($hour = $startHour; $hour <= $endHour; $hour++)
                        <tr class="week-row">
                            <td class="text-center py-2 bg-light" style="vertical-align: middle;">
                                <small class="fw-bold text-muted">{{ sprintf('%02d:00', $hour) }}</small>
                            </td>
                            @for($dayIndex = 0; $dayIndex < 7; $dayIndex++)
                                @php
                                    $cellDate = $weekStart->copy()->addDays($dayIndex)->format('Y-m-d');
                                    $isPastDate = $cellDate < $today;
                                    $isToday = $cellDate === $today;
                                    $hourStr = sprintf('%02d:00', $hour);
                                    
                                    // Buscar slots para esta hora y día (excluyendo pasados)
                                    $daySlots = $allSlots[$cellDate] ?? [];
                                    $hourSlots = collect($daySlots)->filter(function($slot) use ($hourStr, $hour, $isPastDate, $isToday) {
                                        // No mostrar slots de días pasados
                                        if ($isPastDate) return false;
                                        // No mostrar slots de horas pasadas del día actual
                                        if ($isToday && \Carbon\Carbon::parse($slot['start_time'])->lte(now())) return false;
                                        $slotHour = (int)substr($slot['start_time'], 0, 2);
                                        return $slotHour === $hour;
                                    })->values()->all();
                                @endphp
                                <td class="week-cell p-1 {{ $isToday ? 'today-cell' : '' }} {{ $isPastDate ? 'past-cell' : '' }}" 
                                    data-date="{{ $cellDate }}" 
                                    data-hour="{{ $hourStr }}"
                                    style="height: 50px; vertical-align: top;">
                                    <div class="week-appointments d-flex flex-column gap-1">
                                        @foreach($hourSlots as $slot)
                                            @php
                                                $statusClass = $slot['status'];
                                                $statusColor = match($slot['status']) {
                                                    'available' => 'info',
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'cancelled' => 'danger',
                                                    'blocked' => 'dark',
                                                    default => 'secondary'
                                                };
                                                $displayText = match($slot['status']) {
                                                    'available' => 'Disponible',
                                                    'blocked' => '🔒',
                                                    default => $slot['client_name'] ? Str::limit($slot['client_name'], 8) : 'Cita'
                                                };
                                            @endphp
                                            <div class="appointment-card week-appointment {{ $statusClass }} slot-clickable small" 
                                                 data-slot='@json($slot)'
                                                 data-date="{{ $cellDate }}"
                                                 data-status="{{ $slot['status'] }}"
                                                 data-is-past="false"
                                                 title="{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $displayText }}">
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="dot bg-{{ $statusColor }}"></span>
                                                    <strong class="time" style="font-size: 0.65rem;">{{ substr($slot['start_time'], 0, 5) }}</strong>
                                                    <span class="client text-truncate" style="font-size: 0.6rem;">{{ $displayText }}</span>
                                                </div>
                                            </div>
                                        @endforeach
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

<!-- Modal de Detalle/Gestión de Cita (para slots reservados) -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0" style="background-color: var(--color-primary-cal); padding: 1.25rem 1.5rem;">
                <h5 class="modal-title text-white fw-semibold" id="appointmentModalLabel">
                    <i class="ti ti-calendar-event me-2"></i>{{ __('Detalles de la Cita') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border" role="status" style="color: var(--color-primary-cal);">
                        <span class="visually-hidden">{{ __('Cargando...') }}</span>
                    </div>
                    <p class="mt-3 text-muted small">{{ __('Cargando detalles...') }}</p>
                </div>
                
                <div id="modalContent" style="display: none;">
                    <!-- Información del cliente -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-circle text-white" style="background-color: var(--color-primary-cal);">
                                <span id="clientInitials">AB</span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" id="clientName" style="color: var(--color-secondary-cal);">Nombre Cliente</h6>
                                <small class="text-muted" id="clientEmail">email@example.com</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap" id="clientPhoneContainer">
                            <span class="badge" style="background-color: var(--color-light-cal); color: var(--color-secondary-cal); border: 1px solid var(--color-border-cal);">
                                <i class="ti ti-phone me-1" style="color: var(--color-primary-cal);"></i>
                                <span id="clientPhone">+34 600 000 000</span>
                            </span>
                        </div>
                    </div>

                    <!-- Datos de la cita -->
                    <div class="rounded-3 p-3 mb-4" style="background-color: var(--color-light-cal); border: 1px solid var(--color-border-cal);">
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Fecha') }}</small>
                                <strong id="appointmentDate" style="color: var(--color-secondary-cal);">24 de enero de 2026</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Horario') }}</small>
                                <strong id="appointmentTime" style="color: var(--color-secondary-cal);">10:00 - 11:00</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Servicio') }}</small>
                                <strong id="appointmentService" style="color: var(--color-secondary-cal);">Consulta</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Estado') }}</small>
                                <span class="badge" id="appointmentStatus">Pendiente</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cambiar estado -->
                    <div class="mb-4 status-section">
                        <label class="form-label small fw-semibold text-uppercase mb-2" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Cambiar estado') }}</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-warning btn-sm status-btn" data-status="pending">
                                <i class="ti ti-clock me-1"></i>{{ __('Pendiente') }}
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm status-btn" data-status="confirmed">
                                <i class="ti ti-check me-1"></i>{{ __('Confirmar') }}
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm status-btn" data-status="cancelled">
                                <i class="ti ti-x me-1"></i>{{ __('Cancelar') }}
                            </button>
                        </div>
                    </div>

                    <!-- Reubicar cita -->
                    <div class="pt-3 move-section" style="border-top: 1px solid var(--color-border-cal);">
                        <button class="btn btn-sm w-100 mb-2 d-flex align-items-center justify-content-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#moveAppointmentSection" style="background-color: var(--color-light-cal); color: var(--color-secondary-cal); border: 1px solid var(--color-border-cal);">
                            <i class="ti ti-calendar-plus"></i>{{ __('Reubicar cita a otra fecha disponible') }}
                            <i class="ti ti-chevron-down ms-auto"></i>
                        </button>
                        <div class="collapse" id="moveAppointmentSection">
                            <div class="card border-0 mt-2" style="background-color: var(--color-light-cal);">
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold" style="color: var(--color-secondary-cal);">
                                            <i class="ti ti-calendar me-1" style="color: var(--color-primary-cal);"></i>{{ __('Selecciona una fecha disponible') }}
                                        </label>
                                        <select class="form-select form-select-sm" id="newDate" style="border-color: var(--color-border-cal);">
                                            <option value="">{{ __('Cargando fechas disponibles...') }}</option>
                                        </select>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="ti ti-info-circle me-1"></i>{{ __('Solo se muestran fechas con disponibilidad configurada') }}
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold" style="color: var(--color-secondary-cal);">
                                            <i class="ti ti-clock me-1" style="color: var(--color-primary-cal);"></i>{{ __('Selecciona un horario libre') }}
                                        </label>
                                        <select class="form-select form-select-sm" id="newTimeSlot" disabled style="border-color: var(--color-border-cal);">
                                            <option value="">{{ __('Selecciona una fecha primero') }}</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-sm w-100" id="confirmMoveBtn" disabled style="background-color: var(--color-primary-cal); border-color: var(--color-primary-cal); color: white;">
                                        <i class="ti ti-check me-1"></i>{{ __('Confirmar reubicación') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-between" style="background-color: #fafafa; padding: 1rem 1.5rem;">
                <div>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="deleteAppointmentBtn">
                        <i class="ti ti-trash me-1"></i>{{ __('Eliminar cita') }}
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm" id="unblockAppointmentBtn" style="display: none;">
                        <i class="ti ti-lock-open me-1"></i>{{ __('Desbloquear') }}
                    </button>
                </div>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="background-color: var(--color-secondary-cal); color: white;">
                    {{ __('Cerrar') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Slot Disponible (Bloquear) -->
<div class="modal fade" id="availableSlotModal" tabindex="-1" aria-labelledby="availableSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0" style="background-color: var(--color-primary-cal); padding: 1.25rem 1.5rem;">
                <h5 class="modal-title text-white fw-semibold" id="availableSlotModalLabel">
                    <i class="ti ti-calendar-check me-2"></i>{{ __('Horario Disponible') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar-circle mx-auto mb-3" style="background-color: var(--color-light-cal); width: 64px; height: 64px;">
                        <i class="ti ti-calendar-time" style="font-size: 1.8rem; color: var(--color-primary-cal);"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color: var(--color-secondary-cal);">{{ __('Este horario está disponible') }}</h6>
                    <p class="text-muted small mb-0">{{ __('Un cliente puede reservar este horario') }}</p>
                </div>
                
                <!-- Datos del slot -->
                <div class="rounded-3 p-3 mb-4" style="background-color: var(--color-light-cal); border: 1px solid var(--color-border-cal);">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ti ti-calendar" style="color: var(--color-primary-cal);"></i>
                                <div>
                                    <small class="text-uppercase fw-semibold d-block" style="font-size: 0.65rem; color: #999; letter-spacing: 0.5px;">{{ __('Fecha') }}</small>
                                    <strong id="slotDate" class="small" style="color: var(--color-secondary-cal);">24 de enero de 2026</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-clock" style="color: var(--color-primary-cal);"></i>
                                <div>
                                    <small class="text-uppercase fw-semibold d-block" style="font-size: 0.65rem; color: #999; letter-spacing: 0.5px;">{{ __('Horario') }}</small>
                                    <strong id="slotTime" class="small" style="color: var(--color-secondary-cal);">10:00 - 11:00</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-tag" style="color: var(--color-primary-cal);"></i>
                                <div>
                                    <small class="text-uppercase fw-semibold d-block" style="font-size: 0.65rem; color: #999; letter-spacing: 0.5px;">{{ __('Servicio') }}</small>
                                    <strong id="slotService" class="small" style="color: var(--color-secondary-cal);">Consulta</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Opción de bloquear -->
                <div class="rounded-3 p-3" style="background-color: #fdf8f6; border: 1px dashed var(--color-primary-cal);">
                    <h6 class="small fw-semibold mb-2" style="color: var(--color-secondary-cal);">
                        <i class="ti ti-lock me-1" style="color: var(--color-primary-cal);"></i>{{ __('¿Necesitas bloquear este horario?') }}
                    </h6>
                    <p class="text-muted small mb-3">{{ __('Al bloquear, los clientes no podrán reservar este horario.') }}</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1" style="color: var(--color-secondary-cal);">
                            {{ __('Motivo del bloqueo') }} <span class="text-muted fw-normal">({{ __('opcional') }})</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="blockReasonInput" 
                               placeholder="{{ __('Ej: Cita personal, Mantenimiento...') }}" 
                               style="border-color: var(--color-border-cal); border-radius: 8px;">
                    </div>
                    
                    <button type="button" class="btn btn-sm w-100" id="blockSlotBtn" 
                            style="background-color: var(--color-secondary-cal); color: white; border-radius: 8px;">
                        <i class="ti ti-lock me-1"></i>{{ __('Bloquear horario') }}
                    </button>
                </div>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center" style="background-color: var(--color-light-cal); padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal" style="border-radius: 20px;">
                    <i class="ti ti-x me-1"></i>{{ __('Cerrar') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Citas Pasadas (Solo lectura) -->
<div class="modal fade" id="pastAppointmentModal" tabindex="-1" aria-labelledby="pastAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0" style="background-color: var(--color-primary-cal); padding: 1.25rem 1.5rem;">
                <h5 class="modal-title text-white fw-semibold" id="pastAppointmentModalLabel">
                    <i class="ti ti-history me-2"></i>{{ __('Cita Pasada') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="pastModalLoading" class="text-center py-5">
                    <div class="spinner-border" role="status" style="color: var(--color-primary-cal);">
                        <span class="visually-hidden">{{ __('Cargando...') }}</span>
                    </div>
                    <p class="mt-3 text-muted small">{{ __('Cargando detalles...') }}</p>
                </div>
                
                <div id="pastModalContent" style="display: none;">
                    <!-- Aviso de cita pasada -->
                    <div class="d-flex align-items-center mb-4 p-3 rounded-3" style="background-color: var(--color-light-cal); border: 1px solid var(--color-border-cal);">
                        <i class="ti ti-history me-2" style="font-size: 1.25rem; color: var(--color-primary-cal);"></i>
                        <small style="color: var(--color-secondary-cal);">{{ __('Esta cita ya ha pasado. Solo puedes ver su información.') }}</small>
                    </div>
                    
                    <!-- Información del cliente -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-circle text-white" style="background-color: var(--color-primary-cal);">
                                <span id="pastClientInitials">AB</span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold" id="pastClientName" style="color: var(--color-secondary-cal);">Nombre Cliente</h6>
                                <small class="text-muted" id="pastClientEmail">email@example.com</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge" style="background-color: var(--color-light-cal); color: var(--color-secondary-cal); border: 1px solid var(--color-border-cal);">
                                <i class="ti ti-phone me-1" style="color: var(--color-primary-cal);"></i>
                                <span id="pastClientPhone">+34 600 000 000</span>
                            </span>
                        </div>
                    </div>

                    <!-- Datos de la cita -->
                    <div class="rounded-3 p-3 mb-3" style="background-color: var(--color-light-cal); border: 1px solid var(--color-border-cal);">
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Fecha') }}</small>
                                <strong id="pastAppointmentDate" style="color: var(--color-secondary-cal);">24 de enero de 2026</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Horario') }}</small>
                                <strong id="pastAppointmentTime" style="color: var(--color-secondary-cal);">10:00 - 11:00</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Servicio') }}</small>
                                <strong id="pastAppointmentService" style="color: var(--color-secondary-cal);">Consulta</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">{{ __('Estado') }}</small>
                                <span class="badge" id="pastAppointmentStatus">Pendiente</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notas si existen -->
                    <div id="pastNotesSection" class="rounded-3 p-3" style="background-color: #fdf8f6; border: 1px solid var(--color-primary-cal); display: none;">
                        <small class="text-uppercase fw-semibold d-block mb-1" style="font-size: 0.7rem; color: var(--color-primary-cal); letter-spacing: 0.5px;">
                            <i class="ti ti-note me-1"></i>{{ __('Notas') }}
                        </small>
                        <p id="pastAppointmentNotes" class="mb-0 small" style="color: var(--color-secondary-cal);"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center" style="background-color: #fafafa; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-sm px-4" data-bs-dismiss="modal" style="background-color: var(--color-secondary-cal); color: white; border-radius: 20px;">
                    <i class="ti ti-x me-1"></i>{{ __('Cerrar') }}
                </button>
            </div>
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

    .calendar-grid { table-layout: fixed; border-collapse: collapse; }
    .calendar-cell { border: 1px solid var(--color-border-cal) !important; transition: all 0.2s ease; }
    .calendar-cell:hover:not(.other-month) { background-color: #fffaf7; box-shadow: inset 0 0 10px rgba(160, 138, 122, 0.05); }
    .calendar-cell.other-month { background-color: #fafafa; }
    .day-number { font-size: 0.85rem; display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; }
    .today-badge { background-color: var(--color-primary-cal); color: white !important; box-shadow: 0 2px 4px rgba(160, 138, 122, 0.3); }
    
    .appointment-card { padding: 4px 8px; border-radius: 5px; font-size: 0.7rem; cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; white-space: nowrap; background-color: var(--color-white); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .appointment-card:hover { transform: translateY(-1px); box-shadow: 0 3px 6px rgba(0,0,0,0.1); z-index: 5; }
    .appointment-card.available { border-left: 3px solid #17a2b8; background-color: #e3f6f8; color: #0c5460; }
    .appointment-card.available:hover { background-color: #d1ecf1; }
    .appointment-card.confirmed { border-left: 3px solid #28a745; background-color: #f0fff4; color: #155724; }
    .appointment-card.pending { border-left: 3px solid #ffc107; background-color: #fffbeb; color: #856404; }
    .appointment-card.cancelled { border-left: 3px solid #dc3545; background-color: #fff5f5; color: #721c24; opacity: 0.7; text-decoration: line-through; }
    .appointment-card.blocked { border-left: 3px solid #343434; background-color: #e9e9e9; color: #343434; opacity: 0.9; font-style: italic; }
    
    /* Citas de fechas pasadas - tachadas pero clickeables para ver info */
    .appointment-card.past-date { 
        opacity: 0.5; 
        text-decoration: line-through; 
        background-color: #f0f0f0 !important; 
        border-left-color: #aaa !important; 
        color: #888 !important; 
    }
    .appointment-card.past-date.past-clickable {
        cursor: pointer !important;
    }
    .appointment-card.past-date:hover { 
        opacity: 0.65;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
    }
    
    /* Scrollbar para lista de citas */
    .appointment-list { scrollbar-width: thin; scrollbar-color: var(--color-border-cal) transparent; }
    .appointment-list::-webkit-scrollbar { width: 4px; }
    .appointment-list::-webkit-scrollbar-track { background: transparent; }
    .appointment-list::-webkit-scrollbar-thumb { background-color: var(--color-border-cal); border-radius: 4px; }
    
    .dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .time { font-weight: 700; color: inherit; }
    .client { font-weight: 500; }
    .avatar-circle { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem; }
    .text-primary-custom { color: var(--color-primary-cal) !important; }
    .bg-primary-custom { background-color: var(--color-primary-cal) !important; }
    .btn-primary-custom { background-color: var(--color-primary-cal); border-color: var(--color-primary-cal); color: white; }
    .btn-primary-custom:hover { background-color: #8c786a; border-color: #8c786a; color: white; }
    .btn-outline-primary-custom { color: var(--color-primary-cal); border-color: var(--color-primary-cal); }
    .btn-outline-primary-custom:hover, .btn-outline-primary-custom.active { background-color: var(--color-primary-cal); color: white; }
    .hover-bg-white:hover { background-color: white !important; }
    .tracking-wider { letter-spacing: 0.05em; }
    .status-btn.active-status { opacity: 1 !important; }
    .status-btn:not(.active-status) { opacity: 0.6; }
    
    /* Filtro activo en dropdown */
    .filter-status.active { background-color: var(--color-light-cal) !important; }
    
    /* Estilos del Modal */
    #appointmentModal .form-select:focus, #appointmentModal .form-control:focus,
    #availableSlotModal .form-select:focus, #availableSlotModal .form-control:focus {
        border-color: var(--color-primary-cal);
        box-shadow: 0 0 0 0.2rem rgba(160, 138, 122, 0.25);
    }
    #appointmentModal .form-select option:checked {
        background-color: var(--color-primary-cal);
        color: white;
    }
    #confirmMoveBtn:not(:disabled):hover {
        background-color: #8c786a !important;
        border-color: #8c786a !important;
    }
    #confirmMoveBtn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .collapse-icon {
        transition: transform 0.3s ease;
    }
    [aria-expanded="true"] .collapse-icon {
        transform: rotate(180deg);
    }
    
    /* ===== ESTILOS VISTA SEMANAL ===== */
    .weekly-grid { table-layout: fixed; }
    .weekly-grid th { min-width: 100px; }
    .weekly-grid th:first-child, .hour-column { min-width: 70px; width: 70px; white-space: nowrap; }
    
    .week-cell { 
        border: 1px solid var(--color-border-cal) !important; 
        transition: all 0.2s ease;
        min-height: 50px;
    }
    .week-cell:hover { 
        background-color: #fffaf7; 
    }
    .week-cell.today-cell { 
        background-color: rgba(160, 138, 122, 0.08) !important;
        border-left: 2px solid var(--color-primary-cal) !important;
        border-right: 2px solid var(--color-primary-cal) !important;
    }
    .week-cell.past-cell { 
        background-color: #fafafa; 
    }
    
    .week-appointment { 
        padding: 2px 4px; 
        border-radius: 3px;
        font-size: 0.65rem;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .week-appointments { 
        max-height: 45px; 
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--color-border-cal) transparent;
    }
    
    .week-row td:first-child { 
        background-color: var(--color-light-cal) !important; 
        border-right: 2px solid var(--color-border-cal) !important;
    }
    
    /* Toggle activo/inactivo */
    #viewMonthBtn.active, #viewWeekBtn.active {
        background-color: var(--color-primary-cal) !important;
        border-color: var(--color-primary-cal) !important;
        color: white !important;
    }
    
    @media (max-width: 768px) { 
        .calendar-cell { height: 100px !important; padding: 4px !important; } 
        .appointment-card { font-size: 0.6rem; padding: 2px 4px; } 
        .day-number { width: 22px; height: 22px; font-size: 0.75rem; }
        .weekly-grid th { min-width: 50px; font-size: 0.7rem; }
        .weekly-grid th:first-child, .hour-column { min-width: 50px; width: 50px; }
        .week-cell { min-height: 40px; }
        .week-appointment { font-size: 0.55rem; padding: 1px 2px; }
    }
    
    /* ===== Select2 dentro del modal de cita ===== */
    #appointmentModal .select2-container { width: 100% !important; }
    #appointmentModal .select2-container--default .select2-selection--single {
        border: 1px solid var(--color-border-cal) !important;
        border-radius: 6px !important;
        height: 34px !important;
        background-color: #fff !important;
    }
    #appointmentModal .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--color-secondary-cal) !important;
        font-size: 0.875rem !important;
        padding: 4px 12px !important;
        line-height: 26px !important;
    }
    #appointmentModal .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 32px !important;
    }
    #appointmentModal .select2-container--default.select2-container--focus .select2-selection--single,
    #appointmentModal .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--color-primary-cal) !important;
        box-shadow: 0 0 0 0.2rem rgba(160, 138, 122, 0.25) !important;
    }
    #appointmentModal .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #f8f9fa !important;
        opacity: 0.7;
    }
    .select2-dropdown.select2-dropdown--calendar {
        border: 1px solid var(--color-border-cal, #D9D4CE) !important;
        border-radius: 6px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
        z-index: 99999 !important;
    }
    .select2-dropdown.select2-dropdown--calendar .select2-results__option {
        padding: 8px 12px !important;
        font-size: 0.875rem !important;
    }
    .select2-dropdown.select2-dropdown--calendar .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(160, 138, 122, 0.15) !important;
        color: var(--color-secondary-cal, #343434) !important;
    }
    .select2-dropdown.select2-dropdown--calendar .select2-results__option[aria-selected=true] {
        background-color: rgba(160, 138, 122, 0.1) !important;
        color: var(--color-primary-cal, #A08A7A) !important;
        font-weight: 500 !important;
    }
    .select2-dropdown.select2-dropdown--calendar .select2-search--dropdown .select2-search__field {
        border: 1px solid var(--color-border-cal, #D9D4CE) !important;
        border-radius: 4px !important;
        padding: 6px 8px !important;
        font-size: 0.85rem !important;
    }
    .select2-dropdown.select2-dropdown--calendar .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--color-primary-cal, #A08A7A) !important;
        outline: none !important;
    }
    .select2-dropdown.select2-dropdown--calendar .select2-results__group {
        padding: 8px 12px !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        color: var(--color-primary-cal, #A08A7A) !important;
        background-color: var(--color-light-cal, #F5F3F0) !important;
        border-top: 1px solid var(--color-border-cal, #D9D4CE) !important;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    const availableSlotModal = new bootstrap.Modal(document.getElementById('availableSlotModal'));
    const pastAppointmentModal = new bootstrap.Modal(document.getElementById('pastAppointmentModal'));
    let currentAppointmentId = null;
    let currentSlotData = null;
    let currentSlotDate = null;
    let currentMonth = {{ $currentMonth->month }};
    let currentYear = {{ $currentMonth->year }};
    
    // ===== CONTROL DE VISTAS MENSUAL/SEMANAL =====
    let currentView = '{{ $currentView }}'; // 'month' o 'week'
    let currentWeekStart = new Date('{{ $weekStart->format("Y-m-d") }}');
    
    const monthView = document.getElementById('monthView');
    const weekView = document.getElementById('weekView');
    const viewMonthBtn = document.getElementById('viewMonthBtn');
    const viewWeekBtn = document.getElementById('viewWeekBtn');
    const currentMonthLabel = document.getElementById('currentMonthLabel');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');
    
    // Función para cambiar entre vistas
    function switchView(view) {
        currentView = view;
        
        if (view === 'month') {
            // Ir a la vista mensual (recargar página sin parámetros de semana)
            const url = new URL(window.location);
            url.searchParams.delete('week_start');
            url.searchParams.delete('view');
            url.searchParams.set('tab', 'appointments');
            window.location.href = url.toString();
        } else {
            // Ir a la vista semanal (recargar página con parámetros de semana)
            loadWeekData();
        }
    }
    
    // Función para cargar datos de la semana actual (navega a la página con parámetros de semana)
    function loadWeekData() {
        const weekEnd = new Date(currentWeekStart);
        weekEnd.setDate(weekEnd.getDate() + 6);
        
        const startStr = currentWeekStart.toISOString().split('T')[0];
        const endStr = weekEnd.toISOString().split('T')[0];
        
        // Navegar con parámetros de semana
        const url = new URL(window.location);
        url.searchParams.set('week_start', startStr);
        url.searchParams.set('view', 'week');
        url.searchParams.set('tab', 'appointments');
        window.location.href = url.toString();
    }
    
    // Event listeners para los botones de vista
    viewMonthBtn.addEventListener('click', function() {
        if (currentView !== 'month') {
            switchView('month');
        }
    });
    
    viewWeekBtn.addEventListener('click', function() {
        if (currentView !== 'week') {
            switchView('week');
        }
    });
    
    // Verificar parámetros de URL para actualizar weekStart si es necesario
    const urlParams = new URLSearchParams(window.location.search);
    const weekStartParam = urlParams.get('week_start');
    if (weekStartParam) {
        currentWeekStart = new Date(weekStartParam);
    }
    
    // Navegación con prevMonth y nextMonth (funciona para ambas vistas)
    prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentView === 'week') {
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
            loadWeekData();
        } else {
            currentMonth--;
            if (currentMonth < 1) { currentMonth = 12; currentYear--; }
            window.location.href = `{{ route('dashboardAdmin.index') }}?month=${currentMonth}&year=${currentYear}&tab=appointments`;
        }
    });
    
    nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentView === 'week') {
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            loadWeekData();
        } else {
            currentMonth++;
            if (currentMonth > 12) { currentMonth = 1; currentYear++; }
            window.location.href = `{{ route('dashboardAdmin.index') }}?month=${currentMonth}&year=${currentYear}&tab=appointments`;
        }
    });
    
    // Click en cualquier slot del calendario (slots activos)
    document.querySelectorAll('.slot-clickable').forEach(card => {
        card.addEventListener('click', function() {
            const slotData = JSON.parse(this.dataset.slot);
            const slotDate = this.dataset.date;
            
            if (slotData.status === 'available') {
                // Mostrar modal de slot disponible (para bloquear)
                showAvailableSlotModal(slotData, slotDate);
            } else {
                // Mostrar modal de cita existente
                currentAppointmentId = slotData.appointment_id;
                loadAppointmentDetails(currentAppointmentId);
                appointmentModal.show();
            }
        });
    });
    
    // Click en slots de fechas pasadas (solo lectura)
    document.querySelectorAll('.past-clickable').forEach(card => {
        card.addEventListener('click', function() {
            const slotData = JSON.parse(this.dataset.slot);
            const slotDate = this.dataset.date;
            
            if (slotData.status === 'available') {
                // Para slots disponibles pasados, mostrar info básica
                showPastSlotInfo(slotData, slotDate);
            } else if (slotData.appointment_id) {
                // Para citas pasadas, cargar detalles
                loadPastAppointmentDetails(slotData.appointment_id);
                pastAppointmentModal.show();
            }
        });
    });
    
    // Mostrar info de slot pasado disponible (sin cita)
    function showPastSlotInfo(slot, date) {
        document.getElementById('pastModalLoading').style.display = 'none';
        document.getElementById('pastModalContent').style.display = 'block';
        
        const dateObj = new Date(date + 'T00:00:00');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = dateObj.toLocaleDateString('es-ES', options);
        
        document.getElementById('pastClientInitials').textContent = '📅';
        document.getElementById('pastClientName').textContent = 'Horario sin reservar';
        document.getElementById('pastClientEmail').textContent = slot.availability_title || 'Sin información';
        document.getElementById('pastClientPhone').textContent = 'N/A';
        document.getElementById('pastAppointmentDate').textContent = formattedDate;
        document.getElementById('pastAppointmentTime').textContent = `${slot.start_time} - ${slot.end_time}`;
        document.getElementById('pastAppointmentService').textContent = slot.availability_title || 'Cita';
        document.getElementById('pastAppointmentStatus').textContent = 'No reservado';
        document.getElementById('pastAppointmentStatus').className = 'badge bg-secondary';
        document.getElementById('pastNotesSection').style.display = 'none';
        
        pastAppointmentModal.show();
    }
    
    // Cargar detalles de cita pasada
    function loadPastAppointmentDetails(id) {
        document.getElementById('pastModalLoading').style.display = 'block';
        document.getElementById('pastModalContent').style.display = 'none';
        
        fetch(`{{ url('dashboardAdmin/appointment') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayPastAppointmentDetails(data.appointment);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar la información de la cita', 'error');
                pastAppointmentModal.hide();
            });
    }
    
    // Mostrar detalles de cita pasada en el modal
    function displayPastAppointmentDetails(apt) {
        document.getElementById('pastModalLoading').style.display = 'none';
        document.getElementById('pastModalContent').style.display = 'block';
        
        const isBlocked = apt.status === 'blocked';
        
        const initials = isBlocked ? '🔒' : apt.client_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        document.getElementById('pastClientInitials').textContent = initials;
        document.getElementById('pastClientName').textContent = isBlocked ? 'Horario Bloqueado' : apt.client_name;
        document.getElementById('pastClientEmail').textContent = isBlocked ? 'Este horario estuvo bloqueado' : apt.client_email;
        document.getElementById('pastClientPhone').textContent = apt.client_phone || 'No disponible';
        document.getElementById('pastAppointmentDate').textContent = apt.date_formatted;
        document.getElementById('pastAppointmentTime').textContent = `${apt.start_time} - ${apt.end_time}`;
        document.getElementById('pastAppointmentService').textContent = apt.availability ? apt.availability.title : 'Cita';
        
        // Estado con colores
        const statusConfig = {
            'pending': { text: 'Pendiente', class: 'bg-warning text-dark' },
            'confirmed': { text: 'Confirmada', class: 'bg-success' },
            'cancelled': { text: 'Cancelada', class: 'bg-danger' },
            'blocked': { text: 'Bloqueado', class: 'bg-dark' }
        };
        const status = statusConfig[apt.status] || { text: apt.status, class: 'bg-secondary' };
        document.getElementById('pastAppointmentStatus').textContent = status.text;
        document.getElementById('pastAppointmentStatus').className = `badge ${status.class}`;
        
        // Notas
        if (apt.notes) {
            document.getElementById('pastNotesSection').style.display = 'block';
            document.getElementById('pastAppointmentNotes').textContent = apt.notes;
        } else {
            document.getElementById('pastNotesSection').style.display = 'none';
        }
    }
    
    function showAvailableSlotModal(slot, date) {
        currentSlotData = slot;
        currentSlotDate = date;
        
        // Formatear fecha
        const dateObj = new Date(date + 'T00:00:00');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = dateObj.toLocaleDateString('es-ES', options);
        
        document.getElementById('slotDate').textContent = formattedDate;
        document.getElementById('slotTime').textContent = `${slot.start_time} - ${slot.end_time}`;
        document.getElementById('slotService').textContent = slot.availability_title || 'Cita';
        document.getElementById('blockReasonInput').value = '';
        
        availableSlotModal.show();
    }
    
    // Botón para bloquear slot
    document.getElementById('blockSlotBtn').addEventListener('click', function() {
        const reason = document.getElementById('blockReasonInput').value;
        
        Swal.fire({
            title: '¿Bloquear este horario?',
            html: `El horario <strong>${currentSlotData.start_time} - ${currentSlotData.end_time}</strong> no estará disponible para reservas`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#343434',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, bloquear',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ route('dashboardAdmin.blockSlot') }}`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ 
                        date: currentSlotDate, 
                        start_time: currentSlotData.start_time, 
                        end_time: currentSlotData.end_time,
                        availability_id: currentSlotData.availability_id,
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: '¡Bloqueado!', 
                            text: data.message, 
                            confirmButtonColor: '#A08A7A', 
                            timer: 1500 
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Error', 
                            text: data.message, 
                            confirmButtonColor: '#A08A7A' 
                        });
                    }
                });
            }
        });
    });
    
    function loadAppointmentDetails(id) {
        document.getElementById('modalLoading').style.display = 'block';
        document.getElementById('modalContent').style.display = 'none';
        
        fetch(`{{ url('dashboardAdmin/appointment') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) { displayAppointmentDetails(data.appointment); }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los detalles', confirmButtonColor: '#A08A7A' });
            });
    }
    
    function displayAppointmentDetails(apt) {
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
        
        const isBlocked = apt.status === 'blocked';
        
        const initials = isBlocked ? '🔒' : apt.client_name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        document.getElementById('clientInitials').textContent = initials;
        document.getElementById('clientName').textContent = isBlocked ? 'Horario Bloqueado' : apt.client_name;
        document.getElementById('clientEmail').textContent = isBlocked ? (apt.notes || 'Sin motivo especificado') : apt.client_email;
        document.getElementById('clientPhone').textContent = apt.client_phone || 'No disponible';
        document.getElementById('clientPhoneContainer').style.display = isBlocked ? 'none' : 'flex';
        document.getElementById('appointmentDate').textContent = apt.date_formatted;
        document.getElementById('appointmentTime').textContent = `${apt.start_time} - ${apt.end_time}`;
        document.getElementById('appointmentService').textContent = apt.availability?.title || (isBlocked ? 'Bloqueado' : 'Cita');
        
        const statusBadge = document.getElementById('appointmentStatus');
        const statusColors = { pending: 'warning', confirmed: 'success', cancelled: 'danger', blocked: 'dark' };
        const statusLabels = { pending: 'Pendiente', confirmed: 'Confirmada', cancelled: 'Cancelada', blocked: 'Bloqueada' };
        statusBadge.className = `badge bg-${statusColors[apt.status] || 'secondary'}`;
        statusBadge.textContent = statusLabels[apt.status] || 'Desconocido';
        
        // Mostrar/ocultar secciones según si es bloqueada
        const statusSection = document.querySelector('.status-section');
        const moveSection = document.querySelector('.move-section');
        const deleteBtn = document.getElementById('deleteAppointmentBtn');
        const unblockBtn = document.getElementById('unblockAppointmentBtn');
        
        if (isBlocked) {
            if (statusSection) statusSection.style.display = 'none';
            if (moveSection) moveSection.style.display = 'none';
            deleteBtn.style.display = 'none';
            unblockBtn.style.display = 'inline-flex';
        } else {
            if (statusSection) statusSection.style.display = 'block';
            if (moveSection) moveSection.style.display = 'block';
            deleteBtn.style.display = 'inline-flex';
            unblockBtn.style.display = 'none';
            
            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.classList.remove('active-status', 'btn-warning', 'btn-success', 'btn-danger');
                btn.classList.add(`btn-outline-${statusColors[btn.dataset.status]}`);
                if (btn.dataset.status === apt.status) {
                    btn.classList.add('active-status');
                    btn.classList.remove(`btn-outline-${statusColors[btn.dataset.status]}`);
                    btn.classList.add(`btn-${statusColors[btn.dataset.status]}`);
                }
            });
        }
        
        // Cargar fechas disponibles
        loadAvailableDates();
        document.getElementById('newTimeSlot').innerHTML = '<option value="">{{ __("Selecciona una fecha primero") }}</option>';
        document.getElementById('newTimeSlot').disabled = true;
        document.getElementById('confirmMoveBtn').disabled = true;
    }
    
    // Cargar fechas disponibles desde el servidor
    function loadAvailableDates() {
        const select = document.getElementById('newDate');
        select.innerHTML = '<option value="">{{ __("Cargando fechas...") }}</option>';
        
        // Destruir Select2 antes de modificar opciones
        if ($(select).hasClass('select2-hidden-accessible')) {
            $(select).select2('destroy');
        }
        
        fetch(`{{ route('dashboardAdmin.getAvailableDates') }}`)
            .then(response => response.json())
            .then(data => {
                select.innerHTML = '';
                if (data.dates && data.dates.length > 0) {
                    select.innerHTML = '<option value="">{{ __("Selecciona una fecha") }}</option>';
                    data.dates.forEach(dateInfo => {
                        const option = document.createElement('option');
                        option.value = dateInfo.date;
                        // Mostrar fecha + títulos de eventos disponibles
                        const label = dateInfo.titles ? `${dateInfo.day_name} — ${dateInfo.titles}` : dateInfo.day_name;
                        option.textContent = label;
                        select.appendChild(option);
                    });
                } else {
                    select.innerHTML = '<option value="">{{ __("No hay fechas disponibles") }}</option>';
                }
                // Inicializar Select2 después de cargar opciones
                initSelect2Date();
            })
            .catch(error => {
                console.error('Error:', error);
                select.innerHTML = '<option value="">{{ __("Error al cargar fechas") }}</option>';
                initSelect2Date();
            });
    }
    
    // Inicializar Select2 en #newDate
    function initSelect2Date() {
        $('#newDate').select2({
            width: '100%',
            dropdownParent: $('#appointmentModal'),
            dropdownCssClass: 'select2-dropdown--calendar',
            placeholder: '{{ __("Selecciona una fecha") }}'
        });
    }
    
    // Inicializar Select2 en #newTimeSlot
    function initSelect2Time() {
        if ($('#newTimeSlot').hasClass('select2-hidden-accessible')) {
            $('#newTimeSlot').select2('destroy');
        }
        $('#newTimeSlot').select2({
            minimumResultsForSearch: Infinity,
            width: '100%',
            dropdownParent: $('#appointmentModal'),
            dropdownCssClass: 'select2-dropdown--calendar',
            placeholder: '{{ __("Selecciona un horario") }}'
        });
    }
    
    // Inicializar Select2 del time slot al cargar
    initSelect2Time();
    
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            updateAppointmentStatus(currentAppointmentId, this.dataset.status);
        });
    });
    
    function updateAppointmentStatus(id, status) {
        fetch(`{{ url('dashboardAdmin/appointment') }}/${id}/status`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '¡Actualizado!', text: data.message, confirmButtonColor: '#A08A7A', timer: 1500, timerProgressBar: true }).then(() => location.reload());
            }
        })
        .catch(error => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar', confirmButtonColor: '#A08A7A' });
        });
    }
    
    document.getElementById('newDate').addEventListener('change', function() {
        const date = this.value;
        if (!date) {
            document.getElementById('newTimeSlot').innerHTML = '<option value="">{{ __("Selecciona una fecha primero") }}</option>';
            document.getElementById('newTimeSlot').disabled = true;
            initSelect2Time();
            return;
        }
        const select = document.getElementById('newTimeSlot');
        select.innerHTML = '<option value="">{{ __("Cargando horarios...") }}</option>';
        select.disabled = true;
        
        // Destruir Select2 antes de modificar opciones
        if ($('#newTimeSlot').hasClass('select2-hidden-accessible')) {
            $('#newTimeSlot').select2('destroy');
        }
        
        fetch(`{{ route('dashboardAdmin.getAvailableSlots') }}?date=${date}`)
            .then(response => response.json())
            .then(data => {
                select.innerHTML = '';
                if (data.slots && data.slots.length > 0) {
                    select.innerHTML = '<option value="">{{ __("Selecciona un horario") }}</option>';
                    
                    // Agrupar slots por título del evento/servicio
                    const grouped = {};
                    data.slots.forEach(slot => {
                        const key = slot.title || 'Sin título';
                        if (!grouped[key]) grouped[key] = [];
                        grouped[key].push(slot);
                    });
                    
                    const groupKeys = Object.keys(grouped);
                    if (groupKeys.length > 1) {
                        // Múltiples eventos: usar optgroup para separar
                        groupKeys.forEach(title => {
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = title;
                            grouped[title].forEach(slot => {
                                const option = document.createElement('option');
                                option.value = `${slot.start_time}|${slot.end_time}`;
                                option.textContent = `${slot.start_time} - ${slot.end_time}`;
                                optgroup.appendChild(option);
                            });
                            select.appendChild(optgroup);
                        });
                    } else {
                        // Un solo evento: opciones simples
                        data.slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = `${slot.start_time}|${slot.end_time}`;
                            option.textContent = `${slot.start_time} - ${slot.end_time}`;
                            select.appendChild(option);
                        });
                    }
                    select.disabled = false;
                } else {
                    select.innerHTML = '<option value="">{{ __("No hay horarios disponibles en esta fecha") }}</option>';
                }
                initSelect2Time();
            })
            .catch(error => {
                console.error('Error:', error);
                select.innerHTML = '<option value="">{{ __("Error al cargar horarios") }}</option>';
                initSelect2Time();
            });
    });
    
    document.getElementById('newTimeSlot').addEventListener('change', function() {
        document.getElementById('confirmMoveBtn').disabled = !this.value;
    });
    
    // También escuchar el evento select2:select para el time slot
    $('#newTimeSlot').on('select2:select select2:unselect', function() {
        document.getElementById('confirmMoveBtn').disabled = !this.value;
    });
    $('#newDate').on('select2:select select2:unselect', function() {
        // Disparar el change nativo para que se carguen los horarios
        this.dispatchEvent(new Event('change'));
    });
    
    document.getElementById('confirmMoveBtn').addEventListener('click', function() {
        const dateSelect = document.getElementById('newDate');
        const date = dateSelect.value;
        const dateText = dateSelect.options[dateSelect.selectedIndex].text;
        const timeSlot = document.getElementById('newTimeSlot').value;
        if (!date || !timeSlot) return;
        const [startTime, endTime] = timeSlot.split('|');
        
        Swal.fire({
            title: '¿Reubicar cita?',
            html: `La cita se moverá a:<br><strong>${dateText}</strong><br>a las <strong>${startTime}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#A08A7A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, reubicar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('dashboardAdmin/appointment') }}/${currentAppointmentId}/move`, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ date: date, start_time: startTime, end_time: endTime })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: '¡Reubicada!', text: data.message, confirmButtonColor: '#A08A7A', timer: 1500 }).then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#A08A7A' });
                    }
                });
            }
        });
    });
    
    document.getElementById('deleteAppointmentBtn').addEventListener('click', function() {
        Swal.fire({
            title: '¿Eliminar cita?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('dashboardAdmin/appointment') }}/${currentAppointmentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: '¡Eliminada!', text: data.message, confirmButtonColor: '#A08A7A', timer: 1500 }).then(() => location.reload());
                    }
                });
            }
        });
    });
    
    // Desbloquear horario
    document.getElementById('unblockAppointmentBtn').addEventListener('click', function() {
        Swal.fire({
            title: '¿Desbloquear horario?',
            text: 'El horario volverá a estar disponible para reservas',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#A08A7A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, desbloquear',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('dashboardAdmin/appointments/unblock') }}/${currentAppointmentId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: '¡Desbloqueado!', text: data.message, confirmButtonColor: '#A08A7A', timer: 1500 }).then(() => location.reload());
                    }
                });
            }
        });
    });
    
    // Botón "Hoy" - navega a la fecha actual en la vista correspondiente
    const goToTodayBtn = document.getElementById('goToToday');
    if (goToTodayBtn) {
        goToTodayBtn.addEventListener('click', function() { 
            if (currentView === 'week') {
                // Ir a la semana actual
                const today = new Date('{{ $today }}');
                const dayOfWeek = today.getDay();
                const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
                today.setDate(diff);
                const weekStartStr = today.toISOString().split('T')[0];
                window.location.href = `{{ route('dashboardAdmin.index') }}?tab=appointments&view=week&week_start=${weekStartStr}`; 
            } else {
                window.location.href = `{{ route('dashboardAdmin.index') }}?tab=appointments`; 
            }
        });
    }
    
    // Filtro por estado - Múltiple selección
    let selectedFilters = []; // Array para múltiples filtros
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    const searchInput = document.getElementById('searchAppointments');
    const filterCountBadge = document.getElementById('filterCount');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    // Función para actualizar el contador de filtros
    function updateFilterCount() {
        const count = selectedFilters.length;
        if (count > 0) {
            filterCountBadge.textContent = count;
            filterCountBadge.style.display = 'inline-block';
        } else {
            filterCountBadge.style.display = 'none';
        }
    }
    
    // Evento para cada checkbox de filtro
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const status = this.getAttribute('data-status');
            
            if (this.checked) {
                // Añadir al array si no existe
                if (!selectedFilters.includes(status)) {
                    selectedFilters.push(status);
                }
            } else {
                // Eliminar del array
                selectedFilters = selectedFilters.filter(f => f !== status);
            }
            
            console.log('>>> Filtros seleccionados:', selectedFilters);
            updateFilterCount();
            applyFilters();
        });
    });
    
    // Botón para limpiar todos los filtros
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            selectedFilters = [];
            filterCheckboxes.forEach(cb => cb.checked = false);
            updateFilterCount();
            applyFilters();
        });
    }
    
    // Buscador de clientes
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            console.log('>>> Búsqueda keyup:', this.value);
            applyFilters();
        });
        
        searchInput.addEventListener('input', function(e) {
            console.log('>>> Búsqueda input:', this.value);
            applyFilters();
        });
    }
    
    function applyFilters() {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;
        let hiddenCount = 0;
        
        // Seleccionar TODOS los slots del calendario (incluyendo pasados)
        const allCards = document.querySelectorAll('#calendarContainer .slot-clickable, #calendarContainer .past-clickable');
        
        allCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            const cardText = card.textContent.toLowerCase();
            
            // Verificar si coincide con el filtro de estado
            // Si no hay filtros seleccionados (array vacío), mostrar todos
            // Si hay filtros, verificar si el status está en el array
            const matchesStatus = (selectedFilters.length === 0) || selectedFilters.includes(cardStatus);
            
            // Verificar si coincide con la búsqueda
            const matchesSearch = (searchValue === '') || cardText.includes(searchValue);
            
            // Mostrar u ocultar
            if (matchesStatus && matchesSearch) {
                card.style.cssText = 'display: flex !important;';
                card.removeAttribute('hidden');
                visibleCount++;
            } else {
                card.style.cssText = 'display: none !important;';
                hiddenCount++;
            }
        });
    }
    
    // Ejecutar filtro inicial después de un pequeño delay para asegurar que el DOM esté listo
    setTimeout(function() {
        console.log('=== Aplicando filtro inicial ===');
        applyFilters();
    }, 100);
});
</script>
@endpush