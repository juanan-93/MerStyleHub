@extends('layouts.app', ['title' => __('Crear Producto')])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted">
            <i class="ti ti-package me-1"></i>{{ __('Productos') }}
        </a>
    </li>
    <li class="breadcrumb-item active">
        <i class="ti ti-plus me-1"></i>{{ __('Crear Producto') }}
    </li>
@endsection

@section('content')
<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-primary-custom" style="color: var(--color-primary) !important;">
                    <i class="ti ti-package-plus me-2"></i>{{ __('Nuevo Producto') }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="params-section p-4 rounded-3 border">
                        <div class="row g-4">
                            <!-- Título del Producto -->
                            <div class="col-12">
                                <label for="title" class="form-label-custom small mb-2">
                                    {{ __('Título del Producto') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control border-0 shadow-sm bg-white" 
                                       id="title" 
                                       name="title" 
                                       placeholder="{{ __('Ej:Servicio ha crear...') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="col-md-6">
                                <label for="price" class="form-label-custom small mb-2">
                                    {{ __('Precio (€)') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-white shadow-none"><i class="ti ti-currency-euro"></i></span>
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control border-0 shadow-none" 
                                           id="price" 
                                           name="price" 
                                           placeholder="0.00" 
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label for="description" class="form-label-custom small mb-2">
                                    {{ __('Descripción Detallada') }}
                                </label>
                                <textarea class="form-control border-0 shadow-sm bg-white" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          placeholder="{{ __('Escribe una descripción que ayude a tus clientes a conocer mejor el producto...') }}"></textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="btn btn-primary-custom px-5 shadow-sm">
                            <i class="ti ti-check me-2"></i>{{ __('Guardar Producto') }}
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
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #8B7669;
            border-color: #8B7669;
            color: var(--color-white);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3) !important;
        }

        /* Form control styling */
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(160, 138, 122, 0.15);
            background-color: #fff !important;
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

        /* Summernote Custom Styles */
        .note-editor.note-frame {
            border: none !important;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
            background-color: #fff;
            border-radius: 12px !important;
            overflow: hidden;
        }

        .note-toolbar {
            background-color: #EDEBE6 !important; /* Color ligeramente más oscuro para contraste */
            border-bottom: 1px solid var(--color-border) !important;
            padding: 10px 14px !important;
        }

        .note-btn {
            background: #FFFFFF !important;
            border: 1px solid var(--color-border) !important;
            color: var(--color-secondary) !important;
            margin: 2px !important;
            padding: 5px 10px !important;
            font-size: 13px !important;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
        }

        .note-btn:hover {
            border-color: var(--color-primary) !important;
            color: var(--color-primary) !important;
            background-color: #fff !important;
        }

        .note-btn.active {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: white !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#description').summernote({
                placeholder: '{{ __('Escribe una descripción que ayude a tus clientes a conocer mejor el producto...') }}',
                tabsize: 2,
                height: 250,
                lang: 'es-ES',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onInit: function() {
                        $('.note-editable').css('background-color', '#fff');
                    }
                }
            });
        });
    </script>
@endpush
