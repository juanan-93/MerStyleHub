<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - MerStyleHub</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/png">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    <body style="background-color: #ECE9E2;">
        <div class="container">
            <div class="row min-vh-100 align-items-center justify-content-center py-5">
                <div class="col-md-6 col-lg-5">
                    <div class="bg-white rounded-4 shadow p-4 p-md-5">
                        
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logos/logo.png') }}" alt="MerStyleHub Logo" class="img-fluid" style="max-width: 200px;">
                        </div>

                        <!-- Formulario de Registro -->
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium" style="color: #343434;">Nombre</label>
                                <input type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium" style="color: #343434;">Email</label>
                                <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium" style="color: #343434;">Contraseña</label>
                                <input type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-medium" style="color: #343434;">Confirmar Contraseña</label>
                                <input type="password" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Already Registered & Submit -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                                <a href="{{ route('login') }}" class="text-decoration-none small" style="color: #A08A7A;">
                                    ¿Ya tienes cuenta?
                                </a>
                                <button type="submit" class="btn px-4" style="background-color: #A08A7A; border-color: #A08A7A; color: white;">
                                    Registrarse
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
