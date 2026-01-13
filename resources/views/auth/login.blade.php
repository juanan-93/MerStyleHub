<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar Sesión - MerStyleHub</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- Estilos Personalizados -->
    <style>
        :root {
            --color-base: #ECE9E2;
            --color-white: #FFFFFF;
            --color-primary: #A08A7A;
            --color-secondary: #343434;
        }

        html, body {
            height: 100%;
        }

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

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: var(--color-white);
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(160, 138, 122, 0.1);
        }

        .login-header {
            text-align: center;
            padding: 2rem 0 1.5rem 0;
            border-bottom: 1px solid rgba(160, 138, 122, 0.1);
        }

        .login-header img {
            max-width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }

        .login-header h2 {
            color: var(--color-secondary);
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #999;
            font-size: 0.9rem;
            margin: 0;
        }

        .login-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            color: var(--color-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(160, 138, 122, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .form-check {
            margin: 0;
        }

        .form-check-label {
            color: var(--color-secondary);
            font-weight: 500;
            cursor: pointer;
            user-select: none;
            margin-bottom: 0;
        }

        .forgot-password-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password-link:hover {
            color: #8f7668;
            text-decoration: underline;
        }

        .btn-login {
            background-color: var(--color-primary);
            border: none;
            color: var(--color-white);
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .btn-login:hover {
            background-color: #8f7668;
            color: var(--color-white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #ccc;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #ddd;
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.85rem;
            color: #999;
        }

        .register-footer {
            text-align: center;
            padding-bottom: 2rem;
            color: var(--color-secondary);
        }

        .register-footer p {
            margin: 0 0 0.5rem 0;
            font-size: 0.9rem;
        }

        .register-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link:hover {
            color: #8f7668;
            text-decoration: underline;
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #155724;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #721c24;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .login-form {
                padding: 1.5rem;
            }

            .login-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="animate__animated animate__fadeIn">
    
    <div class="login-container">
        <div class="login-card">
            
            <!-- Encabezado -->
            <div class="login-header">
                <img src="{{ asset('images/logos/logo.png') }}" alt="MerStyleHub Logo">
                <h2>MerStyleHub</h2>
                <p>Inicia sesión en tu cuenta</p>
            </div>

            <!-- Formulario -->
            <div class="login-form">
                
                <!-- Mensaje de Estado -->
                @if (session('status'))
                    <div class="alert alert-success">
                        <i class="ti ti-check me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Formulario de Login -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            <i class="ti ti-mail me-1"></i>Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="correo@ejemplo.com"
                               required 
                               autofocus 
                               autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password">
                            <i class="ti ti-lock me-1"></i>Contraseña
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••"
                               required 
                               autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="login-options">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">
                                Recuérdame
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password-link">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login">
                        <i class="ti ti-login me-2"></i>Iniciar Sesión
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider">
                    <span>o</span>
                </div>

                <!-- Register Link -->
                <div class="register-footer">
                    <p>¿No tienes cuenta?</p>
                    <a href="{{ route('register') }}" class="register-link">
                        <i class="ti ti-user-plus me-1"></i>Crea una aquí
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Animación de formulario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Aquí puedes agregar validaciones adicionales si lo deseas
                });
            }
        });
    </script>
</body>
</html>
