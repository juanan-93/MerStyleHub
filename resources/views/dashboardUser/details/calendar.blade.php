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
    <div class="card-header bg-white py-3 py-md-4 border-bottom d-flex flex-column gap-2 gap-md-3">
        <div class="d-flex flex-row justify-content-between align-items-center gap-2 gap-md-3">
            <div class="d-none d-md-block">
                <h4 class="mb-0 fw-bold text-primary-custom">
                    <i class="ti ti-calendar me-2"></i>{{ __('Mis Citas') }}
                </h4>
                <p class="text-muted small mb-0">{{ __('Visualiza tu calendario de citas') }}</p>
            </div>
            
            <!-- Título compacto móvil -->
            <div class="d-block d-md-none">
                <h5 class="mb-0 fw-bold text-primary-custom">
                    <i class="ti ti-calendar me-1"></i>{{ __('Mis Citas') }}
                </h5>
            </div>
            
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- Buscador rápido (oculto en móvil) -->
                <div class="input-group input-group-sm shadow-sm d-none d-md-flex" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="ti ti-search text-muted"></i>
                    </span>
                    <input type="text" id="searchAppointments" class="form-control border-start-0" placeholder="{{ __('Buscar cita...') }}">
                </div>

                <div class="dropdown" id="filterDropdown">
                    <button class="btn btn-outline-secondary btn-sm px-2 px-md-3 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="ti ti-filter me-md-1"></i><span class="d-none d-md-inline">{{ __('Filtros') }}</span> <span id="filterCount" class="badge bg-primary-custom ms-1" style="display: none;">0</span>
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
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" id="clearFilters"><i class="ti ti-x me-1"></i>{{ __('Limpiar filtros') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Toolbar de Navegación y Leyenda -->
        <div class="d-flex flex-row justify-content-between align-items-center gap-2 gap-md-3 pt-1 pt-md-2">
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <!-- Toggle Vista Mensual/Semanal -->
                <div class="btn-group shadow-sm" role="group" aria-label="Vista del calendario">
                    <button type="button" class="btn btn-sm {{ $currentView === 'month' ? 'btn-primary-custom active' : 'btn-outline-primary-custom' }}" id="viewMonthBtn" data-view="month">
                        <i class="ti ti-calendar-month me-md-1"></i><span class="d-none d-md-inline">{{ __('Mes') }}</span>
                    </button>
                    <button type="button" class="btn btn-sm {{ $currentView === 'week' ? 'btn-primary-custom active' : 'btn-outline-primary-custom' }}" id="viewWeekBtn" data-view="week">
                        <i class="ti ti-calendar-week me-md-1"></i><span class="d-none d-md-inline">{{ __('Semana') }}</span>
                    </button>
                </div>
                
                <!-- Navegación -->
                <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                    <button class="btn btn-icon btn-sm rounded-circle hover-bg-white border-0" id="prevMonth">
                        <i class="ti ti-chevron-left text-secondary"></i>
                    </button>
                    <span class="px-2 px-md-3 fw-bold text-secondary text-capitalize" id="currentMonthLabel" style="min-width: 100px; text-align: center; font-size: 0.8rem;">
                        @if($currentView === 'week')
                            {{ $weekStart->locale('es')->isoFormat('D MMM') }} - {{ $weekEnd->locale('es')->isoFormat('D MMM YY') }}
                        @else
                            {{ $calendarData['monthName'] }}
                        @endif
                    </span>
                    <button class="btn btn-icon btn-sm rounded-circle hover-bg-white border-0" id="nextMonth">
                        <i class="ti ti-chevron-right text-secondary"></i>
                    </button>
                </div>
            </div>

            <!-- Leyenda de Estados (Desktop) -->
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
            </div>
        </div>
        
        <!-- Leyenda compacta para móviles -->
        <div class="d-flex d-lg-none justify-content-center gap-3 flex-wrap pb-1">
            <div class="d-flex align-items-center gap-1">
                <span class="dot bg-info"></span>
                <span style="font-size: 0.6rem;" class="text-muted">Disp.</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span class="dot bg-warning"></span>
                <span style="font-size: 0.6rem;" class="text-muted">Pend.</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span class="dot bg-success"></span>
                <span style="font-size: 0.6rem;" class="text-muted">Conf.</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span class="dot bg-danger"></span>
                <span style="font-size: 0.6rem;" class="text-muted">Canc.</span>
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
                                            'occupied' => 'secondary', // Citas de otros usuarios
                                            default => 'secondary'
                                        };
                                        
                                        // Texto a mostrar según el estado
                                        $displayText = match($slot['status']) {
                                            'available' => 'Disponible',
                                            'occupied' => 'No disponible', // Cita de otro usuario
                                            default => ($slot['is_user_appointment'] && $slot['client_name']) ? Str::limit($slot['client_name'], 10) : 'Mi Cita'
                                        };
                                        
                                        // No usar clase especial, todas son disponibles para el usuario
                                        $assignedClass = '';
                                        
                                        // Solo clickeable si es del usuario o está disponible (NO si está ocupado)
                                        $isClickable = ($slot['is_user_appointment'] || $slot['status'] === 'available') && $slot['status'] !== 'occupied';
                                    @endphp
                                    @if($slot['is_user_appointment'] || $slot['status'] === 'available' || $slot['status'] === 'occupied')
                                        <div class="appointment-card {{ $statusClass }} {{ $assignedClass }} {{ $isClickable ? 'slot-clickable' : '' }} small d-flex align-items-center justify-content-between px-2" 
                                             @if($slot['status'] !== 'occupied')
                                             data-slot='@json($slot)'
                                             data-date="{{ $cellDate }}"
                                             data-status="{{ $slot['status'] }}"
                                             data-is-past="false"
                                             data-is-assigned="{{ $slot['is_assigned_to_user'] ? 'true' : 'false' }}"
                                             @endif
                                             title="{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $displayText }}">
                                            <div class="d-flex align-items-center gap-1 text-truncate">
                                                <span class="dot bg-{{ $statusColor }}"></span>
                                                <strong class="time">{{ $slot['start_time'] }}</strong>
                                                <span class="client text-truncate">{{ $displayText }}</span>
                                            </div>
                                        </div>
                                    @endif
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
                                                    'occupied' => 'secondary', // Citas de otros usuarios
                                                    default => 'secondary'
                                                };
                                                $displayText = match($slot['status']) {
                                                    'available' => 'Disponible',
                                                    'occupied' => 'No disponible', // Cita de otro usuario
                                                    default => ($slot['is_user_appointment'] && $slot['client_name']) ? Str::limit($slot['client_name'], 8) : 'Mi Cita'
                                                };
                                                
                                                $assignedClass = '';
                                                
                                                $isClickable = ($slot['is_user_appointment'] || $slot['status'] === 'available') && $slot['status'] !== 'occupied';
                                            @endphp
                                            @if($slot['is_user_appointment'] || $slot['status'] === 'available' || $slot['status'] === 'occupied')
                                                <div class="appointment-card week-appointment {{ $statusClass }} {{ $assignedClass }} {{ $isClickable ? 'slot-clickable' : '' }} small" 
                                                     @if($slot['status'] !== 'occupied')
                                                     data-slot='@json($slot)'
                                                     data-date="{{ $cellDate }}"
                                                     data-status="{{ $slot['status'] }}"
                                                     data-is-past="false"
                                                     data-is-assigned="{{ $slot['is_assigned_to_user'] ? 'true' : 'false' }}"
                                                     @endif
                                                     title="{{ $slot['start_time'] }} - {{ $slot['end_time'] }}: {{ $displayText }}">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="dot bg-{{ $statusColor }}"></span>
                                                        <strong class="time" style="font-size: 0.65rem;">{{ substr($slot['start_time'], 0, 5) }}</strong>
                                                        <span class="client text-truncate" style="font-size: 0.6rem;">{{ $displayText }}</span>
                                                    </div>
                                                </div>
                                            @endif
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

