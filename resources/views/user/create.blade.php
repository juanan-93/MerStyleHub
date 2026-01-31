@extends('layouts.app', ['title' => __('Crear Usuario')])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted">
            <i class="ti ti-users me-1"></i>{{ __('Gestión de Usuarios') }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="ti ti-user-plus me-1"></i>{{ __('Crear Usuario') }}
    </li>
@endsection

@section('content')
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-primary-custom" style="color: var(--color-primary) !important;">
                    <i class="ti ti-user-plus me-2"></i>{{ __('Nuevo Usuario') }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="params-section p-4 rounded-3 border">
                        <div class="row g-4">
                            {{-- SECCIÓN: INFORMACIÓN BÁSICA DEL USUARIO --}}
                            <div class="col-12">
                                <h6 class="form-label-custom small mb-3">
                                    <i class="ti ti-user me-2"></i>{{ __('Información Básica') }}
                                </h6>
                            </div>

                            {{-- Nombre --}}
                            <div class="col-lg-6">
                                <label for="name" class="form-label-custom small mb-2">
                                    {{ __('Nombre') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control border-0 shadow-sm bg-white @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       placeholder="{{ __('Ej: Juan') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Apellido --}}
                            <div class="col-lg-6">
                                <label for="last_name" class="form-label-custom small mb-2">
                                    {{ __('Apellido') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control border-0 shadow-sm bg-white @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       placeholder="{{ __('Ej: Pérez García') }}"
                                       required>
                                @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-12">
                                <label for="email" class="form-label-custom small mb-2">
                                    {{ __('Email') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-mail"></i></span>
                                    <input type="email" 
                                           class="form-control border-0 shadow-sm bg-white @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           placeholder="{{ __('Ej: usuario@ejemplo.com') }}"
                                           required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECCIÓN: INFORMACIÓN PERSONAL --}}
                            <div class="col-12">
                                <hr class="my-3">
                                <h6 class="form-label-custom small mb-3">
                                    <i class="ti ti-id me-2"></i>{{ __('Información Personal') }}
                                </h6>
                            </div>

                            {{-- Teléfono --}}
                            <div class="col-lg-6">
                                <label for="phone" class="form-label-custom small mb-2">
                                    {{ __('Teléfono') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-phone"></i></span>
                                    <input type="text" 
                                           class="form-control border-0 shadow-sm bg-white @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           placeholder="{{ __('Ej: +34 666 777 888') }}">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Edad --}}
                            <div class="col-lg-6">
                                <label for="age" class="form-label-custom small mb-2">
                                    {{ __('Edad') }}
                                </label>
                                <input type="number" 
                                       class="form-control border-0 shadow-sm bg-white @error('age') is-invalid @enderror" 
                                       id="age" 
                                       name="age" 
                                       min="1" 
                                       max="150"
                                       placeholder="{{ __('Ej: 30') }}">
                                @error('age')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ciudad --}}
                            <div class="col-lg-6">
                                <label for="city" class="form-label-custom small mb-2">
                                    {{ __('Ciudad') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-map-pin"></i></span>
                                    <input type="text" 
                                           class="form-control border-0 shadow-sm bg-white @error('city') is-invalid @enderror" 
                                           id="city" 
                                           name="city" 
                                           placeholder="{{ __('Ej: Madrid') }}">
                                </div>
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Profesión --}}
                            <div class="col-lg-6">
                                <label for="profession" class="form-label-custom small mb-2">
                                    {{ __('Profesión') }}
                                </label>
                                <input type="text" 
                                       class="form-control border-0 shadow-sm bg-white @error('profession') is-invalid @enderror" 
                                       id="profession" 
                                       name="profession" 
                                       placeholder="{{ __('Ej: Abogada') }}">
                                @error('profession')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECCIÓN: INFORMACIÓN DE ESTILO Y PRODUCTO --}}
                            <div class="col-12">
                                <hr class="my-3">
                                <h6 class="form-label-custom small mb-3">
                                    <i class="ti ti-palette me-2"></i>{{ __('Estilo y Servicios') }}
                                </h6>
                            </div>

                            {{-- Estilo --}}
                            <div class="col-lg-6">
                                <label for="style" class="form-label-custom small mb-2">
                                    {{ __('Estilo') }}
                                </label>
                                <input type="text" 
                                       class="form-control border-0 shadow-sm bg-white @error('style') is-invalid @enderror" 
                                       id="style" 
                                       name="style" 
                                       placeholder="{{ __('Ej: Clásico, Moderno, Bohemio') }}">
                                @error('style')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Morfología --}}
                            <div class="col-lg-6">
                                <label for="morphology" class="form-label-custom small mb-2">
                                    {{ __('Morfología') }}
                                </label>
                                <input type="text" 
                                       class="form-control border-0 shadow-sm bg-white @error('morphology') is-invalid @enderror" 
                                       id="morphology" 
                                       name="morphology" 
                                       autocomplete="off"
                                       placeholder="{{ __('Ej: Pera, Reloj de Arena, Triángulo') }}">
                                @error('morphology')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Producto --}}
                            <div class="col-lg-6">
                                <label for="product_id" class="form-label-custom small mb-2">
                                    {{ __('Servicio') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="ti ti-shopping-bag"></i></span>
                                    <select class="form-select" 
                                            id="product_id" 
                                            name="product_id">
                                        <option value="">{{ __('Seleccionar servicio') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tipo de Servicio --}}
                            <div class="col-lg-6">
                                <label for="service_type" class="form-label-custom small mb-2">
                                    {{ __('Modalidad del Servicio') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="ti ti-switch-horizontal"></i></span>
                                    <select class="form-select" 
                                            id="service_type" 
                                            name="service_type">
                                        <option value="presencial">{{ __('Presencial') }}</option>
                                        <option value="online">{{ __('Online') }}</option>
                                    </select>
                                </div>
                                @error('service_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Colorimetría --}}
                            <div class="col-lg-6">
                                <label for="colorimetry_id" class="form-label-custom small mb-2">
                                    {{ __('Colorimetría') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text"><i class="ti ti-palette"></i></span>
                                    <select class="form-select" 
                                            id="colorimetry_id" 
                                            name="colorimetry_id">
                                        <option value="">{{ __('Seleccionar colorimetría') }}</option>
                                        @foreach($colorimetries as $colorimetry)
                                            <option value="{{ $colorimetry->id }}">{{ $colorimetry->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('colorimetry_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fecha de llamada --}}
                            <div class="col-lg-6">
                                <label for="phone_call_date" class="form-label-custom small mb-2">
                                    {{ __('Fecha de Llamada') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-calendar"></i></span>
                                    <input type="date" 
                                           class="form-control border-0 shadow-sm bg-white @error('phone_call_date') is-invalid @enderror" 
                                           id="phone_call_date" 
                                           name="phone_call_date">
                                </div>
                                @error('phone_call_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECCIÓN: GESTIÓN COMERCIAL --}}
                            <div class="col-12">
                                <hr class="my-3">
                                <h6 class="form-label-custom small mb-3">
                                    <i class="ti ti-cash me-2"></i>{{ __('Gestión Comercial') }}
                                </h6>
                            </div>

                            {{-- Fecha de pago (Primer pago) --}}
                            <div class="col-lg-6">
                                <label for="payment_date" class="form-label-custom small mb-2">
                                    {{ __('Fecha del Primer Pago') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-calendar"></i></span>
                                    <input type="date" 
                                           class="form-control border-0 shadow-sm bg-white @error('payment_date') is-invalid @enderror" 
                                           id="payment_date" 
                                           name="payment_date">
                                </div>
                                @error('payment_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fecha de finalización del servicio --}}
                            <div class="col-lg-6">
                                <label for="service_completion_date" class="form-label-custom small mb-2">
                                    {{ __('Fecha de Finalización del Servicio') }}
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-calendar"></i></span>
                                    <input type="date" 
                                           class="form-control border-0 shadow-sm bg-white @error('service_completion_date') is-invalid @enderror" 
                                           id="service_completion_date" 
                                           name="service_completion_date">
                                </div>
                                @error('service_completion_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Porcentaje pagado (Select predefinido) --}}
                            <div class="col-lg-6">
                                <label for="percentage_paid" class="form-label-custom small mb-2">
                                    {{ __('Porcentaje Pagado (%)') }}
                                </label>
                                <select id="percentage_paid" name="percentage_paid">
                                    <option value="0.00">0%</option>
                                    <option value="75.00" selected>75%</option>
                                    <option value="100.00">100%</option>
                                </select>
                                @error('percentage_paid')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Porcentaje pendiente (Select predefinido - sincronizado) --}}
                            <div class="col-lg-6">
                                <label for="percentage_pending" class="form-label-custom small mb-2">
                                    {{ __('Porcentaje Pendiente (%)') }}
                                </label>
                                <select id="percentage_pending" name="percentage_pending" disabled>
                                    <option value="25.00" selected>25%</option>
                                    <option value="0.00">0%</option>
                                </select>
                                @error('percentage_pending')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECCIÓN: OBSERVACIONES --}}
                            <div class="col-12">
                                <label for="observations" class="form-label-custom small mb-2">
                                    {{ __('Observaciones') }}
                                </label>
                                <textarea class="form-control border-0 shadow-sm bg-white @error('observations') is-invalid @enderror" 
                                          id="observations" 
                                          name="observations" 

                                          placeholder="{{ __('Notas adicionales sobre el cliente') }}"></textarea>
                                @error('observations')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECCIÓN: CONTRASEÑA --}}
                            <div class="col-12">
                                <hr class="my-3">
                                <h6 class="form-label-custom small mb-3">
                                    <i class="ti ti-lock me-2"></i>{{ __('Credenciales de Acceso') }}
                                </h6>
                            </div>

                            {{-- Contraseña --}}
                            <div class="col-lg-6">
                                <label for="password" class="form-label-custom small mb-2">
                                    {{ __('Contraseña') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-lock"></i></span>
                                    <input type="password" 
                                           class="form-control border-0 shadow-sm bg-white @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password"
                                           autocomplete="new-password"
                                           required>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div class="col-lg-6">
                                <label for="password_confirmation" class="form-label-custom small mb-2">
                                    {{ __('Confirmar Contraseña') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-lock-check"></i></span>
                                    <input type="password" 
                                           class="form-control border-0 shadow-sm bg-white" 
                                           id="password_confirmation" 
                                           name="password_confirmation"
                                           autocomplete="new-password"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary-custom px-5 shadow-sm">
                            <i class="ti ti-check me-2"></i>{{ __('Crear Usuario') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        /* Custom UI Elements aligned with platform */
        .params-section {
            background-color: var(--color-light);
            border: 1px solid var(--color-border) !important;
            border-radius: 12px;
        }

        .form-label-custom {
            color: var(--color-primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .text-primary-custom {
            color: var(--color-primary) !important;
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
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3) !important;
        }

        .btn-outline-secondary {
            color: #343434 !important;
            border-color: #D9D4CE !important;
        }

        .btn-outline-secondary:hover {
            background-color: #343434 !important;
            border-color: #343434 !important;
            color: white !important;
        }

        /* Form control styling */
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: none;
            background-color: white;
        }

        .form-control:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15) !important;
            background-color: #fff !important;
        }

        .form-control.is-invalid {
            border-color: #dc3545 !important;
        }

        .input-group .input-group-text {
            background: var(--color-white) !important;
            border-right: none !important;
            color: var(--color-primary) !important;
            border-radius: 8px 0 0 8px;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Card styles */
        .card {
            border-radius: 12px;
        }

        .card-header {
            background-color: white !important;
            border-bottom: 1px solid var(--color-border);
            border-radius: 12px 12px 0 0;
        }

        /* Select styling */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23A08A7A' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }

        select.form-control:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15) !important;
        }

        /* Section headers styling */
        h6.form-label-custom {
            font-size: 0.9rem;
            color: var(--color-primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Textarea styling */
        textarea.form-control {
            resize: vertical;
            border-radius: 8px;
        }

        textarea.form-control:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15) !important;
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

        .select2-search__field {
            border: none !important;
            border-bottom: 1px solid var(--color-border) !important;
            border-radius: 0;
            padding: 8px 12px !important;
            color: var(--color-secondary) !important;
        }

        .select2-search__field:focus {
            border-color: var(--color-primary) !important;
            outline: none !important;
        }

        /* Input group fixes */
        .input-group .input-group-text {
            background: var(--color-white) !important;
            border-right: none !important;
            color: var(--color-primary) !important;
            border-radius: 8px 0 0 8px;
        }

        .input-group .form-select {
            border-radius: 0 8px 8px 0;
        }

        /* Select2 en input-group */
        .input-group .select2-container {
            flex: 1 !important;
        }

        .input-group .select2-container--default .select2-selection--single {
            border-left: none !important;
            border-radius: 0 8px 8px 0 !important;
        }

        /* Validación Select2 */
        .select2-container--default .select2-selection--single.is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2 para los campos de producto, colorimetría, modalidad y porcentajes
            $('#product_id, #colorimetry_id, #service_type, #percentage_paid, #percentage_pending').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // Sincronizar porcentajes: cuando cambia el porcentaje pagado, actualizar el pendiente
            $('#percentage_paid').on('change', function() {
                const paidPercentage = parseFloat($(this).val());
                const pendingPercentage = 100 - paidPercentage;
                
                // Actualizar el valor del campo pendiente
                $('#percentage_pending').val(pendingPercentage.toFixed(2));
                
                // Actualizar las opciones del select pendiente
                if (paidPercentage === 100) {
                    $('#percentage_pending').html('<option value="0.00" selected>0%</option>');
                } else if (paidPercentage === 75) {
                    $('#percentage_pending').html('<option value="25.00" selected>25%</option>');
                } else if (paidPercentage === 0) {
                    $('#percentage_pending').html('<option value="100.00" selected>100%</option>');
                }
            });
            
            $('#observations').summernote({
                height: 250,
                minHeight: 200,
                maxHeight: 400,
                focus: false,
                placeholder: '{{ __("Notas adicionales sobre el cliente") }}',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['fullscreen']]
                ],
                callbacks: {
                    onChange: function(contents) {
                        $('#observations').val(contents);
                    }
                }
            });
        });
    </script>
@endpush
