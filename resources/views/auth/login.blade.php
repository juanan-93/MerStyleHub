<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MerStyleHub</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #ECE9E2;">
    <div class="container-fluid">
        <div class="row min-vh-100 align-items-center justify-content-center px-3 py-4">
            <div class="col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4">
                <div class="bg-white rounded-3 shadow-sm p-3 p-sm-4">
                    
                    <!-- Logo -->
                    <div class="text-center mb-3 mb-sm-4">
                        <img src="{{ asset('images/logos/logo.png') }}" alt="MerStyleHub Logo" class="img-fluid" style="max-width: 150px;">
                    </div>

                    <h4 class="text-center mb-3 mb-sm-4" style="color: #343434;">Iniciar Sesión</h4>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Formulario de Login -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium small" style="color: #343434;">Email</label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="tu@email.com"
                                   required 
                                   autofocus 
                                   autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium small" style="color: #343434;">Contraseña</label>
                            <input type="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label small" for="remember_me" style="color: #343434;">
                                    Recuérdame
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none small" style="color: #A08A7A;">
                                    ¿Olvidaste?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-lg" style="background-color: #A08A7A; border-color: #A08A7A; color: white;">
                                Iniciar Sesión
                            </button>
                        </div>

                        <!-- Divider -->
                        <hr class="my-3" style="border-color: #A08A7A; opacity: 0.2;">

                        <!-- Register Link -->
                        <div class="text-center">
                            <span class="small" style="color: #343434;">¿No tienes cuenta?</span>
                            <a href="{{ route('register') }}" class="text-decoration-none small fw-medium ms-1" style="color: #A08A7A;">Regístrate aquí</a>
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
