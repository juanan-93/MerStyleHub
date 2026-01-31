<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cancelar Cita - MerStyleHub</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        :root {
            --color-base: #ECE9E2;
            --color-white: #FFFFFF;
            --color-primary: #A08A7A;
            --color-primary-dark: #8f7668;
            --color-secondary: #343434;
            --color-light: #F5F3F0;
            --color-border: #D9D4CE;
            --color-text-muted: #6C757D;
            --color-danger: #dc3545;
            --color-danger-dark: #c82333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--color-secondary);
            background-color: var(--color-base);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
        }

        .cancel-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .cancel-card {
            background: var(--color-white);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }

        .cancel-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .cancel-header .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .cancel-header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .cancel-body {
            padding: 30px;
        }

        .appointment-details {
            background: var(--color-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--color-primary);
        }

        .appointment-details .label {
            font-size: 0.75rem;
            color: var(--color-primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-row .icon {
            width: 36px;
            height: 36px;
            background: var(--color-white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .detail-row .info .title {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .detail-row .info .value {
            font-weight: 600;
            color: var(--color-secondary);
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            display: flex;
            gap: 12px;
        }

        .warning-box i {
            color: #856404;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .warning-box p {
            margin: 0;
            font-size: 0.9rem;
            color: #856404;
        }

        .btn-cancel {
            width: 100%;
            padding: 14px 24px;
            background: var(--color-danger);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-cancel:hover {
            background: var(--color-danger-dark);
            transform: translateY(-2px);
        }

        .btn-cancel:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-back {
            width: 100%;
            padding: 14px 24px;
            background: transparent;
            border: 2px solid var(--color-border);
            color: var(--color-secondary);
            font-weight: 500;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 12px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-back:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
        }

        /* Error state */
        .error-state {
            text-align: center;
            padding: 40px 30px;
        }

        .error-state .icon {
            width: 80px;
            height: 80px;
            background: #f8d7da;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .error-state .icon i {
            font-size: 2.5rem;
            color: var(--color-danger);
        }

        .error-state h2 {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .error-state p {
            color: var(--color-text-muted);
            margin-bottom: 25px;
        }

        /* Success state */
        .success-state {
            text-align: center;
            padding: 40px 30px;
            display: none;
        }

        .success-state .icon {
            width: 80px;
            height: 80px;
            background: #d4edda;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .success-state .icon i {
            font-size: 2.5rem;
            color: #28a745;
        }

        .success-state h2 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #28a745;
        }

        .success-state p {
            color: var(--color-text-muted);
            margin-bottom: 25px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 576px) {
            .cancel-container {
                padding: 20px 15px;
            }

            .cancel-header {
                padding: 25px 20px;
            }

            .cancel-body {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="cancel-container">
        <div class="cancel-card">
            <div class="cancel-header">
                <div class="logo">MerStyleHub</div>
                <h1>Cancelar Cita</h1>
            </div>

            @if($error)
                <!-- Error State -->
                <div class="error-state">
                    <div class="icon">
                        <i class="ti ti-calendar-off"></i>
                    </div>
                    <h2>Cita no encontrada</h2>
                    <p>{{ $error }}</p>
                    <a href="{{ route('calendar.index') }}" class="btn-back">
                        Reservar nueva cita
                    </a>
                </div>
            @else
                <!-- Cancel Form -->
                <div class="cancel-body" id="cancel-form">
                    <div class="appointment-details">
                        <div class="label">Detalles de tu cita</div>
                        
                        <div class="detail-row">
                            <div class="icon">📅</div>
                            <div class="info">
                                <div class="title">Fecha</div>
                                <div class="value">{{ \Carbon\Carbon::parse($appointment->date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="icon">🕐</div>
                            <div class="info">
                                <div class="title">Horario</div>
                                <div class="value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}h</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="icon">👤</div>
                            <div class="info">
                                <div class="title">Nombre</div>
                                <div class="value">{{ $appointment->client_name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="warning-box">
                        <i class="ti ti-alert-triangle"></i>
                        <p><strong>¿Estás seguro?</strong> Esta acción no se puede deshacer. Si cancelas tu cita, el horario quedará disponible para otras personas.</p>
                    </div>

                    <button type="button" class="btn-cancel" id="btn-confirm-cancel">
                        <i class="ti ti-calendar-x"></i>
                        Cancelar mi cita
                    </button>

                    <a href="{{ route('calendar.index') }}" class="btn-back">
                        Volver sin cancelar
                    </a>
                </div>

                <!-- Success State -->
                <div class="success-state" id="success-state">
                    <div class="icon">
                        <i class="ti ti-check"></i>
                    </div>
                    <h2>¡Cita cancelada!</h2>
                    <p>Tu cita ha sido cancelada correctamente. Esperamos verte pronto.</p>
                    <a href="{{ route('calendar.index') }}" class="btn-back" style="border-color: var(--color-primary); color: var(--color-primary);">
                        Reservar nueva cita
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnCancel = document.getElementById('btn-confirm-cancel');
            const cancelForm = document.getElementById('cancel-form');
            const successState = document.getElementById('success-state');

            if (btnCancel) {
                btnCancel.addEventListener('click', async function() {
                    const result = await Swal.fire({
                        title: '¿Cancelar cita?',
                        text: 'Esta acción no se puede deshacer',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, cancelar',
                        cancelButtonText: 'No, mantener'
                    });

                    if (result.isConfirmed) {
                        btnCancel.disabled = true;
                        btnCancel.innerHTML = '<div class="spinner"></div> Cancelando...';

                        try {
                            const response = await fetch(window.location.href, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                cancelForm.style.display = 'none';
                                successState.style.display = 'block';
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'No se pudo cancelar la cita'
                                });
                                btnCancel.disabled = false;
                                btnCancel.innerHTML = '<i class="ti ti-calendar-x"></i> Cancelar mi cita';
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la solicitud'
                            });
                            btnCancel.disabled = false;
                            btnCancel.innerHTML = '<i class="ti ti-calendar-x"></i> Cancelar mi cita';
                        }
                    }
                });
            }
        });
    </script>

</body>
</html>
