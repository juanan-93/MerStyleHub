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
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
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
                            <div class="btn-group" role="group">
                                <button type="button" id="applyToAllBtn" class="btn btn-sm btn-primary-custom">
                                    <i class="ti ti-copy me-1"></i>{{ __('Aplicar a todos') }}
                                </button>
                                <button type="button" id="deselectAllBtn" class="btn btn-sm btn-outline-secondary">
                                    <i class="ti ti-square me-1"></i>{{ __('Deseleccionar') }}
                                </button>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small text-muted">{{ __('Hora inicio') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clock-play"></i></span>
                                    <select class="form-select" id="generalStartTime">
                                        @for ($hour = 6; $hour <= 22; $hour++)
                                            @for ($minute = 0; $minute < 60; $minute += 15)
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
                                            @for ($minute = 0; $minute < 60; $minute += 15)
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
            transform: scale(0.88) !important;
            border-radius: 6px !important;
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
            max-height: 600px;
            overflow-y: auto;
        }

        /* Month Accordion Styles */
        .month-accordion {
            margin-bottom: 15px;
        }

        .month-header {
            background: var(--color-primary);
            color: var(--color-white);
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(160, 138, 122, 0.25);
        }

        .month-header:hover {
            background: #8B7669;
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.35);
            transform: translateY(-1px);
        }

        .month-header.collapsed {
            background: var(--color-light);
            color: var(--color-primary);
            border: 1px solid var(--color-border);
        }

        .month-header.collapsed:hover {
            background: rgba(160, 138, 122, 0.1);
            border-color: var(--color-primary);
        }

        .month-header .month-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .month-header .month-badge {
            background: var(--color-white);
            color: var(--color-primary);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .month-header.collapsed .month-badge {
            background: var(--color-primary);
            color: var(--color-white);
        }

        .month-header .toggle-icon {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .month-header:not(.collapsed) .toggle-icon {
            transform: rotate(180deg);
        }

        .month-body {
            padding: 15px 10px;
            background: var(--color-light);
            border: 1px solid var(--color-border);
            border-top: none;
            border-radius: 0 0 10px 10px;
            margin-top: -5px;
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

        /* Time slots styling */
        .time-slot {
            background: var(--color-light);
            border-radius: 8px;
            padding: 10px;
        }

        .time-slot:hover {
            background: rgba(160, 138, 122, 0.1);
        }
        
        /* Checkbox styling */
        .form-check {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 0;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0;
            border: 2px solid var(--color-border);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            accent-color: var(--color-primary);
        }
        
        .form-check-input:hover {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15);
        }
        
        .form-check-input:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            box-shadow: inset 0 0 0 2px var(--color-white);
        }

        .add-slot-btn {
            font-size: 0.8rem;
            padding: 4px 10px;
        }

        .remove-slot-btn {
            padding: 4px 8px;
        }

        .slots-container .time-slot:last-child {
            margin-bottom: 0 !important;
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

        /* Button group spacing */
        .btn-group {
            gap: 8px !important;
            display: flex !important;
        }

        .btn-group .btn {
            border-radius: 6px !important;
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

        /* Select2 Custom Styling - Corporativo */
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--color-border) !important;
            border-radius: 8px !important;
            height: auto !important;
            padding: 0 !important;
            background-color: var(--color-white) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 8px 12px !important;
            color: var(--color-secondary) !important;
            line-height: 1.5 !important;
            font-size: 0.9rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--color-primary) transparent transparent !important;
            margin-top: -2px !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15) !important;
        }

        .select2-dropdown {
            border: 1px solid var(--color-border) !important;
            border-radius: 8px !important;
            background-color: var(--color-white) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-container--default .select2-results__option {
            padding: 10px 12px !important;
            color: var(--color-secondary) !important;
            font-size: 0.9rem !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgba(160, 138, 122, 0.15) !important;
            color: var(--color-primary) !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(160, 138, 122, 0.1) !important;
            color: var(--color-primary) !important;
            font-weight: 500 !important;
        }

        /* Select2 en input-group */
        .input-group .select2-container {
            flex: 1 !important;
        }

        .input-group .select2-container--default .select2-selection--single {
            border-left: none !important;
            border-radius: 0 8px 8px 0 !important;
        }

        /* Select2 tamaño pequeño para modal */
        .select2-container--default.select2-sm .select2-selection--single .select2-selection__rendered {
            padding: 6px 10px !important;
            font-size: 0.85rem !important;
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

            // Update selected dates - Sincroniza selectedDates con scheduleData
            function updateSelectedDates(dates) {
                const newSelectedDates = dates.map(d => formatDate(d));
                
                // Encontrar días que fueron deseleccionados
                const removedDates = selectedDates.filter(d => !newSelectedDates.includes(d));
                
                // Encontrar días nuevos que fueron agregados
                const addedDates = newSelectedDates.filter(d => !selectedDates.includes(d));
                
                // Actualizar selectedDates
                selectedDates = newSelectedDates;
                
                // Eliminar de scheduleData los días deseleccionados
                if (removedDates.length > 0) {
                    scheduleData = scheduleData.filter(item => !removedDates.includes(item.date));
                }
                
                // Para días nuevos, agregar horario por defecto si no existe
                addedDates.forEach(date => {
                    const existsInSchedule = scheduleData.some(s => s.date === date);
                    if (!existsInSchedule) {
                        scheduleData.push({
                            date: date,
                            start_time: '09:00',
                            end_time: '18:00'
                        });
                    }
                });
                
                // Actualizar el campo hidden del formulario
                scheduleDataInput.value = JSON.stringify(scheduleData);
                
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
                const hasScheduleData = scheduleData.length > 0;
                
                // Show/hide preview
                selectedDaysPreview.style.display = hasDates ? 'block' : 'none';
                
                // Update badges (show max 5) - usar Set para evitar duplicados
                selectedDaysBadges.innerHTML = '';
                const maxShow = 5;
                const uniqueDates = [...new Set(selectedDates)].sort();
                const datesToShow = uniqueDates.slice(0, maxShow);
                
                datesToShow.forEach(date => {
                    const badge = document.createElement('span');
                    badge.className = 'badge';
                    badge.textContent = formatDateDisplay(date);
                    selectedDaysBadges.appendChild(badge);
                });
                
                if (uniqueDates.length > maxShow) {
                    const moreBadge = document.createElement('span');
                    moreBadge.className = 'badge bg-secondary';
                    moreBadge.textContent = `+${uniqueDates.length - maxShow} más`;
                    selectedDaysBadges.appendChild(moreBadge);
                }

                // Enable/disable buttons
                openScheduleModalBtn.disabled = !hasDates;
                
                // Habilitar botón de guardar solo si hay datos válidos
                submitBtn.disabled = !hasScheduleData;
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

            // Variable global para el modal
            let modalInstance = null;

            // Open schedule modal
            openScheduleModalBtn.addEventListener('click', function() {
                populateScheduleModal();
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(document.getElementById('scheduleModal'));
                }
                modalInstance.show();
            });

            // Populate schedule modal with days
            function populateScheduleModal() {
                daysScheduleList.innerHTML = '';
                daysCount.textContent = `${selectedDates.length} días`;

                // Agrupar fechas por mes
                const datesByMonth = {};
                selectedDates.forEach(date => {
                    const dateObj = new Date(date + 'T00:00:00');
                    const monthKey = dateObj.toLocaleDateString('es-ES', { year: 'numeric', month: 'long' });
                    const monthSort = dateObj.getFullYear() * 100 + dateObj.getMonth();
                    if (!datesByMonth[monthKey]) {
                        datesByMonth[monthKey] = {
                            dates: [],
                            sortKey: monthSort
                        };
                    }
                    datesByMonth[monthKey].dates.push(date);
                });

                // Ordenar meses cronológicamente
                const sortedMonths = Object.keys(datesByMonth).sort((a, b) => {
                    return datesByMonth[a].sortKey - datesByMonth[b].sortKey;
                });

                // Renderizar días agrupados por mes con collapses
                let globalIndex = 0;
                sortedMonths.forEach((monthKey, monthIndex) => {
                    const dates = datesByMonth[monthKey].dates;
                    const collapseId = `month-collapse-${monthIndex}`;
                    
                    // Crear contenedor del acordeon
                    const monthAccordion = document.createElement('div');
                    monthAccordion.className = 'month-accordion';
                    
                    // Crear header del mes (botón colapsable)
                    const monthHeader = document.createElement('button');
                    monthHeader.className = 'month-header';
                    monthHeader.type = 'button';
                    monthHeader.setAttribute('data-bs-toggle', 'collapse');
                    monthHeader.setAttribute('data-bs-target', `#${collapseId}`);
                    monthHeader.setAttribute('aria-expanded', 'true');
                    monthHeader.setAttribute('aria-controls', collapseId);
                    
                    monthHeader.innerHTML = `
                        <div class="month-title">
                            <i class="ti ti-calendar-month"></i>
                            <span>${monthKey.charAt(0).toUpperCase() + monthKey.slice(1)}</span>
                            <span class="month-badge">${dates.length} días</span>
                        </div>
                        <i class="ti ti-chevron-down toggle-icon"></i>
                    `;
                    
                    // Crear body colapsable
                    const monthCollapse = document.createElement('div');
                    monthCollapse.className = 'collapse show';
                    monthCollapse.id = collapseId;
                    monthCollapse.setAttribute('data-bs-parent', '#daysScheduleList');
                    
                    const monthBody = document.createElement('div');
                    monthBody.className = 'month-body';
                    
                    // Renderizar días del mes
                    dates.forEach(date => {
                        const existingSchedule = getScheduleForDate(date);
                        const dayItem = createDayScheduleItem(date, globalIndex, existingSchedule);
                        monthBody.appendChild(dayItem);
                        globalIndex++;
                    });
                    
                    monthCollapse.appendChild(monthBody);
                    monthAccordion.appendChild(monthHeader);
                    monthAccordion.appendChild(monthCollapse);
                    daysScheduleList.appendChild(monthAccordion);
                    
                    // Event listener para cambiar clase collapsed
                    monthCollapse.addEventListener('hidden.bs.collapse', function() {
                        monthHeader.classList.add('collapsed');
                    });
                    
                    monthCollapse.addEventListener('shown.bs.collapse', function() {
                        monthHeader.classList.remove('collapsed');
                    });
                });
            }

            // Get existing schedule for a date (todas las franjas)
            function getScheduleForDate(dateStr) {
                const daySlots = scheduleData.filter(s => s.date === dateStr);
                if (daySlots.length === 0) return null;
                return {
                    slots: daySlots.map(slot => ({
                        start_time: slot.start_time,
                        end_time: slot.end_time
                    }))
                };
            }

            // Create day schedule item
            function createDayScheduleItem(date, index, existingSchedule) {
                const div = document.createElement('div');
                div.className = 'day-schedule-item';
                div.dataset.date = date;

                const dayName = getDayName(date);
                const displayDate = formatDateDisplay(date);

                // Obtener franjas existentes o crear una por defecto
                const existingSlots = existingSchedule && existingSchedule.slots ? existingSchedule.slots : 
                    [{ start_time: generalStartTime.value, end_time: generalEndTime.value }];

                let slotsHtml = '';
                const showDeleteBtn = existingSlots.length > 1; // Solo mostrar botón eliminar si hay más de 1 franja
                
                existingSlots.forEach((slot, slotIndex) => {
                    slotsHtml += `
                        <div class="time-slot mb-2" data-slot-index="${slotIndex}">
                            <div class="row align-items-center g-2">
                                <div class="col-1">
                                    <div class="form-check">
                                        <input class="form-check-input slot-checkbox" type="checkbox" data-date="${date}" data-slot="${slotIndex}" id="slot_${date}_${slotIndex}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="ti ti-clock-play"></i></span>
                                        <select class="form-select day-start-time" data-date="${date}" data-slot="${slotIndex}">
                                            ${generateTimeOptions(slot.start_time)}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="ti ti-clock-stop"></i></span>
                                        <select class="form-select day-end-time" data-date="${date}" data-slot="${slotIndex}">
                                            ${generateTimeOptions(slot.end_time)}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-slot-btn ${showDeleteBtn ? '' : 'd-none'}" data-date="${date}" data-slot="${slotIndex}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                div.innerHTML = `
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="day-label text-capitalize">${dayName}</div>
                                <div class="day-date">${displayDate}</div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary-custom add-slot-btn" data-date="${date}">
                                    <i class="ti ti-plus"></i> Añadir franja
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-day-btn" data-date="${date}">
                                    <i class="ti ti-trash"></i> Eliminar registro
                                </button>
                            </div>
                        </div>
                        <div class="slots-container" data-date="${date}">
                            ${slotsHtml}
                        </div>
                    </div>
                `;

                // Event listeners para botones de añadir/eliminar franjas
                setTimeout(() => {
                    const addBtn = div.querySelector('.add-slot-btn');
                    addBtn.addEventListener('click', function() {
                        addTimeSlot(date);
                    });

                    const removeDayBtn = div.querySelector('.remove-day-btn');
                    removeDayBtn.addEventListener('click', function() {
                        removeDay(date);
                    });

                    div.querySelectorAll('.remove-slot-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            removeTimeSlot(date, this.dataset.slot);
                        });
                    });
                    
                    // Inicializar Select2 en los selectores de tiempo de este día
                    $(div).find('.day-start-time, .day-end-time').each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            $(this).select2({
                                minimumResultsForSearch: Infinity,
                                width: '100%',
                                dropdownParent: $('#scheduleModal')
                            });
                        }
                    });
                    
                    // Event listeners para sincronizar cambios en los selects
                    div.querySelectorAll('.day-start-time, .day-end-time').forEach(select => {
                        select.addEventListener('change', function() {
                            syncScheduleDataFromDOM();
                        });
                    });
                }, 0);

                return div;
            }

            // Añadir nueva franja horaria a un día
            function addTimeSlot(date) {
                const container = document.querySelector(`.slots-container[data-date="${date}"]`);
                const slots = container.querySelectorAll('.time-slot');
                
                // Limitar a 5 franjas máximo
                if (slots.length >= 5) {
                    Swal.fire({
                        title: '{{ __("Límite alcanzado") }}',
                        text: '{{ __("No se pueden añadir más de 5 franjas horarias por día") }}',
                        icon: 'warning',
                        timer: 2500
                    });
                    return;
                }

                const newSlotIndex = slots.length;

                const slotDiv = document.createElement('div');
                slotDiv.className = 'time-slot mb-2';
                slotDiv.dataset.slotIndex = newSlotIndex;
                slotDiv.innerHTML = `
                    <div class="row align-items-center g-2">
                        <div class="col-1">
                            <div class="form-check">
                                <input class="form-check-input slot-checkbox" type="checkbox" data-date="${date}" data-slot="${newSlotIndex}" id="slot_${date}_${newSlotIndex}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ti ti-clock-play"></i></span>
                                <select class="form-select day-start-time" data-date="${date}" data-slot="${newSlotIndex}">
                                    ${generateTimeOptions(generalStartTime.value)}
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ti ti-clock-stop"></i></span>
                                <select class="form-select day-end-time" data-date="${date}" data-slot="${newSlotIndex}">
                                    ${generateTimeOptions(generalEndTime.value)}
                                </select>
                            </div>
                        </div>
                        <div class="col-3 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-slot-btn" data-date="${date}" data-slot="${newSlotIndex}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(slotDiv);

                // Event listener para el botón de eliminar
                slotDiv.querySelector('.remove-slot-btn').addEventListener('click', function() {
                    removeTimeSlot(date, this.dataset.slot);
                });
                
                // Inicializar Select2 en los nuevos selectores de tiempo
                $(slotDiv).find('.day-start-time, .day-end-time').each(function() {
                    $(this).select2({
                        minimumResultsForSearch: Infinity,
                        width: '100%',
                        dropdownParent: $('#scheduleModal')
                    });
                });
                
                // Event listeners para sincronizar cambios en los selects
                slotDiv.querySelector('.day-start-time').addEventListener('change', function() {
                    syncScheduleDataFromDOM();
                });
                slotDiv.querySelector('.day-end-time').addEventListener('change', function() {
                    syncScheduleDataFromDOM();
                });
                
                // Actualizar scheduleData con el nuevo slot
                scheduleData.push({
                    date: date,
                    start_time: generalStartTime.value || '09:00',
                    end_time: generalEndTime.value || '18:00'
                });
                scheduleDataInput.value = JSON.stringify(scheduleData);
                
                // Actualizar estado del botón "Añadir franja"
                updateAddSlotButtonState(date);
                
                // Actualizar visibilidad de botones eliminar (mostrar todos si hay más de 1)
                updateRemoveSlotButtonsVisibility(date);
                
                updateUI();
            }
            
            // Sincronizar scheduleData desde el DOM
            function syncScheduleDataFromDOM() {
                scheduleData = [];
                selectedDates.forEach(date => {
                    const container = document.querySelector(`.slots-container[data-date="${date}"]`);
                    if (!container) return;
                    
                    const slots = container.querySelectorAll('.time-slot');
                    slots.forEach(slot => {
                        const startSelect = slot.querySelector('.day-start-time');
                        const endSelect = slot.querySelector('.day-end-time');
                        if (startSelect && endSelect) {
                            scheduleData.push({
                                date: date,
                                start_time: startSelect.value,
                                end_time: endSelect.value
                            });
                        }
                    });
                });
                scheduleDataInput.value = JSON.stringify(scheduleData);
                updateUI();
            }
            
            // Actualizar visibilidad de los botones eliminar franja
            function updateRemoveSlotButtonsVisibility(date) {
                const container = document.querySelector(`.slots-container[data-date="${date}"]`);
                if (!container) return;
                
                const slots = container.querySelectorAll('.time-slot');
                const removeButtons = container.querySelectorAll('.remove-slot-btn');
                
                if (slots.length > 1) {
                    // Mostrar todos los botones eliminar
                    removeButtons.forEach(btn => btn.classList.remove('d-none'));
                } else {
                    // Ocultar todos los botones eliminar
                    removeButtons.forEach(btn => btn.classList.add('d-none'));
                }
            }
            
            // Actualizar estado del botón "Añadir franja"
            function updateAddSlotButtonState(date) {
                const dayItem = document.querySelector(`.day-schedule-item[data-date="${date}"]`);
                if (!dayItem) return;
                
                const addBtn = dayItem.querySelector('.add-slot-btn');
                const container = dayItem.querySelector(`.slots-container[data-date="${date}"]`);
                const slots = container.querySelectorAll('.time-slot');
                
                if (slots.length >= 5) {
                    addBtn.disabled = true;
                    addBtn.style.opacity = '0.5';
                    addBtn.style.cursor = 'not-allowed';
                } else {
                    addBtn.disabled = false;
                    addBtn.style.opacity = '1';
                    addBtn.style.cursor = 'pointer';
                }
            }

            // Eliminar franja horaria
            function removeTimeSlot(date, slotIndex) {
                const container = document.querySelector(`.slots-container[data-date="${date}"]`);
                const slots = container.querySelectorAll('.time-slot');
                
                // Si es la última franja, eliminar el día completo
                if (slots.length === 1) {
                    removeDay(date);
                    return;
                }
                
                const slotToRemove = container.querySelector(`.time-slot[data-slot-index="${slotIndex}"]`);
                slotToRemove.remove();

                // Reindexar las franjas restantes
                container.querySelectorAll('.time-slot').forEach((slot, index) => {
                    slot.dataset.slotIndex = index;
                    slot.querySelectorAll('[data-slot]').forEach(el => {
                        el.dataset.slot = index;
                    });
                });
                
                // Actualizar botones de eliminar franja
                container.querySelectorAll('.remove-slot-btn').forEach(btn => {
                    btn.removeEventListener('click', arguments.callee);
                    btn.addEventListener('click', function() {
                        removeTimeSlot(date, this.dataset.slot);
                    });
                });
                
                // Actualizar estado del botón Añadir franja
                updateAddSlotButtonState(date);
                
                // Actualizar visibilidad de botones eliminar
                updateRemoveSlotButtonsVisibility(date);
                
                // Actualizar scheduleData
                scheduleData = scheduleData.filter(item => item.date !== date);
                const inputs = document.querySelectorAll(`.slots-container[data-date="${date}"] .time-slot`);
                inputs.forEach(slot => {
                    const startTime = slot.querySelector('.day-start-time').value;
                    const endTime = slot.querySelector('.day-end-time').value;
                    scheduleData.push({ date: date, start_time: startTime, end_time: endTime });
                });
                
                // Actualizar el campo hidden del formulario
                scheduleDataInput.value = JSON.stringify(scheduleData);
                updateUI();
            }
            
            // Eliminar día completo (registro) con todas sus franjas
            function removeDay(date) {
                Swal.fire({
                    title: '¿Eliminar registro?',
                    text: 'Se eliminará el día y todas sus franjas horarias',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: 'var(--color-primary)',
                    cancelButtonColor: '#999'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Eliminar del DOM
                        const dayItem = document.querySelector(`.day-schedule-item[data-date="${date}"]`);
                        if (dayItem) {
                            dayItem.remove();
                        }
                        
                        // Eliminar de selectedDates
                        selectedDates = selectedDates.filter(d => d !== date);
                        
                        // Eliminar de scheduleData
                        scheduleData = scheduleData.filter(item => item.date !== date);
                        
                        // Si no hay más días, cerrar el modal correctamente
                        if (selectedDates.length === 0) {
                            daysScheduleList.innerHTML = '';
                            daysCount.textContent = '0 días';
                            
                            // Limpiar el campo hidden del formulario
                            scheduleDataInput.value = '[]';
                            
                            // Cerrar modal correctamente
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                            
                            // Limpiar Flatpickr completamente
                            fp.clear();
                            
                            // Esperar a que el modal se cierre antes de mostrar mensaje
                            const scheduleModal = document.getElementById('scheduleModal');
                            scheduleModal.addEventListener('hidden.bs.modal', function onHidden() {
                                scheduleModal.removeEventListener('hidden.bs.modal', onHidden);
                                
                                // Eliminar backdrop manualmente si queda
                                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                                document.body.classList.remove('modal-open');
                                document.body.style.overflow = '';
                                document.body.style.paddingRight = '';
                                
                                // Destruir modal instance
                                if (modalInstance) {
                                    modalInstance.dispose();
                                    modalInstance = null;
                                }
                                
                                // Actualizar UI
                                updateUI();
                                
                                Swal.fire({
                                    title: 'Seleccione días',
                                    text: 'Debe seleccionar al menos un día para configurar horarios',
                                    icon: 'info',
                                    confirmButtonColor: 'var(--color-primary)'
                                });
                            }, { once: true });
                        } else {
                            // Actualizar el campo hidden del formulario
                            scheduleDataInput.value = JSON.stringify(scheduleData);
                            
                            // Actualizar Flatpickr con fechas válidas
                            const datesToSet = selectedDates.map(dateStr => {
                                const [year, month, day] = dateStr.split('-');
                                return new Date(year, month - 1, day);
                            });
                            fp.setDate(datesToSet, false);
                            
                            // Actualizar modal si hay días restantes
                            populateScheduleModal();
                            
                            // Actualizar UI
                            updateUI();
                            
                            Swal.fire({
                                title: 'Registro eliminado',
                                icon: 'success',
                                confirmButtonColor: 'var(--color-primary)',
                                timer: 2000
                            });
                        }
                    }
                });
            }
                    
            // Generate time options HTML
            function generateTimeOptions(selectedValue) {
                let options = '';
                for (let hour = 6; hour <= 22; hour++) {
                    for (let minute = 0; minute < 60; minute += 15) {
                        const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                        const selected = time === selectedValue ? 'selected' : '';
                        options += `<option value="${time}" ${selected}>${time}</option>`;
                    }
                }
                return options;
            }

            // Apply general schedule to selected slots
            document.getElementById('applyToAllBtn').addEventListener('click', function() {
                const startTime = generalStartTime.value;
                const endTime = generalEndTime.value;
                
                // Validar que haya horarios definidos
                if (!startTime || !endTime) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe establecer un horario general antes de aplicar',
                        icon: 'warning',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    return;
                }
                
                // Obtener todos los checkboxes marcados
                const selectedSlots = document.querySelectorAll('.slot-checkbox:checked');
                
                if (selectedSlots.length === 0) {
                    Swal.fire({
                        title: 'Seleccione franjas',
                        text: 'Marque al menos una franja horaria para aplicar el horario general',
                        icon: 'info',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    return;
                }
                
                // Aplicar horario a las franjas seleccionadas
                selectedSlots.forEach(checkbox => {
                    const date = checkbox.dataset.date;
                    const slotIndex = checkbox.dataset.slot;
                    
                    // Buscar el contenedor del día específico
                    const daySchedule = document.querySelector(`.day-schedule-item[data-date="${date}"]`);
                    
                    if (daySchedule) {
                        // Buscar la franja específica dentro de ese día
                        const timeSlot = daySchedule.querySelector(`.time-slot[data-slot-index="${slotIndex}"]`);
                        
                        if (timeSlot) {
                            const startSelect = timeSlot.querySelector(`.day-start-time[data-date="${date}"][data-slot="${slotIndex}"]`);
                            const endSelect = timeSlot.querySelector(`.day-end-time[data-date="${date}"][data-slot="${slotIndex}"]`);
                            
                            // Usar Select2 para cambiar el valor y triggear el evento
                            if (startSelect) {
                                $(startSelect).val(startTime).trigger('change');
                            }
                            if (endSelect) {
                                $(endSelect).val(endTime).trigger('change');
                            }
                        }
                    }
                });
                
                // Sincronizar scheduleData
                syncScheduleDataFromDOM();
                
                Swal.fire({
                    title: '¡Horario aplicado!',
                    text: `Se ha aplicado el horario general a ${selectedSlots.length} franja(s)`,
                    icon: 'success',
                    confirmButtonColor: 'var(--color-primary)',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Deselect all checkboxes
            document.getElementById('deselectAllBtn').addEventListener('click', function() {
                const selectedSlots = document.querySelectorAll('.slot-checkbox:checked');
                
                if (selectedSlots.length === 0) {
                    Swal.fire({
                        title: 'Sin selección',
                        text: 'No hay franjas horarias seleccionadas',
                        icon: 'info',
                        confirmButtonColor: 'var(--color-primary)',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return;
                }
                
                const count = selectedSlots.length;
                selectedSlots.forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                Swal.fire({
                    title: '¡Deseleccionado!',
                    text: `Se han deseleccionado ${count} franja(s)`,
                    icon: 'success',
                    confirmButtonColor: 'var(--color-primary)',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Save schedule
            document.getElementById('saveScheduleBtn').addEventListener('click', function() {
                scheduleData = [];
                let hasError = false;

                // Recopilar datos por día (cada día puede tener múltiples franjas)
                selectedDates.forEach(date => {
                    const slotsContainer = document.querySelector(`.slots-container[data-date="${date}"]`);
                    
                    if (!slotsContainer) {
                        console.error('No se encontró contenedor para fecha:', date);
                        return;
                    }
                    
                    const slots = slotsContainer.querySelectorAll('.time-slot');
                    
                    slots.forEach(slot => {
                        const slotIndex = slot.dataset.slotIndex;
                        const startSelect = slot.querySelector(`.day-start-time[data-slot="${slotIndex}"]`);
                        const endSelect = slot.querySelector(`.day-end-time[data-slot="${slotIndex}"]`);
                        
                        if (!startSelect || !endSelect) {
                            console.error('No se encontraron selectores para slot:', slotIndex);
                            return;
                        }
                        
                        const startTime = startSelect.value;
                        const endTime = endSelect.value;

                        if (startTime >= endTime) {
                            hasError = true;
                            Swal.fire({
                                title: '{{ __("Error en horarios") }}',
                                text: `{{ __("La hora de inicio debe ser menor que la hora de fin en") }} ${formatDateDisplay(date)}`,
                                icon: 'error'
                            });
                            return;
                        }

                        scheduleData.push({
                            date: date,
                            start_time: startTime,
                            end_time: endTime
                        });
                    });
                });

                if (hasError) return;

                if (scheduleData.length > 0) {
                    scheduleDataInput.value = JSON.stringify(scheduleData);
                    submitBtn.disabled = false;
                    
                    const modalElement = document.getElementById('scheduleModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    Swal.fire({
                        title: '{{ __("¡Horarios configurados!") }}',
                        text: '{{ __("Ya puedes guardar la configuración") }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("Error") }}',
                        text: '{{ __("No se han configurado horarios") }}',
                        icon: 'error'
                    });
                }
            });

            // Form validation before submit
            document.getElementById('availabilityForm').addEventListener('submit', function(e) {
                const title = document.getElementById('eventTitle').value.trim();
                
                if (!title) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, introduce un título para el evento',
                        icon: 'error',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    return;
                }

                if (scheduleData.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Horarios no configurados',
                        text: 'Debe configurar al menos un día con horarios antes de guardar. Abre el modal de configuración y selecciona días con sus franjas horarias.',
                        icon: 'error',
                        confirmButtonColor: 'var(--color-primary)'
                    });
                    return;
                }
            });

            // Initialize
            currentMode = 'custom';
            selectionType.value = 'custom';
            updateButtonStyles();
            
            // Inicializar Select2 en todos los selectores
            initializeSelect2();
        });
        
        // Función para inicializar Select2
        function initializeSelect2() {
            // Selectores principales del formulario
            $('#meetingDuration, #meetingType').select2({
                minimumResultsForSearch: Infinity, // Sin búsqueda
                width: '100%'
            });
            
            // Selectores de tiempo generales del modal
            $('#generalStartTime, #generalEndTime').select2({
                minimumResultsForSearch: Infinity,
                width: '100%',
                dropdownParent: $('#scheduleModal')
            });
            
            // Selectores dinámicos de tiempo en el modal
            $('.day-start-time, .day-end-time').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        minimumResultsForSearch: Infinity,
                        width: '100%',
                        dropdownParent: $('#scheduleModal')
                    });
                }
            });
        }
    </script>
@endpush
