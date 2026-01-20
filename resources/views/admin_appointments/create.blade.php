@extends('layouts.app', ['title' => __('Create Appointment')])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-calendar me-1"></i>
        {{ __('Create Appointment') }}
    </li>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-primary-custom">
                            <i class="ti ti-calendar-plus me-2"></i>{{ __('Configurar Disponibilidad') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="availabilityForm" action="{{ route('admin_appointments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="selection_type" id="selectionType" value="range">
                            <input type="hidden" name="schedule_data" id="scheduleData" value="[]">

                            <div class="row g-4">
                                <!-- Left Column: Title & Settings -->
                                <div class="col-md-5">
                                    <div class="params-section p-3 h-100">
                                        <h6 class="fw-bold mb-3 text-secondary">{{ __('Información del Evento') }}</h6>
                                        
                                        <!-- Título del evento -->
                                        <div class="mb-3">
                                            <label for="eventTitle" class="form-label-custom small">{{ __('Título del Evento') }}</label>
                                            <input type="text" class="form-control border-0 shadow-sm" 
                                                   name="title" id="eventTitle" 
                                                   placeholder="{{ __('Ej: Consulta inicial, Reunión de seguimiento...') }}"
                                                   required>
                                        </div>

                                        <!-- Duración de la reunión -->
                                        <div class="mb-3">
                                            <label for="meetingDuration" class="form-label-custom small">{{ __('Duración (minutos)') }}</label>
                                            <select class="form-select border-0 shadow-sm" name="duration" id="meetingDuration">
                                                <option value="10">10 min</option>
                                                <option value="15">15 min</option>
                                                <option value="20">20 min</option>
                                                <option value="30" selected>30 min</option>
                                                <option value="45">45 min</option>
                                                <option value="60">60 min</option>
                                            </select>
                                        </div>

                                        <!-- Tipo de reunión -->
                                        <div class="mb-3">
                                            <label for="meetingType" class="form-label-custom small">{{ __('Categoría') }}</label>
                                            <select class="form-select border-0 shadow-sm" name="category" id="meetingType">
                                                <option value="standard">{{ __('Reunión Estándar') }}</option>
                                                <option value="custom">{{ __('Reunión Personalizada') }}</option>
                                            </select>
                                        </div>

                                        <!-- Botones: modo de selección -->
                                        <div class="mt-3">
                                            <label class="form-label-custom small mb-2">{{ __('Modo de Selección de Días') }}</label>
                                            <div class="d-flex gap-2">
                                                <!-- Modo Rango desactivado -->
                                                <!-- <button type="button" id="selectRangeBtn" class="btn btn-primary btn-sm flex-grow-1">
                                                    <i class="ti ti-calendar-range me-1"></i>{{ __('Rango') }}
                                                </button> -->
                                                <button type="button" id="selectCustomBtn" class="btn btn-secondary btn-sm flex-grow-1">
                                                    <i class="ti ti-hand-click me-1"></i>{{ __('Individual') }}
                                                </button>
                                                <!-- Modo Laborables desactivado -->
                                                <!-- <button type="button" id="selectWeekdaysBtn" class="btn btn-outline-success btn-sm flex-grow-1">
                                                    <i class="ti ti-briefcase me-1"></i>{{ __('Laborables') }}
                                                </button> -->
                                            </div>
                                        </div>

                                        <!-- Días seleccionados preview -->
                                        <div class="mt-3" id="selectedDaysPreview" style="display: none;">
                                            <label class="form-label-custom small">{{ __('Días Seleccionados') }}</label>
                                            <div class="selected-days-badges d-flex flex-wrap gap-1" id="selectedDaysBadges">
                                                <!-- Badges dinámicos -->
                                            </div>
                                        </div>

                                        <!-- Botón para configurar horarios -->
                                        <div class="mt-4">
                                            <button type="button" id="openScheduleModal" class="btn btn-primary-custom w-100" disabled>
                                                <i class="ti ti-clock me-2"></i>{{ __('Configurar Horarios') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Calendar -->
                                <div class="col-md-7">
                                    <div class="calendar-section h-100">
                                        <label class="form-label fw-bold mb-3 d-flex justify-content-between align-items-center text-secondary">
                                            {{ __('Selecciona los Días') }}
                                            <span class="badge badge-primary-custom" id="selectionModeBadge" style="font-size: 0.7rem;">
                                                {{ __('Modo Rango') }}
                                            </span>
                                        </label>
                                        <div class="calendar-wrapper">
                                            <input type="text" id="datepicker" class="form-control d-none" placeholder="Select dates">
                                        </div>
                                        <div class="mt-3 d-flex gap-3 justify-content-center small text-muted">
                                            <div class="d-flex align-items-center">
                                                <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--color-primary);"></span>
                                                {{ __('Seleccionado') }}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--color-border);"></span>
                                                {{ __('Disponible') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                                <a href="{{ route('admin_appointments.index') }}" class="btn btn-outline-secondary px-3">{{ __('Cancelar') }}</a>
                                <button type="submit" id="submitBtn" class="btn btn-primary-custom px-4 shadow-sm" disabled>
                                    <i class="ti ti-check me-1"></i> {{ __('Guardar Configuración') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para configurar horarios -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom" style="background: var(--color-light);">
                    <h5 class="modal-title fw-bold" id="scheduleModalLabel">
                        <i class="ti ti-clock me-2 text-primary-custom"></i>{{ __('Configurar Horarios') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Horario general -->
                    <div class="general-schedule-section mb-4 p-3 rounded-3" style="background: var(--color-light); border: 1px solid var(--color-border);">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-bold text-secondary">
                                <i class="ti ti-clock-hour-4 me-2"></i>{{ __('Horario General') }}
                            </h6>
                            <button type="button" id="applyToAllBtn" class="btn btn-sm btn-primary-custom">
                                <i class="ti ti-copy me-1"></i>{{ __('Aplicar a todos') }}
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small text-muted">{{ __('Hora inicio') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clock-play"></i></span>
                                    <select class="form-select" id="generalStartTime">
                                        @for ($hour = 6; $hour <= 22; $hour++)
                                            @for ($minute = 0; $minute < 60; $minute += 30)
                                                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" {{ $hour == 9 && $minute == 0 ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:%02d', $hour, $minute) }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted">{{ __('Hora fin') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clock-stop"></i></span>
                                    <select class="form-select" id="generalEndTime">
                                        @for ($hour = 6; $hour <= 22; $hour++)
                                            @for ($minute = 0; $minute < 60; $minute += 30)
                                                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" {{ $hour == 18 && $minute == 0 ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:%02d', $hour, $minute) }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de días con horarios personalizados -->
                    <div class="custom-schedules-section">
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="ti ti-calendar-event me-2"></i>{{ __('Horarios por Día') }}
                            <span class="badge bg-secondary-subtle text-secondary ms-2" id="daysCount">0 días</span>
                        </h6>
                        <div class="days-schedule-list" id="daysScheduleList">
                            <!-- Días dinámicos se insertarán aquí -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="button" id="saveScheduleBtn" class="btn btn-primary-custom">
                        <i class="ti ti-check me-1"></i>{{ __('Confirmar Horarios') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Modern Flatpickr Layout Enhancements with Platform Colors */
        .calendar-section {
            background: var(--color-white);
            border-radius: 12px;
            padding: 15px;
            border: 1px solid var(--color-border);
        }

        .calendar-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .flatpickr-calendar {
            width: 100% !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            font-family: inherit !important;
        }

        @media (min-width: 768px) {
            .flatpickr-calendar {
                max-width: 480px !important;
            }
        }

        .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .flatpickr-days {
            width: 100% !important;
        }

        .flatpickr-day {
            max-width: none !important;
            flex: 1 0 14% !important;
            height: 48px !important;
            line-height: 48px !important;
            margin: 2px 0 !important;
            border-radius: 8px !important;
            border: 1px solid transparent !important;
            transition: all 0.2s ease;
            color: var(--color-secondary) !important;
        }

        .flatpickr-day.today {
            border-color: var(--color-primary) !important;
            background: var(--color-light) !important;
            color: var(--color-primary) !important;
        }

        .flatpickr-day.selected, .flatpickr-day.selected:hover {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: var(--color-white) !important;
        }

        .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: var(--color-white) !important;
        }

        .flatpickr-day.inRange {
            background: rgba(160, 138, 122, 0.2) !important;
            border-color: transparent !important;
        }

        .flatpickr-day:hover {
            background: var(--color-light) !important;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            visibility: hidden !important;
            pointer-events: none !important;
        }

        .flatpickr-current-month {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0 !important;
            color: var(--color-secondary);
        }

        .flatpickr-months {
            margin-bottom: 15px;
        }

        .flatpickr-months .flatpickr-prev-month, 
        .flatpickr-months .flatpickr-next-month {
            color: var(--color-primary);
            fill: var(--color-primary);
        }

        .flatpickr-weekday {
            font-weight: 600 !important;
            color: var(--color-primary) !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Custom UI Elements aligned with platform */
        .params-section {
            background-color: var(--color-light);
            border: 1px solid var(--color-border);
            border-radius: 12px;
        }

        .form-label-custom {
            color: var(--color-primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .text-primary-custom {
            color: var(--color-primary) !important;
        }

        .badge-primary-custom {
            background-color: var(--color-light);
            color: var(--color-primary);
            border: 1px solid var(--color-border);
        }

        @media (max-width: 576px) {
            .flatpickr-day {
                height: 40px !important;
                line-height: 40px !important;
            }
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
        }

        .days-schedule-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .day-schedule-item {
            background: var(--color-white);
            border: 1px solid var(--color-border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .day-schedule-item:hover {
            border-color: var(--color-primary);
            box-shadow: 0 2px 8px rgba(160, 138, 122, 0.15);
        }

        .day-schedule-item .day-label {
            font-weight: 600;
            color: var(--color-secondary);
        }

        .day-schedule-item .day-date {
            font-size: 0.85rem;
            color: var(--color-primary);
        }

        .day-schedule-item .form-select {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
            height: 32px;
        }

        .day-schedule-item .input-group-text {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }

        /* Selected days badges */
        .selected-days-badges .badge {
            background: var(--color-primary);
            color: var(--color-white);
            font-weight: 500;
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        /* Input group fixes */
        .input-group .input-group-text {
            background: var(--color-white) !important;
            border: 1px solid var(--color-border) !important;
            color: var(--color-primary) !important;
        }

        .input-group .form-select {
            border: 1px solid var(--color-border) !important;
            border-left: none !important;
        }

        /* Button styles */
        .btn-primary-custom {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: var(--color-white);
        }

        .btn-primary-custom:hover {
            background-color: #8B7669;
            border-color: #8B7669;
            color: var(--color-white);
        }

        .btn-primary-custom:disabled {
            background-color: var(--color-border);
            border-color: var(--color-border);
            color: #999;
        }

        /* Form control styling */
        .form-control {
            border: 1px solid var(--color-border);
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15);
        }

        .form-select {
            border-radius: 8px;
        }

        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // State
            let currentMode = 'range';
            let selectedDates = [];
            let scheduleData = [];

            // Elements
            const datepicker = document.getElementById('datepicker');
            const selectionType = document.getElementById('selectionType');
            const scheduleDataInput = document.getElementById('scheduleData');
            const openScheduleModalBtn = document.getElementById('openScheduleModal');
            const submitBtn = document.getElementById('submitBtn');
            const selectedDaysPreview = document.getElementById('selectedDaysPreview');
            const selectedDaysBadges = document.getElementById('selectedDaysBadges');
            const selectionModeBadge = document.getElementById('selectionModeBadge');
            const daysScheduleList = document.getElementById('daysScheduleList');
            const daysCount = document.getElementById('daysCount');
            const generalStartTime = document.getElementById('generalStartTime');
            const generalEndTime = document.getElementById('generalEndTime');

            // Initialize Flatpickr - Modo individual (múltiple selección)
            let fp = flatpickr("#datepicker", {
                mode: "multiple",
                dateFormat: "Y-m-d",
                inline: true,
                monthSelectorType: "static",
                locale: "es",
                minDate: "today",
                prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                onChange: function(dates, dateStr, instance) {
                    updateSelectedDates(dates);
                }
            });

            // Update selected dates
            function updateSelectedDates(dates) {
                selectedDates = dates.map(d => formatDate(d));
                updateUI();
            }

            // Format date to Y-m-d
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Format date for display
            function formatDateDisplay(dateStr) {
                const date = new Date(dateStr + 'T00:00:00');
                const options = { weekday: 'short', day: 'numeric', month: 'short' };
                return date.toLocaleDateString('es-ES', options);
            }

            // Get day name
            function getDayName(dateStr) {
                const date = new Date(dateStr + 'T00:00:00');
                const options = { weekday: 'long' };
                return date.toLocaleDateString('es-ES', options);
            }

            // Update UI based on selected dates
            function updateUI() {
                const hasDates = selectedDates.length > 0;
                
                // Show/hide preview
                selectedDaysPreview.style.display = hasDates ? 'block' : 'none';
                
                // Update badges (show max 5)
                selectedDaysBadges.innerHTML = '';
                const maxShow = 5;
                const datesToShow = selectedDates.slice(0, maxShow);
                
                datesToShow.forEach(date => {
                    const badge = document.createElement('span');
                    badge.className = 'badge';
                    badge.textContent = formatDateDisplay(date);
                    selectedDaysBadges.appendChild(badge);
                });
                
                if (selectedDates.length > maxShow) {
                    const moreBadge = document.createElement('span');
                    moreBadge.className = 'badge bg-secondary';
                    moreBadge.textContent = `+${selectedDates.length - maxShow} más`;
                    selectedDaysBadges.appendChild(moreBadge);
                }

                // Enable/disable buttons
                openScheduleModalBtn.disabled = !hasDates;
            }

            // Mode buttons - Solo modo individual activo
            /*
            document.getElementById('selectRangeBtn').addEventListener('click', function(e) {
                e.preventDefault();
                setMode('range');
            });
            */

            document.getElementById('selectCustomBtn').addEventListener('click', function(e) {
                e.preventDefault();
                // Solo modo individual, no hay cambio
            });

            /*
            document.getElementById('selectWeekdaysBtn').addEventListener('click', function(e) {
                e.preventDefault();
                selectWeekdays();
            });
            */

            // Funciones comentadas - Solo modo individual activo
            /*
            function setMode(mode) {
                currentMode = mode;
                selectionType.value = mode;
                
                // Update badge
                const modeLabels = {
                    'range': '{{ __("Modo Rango") }}',
                    'custom': '{{ __("Modo Individual") }}',
                    'weekdays': '{{ __("Días Laborables") }}'
                };
                selectionModeBadge.textContent = modeLabels[mode] || modeLabels['range'];

                // Show info message
                const toastMessages = {
                    'range': '{{ __("Selecciona el primer y último día del rango") }}',
                    'custom': '{{ __("Haz clic en los días que desees seleccionar") }}'
                };
                
                if (toastMessages[mode]) {
                    Swal.fire({
                        title: modeLabels[mode],
                        text: toastMessages[mode],
                        icon: 'info',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                updateButtonStyles();
            }

            function selectWeekdays() {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const currentDate = fp.selectedDates[0] || new Date();
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const lastDay = new Date(year, month + 1, 0).getDate();
                const weekdayDates = [];
                
                for (let d = 1; d <= lastDay; d++) {
                    const date = new Date(year, month, d);
                    date.setHours(0, 0, 0, 0);
                    
                    if (date.getDay() !== 0 && date.getDay() !== 6 && date >= today) {
                        weekdayDates.push(date);
                    }
                }

                if (weekdayDates.length > 0) {
                    currentMode = 'weekdays';
                    selectionType.value = 'weekdays';
                    
                    fp.setDate(weekdayDates, true);
                    selectedDates = weekdayDates.map(d => formatDate(d));
                    
                    updateUI();
                    updateButtonStyles();
                    selectionModeBadge.textContent = '{{ __("Días Laborables") }}';

                    Swal.fire({
                        title: '{{ __("Días laborables seleccionados") }}',
                        text: `{{ __("Se agregaron") }} ${weekdayDates.length} {{ __("días") }}`,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
            */

            function updateButtonStyles() {
                // Solo modo individual activo
                const customBtn = document.getElementById('selectCustomBtn');
                if (customBtn) {
                    customBtn.className = 'btn btn-secondary btn-sm flex-grow-1';
                }
            }

            // Open schedule modal
            openScheduleModalBtn.addEventListener('click', function() {
                populateScheduleModal();
                const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
                modal.show();
            });

            // Populate schedule modal with days
            function populateScheduleModal() {
                daysScheduleList.innerHTML = '';
                daysCount.textContent = `${selectedDates.length} días`;

                selectedDates.forEach((date, index) => {
                    const existingSchedule = getScheduleForDate(date);
                    const dayItem = createDayScheduleItem(date, index, existingSchedule);
                    daysScheduleList.appendChild(dayItem);
                });
            }

            // Get existing schedule for a date
            function getScheduleForDate(dateStr) {
                return scheduleData.find(item => item.date === dateStr);
            }

            // Create day schedule item
            function createDayScheduleItem(date, index, existingSchedule) {
                const div = document.createElement('div');
                div.className = 'day-schedule-item';
                div.dataset.date = date;

                const dayName = getDayName(date);
                const displayDate = formatDateDisplay(date);

                // Usar horarios existentes o valores por defecto
                const startTime = existingSchedule ? existingSchedule.start_time : generalStartTime.value;
                const endTime = existingSchedule ? existingSchedule.end_time : generalEndTime.value;

                div.innerHTML = `
                    <div class="row align-items-center g-2">
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="day-label text-capitalize">${dayName}</div>
                            <div class="day-date">${displayDate}</div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ti ti-clock-play"></i></span>
                                <select class="form-select day-start-time" data-date="${date}">
                                    ${generateTimeOptions(startTime)}
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ti ti-clock-stop"></i></span>
                                <select class="form-select day-end-time" data-date="${date}">
                                    ${generateTimeOptions(endTime)}
                                </select>
                            </div>
                        </div>
                    </div>
                `;

                return div;
            }

            // Generate time options HTML
            function generateTimeOptions(selectedValue) {
                let options = '';
                for (let hour = 6; hour <= 22; hour++) {
                    for (let minute = 0; minute < 60; minute += 30) {
                        const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                        const selected = time === selectedValue ? 'selected' : '';
                        options += `<option value="${time}" ${selected}>${time}</option>`;
                    }
                }
                return options;
            }

            // Apply general schedule to all days
            document.getElementById('applyToAllBtn').addEventListener('click', function() {
                const startTime = generalStartTime.value;
                const endTime = generalEndTime.value;

                document.querySelectorAll('.day-start-time').forEach(select => {
                    select.value = startTime;
                });
                document.querySelectorAll('.day-end-time').forEach(select => {
                    select.value = endTime;
                });

                Swal.fire({
                    title: '{{ __("¡Horario aplicado!") }}',
                    text: '{{ __("El horario general se ha aplicado a todos los días") }}',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Save schedule
            document.getElementById('saveScheduleBtn').addEventListener('click', function() {
                scheduleData = [];
                let hasError = false;
                
                document.querySelectorAll('.day-schedule-item').forEach(item => {
                    if (hasError) return;
                    
                    const date = item.dataset.date;
                    const startTime = item.querySelector('.day-start-time').value;
                    const endTime = item.querySelector('.day-end-time').value;

                    if (startTime >= endTime) {
                        Swal.fire({
                            title: '{{ __("Error de horario") }}',
                            text: `{{ __("La hora de fin debe ser posterior a la hora de inicio para el día") }} ${formatDateDisplay(date)}`,
                            icon: 'error'
                        });
                        hasError = true;
                        return;
                    }

                    scheduleData.push({
                        date: date,
                        start_time: startTime,
                        end_time: endTime
                    });
                });

                if (!hasError && scheduleData.length === selectedDates.length) {
                    scheduleDataInput.value = JSON.stringify(scheduleData);
                    submitBtn.disabled = false;
                    
                    bootstrap.Modal.getInstance(document.getElementById('scheduleModal')).hide();

                    Swal.fire({
                        title: '{{ __("¡Horarios configurados!") }}',
                        text: '{{ __("Ya puedes guardar la configuración") }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

            // Form validation before submit
            document.getElementById('availabilityForm').addEventListener('submit', function(e) {
                const title = document.getElementById('eventTitle').value.trim();
                
                if (!title) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __("Error") }}',
                        text: '{{ __("Por favor, introduce un título para el evento") }}',
                        icon: 'error'
                    });
                    return;
                }

                if (scheduleData.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __("Error") }}',
                        text: '{{ __("Por favor, configura los horarios antes de guardar") }}',
                        icon: 'error'
                    });
                    return;
                }
            });

            // Initialize
            currentMode = 'custom';
            selectionType.value = 'custom';
            updateButtonStyles();
        });
    </script>
@endpush
