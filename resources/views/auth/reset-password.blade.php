<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña - MerStyleHub</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <!-- Animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">

    <style>
        :root {
            --color-base: #ECE9E2;
            --color-white: #FFFFFF;
            --color-primary: #A08A7A;
            --color-secondary: #343434;
        }

        html, body { height: 100%; }

        body {
            background: linear-gradient(135deg, var(--color-base) 0%, #f5f3f0 100%);
            color: var(--color-secondary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }

        .auth-container { width: 100%; max-width: 420px; padding: 20px; }

        .auth-card {
            background: var(--color-white);
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            border: 1px solid rgba(160,138,122,0.1);
        }

        .auth-header {
            text-align: center;
            padding: 2rem 0 1.5rem 0;
            border-bottom: 1px solid rgba(160,138,122,0.1);
        }

        .auth-header img { max-width: 120px; height: auto; margin-bottom: 1rem; }
        .auth-header h2 { color: var(--color-secondary); font-weight: 700; font-size: 1.75rem; margin-bottom: 0.5rem; }
        .auth-header p { color: #999; font-size: 0.9rem; margin: 0; }

        .auth-body { padding: 2rem; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { color: var(--color-secondary); font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; display: block; }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            height: auto;
        }

        .form-control:focus { border-color: var(--color-primary); box-shadow: 0 0 0 0.2rem rgba(160,138,122,0.25); }
        .form-control.is-invalid { border-color: #dc3545; }
        .form-control.is-invalid:focus { box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25); }
        .invalid-feedback { display: block; color: #dc3545; font-size: 0.85rem; margin-top: 0.5rem; }

        .btn-primary-custom {
            background-color: var(--color-primary);
            border: none;
            color: var(--color-white);
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #8f7668;
            color: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(160,138,122,0.3);
        }

        .back-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        .back-link:hover { color: #8f7668; text-decoration: underline; }

        @media (max-width: 576px) {
            .auth-container { padding: 15px; }
            .auth-body { padding: 1.5rem; }
            .auth-header h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body class="animate__animated animate__fadeIn">

    <div class="auth-container">
        <div class="auth-card">

            {{-- Header --}}
            <div class="auth-header">
                <img src="{{ asset('images/logos/logo.png') }}" alt="MerStyleHub Logo">
                <h2>MerStyleHub</h2>
                <p>Establece tu nueva contraseña</p>
            </div>

            {{-- Body --}}
            <div class="auth-body">

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">
                            <i class="ti ti-mail me-1"></i>Email
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               autofocus
                               autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nueva contraseña --}}
                    <div class="form-group">
                        <label for="password">
                            <i class="ti ti-lock me-1"></i>Nueva contraseña
                        </label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="••••••••"
                               required
                               autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="form-group">
                        <label for="password_confirmation">
                            <i class="ti ti-lock-check me-1"></i>Confirmar contraseña
                        </label>
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="••••••••"
                               required
                               autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-primary-custom">
                        <i class="ti ti-key me-2"></i>Restablecer contraseña
                    </button>
                </form>

                {{-- Back to login --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="back-link">
                        <i class="ti ti-arrow-left me-1"></i>Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