<!-- Modal de Detalle de Cita del Usuario -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="appointmentModalLabel">
                    <i class="ti ti-calendar-event me-2 text-primary-custom"></i><span id="modalTitle">Detalle de Cita</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div id="appointmentDetails">
                    <!-- Cliente -->
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-1">Cliente</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle bg-primary-custom text-white" id="modalAvatar">
                                <i class="ti ti-user"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold" id="modalClientName">-</p>
                                <small class="text-muted" id="modalClientContact">-</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fecha y Hora -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Fecha</label>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-calendar text-primary-custom"></i>
                                <span class="fw-medium" id="modalDate">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Horario</label>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-clock text-primary-custom"></i>
                                <span class="fw-medium" id="modalTime">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Servicio y Estado -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Servicio</label>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-tag text-primary-custom"></i>
                                <span class="fw-medium" id="modalService">-</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Estado</label>
                            <div id="modalStatus">
                                <span class="badge">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notas -->
                    <div class="mb-4" id="notesSection" style="display: none;">
                        <label class="form-label text-muted small mb-1">Notas</label>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0 small" id="modalNotes">-</p>
                        </div>
                    </div>
                    
                    <!-- Botones de acción para cita reservada -->
                    <div id="appointmentActions" style="display: none;">
                        <button type="button" id="cancelAppointmentBtn" class="btn btn-danger w-100">
                            <i class="ti ti-trash me-1"></i>Cancelar Cita
                        </button>
                    </div>
                </div>
                
                <!-- Info para slot disponible -->
                <div id="availableSlotInfo" style="display: none;">
                    <div class="text-center py-4">
                        <i class="ti ti-calendar-plus" style="font-size: 3rem; color: var(--color-primary);"></i>
                        <h6 class="mt-3 mb-2">Horario Disponible</h6>
                        <p class="text-muted mb-2"><span id="availableDate">-</span> a las <span id="availableTime">-</span></p>
                        
                        <div id="existingAppointmentWarning" class="alert alert-warning mb-3" style="display: none;">
                            <small class="text-dark d-block mb-2">
                                <i class="ti ti-alert-circle me-1"></i>
                                Tienes una cita reservada el <strong><span id="existingDate">-</span></strong>. 
                                Debes cancelarla antes de reservar otra.
                            </small>
                            <div class="text-center">
                                <a href="#" id="cancelExistingBtn" class="btn btn-sm btn-warning">Cancelar cita existente</a>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" id="bookBtn" class="btn btn-primary-custom px-4">
                                <i class="ti ti-calendar-plus me-1"></i>Reservar Cita
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --color-primary-cal: #A08A7A;
        --color-border-cal: #e5e7eb;
        --color-light-cal: #fef8f4;
        --color-white: #ffffff;
    }

    .calendar-grid { table-layout: fixed; border-collapse: collapse; }
    .calendar-cell { border: 1px solid var(--color-border-cal) !important; transition: all 0.2s ease; }
    .calendar-cell:hover:not(.other-month) { background-color: #fffaf7; box-shadow: inset 0 0 10px rgba(160, 138, 122, 0.05); }
    .calendar-cell.other-month { background-color: #fafafa; }
    .day-number { font-size: 0.85rem; display: inline-flex; align-items-center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; }
    .today-badge { background-color: var(--color-primary-cal); color: white !important; box-shadow: 0 2px 4px rgba(160, 138, 122, 0.3); }
    
    .appointment-card { padding: 4px 8px; border-radius: 5px; font-size: 0.7rem; cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; white-space: nowrap; background-color: var(--color-white); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .appointment-card:hover { transform: translateY(-1px); box-shadow: 0 3px 6px rgba(0,0,0,0.1); z-index: 5; }
    .appointment-card.available { border-left: 3px solid #17a2b8; background-color: #e3f6f8; color: #0c5460; }
    .appointment-card.available:hover { background-color: #d1ecf1; }
    
    .appointment-card.confirmed { border-left: 3px solid #28a745; background-color: #f0fff4; color: #155724; }
    .appointment-card.pending { border-left: 3px solid #ffc107; background-color: #fffbeb; color: #856404; }
    .appointment-card.cancelled { border-left: 3px solid #dc3545; background-color: #fff5f5; color: #721c24; opacity: 0.7; text-decoration: line-through; }
    
    /* Citas ocupadas por otros usuarios - NO clickeables */
    .appointment-card.occupied { 
        border-left: 3px solid #6c757d; 
        background-color: #f8f9fa; 
        color: #6c757d; 
        cursor: not-allowed; 
        opacity: 0.7;
    }
    .appointment-card.occupied:hover { 
        transform: none; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        background-color: #f8f9fa;
    }
    
    /* Citas de fechas pasadas */
    .appointment-card.past-date {
        opacity: 0.6;
        text-decoration: line-through;
        cursor: default;
    }
    .appointment-card.past-date.past-clickable {
        cursor: pointer;
    }
    .appointment-card.past-date:hover {
        opacity: 0.8;
        transform: none;
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
    
    /* ===== ESTILOS VISTA SEMANAL ===== */
    .weekly-grid { table-layout: fixed; }
    .weekly-grid th { min-width: 100px; }
    .weekly-grid th:first-child, .hour-column { min-width: 70px; width: 70px; white-space: nowrap; }
    
    .week-cell {
        border: 1px solid var(--color-border-cal) !important;
        transition: background-color 0.2s;
    }
    .week-cell:hover {
        background-color: #fffaf7;
    }
    .week-cell.today-cell {
        background-color: rgba(160, 138, 122, 0.05);
    }
    .week-cell.past-cell {
        background-color: #f9fafb;
    }
    
    .week-appointment {
        font-size: 0.65rem;
        padding: 2px 4px;
    }
    
    .week-appointments {
        min-height: 45px;
        max-height: 45px;
        overflow-y: auto;
    }
    
    .week-row td:first-child {
        background-color: #f9fafb;
        font-weight: 600;
    }
    
    /* ===== ESTILOS RESPONSIVE MÓVIL - CALENDARIO ===== */
    @media (max-width: 768px) {
        /* ---- Quitar padding extra del card del calendario ---- */
        #calendarContainer {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        #calendarContainer > .card-header {
            padding: 0.5rem 0.4rem !important;
            border-radius: 0 !important;
        }
        
        #calendarContainer > .card-header h4,
        #calendarContainer > .card-header h5 {
            font-size: 0.95rem;
        }
        
        /* Toggle mes/semana - solo iconos */
        #viewMonthBtn span, #viewWeekBtn span {
            display: none;
        }
        
        #viewMonthBtn i, #viewWeekBtn i {
            margin: 0 !important;
        }
        
        .btn-group.shadow-sm .btn {
            padding: 0.3rem 0.5rem !important;
            font-size: 0.8rem;
        }
        
        /* Navegación del mes */
        #currentMonthLabel {
            min-width: 95px !important;
            font-size: 0.72rem !important;
            padding-left: 0.3rem !important;
            padding-right: 0.3rem !important;
        }
        
        /* ======= VISTA MENSUAL MÓVIL ======= */
        /* CLAVE: tabla con ancho mínimo para scroll horizontal */
        #monthView {
            -webkit-overflow-scrolling: touch;
        }
        
        #monthView .calendar-grid {
            min-width: 520px;
        }
        
        .calendar-grid th {
            padding: 0.3rem 0.15rem !important;
        }
        
        .calendar-grid th span {
            font-size: 0.6rem !important;
        }
        
        .calendar-cell {
            height: 90px !important;
            padding: 3px !important;
            vertical-align: top !important;
        }
        
        .calendar-cell .day-number {
            font-size: 0.7rem !important;
            width: 20px !important;
            height: 20px !important;
        }
        
        .calendar-cell .badge {
            font-size: 0.5rem !important;
            padding: 1px 3px !important;
        }
        
        .calendar-cell > div:first-child {
            margin-bottom: 2px !important;
        }
        
        /* Lista de citas scrolleable con suficiente espacio */
        .appointment-list {
            max-height: 60px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch;
            gap: 2px !important;
        }
        
        /* Tarjetas de citas legibles */
        .appointment-card {
            padding: 3px 5px !important;
            font-size: 0.6rem !important;
            min-height: 18px !important;
            border-radius: 3px !important;
            white-space: nowrap !important;
        }
        
        .appointment-card .time {
            font-size: 0.6rem !important;
            font-weight: 700 !important;
        }
        
        .appointment-card .client {
            font-size: 0.55rem !important;
        }
        
        .appointment-card .dot {
            width: 5px !important;
            height: 5px !important;
            flex-shrink: 0 !important;
        }
        
        /* ======= VISTA SEMANAL MÓVIL ======= */
        #weekView {
            -webkit-overflow-scrolling: touch;
        }
        
        #weekView .weekly-grid {
            min-width: 560px;
        }
        
        .weekly-grid th {
            min-width: 58px !important;
            padding: 0.2rem 0.1rem !important;
        }
        
        .weekly-grid th:first-child {
            min-width: 48px !important;
            width: 48px !important;
        }
        
        .weekly-grid th span.day-number {
            font-size: 0.65rem !important;
        }
        
        .weekly-grid th span:first-child {
            font-size: 0.55rem !important;
        }
        
        .week-cell {
            height: 48px !important;
            padding: 2px !important;
        }
        
        .week-row td:first-child small {
            font-size: 0.55rem !important;
        }
        
        .week-appointment {
            padding: 2px 4px !important;
            font-size: 0.55rem !important;
            min-height: 18px !important;
        }
        
        .week-appointment .time {
            font-size: 0.55rem !important;
        }
        
        .week-appointment .client {
            display: none !important;
        }
        
        .week-appointments {
            min-height: 42px !important;
            max-height: 42px !important;
            overflow-y: auto !important;
        }
        
        /* Filtros compactos */
        #filterDropdown .btn {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.7rem !important;
        }
    }
    
    @media (max-width: 576px) {
        #monthView .calendar-grid {
            min-width: 480px;
        }
        
        #weekView .weekly-grid {
            min-width: 520px;
        }
        
        .calendar-cell {
            height: 85px !important;
        }
        
        .appointment-list {
            max-height: 55px !important;
        }
        
        .appointment-card {
            padding: 2px 4px !important;
            font-size: 0.55rem !important;
            min-height: 16px !important;
        }
        
        .appointment-card .client {
            display: none !important;
        }
        
        #currentMonthLabel {
            min-width: 85px !important;
            font-size: 0.68rem !important;
        }
    }
    
    @media (max-width: 375px) {
        #monthView .calendar-grid {
            min-width: 460px;
        }
        
        .calendar-cell {
            height: 80px !important;
        }
        
        .calendar-cell .day-number {
            font-size: 0.6rem !important;
            width: 17px !important;
            height: 17px !important;
        }
        
        .appointment-list {
            max-height: 50px !important;
        }
        
        .appointment-card {
            padding: 2px 3px !important;
            font-size: 0.5rem !important;
            min-height: 15px !important;
        }
        
        .appointment-card .dot {
            width: 4px !important;
            height: 4px !important;
        }
    }
    
    /* Modal responsive */
    @media (max-width: 768px) {
        #appointmentModal .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
        
        #appointmentModal .modal-content {
            border-radius: 12px;
        }
        
        #appointmentModal .modal-header {
            padding: 0.75rem 1rem;
        }
        
        #appointmentModal .modal-body {
            padding: 1rem;
        }
        
        #appointmentModal .avatar-circle {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }
        
        #appointmentModal .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        #bookBtn {
            width: 100%;
            padding: 0.75rem !important;
            font-size: 1rem !important;
        }
        
        #cancelAppointmentBtn {
            width: 100%;
            padding: 0.75rem !important;
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const appointmentModalEl = document.getElementById('appointmentModal');
    const appointmentModal = new bootstrap.Modal(appointmentModalEl);

    // Evitar warning aria-hidden: quitar foco del interior del modal antes de ocultarlo
    appointmentModalEl.addEventListener('hide.bs.modal', function() {
        if (this.contains(document.activeElement)) {
            document.activeElement.blur();
        }
    });

    let currentMonth = {{ $currentMonth->month }};
    let currentYear = {{ $currentMonth->year }};
    
    // Variables para almacenar el slot actual y el ID de la cita
    let currentSlot = null;
    let currentSlotDate = null;
    let currentAppointmentId = null;
    
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
            monthView.style.display = 'block';
            weekView.style.display = 'none';
            viewMonthBtn.classList.add('btn-primary-custom', 'active');
            viewMonthBtn.classList.remove('btn-outline-primary-custom');
            viewWeekBtn.classList.remove('btn-primary-custom', 'active');
            viewWeekBtn.classList.add('btn-outline-primary-custom');
        } else {
            monthView.style.display = 'none';
            weekView.style.display = 'block';
            viewWeekBtn.classList.add('btn-primary-custom', 'active');
            viewWeekBtn.classList.remove('btn-outline-primary-custom');
            viewMonthBtn.classList.remove('btn-primary-custom', 'active');
            viewMonthBtn.classList.add('btn-outline-primary-custom');
        }
    }
    
    // Event listeners para los botones de vista
    viewMonthBtn.addEventListener('click', function() {
        window.location.href = `{{ route('dashboardUser.index') }}?month=${currentMonth}&year=${currentYear}&view=month`;
    });
    
    viewWeekBtn.addEventListener('click', function() {
        const weekStart = currentWeekStart.toISOString().split('T')[0];
        window.location.href = `{{ route('dashboardUser.index') }}?week_start=${weekStart}&view=week`;
    });
    
    // Navegación con prevMonth y nextMonth
    prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentView === 'month') {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            window.location.href = `{{ route('dashboardUser.index') }}?month=${currentMonth}&year=${currentYear}&view=month`;
        } else {
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
            const weekStart = currentWeekStart.toISOString().split('T')[0];
            window.location.href = `{{ route('dashboardUser.index') }}?week_start=${weekStart}&view=week`;
        }
    });
    
    nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentView === 'month') {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            window.location.href = `{{ route('dashboardUser.index') }}?month=${currentMonth}&year=${currentYear}&view=month`;
        } else {
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            const weekStart = currentWeekStart.toISOString().split('T')[0];
            window.location.href = `{{ route('dashboardUser.index') }}?week_start=${weekStart}&view=week`;
        }
    });
    
    // Click en slots activos (citas del usuario o disponibles)
    document.querySelectorAll('.slot-clickable').forEach(card => {
        card.addEventListener('click', function() {
            const slot = JSON.parse(this.dataset.slot);
            const date = this.dataset.date;
            
            if (slot.status === 'available') {
                showAvailableSlotModal(slot, date);
            } else if (slot.is_user_appointment && slot.appointment_id) {
                loadAppointmentDetails(slot.appointment_id);
            }
        });
    });
    
    // Click en slots de fechas pasadas
    document.querySelectorAll('.past-clickable').forEach(card => {
        card.addEventListener('click', function() {
            const slot = JSON.parse(this.dataset.slot);
            if (slot.is_user_appointment && slot.appointment_id) {
                loadAppointmentDetails(slot.appointment_id);
            }
        });
    });
    
    function showAvailableSlotModal(slot, date) {
        // Guardar datos del slot para la reserva
        currentSlot = slot;
        currentSlotDate = date;
        
        document.getElementById('appointmentDetails').style.display = 'none';
        document.getElementById('availableSlotInfo').style.display = 'block';
        document.getElementById('modalTitle').textContent = 'Horario Disponible';
        document.getElementById('existingAppointmentWarning').style.display = 'none';
        document.getElementById('bookBtn').style.display = 'block';
        document.getElementById('bookBtn').disabled = false;
        
        const dateObj = new Date(date + 'T00:00:00');
        const formattedDate = dateObj.toLocaleDateString('es-ES', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        document.getElementById('availableDate').textContent = formattedDate;
        document.getElementById('availableTime').textContent = `${slot.start_time} - ${slot.end_time}`;
        
        appointmentModal.show();
    }
    
    function loadAppointmentDetails(id) {
        fetch(`/dashboardUser/appointment/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAppointmentDetails(data.appointment);
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    function displayAppointmentDetails(apt) {
        document.getElementById('availableSlotInfo').style.display = 'none';
        document.getElementById('appointmentDetails').style.display = 'block';
        document.getElementById('modalTitle').textContent = 'Detalle de Mi Cita';
        
        // Avatar
        const initial = apt.client_name ? apt.client_name.charAt(0).toUpperCase() : 'U';
        document.getElementById('modalAvatar').textContent = initial;
        
        // Cliente
        document.getElementById('modalClientName').textContent = apt.client_name || '-';
        document.getElementById('modalClientContact').textContent = 
            `${apt.client_email || ''}${apt.client_phone ? ' • ' + apt.client_phone : ''}`;
        
        // Fecha y Hora
        document.getElementById('modalDate').textContent = apt.date_formatted || '-';
        document.getElementById('modalTime').textContent = `${apt.start_time} - ${apt.end_time}`;
        
        // Servicio
        document.getElementById('modalService').textContent = apt.availability?.title || '-';
        
        // Estado
        const statusBadge = {
            'pending': '<span class="badge bg-warning">Pendiente</span>',
            'confirmed': '<span class="badge bg-success">Confirmada</span>',
            'cancelled': '<span class="badge bg-danger">Cancelada</span>',
        };
        document.getElementById('modalStatus').innerHTML = statusBadge[apt.status] || '<span class="badge bg-secondary">-</span>';
        
        // Notas
        if (apt.notes) {
            document.getElementById('modalNotes').textContent = apt.notes;
            document.getElementById('notesSection').style.display = 'block';
        } else {
            document.getElementById('notesSection').style.display = 'none';
        }
        
        // Mostrar botones de acción si la cita está confirmada o pendiente
        currentAppointmentId = apt.id;
        if (['confirmed', 'pending'].includes(apt.status)) {
            document.getElementById('appointmentActions').style.display = 'block';
        } else {
            document.getElementById('appointmentActions').style.display = 'none';
        }
        
        appointmentModal.show();
    }
    
    // Evento para reservar cita
    document.getElementById('bookBtn').addEventListener('click', function() {
        if (!currentSlot || !currentSlotDate) return;
        
        this.disabled = true;
        const btnHtml = this.innerHTML;
        this.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Reservando...';
        
        fetch('{{ route("dashboardUser.book") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                availability_id: currentSlot.availability_id,
                date: currentSlotDate,
                start_time: currentSlot.start_time
            })
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            this.innerHTML = btnHtml;
            
            if (data.success) {
                // Mostrar éxito
                appointmentModal.hide();
                
                // Mostrar notificación
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                alertDiv.style.top = '20px';
                alertDiv.style.right = '20px';
                alertDiv.style.zIndex = '9999';
                alertDiv.innerHTML = `
                    <i class="ti ti-check me-2"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(alertDiv);
                
                // Recargar el calendario después de 2 segundos
                setTimeout(() => location.reload(), 2000);
            } else {
                // Mostrar error o advertencia de cita existente
                if (data.has_existing) {
                    // Mostrar advertencia de cita existente
                    document.getElementById('existingAppointmentWarning').style.display = 'block';
                    document.getElementById('existingDate').textContent = data.existing_appointment.date + ' a las ' + data.existing_appointment.time;
                    document.getElementById('bookBtn').style.display = 'none';
                    
                    // Preparar botón de cancelación
                    document.getElementById('cancelExistingBtn').dataset.appointmentId = data.existing_appointment.id;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#A08A7A'
                    });
                }
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al reservar la cita',
                confirmButtonColor: '#A08A7A'
            });
            this.innerHTML = btnHtml;
            this.disabled = false;
        });
    });
    
    // Evento para cancelar cita existente
    document.getElementById('cancelExistingBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        const appointmentId = this.dataset.appointmentId;
        if (!appointmentId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se encontró el ID de la cita',
                confirmButtonColor: '#A08A7A'
            });
            return;
        }
        
        Swal.fire({
            title: '¿Cancelar esta cita?',
            text: 'Esta acción te permitirá reservar otra cita',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#A08A7A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/dashboardUser/appointment/${appointmentId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar éxito
                        document.getElementById('existingAppointmentWarning').style.display = 'none';
                        document.getElementById('bookBtn').style.display = 'block';
                        document.getElementById('bookBtn').disabled = false;
                        
                        Swal.fire({
                            icon: 'success',
                            title: '¡Cita cancelada!',
                            text: 'Ahora puedes reservar otra cita',
                            confirmButtonColor: '#A08A7A'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#A08A7A'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cancelar la cita',
                        confirmButtonColor: '#A08A7A'
                    });
                });
            }
        });
    });
    
    // Evento para cancelar cita desde el detalle
    document.getElementById('cancelAppointmentBtn').addEventListener('click', function() {
        if (!currentAppointmentId) return;
        
        const btnElement = this;
        
        Swal.fire({
            title: '¿Cancelar esta cita?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#A08A7A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                btnElement.disabled = true;
                const btnHtml = btnElement.innerHTML;
                btnElement.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Cancelando...';
                
                fetch(`/dashboardUser/appointment/${currentAppointmentId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    btnElement.innerHTML = btnHtml;
                    
                    if (data.success) {
                        appointmentModal.hide();
                        
                        Swal.fire({
                            icon: 'success',
                            title: '¡Cita cancelada!',
                            text: data.message,
                            confirmButtonColor: '#A08A7A',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#A08A7A'
                        });
                        btnElement.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cancelar la cita',
                        confirmButtonColor: '#A08A7A'
                    });
                    btnElement.innerHTML = btnHtml;
                    btnElement.disabled = false;
                });
            }
        });
    });
    
    // ===== FILTROS Y BUSCADOR (combinados) =====
    let selectedFilters = [];
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    const searchInput = document.getElementById('searchAppointments');
    const filterCountBadge = document.getElementById('filterCount');
    const clearFiltersBtn = document.getElementById('clearFilters');

    function updateFilterCount() {
        const count = selectedFilters.length;
        if (count > 0) {
            filterCountBadge.textContent = count;
            filterCountBadge.style.display = 'inline-block';
        } else {
            filterCountBadge.style.display = 'none';
        }
    }

    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const status = this.getAttribute('data-status');
            if (this.checked) {
                if (!selectedFilters.includes(status)) {
                    selectedFilters.push(status);
                }
            } else {
                selectedFilters = selectedFilters.filter(f => f !== status);
            }
            updateFilterCount();
            applyFilters();
        });
    });

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            selectedFilters = [];
            filterCheckboxes.forEach(cb => cb.checked = false);
            updateFilterCount();
            applyFilters();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            applyFilters();
        });
    }

    function applyFilters() {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';

        // Seleccionar solo tarjetas con data-status (excluye occupied sin atributos)
        const allCards = document.querySelectorAll('#calendarContainer .slot-clickable, #calendarContainer .past-clickable');

        allCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            const cardText = card.textContent.toLowerCase();

            // Filtro de estado: si no hay filtros, mostrar todo; si hay, verificar inclusión
            const matchesStatus = (selectedFilters.length === 0) || selectedFilters.includes(cardStatus);

            // Filtro de búsqueda
            const matchesSearch = (searchValue === '') || cardText.includes(searchValue);

            if (matchesStatus && matchesSearch) {
                card.style.cssText = 'display: flex !important;';
            } else {
                card.style.cssText = 'display: none !important;';
            }
        });
    }

    // Aplicar filtro inicial
    setTimeout(function() {
        applyFilters();
    }, 100);
});
</script>
@endpush