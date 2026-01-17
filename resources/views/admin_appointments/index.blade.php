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
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center">
                <div>
                    <h5 class="mb-0" style="color:#343434;">{{ __('Appointment Availability') }}</h5>
                    <small style="color:#A08A7A;">
                        {{ __('Manage your appointment availability') }}
                    </small>
                </div>

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
                                <th>{{ __('Name') }}</th>
                                <th class="d-none d-md-table-cell">{{ __('Duration') }}</th>
                                <th class="d-none d-lg-table-cell">{{ __('Created') }}</th>
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

<!-- Modal de confirmación -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteConfirmLabel" style="color: var(--color-secondary);">{{ __('Delete Appointment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: var(--color-secondary);">
                {{ __('Are you sure you want to delete this appointment availability? This action cannot be undone.') }}
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">{{ __('Delete') }}</button>
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
            const appointmentsData = [
                {
                    id: 1,
                    name: 'Consulta General',
                    duration: '30 minutos',
                    created: '15 ene 2026'
                },
                {
                    id: 2,
                    name: 'Revisión Completa',
                    duration: '60 minutos',
                    created: '14 ene 2026'
                },
                {
                    id: 3,
                    name: 'Seguimiento',
                    duration: '45 minutos',
                    created: '10 ene 2026'
                },
                {
                    id: 4,
                    name: 'Consulta Rápida',
                    duration: '15 minutos',
                    created: '13 ene 2026'
                },
                {
                    id: 5,
                    name: 'Evaluación Inicial',
                    duration: '90 minutos',
                    created: '12 ene 2026'
                },
                {
                    id: 6,
                    name: 'Control Post-Operatorio',
                    duration: '30 minutos',
                    created: '11 ene 2026'
                },
                {
                    id: 7,
                    name: 'Asesoría Estética',
                    duration: '45 minutos',
                    created: '09 ene 2026'
                },
                {
                    id: 8,
                    name: 'Tratamiento Facial',
                    duration: '60 minutos',
                    created: '08 ene 2026'
                },
                {
                    id: 9,
                    name: 'Limpieza Profunda',
                    duration: '45 minutos',
                    created: '07 ene 2026'
                },
                {
                    id: 10,
                    name: 'Consulta Dermatológica',
                    duration: '30 minutos',
                    created: '06 ene 2026'
                },
                {
                    id: 11,
                    name: 'Peeling Químico',
                    duration: '60 minutos',
                    created: '05 ene 2026'
                },
                {
                    id: 12,
                    name: 'Masaje Terapéutico',
                    duration: '75 minutos',
                    created: '04 ene 2026'
                },
                {
                    id: 13,
                    name: 'Microdermoabrasión',
                    duration: '45 minutos',
                    created: '03 ene 2026'
                },
                {
                    id: 14,
                    name: 'Inyección de Botox',
                    duration: '30 minutos',
                    created: '02 ene 2026'
                },
                {
                    id: 15,
                    name: 'Rellenos Dérmicos',
                    duration: '45 minutos',
                    created: '01 ene 2026'
                }
            ];

            // Llenar tabla con datos
            const data = appointmentsData.map(appointment => [
                appointment.name,
                appointment.duration,
                appointment.created,
                `
                    <div class="text-end">
                        <a href="/admin/appointments/${appointment.id}/edit" class="btn btn-sm btn-outline-secondary rounded-circle" title="Editar">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle btn-delete" title="Eliminar" data-id="${appointment.id}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                `
            ]);

            table.rows.add(data).draw();

            // Manejo de eliminación
            let deleteId = null;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));

            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-delete')) {
                    deleteId = e.target.closest('.btn-delete').getAttribute('data-id');
                    deleteModal.show();
                }
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (deleteId) {
                    mostrarNotificacion('Eliminado', 'La cita ha sido eliminada correctamente', 'success');
                    deleteModal.hide();
                    // Aquí iría la llamada AJAX para eliminar
                }
            });
        });
    </script>
@endpush