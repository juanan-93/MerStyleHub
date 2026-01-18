

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

        .booking-container {
            width: 100%;
            max-width: 800px;
            background: var(--white);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            padding: 3rem;
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

        /* Calendar & Time */
        .selection-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
            align-items: start;
        }

        @media (min-width: 768px) {
            .selection-grid {
                grid-template-columns: 1.2fr 0.8fr;
            }
        }

        .inline-calendar-wrapper {
            background: #fdfcfb;
            border-radius: 20px;
            padding: 15px;
            border: 1px solid var(--border);
        }

        .flatpickr-calendar {
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            width: 100% !important;
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

        .time-selection {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .time-slot {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
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

        .btn-confirm {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 1.25rem;
            border-radius: 16px;
            font-weight: 700;
            width: 100%;
            margin-top: 3rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-confirm:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
            
            <div class="time-selection">
                <div>
                    <p class="small fw-bold text-uppercase text-muted mb-3"><i class="ti ti-sun me-1"></i> {{ __('Mañana') }}</p>
                    <div class="time-slots">
                        <div class="time-slot">09:00</div>
                        <div class="time-slot">10:00</div>
                        <div class="time-slot">11:00</div>
                        <div class="time-slot">12:00</div>
                    </div>
                </div>

                <div>
                    <p class="small fw-bold text-uppercase text-muted mb-3"><i class="ti ti-moon me-1"></i> {{ __('Tarde') }}</p>
                    <div class="time-slots">
                        <div class="time-slot">16:00</div>
                        <div class="time-slot">17:00</div>
                        <div class="time-slot">18:00</div>
                        <div class="time-slot">19:00</div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn-confirm">
            {{ __('Confirmar Selección') }} 
            <i class="ti ti-check fs-5"></i>
        </button>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Calendario
        const fp = flatpickr("#calendar", {
            inline: true,
            locale: "es",
            minDate: "today",
            dateFormat: "d M Y",
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('summary-date').innerText = dateStr;
            }
        });

        // Selección de Horas
        const slots = document.querySelectorAll('.time-slot');
        slots.forEach(slot => {
            slot.addEventListener('click', () => {
                slots.forEach(s => s.classList.remove('selected'));
                slot.classList.add('selected');
            });
        });
    });
</script>

</body>
</html>