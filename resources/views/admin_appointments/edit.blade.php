@extends('layouts.app', ['title' => __('Edit Appointment')])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('admin_appointments.index') }}" class="text-muted">
            <i class="ti ti-calendar me-1"></i>{{ __('Appointments') }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        {{ __('Edit Appointment') }}
    </li>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-primary-custom">
                            <i class="ti ti-calendar-plus me-2"></i>{{ __('Editar Disponibilidad') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin_appointments.updateBatch', $batch_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <!-- Left Column: Settings -->
                                <div class="col-md-5">
                                    <div class="params-section p-3 h-100">
                                        <h6 class="fw-bold mb-3 text-secondary">{{ __('Parámetros de la Cita') }}</h6>
                                        
                                        <!-- Duración de la reunión -->
                                        <div class="mb-3">
                                            <label for="meetingDuration" class="form-label-custom small">{{ __('Duración (minutos)') }}</label>
                                            <select class="form-select border-0 shadow-sm" name="duration" id="meetingDuration">
                                                <option value="10" {{ $availability->duration == 10 ? 'selected' : '' }}>10 min</option>
                                                <option value="15" {{ $availability->duration == 15 ? 'selected' : '' }}>15 min</option>
                                                <option value="20" {{ $availability->duration == 20 ? 'selected' : '' }}>20 min</option>
                                                <option value="30" {{ $availability->duration == 30 ? 'selected' : '' }}>30 min</option>
                                                <option value="45" {{ $availability->duration == 45 ? 'selected' : '' }}>45 min</option>
                                                <option value="60" {{ $availability->duration == 60 ? 'selected' : '' }}>60 min</option>
                                            </select>
                                        </div>

                                        <!-- Tipo de reunión -->
                                        <div class="mb-3">
                                            <label for="meetingType" class="form-label-custom small">{{ __('Categoría') }}</label>
                                            <select class="form-select border-0 shadow-sm" name="category" id="meetingType">
                                                <option value="standard" {{ $availability->category == 'standard' ? 'selected' : '' }}>{{ __('Reunión Estándar') }}</option>
                                                <option value="custom" {{ $availability->category == 'custom' ? 'selected' : '' }}>{{ __('Reunión Personalizada') }}</option>
                                            </select>
                                        </div>

                                        <!-- Franja horaria -->
                                        <div class="mb-0">
                                            <label class="form-label-custom small">{{ __('Horario de Atención') }}</label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text"><i class="ti ti-clock-play"></i></span>
                                                        <select class="form-select" name="start_time" id="startTime">
                                                            @for ($hour = 8; $hour <= 21; $hour++)
                                                                @for ($minute = 0; $minute < 60; $minute += 30)
                                                                    @php $time = sprintf('%02d:%02d', $hour, $minute); @endphp
                                                                    <option value="{{ $time }}" {{ substr($availability->start_time, 0, 5) == $time ? 'selected' : '' }}>
                                                                        {{ $time }}
                                                                    </option>
                                                                @endfor
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text"><i class="ti ti-clock-stop"></i></span>
                                                        <select class="form-select" name="end_time" id="endTime">
                                                            @for ($hour = 8; $hour <= 21; $hour++)
                                                                @for ($minute = 0; $minute < 60; $minute += 30)
                                                                    @php $time = sprintf('%02d:%02d', $hour, $minute); @endphp
                                                                    <option value="{{ $time }}" {{ substr($availability->end_time, 0, 5) == $time ? 'selected' : '' }}>
                                                                        {{ $time }}
                                                                    </option>
                                                                @endfor
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botones: modo de selección -->
                                        <div class="mt-3 d-flex gap-2">
                                            <button type="button" id="selectRangeBtn" class="btn btn-outline-primary btn-sm flex-grow-1">
                                                <i class="ti ti-calendar-range me-1"></i>{{ __('Rango') }}
                                            </button>
                                            <button type="button" id="selectCustomBtn" class="btn btn-outline-secondary btn-sm flex-grow-1">
                                                <i class="ti ti-hand-click me-1"></i>{{ __('Selección Libre') }}
                                            </button>
                                            <button type="button" id="selectWeekdaysBtn" class="btn btn-outline-success btn-sm flex-grow-1">
                                                <i class="ti ti-briefcase me-1"></i>{{ __('Laborables') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Calendar -->
                                <div class="col-md-7">
                                    <div class="calendar-section h-100">
                                        <label class="form-label fw-bold mb-3 d-flex justify-content-between align-items-center text-secondary">
                                            {{ __('Días Disponibles') }}
                                            <span class="badge badge-primary-custom" style="font-size: 0.7rem;">
                                                {{ __('Selección Múltiple') }}
                                            </span>
                                        </label>
                                        <div class="calendar-wrapper">
                                            <input type="text" id="datepicker" name="dates" class="form-control d-none" value="{{ $dates }}" placeholder="Select dates">
                                            <input type="hidden" id="selectionType" name="selection_type" value="{{ $selectionType }}">
                                        </div>
                                        <div class="mt-3 d-flex gap-3 justify-content-center small text-muted">
                                            <div class="d-flex align-items-center">
                                                <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--color-primary);"></span>
                                                {{ __('Seleccionado') }}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: var(--color-border);"></span>
                                                {{ __('Libre') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                                <a href="{{ route('admin_appointments.index') }}" class="btn btn-outline-secondary px-3">{{ __('Cancelar') }}</a>
                                <button type="submit" class="btn btn-primary-custom px-4 shadow-sm">
                                    <i class="ti ti-check me-1"></i> {{ __('Actualizar Configuración') }}
                                </button>
                            </div>
                        </form>
                    </div>
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

        .availability-indicator {
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background-color: var(--color-primary);
            border-radius: 50%;
        }

        .flatpickr-day.selected .availability-indicator {
            background-color: var(--color-white);
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

        /* Estilos Select2 para integrarse con la plataforma */
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--color-border) !important;
            border-radius: 6px !important;
            height: 34px !important;
            padding-top: 2px !important;
            background-color: var(--color-white) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
            font-size: 0.95rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--color-secondary) !important;
            line-height: 28px !important;
        }

        .select2-dropdown {
            border: 1px solid var(--color-border) !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--color-primary) !important;
            color: var(--color-white) !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--color-light) !important;
            color: var(--color-primary) !important;
        }

        /* Ajustes para que los iconos dentro de input-group se integren con los inputs */
        .input-group .input-group-text {
            background: var(--color-white) !important;
            border: 1px solid var(--color-border) !important;
            color: var(--color-primary) !important;
            border-radius: 6px 0 0 6px !important;
            padding: 0 0.6rem !important;
            height: 34px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
        }

        /* Hacer que el select (o el contenedor Select2) quede 'pegado' al icono */
        .input-group .form-select,
        .input-group .select2-container--default .select2-selection--single {
            border: 1px solid var(--color-border) !important;
            border-left: none !important;
            border-radius: 0 6px 6px 0 !important;
            height: 34px !important;
            font-size: 0.9rem !important;
            padding-top: 0 !important;
        }

        /* Forzar color de los iconos (tabler icons) dentro del input-group */
        .input-group .ti {
            color: var(--color-primary) !important;
            font-size: 0.95rem;
        }

        /* Forzar layout flex del input-group sin gaps para que peguen los elementos */
        .input-group {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: stretch !important;
        }

        /* Asegurar contenedor Select2 dentro del input-group ocupe el espacio restante */
        .input-group .select2-container--default {
            flex: 1 1 auto !important;
            width: 1% !important; /* Truco de Bootstrap para flex items */
        }

        .input-group .select2-container--default .select2-selection--single {
            display: flex !important;
            align-items: center !important;
            height: 34px !important;
            padding: 0 0.5rem !important;
        }

        .input-group .select2-container--default .select2-selection__rendered {
            padding-left: 0 !important;
            color: var(--color-secondary) !important;
            line-height: 32px !important;
        }

        /* Ajuste de la flecha de select2 en modo compacto */
        .input-group .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px !important;
        }

        /* Reduce overall select input padding for compact layout */
        .form-select {
            height: 34px !important;
            padding: 0.25rem 0.5rem !important;
            font-size: 0.9rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar Select2
            $('.form-select').each(function() {
                const $this = $(this);
                const parent = $this.closest('.input-group');
                $this.select2({
                    width: '100%',
                    placeholder: "{{ __('Seleccione una opción') }}",
                    minimumResultsForSearch: Infinity,
                    dropdownParent: parent.length ? parent : $(document.body)
                });
            });

            // Estado global del modo de selección - RESTAURAR TIPO DE SELECCIÓN
            const selectionTypeFromDB = "{{ $selectionType }}";
            let currentMode = selectionTypeFromDB;
            let selectedDates = [];
            
            // Parsear array de fechas del controlador
            const datesArray = {!! $datesArray !!};
            
            const fp = flatpickr("#datepicker", {
                mode: (currentMode === 'custom' || currentMode === 'weekdays') ? "multiple" : "range",
                dateFormat: "Y-m-d",
                inline: true,
                monthSelectorType: "static",
                locale: "es",
                defaultDate: datesArray,
                prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                onChange: function(selectedDates, dateStr, instance) {
                    if (currentMode === 'custom' || currentMode === 'weekdays') {
                        // En modo custom o weekdays, guardar el formato para múltiples fechas
                        if (selectedDates.length > 0) {
                            const datesArray = selectedDates.map(d => {
                                const year = d.getFullYear();
                                const month = String(d.getMonth() + 1).padStart(2, '0');
                                const day = String(d.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            });
                            // Guardar la lista de fechas separadas por coma
                            document.getElementById('datepicker').value = datesArray.join(',');
                        }
                    }
                }
            });

            // Función auxiliar para actualizar estilos de botones
            function updateButtonStyles(activeMode) {
                if (activeMode === 'range') {
                    document.getElementById('selectRangeBtn').classList.add('btn-primary');
                    document.getElementById('selectRangeBtn').classList.remove('btn-outline-primary');
                    document.getElementById('selectCustomBtn').classList.remove('btn-secondary');
                    document.getElementById('selectCustomBtn').classList.add('btn-outline-secondary');
                    document.getElementById('selectWeekdaysBtn').classList.remove('btn-success');
                    document.getElementById('selectWeekdaysBtn').classList.add('btn-outline-success');
                } else if (activeMode === 'custom') {
                    document.getElementById('selectRangeBtn').classList.remove('btn-primary');
                    document.getElementById('selectRangeBtn').classList.add('btn-outline-primary');
                    document.getElementById('selectCustomBtn').classList.add('btn-secondary');
                    document.getElementById('selectCustomBtn').classList.remove('btn-outline-secondary');
                    document.getElementById('selectWeekdaysBtn').classList.remove('btn-success');
                    document.getElementById('selectWeekdaysBtn').classList.add('btn-outline-success');
                } else if (activeMode === 'weekdays') {
                    document.getElementById('selectRangeBtn').classList.remove('btn-primary');
                    document.getElementById('selectRangeBtn').classList.add('btn-outline-primary');
                    document.getElementById('selectCustomBtn').classList.remove('btn-secondary');
                    document.getElementById('selectCustomBtn').classList.add('btn-outline-secondary');
                    document.getElementById('selectWeekdaysBtn').classList.add('btn-success');
                    document.getElementById('selectWeekdaysBtn').classList.remove('btn-outline-success');
                }
            }

            // Botón: cambiar a modo Rango
            document.getElementById('selectRangeBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                currentMode = 'range';
                document.getElementById('selectionType').value = 'range';
                fp.set('mode', 'range');
                fp.clear();
                
                updateButtonStyles('range');
                
                Swal.fire({
                    title: 'Modo Rango',
                    text: 'Selecciona el primer y último día del rango.',
                    icon: 'info',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Botón: cambiar a modo Selección Libre (custom)
            document.getElementById('selectCustomBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                currentMode = 'custom';
                document.getElementById('selectionType').value = 'custom';
                fp.set('mode', 'multiple');
                fp.clear();
                
                updateButtonStyles('custom');
                
                Swal.fire({
                    title: 'Selección Libre',
                    text: 'Haz clic en los días que desees seleccionar.',
                    icon: 'info',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Botón: seleccionar todos los días laborables del mes actual
            document.getElementById('selectWeekdaysBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                try {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    // Obtener fecha actual del calendario o usar hoy
                    const currentDate = fp.selectedDates[0] || new Date();
                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth();
                    const lastDay = new Date(year, month + 1, 0).getDate();
                    const weekdayDates = [];
                    
                    // Generar todos los días laborables del mes (solo futuros o hoy)
                    for (let d = 1; d <= lastDay; d++) {
                        const date = new Date(year, month, d);
                        date.setHours(0, 0, 0, 0);
                        const dayOfWeek = date.getDay();
                        
                        // Excluir sábados (6), domingos (0) y fechas pasadas
                        if (dayOfWeek !== 0 && dayOfWeek !== 6 && date >= today) {
                            weekdayDates.push(date);
                        }
                    }

                    if (weekdayDates.length > 0) {
                        // Cambiar a modo multiple para mostrar todos los días laborables seleccionados
                        currentMode = 'weekdays';
                        document.getElementById('selectionType').value = 'weekdays';
                        fp.set('mode', 'multiple');
                        
                        // Establecer todos los días laborables como seleccionados
                        fp.setDate(weekdayDates, true);
                        
                        // Crear el formato string para envío (con todas las fechas)
                        const datesArray = weekdayDates.map(d => {
                            const year = d.getFullYear();
                            const month = String(d.getMonth() + 1).padStart(2, '0');
                            const day = String(d.getDate()).padStart(2, '0');
                            return `${year}-${month}-${day}`;
                        });
                        document.getElementById('datepicker').value = datesArray.join(',');
                        
                        updateButtonStyles('weekdays');
                        
                        Swal.fire({
                            title: '¡Días laborables seleccionados!',
                            text: `Se seleccionó un total de ${weekdayDates.length} días laborables del mes.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            title: 'Sin días laborables',
                            text: 'No hay días laborables en el mes seleccionado.',
                            icon: 'warning',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } catch (err) {
                    console.error('Error:', err);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al seleccionar los días laborables.',
                        icon: 'error',
                        timer: 2000
                    });
                }
            });

            // Inicializar botones según el tipo de selección guardado
            updateButtonStyles(selectionTypeFromDB);

            // Si es modo custom o weekdays, actualizar el valor del input (sin prefijo)
            if ((selectionTypeFromDB === 'custom' || selectionTypeFromDB === 'weekdays') && datesArray.length > 0) {
                document.getElementById('datepicker').value = datesArray.join(',');
            }
        });
    </script>
@endpush