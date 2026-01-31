@extends('layouts.app', ['title' => __('Gestión de Productos')])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-package me-1"></i>
        {{ __('Gestión de Productos') }}
    </li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="ti ti-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">

                <a href="{{ route('products.create') }}"
                   class="btn btn-primary-custom shadow-sm px-4">
                    <i class="ti ti-plus me-1"></i> {{ __('Crear Producto') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="productsTable" class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Título') }}</th>
                                <th>{{ __('Descripción') }}</th>
                                <th>{{ __('Precio Presencial') }}</th>
                                <th>{{ __('Precio Online') }}</th>
                                <th class="text-end" style="width: 140px;">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-primary-custom">{{ $product->title }}</div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ Str::limit(strip_tags($product->description), 60) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><i class="ti ti-home text-muted me-1"></i>{{ number_format($product->price_presencial, 2, ',', '.') }}€</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><i class="ti ti-device-laptop text-muted me-1"></i>{{ number_format($product->price_online, 2, ',', '.') }}€</span>
                                    </td>
                                    <td>
                                        <div class="text-end">
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="btn btn-sm btn-outline-secondary rounded-circle" 
                                               title="Editar">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary rounded-circle btn-delete" 
                                                    data-id="{{ $product->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($products->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="ti ti-inbox me-2"></i>{{ __('No hay productos creados aún') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        /* Tabla */
        #productsTable {
            color: #343434;
        }

        #productsTable thead th {
            background-color: #ECE9E2;
            color: #343434;
            font-weight: 600;
            border-color: #D9D4CE;
            padding: 1rem 0.75rem;
        }

        #productsTable tbody tr {
            border-color: #D9D4CE;
            transition: background-color 0.2s ease;
        }

        #productsTable tbody tr:hover {
            background-color: #F5F3F0;
        }

        #productsTable tbody td {
            color: #343434;
            padding: 1rem 0.75rem;
        }

        /* Botones de acción */
        .btn-outline-secondary {
            color: #343434 !important;
            border-color: #D9D4CE !important;
        }

        .btn-outline-secondary:hover {
            background-color: #343434 !important;
            border-color: #343434 !important;
            color: white !important;
        }

        /* DataTables wrapper */
        .dataTables_wrapper {
            color: #343434;
            font-size: 0.95rem;
        }

        /* Controles (búsqueda y registros por página) */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .table-controls .dataTables_length,
        .table-controls .dataTables_filter {
            margin: 0;
            float: none !important;
        }

        .dataTables_length label,
        .dataTables_filter label {
            display: flex !important;
            align-items: center;
            gap: 0.5rem;
            color: #343434;
            font-size: 0.95rem;
            margin: 0;
            font-weight: normal;
        }

        .dataTables_length select,
        .dataTables_filter input {
            border: 1px solid #D9D4CE !important;
            border-radius: 0.5rem;
            color: #343434;
            background-color: white;
            font-size: 0.95rem;
        }

        .dataTables_length select:focus,
        .dataTables_filter input:focus {
            border-color: #A08A7A !important;
            box-shadow: 0 0 0 0.2rem rgba(160, 138, 122, 0.25) !important;
            outline: none;
        }

        .dataTables_filter {
            text-align: right;
        }

        /* Footer (info y paginación) */
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            padding-top: 1rem;
        }

        .table-footer .dataTables_info {
            margin: 0;
            padding: 0;
            font-size: 0.95rem;
        }

        .table-footer .dataTables_paginate {
            margin: 0 !important;
            padding: 0;
            text-align: right;
        }

        /* Paginación Bootstrap 5 */
        .dataTables_paginate .pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .dataTables_paginate .page-item .page-link {
            background-color: #ECE9E2;
            border-color: #D9D4CE;
            color: #343434;
            padding: 0.5rem 0.75rem;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .dataTables_paginate .page-item:not(.disabled) .page-link:hover {
            background-color: #A08A7A;
            border-color: #A08A7A;
            color: white;
        }

        .dataTables_paginate .page-item.active .page-link {
            background-color: #A08A7A;
            border-color: #A08A7A;
            color: white;
            font-weight: 600;
        }

        .dataTables_paginate .page-item.disabled .page-link {
            background-color: #F5F5F5;
            border-color: #E5E5E5;
            color: #999;
            opacity: 0.6;
        }

        @media (max-width: 576px) {
            .table-controls,
            .table-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .dataTables_filter {
                width: 100%;
                text-align: left;
            }

            .table-footer .dataTables_paginate {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si la tabla tiene datos antes de inicializar DataTables
            const tableBody = document.querySelector('#productsTable tbody');
            const hasData = tableBody.querySelectorAll('tr:not(:has(td[colspan]))').length > 0;

            if (hasData) {
                // Inicializar DataTables solo si hay datos
                const table = $('#productsTable').DataTable({
                    responsive: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                    },
                    columnDefs: [
                        {
                            targets: -1,
                            orderable: false,
                            searchable: false
                        }
                    ],
                    pageLength: 10,
                    dom: '<"table-controls"lf><"table-content"rt><"table-footer"ip>',
                    paging: true,
                    pagingType: "simple_numbers",
                    processing: false,
                    serverSide: false
                });
            }

            // Manejo de eliminación con SweetAlert2
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-delete')) {
                    const productId = e.target.closest('.btn-delete').getAttribute('data-id');
                    
                    Swal.fire({
                        title: '¿Eliminar producto?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#A08A7A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Hacer petición DELETE
                            fetch('{{ route('products.destroy', ':id') }}'.replace(':id', productId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Eliminado!',
                                        text: 'El producto ha sido eliminado correctamente.',
                                        confirmButtonColor: '#A08A7A',
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al eliminar el producto.',
                                    confirmButtonColor: '#A08A7A'
                                });
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush


