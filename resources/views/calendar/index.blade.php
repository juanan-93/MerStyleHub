<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita - MerStyleHub</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --primary: #A08A7A;
            --secondary: #343434;
            --base: #ECE9E2;
            --white: #FFFFFF;
            --border: #D9D4CE;
            --text-muted: #6C757D;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--base);
            color: var(--secondary);
            margin: 0;
            padding: 0;
        }

        .booking-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        @media (max-width: 767px) {
            .booking-page {
                padding: 1rem 0.5rem;
                align-items: flex-start;
                padding-top: 2rem;
            }
        }

        .booking-container {
            width: 100%;
            max-width: 800px;
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            padding: 3rem;
        }

        @media (max-width: 767px) {
            .booking-container {
                padding: 1.5rem 1rem;
                margin: 0;
                border-radius: 16px;
                max-width: 100%;
            }
        }

        .section-title {
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--secondary);
            text-align: center;
        }

        .section-subtitle {
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1rem;
        }

        @media (max-width: 767px) {
            .section-subtitle {
                margin-bottom: 2rem;
                font-size: 0.95rem;
            }
        }

        /* Calendar & Time */
        .selection-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 767px) {
            .selection-grid {
                gap: 1.5rem;
            }
        }

        @media (min-width: 768px) {
            .selection-grid {
                grid-template-columns: 1.2fr 0.8fr;
                gap: 3rem;
                align-items: stretch;
            }
        }

        .inline-calendar-wrapper {
            background: #fdfcfb;
            border-radius: 20px;
            padding: 15px;
            border: 1px solid var(--border);
            display: flex;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .inline-calendar-wrapper {
                height: 100%;
            }
        }

        @media (max-width: 767px) {
            .inline-calendar-wrapper {
                padding: 12px;
                border-radius: 16px;
                margin: 0;
            }
        }

        .flatpickr-calendar {
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            width: 100% !important;
        }

        @media (max-width: 767px) {
            .flatpickr-calendar {
                margin: 0 auto !important;
                width: 280px !important;
            }
            
            .flatpickr-months {
                justify-content: center !important;
            }
            
            .flatpickr-weekdays {
                width: 100% !important;
                display: flex !important;
                justify-content: space-between !important;
            }
            
            .flatpickr-weekday {
                flex: 1 !important;
                text-align: center !important;
            }
            
            .flatpickr-days {
                width: 100% !important;
            }
            
            .dayContainer {
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: flex-start !important;
            }
            
            .flatpickr-day {
                flex: 0 0 14.28% !important;
                max-width: 14.28% !important;
                height: 32px !important;
                line-height: 32px !important;
                margin: 0 !important;
                text-align: center !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
        }

        .flatpickr-months .flatpickr-month {
            color: var(--secondary) !important;
            fill: var(--secondary) !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
        }

        .flatpickr-day.selected {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        /* Dot para días con disponibilidad */
        .flatpickr-day.has-availability { position: relative; }
        .flatpickr-day.has-availability::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--primary);
            border-radius: 50%;
        }

        .time-selection {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-height: 400px;
            overflow-y: auto;
        }

        @media (min-width: 768px) {
            .time-selection {
                max-height: 320px;
                min-height: 200px;
            }
        }

        @media (max-width: 767px) {
            .time-selection {
                max-height: 280px;
                gap: 1.2rem;
            }
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        @media (max-width: 767px) {
            .time-slots {
                grid-template-columns: repeat(3, 1fr);
                gap: 6px;
                justify-items: center;
            }
        }

        .time-slot {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 10px 8px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 767px) {
            .time-slot {
                padding: 8px 6px;
                font-size: 0.8rem;
                min-height: 36px;
                border-radius: 8px;
                border-width: 1px;
                width: 100%;
                max-width: 70px;
            }
        }

        .time-slot:hover {
            border-color: var(--primary);
            color: var(--primary);
            background-color: #fcfcfb;
        }

        .time-slot.selected {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3);
        }

        /* Formulario de datos - Oculto, se usa solo el modal */
        #client-form {
            display: none !important;
        }

        /* Modal para todos los dispositivos */
        .mobile-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .mobile-modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 450px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        @media (max-width: 767px) {
            .modal-content {
                max-width: 90%;
                padding: 1.5rem;
            }
        }

        .modal-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h5 {
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .modal-close:hover {
            background-color: var(--base);
        }

        .modal-form .form-control {
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
        }

        .modal-form .form-control:focus {
            border-color: var(--primary);
            box-shadow: none;
            outline: none;
        }

        .btn-confirm {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 1.25rem;
            border-radius: 16px;
            font-weight: 700;
            width: 100%;
            margin-top: 2rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 767px) {
            .btn-confirm {
                margin-top: 1rem;
                padding: 1rem;
                font-size: 0.9rem;
            }
        }

        .btn-confirm:hover:not(:disabled) {
            background-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-confirm:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Estilo ocultar días otros meses */
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            visibility: hidden !important;
        }
    </style>
</head>
<body>

<div class="booking-page">
    <div class="booking-container">
        <h2 class="section-title">{{ __('Reserva tu Cita') }}</h2>
        <p class="section-subtitle">{{ __('Elige el día y la hora que mejor te convenga') }}</p>
        
        <div class="selection-grid">
            <div class="inline-calendar-wrapper">
                <div id="calendar"></div>
            </div>
            
            <div class="time-selection" id="time-selection-container">
                <div id="morning-section" style="display: none;">
                    <p class="small fw-bold text-uppercase text-muted mb-3"><i class="ti ti-sun me-1"></i> {{ __('Mañana') }}</p>
                    <div class="time-slots" id="slots-morning"></div>
                </div>

                <div id="afternoon-section" style="display: none;">
                    <p class="small fw-bold text-uppercase text-muted mb-3"><i class="ti ti-moon me-1"></i> {{ __('Tarde') }}</p>
                    <div class="time-slots" id="slots-afternoon"></div>
                </div>

                <div id="no-slots-msg" class="text-center text-muted py-4">
                    {{ __('Selecciona una fecha') }}
                </div>
            </div>
        </div>

        <form id="client-form" class="row g-3">
            @csrf
            <div class="col-12"><h5 class="fw-bold mb-2">{{ __('Tus Datos') }}</h5></div>
            <div class="col-12">
                <input type="text" name="client_name" class="form-control" placeholder="Nombre completo *" required>
            </div>
            <div class="col-md-6">
                <input type="email" name="client_email" class="form-control" placeholder="Email *" required>
            </div>
            <div class="col-md-6">
                <input type="tel" name="client_phone" class="form-control" placeholder="Teléfono *" required>
            </div>
        </form>
    </div>
</div>

<!-- Modal para confirmación de cita -->
<div class="mobile-modal" id="mobile-modal">
    <div class="modal-content">
        <button class="modal-close" id="modal-close">&times;</button>
        <div class="modal-header">
            <h5 class="fw-bold mb-2">{{ __('Completar Reserva') }}</h5>
            <p class="text-muted small mb-0" id="selected-time-info">{{ __('Información de la cita') }}</p>
        </div>
        <form id="mobile-client-form" class="modal-form">
            @csrf
            <input type="text" name="client_name" class="form-control" placeholder="Nombre completo *" required>
            <input type="email" name="client_email" class="form-control" placeholder="Email *" required>
            <input type="tel" name="client_phone" class="form-control" placeholder="Teléfono *" required>
            <button type="button" class="btn-confirm" id="mobile-btn-confirm">
                {{ __('Confirmar Reserva') }} 
                <i class="ti ti-check fs-5"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const slotsMorning = document.getElementById('slots-morning');
        const slotsAfternoon = document.getElementById('slots-afternoon');
        const morningSec = document.getElementById('morning-section');
        const afternoonSec = document.getElementById('afternoon-section');
        const noSlotsMsg = document.getElementById('no-slots-msg');
        const mobileModal = document.getElementById('mobile-modal');
        const mobileClientForm = document.getElementById('mobile-client-form');
        const mobileBtnConfirm = document.getElementById('mobile-btn-confirm');
        const modalClose = document.getElementById('modal-close');
        const selectedTimeInfo = document.getElementById('selected-time-info');

        let selectedDate = null;
        let selectedSlot = null;
        let availableDates = [];

        // 1. Cargar fechas
        try {
            const res = await fetch('/calendar/available-dates');
            availableDates = await res.json();
        } catch (e) {}

        // 2. Calendario
        const fp = flatpickr("#calendar", {
            inline: true,
            locale: "es",
            minDate: "today",
            dateFormat: "Y-m-d",
            monthSelectorType: "static",
            prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
            nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
            onDayCreate: (dObj, dStr, fp, dayElem) => {
                const date = dayElem.dateObj;
                const dateStr = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
                if (availableDates.includes(dateStr)) dayElem.classList.add('has-availability');
            },
            onChange: (selectedDates, dateStr) => {
                selectedDate = dateStr;
                selectedSlot = null;
                loadSlots(dateStr);
            }
        });

        // 3. Cargar Horas
        async function loadSlots(date) {
            noSlotsMsg.innerHTML = '<span class="loading-spinner"></span>Cargando horarios...';
            morningSec.style.display = afternoonSec.style.display = 'none';

            try {
                const res = await fetch(`/calendar/available-slots/${date}`);
                const slots = await res.json();
                
                slotsMorning.innerHTML = '';
                slotsAfternoon.innerHTML = '';

                if (slots.length === 0) {
                    noSlotsMsg.innerHTML = 'No hay horarios disponibles';
                    return;
                }

                noSlotsMsg.style.display = 'none';
                
                // Separar slots por período para mejor rendimiento
                const morningSlots = slots.filter(slot => parseInt(slot.start.split(':')[0]) < 14);
                const afternoonSlots = slots.filter(slot => parseInt(slot.start.split(':')[0]) >= 14);
                
                // Renderizar mañana
                if (morningSlots.length > 0) {
                    morningSlots.forEach(slot => {
                        const el = createSlotElement(slot);
                        slotsMorning.appendChild(el);
                    });
                    morningSec.style.display = 'block';
                }
                
                // Renderizar tarde
                if (afternoonSlots.length > 0) {
                    afternoonSlots.forEach(slot => {
                        const el = createSlotElement(slot);
                        slotsAfternoon.appendChild(el);
                    });
                    afternoonSec.style.display = 'block';
                }
            } catch (e) { 
                noSlotsMsg.innerHTML = 'Error al cargar horarios'; 
                console.error('Error loading slots:', e);
            }
        }

        // Función helper para crear elementos de slot
        function createSlotElement(slot) {
            const el = document.createElement('div');
            el.className = 'time-slot';
            el.textContent = slot.start;
            el.onclick = () => {
                document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                el.classList.add('selected');
                selectedSlot = slot;
                
                const dateFormatted = new Date(selectedDate).toLocaleDateString('es-ES', {
                    weekday: 'long',
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric'
                });
                
                // Siempre mostrar modal independientemente del dispositivo
                selectedTimeInfo.textContent = `${dateFormatted} a las ${slot.start}`;
                mobileModal.classList.add('show');
            };
            return el;
        }

        // 4. Modal handlers
        modalClose.onclick = () => {
            mobileModal.classList.remove('show');
        };

        mobileModal.onclick = (e) => {
            if (e.target === mobileModal) {
                mobileModal.classList.remove('show');
            }
        };

        // 5. Función para confirmar reserva
        async function confirmBooking(form, button) {
            const formData = new FormData(form);
            if(!formData.get('client_name') || !formData.get('client_email')) {
                return Swal.fire('Error', 'Completa los campos', 'error');
            }

            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = 'Reservando...';

            try {
                const res = await fetch('/calendar/book', {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({
                        availability_id: selectedSlot.availability_id,
                        date: selectedDate,
                        start_time: selectedSlot.start,
                        client_name: formData.get('client_name'),
                        client_email: formData.get('client_email'),
                        client_phone: formData.get('client_phone')
                    })
                });
                const data = await res.json();
                
                if(data.success) {
                    mobileModal.classList.remove('show');
                    Swal.fire('¡Reservado!', 'Te esperamos.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            } catch (e) {
                button.disabled = false;
                button.innerHTML = originalText;
                Swal.fire('Error', 'Ocurrió un error al procesar la reserva', 'error');
            }
        }

        // 6. Event listener para confirmación (solo modal)
        mobileBtnConfirm.onclick = () => confirmBooking(mobileClientForm, mobileBtnConfirm);
    });
</script>

</body>
</html>