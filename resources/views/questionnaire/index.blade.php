@extends('layouts.app', ['title' => __('Gestión de Cuestionarios')])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-clipboard-list me-1"></i>
        {{ __('Gestión de Cuestionarios') }}
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
                <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                    <i class="ti ti-clipboard-list me-2" style="color: var(--color-primary);"></i>{{ __('Cuestionarios') }}
                </h5>
                <a href="{{ route('questionnaire.create') }}"
                   class="btn btn-primary-custom shadow-sm px-4">
                    <i class="ti ti-plus me-1"></i> {{ __('Crear Cuestionario') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="questionnairesTable" class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Título') }}</th>
                                <th>{{ __('Descripción') }}</th>
                                <th class="text-center">{{ __('Preguntas') }}</th>
                                <th class="text-center">{{ __('Estado') }}</th>
                                <th class="text-end" style="width: 140px;">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questionnaires as $questionnaire)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-primary-custom">{{ $questionnaire->title }}</div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ Str::limit($questionnaire->description, 60) ?: 'Sin descripción' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">
                                            {{ $questionnaire->questions_count }} {{ $questionnaire->questions_count == 1 ? 'pregunta' : 'preguntas' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($questionnaire->status === 'active')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-end">
                                            <a href="{{ route('questionnaire.assign', $questionnaire->id) }}" 
                                               class="btn btn-sm btn-outline-secondary rounded-circle" 
                                               title="Asignar usuarios"
                                               data-bs-toggle="tooltip">
                                                <i class="ti ti-users"></i>
                                            </a>
                                            <a href="{{ route('questionnaire.edit', $questionnaire->id) }}" 
                                               class="btn btn-sm btn-outline-secondary rounded-circle" 
                                               title="Editar"
                                               data-bs-toggle="tooltip">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary rounded-circle btn-delete" 
                                                    data-id="{{ $questionnaire->id }}"
                                                    title="Eliminar"
                                                    data-bs-toggle="tooltip">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- La tabla vacía será manejada por DataTables --}}
                            @endforelse
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
        #questionnairesTable {
            color: #343434;
        }

        #questionnairesTable thead th {
            background-color: #ECE9E2;
            color: #343434;
            font-weight: 600;
            border-color: #D9D4CE;
            padding: 1rem 0.75rem;
        }

        #questionnairesTable tbody tr {
            border-color: #D9D4CE;
            transition: background-color 0.2s ease;
        }

        #questionnairesTable tbody tr:hover {
            background-color: #F5F3F0;
        }

        #questionnairesTable tbody td {
            color: #343434;
            padding: 1rem 0.75rem;
        }

        /* Texto primario personalizado */
        .text-primary-custom {
            color: #A08A7A !important;
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
            const table = document.querySelector('#questionnairesTable');
            if (!table) return;

            const tbody = table.querySelector('tbody');
            const rows = tbody ? tbody.querySelectorAll('tr:not(.dataTables_empty)') : [];
            
            // Inicializar DataTables
            const dataTable = $('#questionnairesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                    emptyTable: '<i class="ti ti-inbox me-2"></i>No hay cuestionarios creados aún',
                    zeroRecords: '<i class="ti ti-search-off me-2"></i>No se encontraron resultados'
                },
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                order: [[0, 'asc']],
                dom: '<"table-controls"lf>rt<"table-footer"ip>',
                columnDefs: [
                    { orderable: false, targets: -1 } // Desactivar ordenación en la columna de acciones
                ]
            });

            // Manejar eliminación de cuestionarios
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    
                    Swal.fire({
                        title: '¿Eliminar cuestionario?',
                        text: 'Esta acción no se puede deshacer. Se eliminarán también todas las preguntas asociadas.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="ti ti-trash me-1"></i> Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Crear formulario para enviar petición DELETE
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/questionnaire/${id}`;
                            form.innerHTML = `
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush