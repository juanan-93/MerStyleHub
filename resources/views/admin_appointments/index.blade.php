@extends('layouts.app', ['title' => __('Appointments Management')])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-calendar me-1"></i>
        {{ __('Appointments Management') }}
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="ti ti-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <a href="{{ route('admin_appointments.create') }}"
                   class="btn"
                   style="background:#A08A7A;border-color:#A08A7A;color:#fff;">
                    <i class="ti ti-plus me-1"></i> {{ __('Create Appointment') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="appointmentsTable" class="table align-middle table-hover mb-0">
                        <thead style="background:#ECE9E2;color:#343434;">
                            <tr>
                                <th>{{ __('Fecha / Rango') }}</th>
                                <th class="d-none d-md-table-cell">{{ __('Tipo') }}</th>
                                <th class="d-none d-lg-table-cell">{{ __('Horario') }}</th>
                                <th class="d-none d-lg-table-cell">{{ __('Categoría') }}</th>
                                <th>{{ __('Duración') }}</th>
                                <th class="text-end" style="width: 140px;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán aquí -->
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
        #appointmentsTable {
            color: #343434;
        }

        #appointmentsTable thead th {
            background-color: #ECE9E2;
            color: #343434;
            font-weight: 600;
            border-color: #D9D4CE;
            padding: 1rem 0.75rem;
        }

        #appointmentsTable tbody tr {
            border-color: #D9D4CE;
            transition: background-color 0.2s ease;
        }

        #appointmentsTable tbody tr:hover {
            background-color: #F5F3F0;
        }

        #appointmentsTable tbody td {
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

            /* Mobile: solo mostrar Name y Actions */
            #appointmentsTable th:nth-child(2),
            #appointmentsTable th:nth-child(3),
            #appointmentsTable td:nth-child(2),
            #appointmentsTable td:nth-child(3) {
                display: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar DataTable
            const table = $('#appointmentsTable').DataTable({
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

            // Datos de ejemplo (reemplazar con datos de la API)
            // Datos reales desde el controlador agrupados por batch_id
            const appointmentsData = @json($availabilities);

            // Mapear los datos para la tabla
            const data = appointmentsData.map(item => {
                const dateDisplay = item.start_date === item.end_date 
                    ? item.start_date 
                    : `Del ${item.start_date} al ${item.end_date} (${item.total_days} días)`;

                // Mostrar el tipo de selección en español
                let typeLabel = 'Rango';
                let typeBadgeColor = 'bg-primary';
                if (item.selection_type === 'custom') {
                    typeLabel = 'Libre';
                    typeBadgeColor = 'bg-warning text-dark';
                } else if (item.selection_type === 'weekdays') {
                    typeLabel = 'Laborables';
                    typeBadgeColor = 'bg-success';
                }

                // Enlace de edición usando route() de Laravel con placeholder para el JS
                let editUrl = '#';
                if (item.batch_id) {
                    editUrl = "{{ route('admin_appointments.edit', ':id') }}".replace(':id', item.batch_id);
                }

                const startTime = item.start_time ? item.start_time.substring(0, 5) : '--:--';
                const endTime = item.end_time ? item.end_time.substring(0, 5) : '--:--';

                return [
                    dateDisplay,
                    `<span class="badge ${typeBadgeColor}">${typeLabel}</span>`,
                    `${startTime} - ${endTime}`,
                    item.category ? item.category.toUpperCase() : 'ESTÁNDAR',
                    item.duration + ' min',
                    `
                        <div class="text-end">
                            <a href="${editUrl}" 
                               class="btn btn-sm btn-outline-secondary rounded-circle ${!item.batch_id ? 'disabled opacity-50' : ''}" 
                               style="${!item.batch_id ? 'pointer-events: none;' : ''}"
                               title="${item.batch_id ? 'Editar' : 'Sin identificador de lote (registro antiguo)'}">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-secondary rounded-circle btn-delete ${!item.batch_id ? 'disabled opacity-50' : ''}" 
                                    style="${!item.batch_id ? 'pointer-events: none;' : ''}"
                                    title="${item.batch_id ? 'Eliminar' : 'Sin identificador de lote (registro antiguo)'}" 
                                    data-batch="${item.batch_id || ''}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    `
                ];
            });

            table.rows.add(data).draw();

            // Manejo de eliminación con SweetAlert2
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-delete')) {
                    const deleteBatchId = e.target.closest('.btn-delete').getAttribute('data-batch');
                    
                    if (!deleteBatchId) {
                        mostrarNotificacion('Error', 'No se puede eliminar este registro (sin identificador)', 'error');
                        return;
                    }

                    // Mostrar confirmación con SweetAlert2
                    Swal.fire({
                        title: '¿Eliminar disponibilidad?',
                        text: 'Esta acción no se puede deshacer. Se eliminarán todos los días del rango seleccionado.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#A08A7A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mostrar loading
                            Swal.fire({
                                title: 'Eliminando...',
                                text: 'Por favor espera',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Realizar petición de eliminación
                            const deleteUrl = `{{ url('admin/appointments/batch') }}/${deleteBatchId}`;
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Eliminado!',
                                        text: 'La disponibilidad ha sido eliminada correctamente',
                                        confirmButtonColor: '#A08A7A',
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'No se pudo eliminar la disponibilidad',
                                        confirmButtonColor: '#A08A7A'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ocurrió un error al procesar la solicitud',
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