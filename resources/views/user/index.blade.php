@extends('layouts.app', ['title' => __('Gestión de Usuarios')])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-users me-1"></i>
        {{ __('Gestión de Usuarios') }}
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
                <h5 class="mb-0 fw-bold text-secondary">
                    <i class="ti ti-users me-2"></i>{{ __('Listado de Usuarios') }}
                </h5>
                <a href="{{ route('users.create') }}"
                   class="btn btn-primary-custom shadow-sm px-4">
                    <i class="ti ti-user-plus me-1"></i> {{ __('Crear Usuario') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Nombre') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Rol') }}</th>
                                <th class="d-none d-lg-table-cell">{{ __('Creado') }}</th>
                                <th class="text-end" style="width: 140px;">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @php
                                    $roles = $user->getRoleNames();
                                    $role = $roles->first();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 36px; height: 36px; background: #ECE9E2; color: #343434; flex-shrink: 0;">
                                                <span class="fw-semibold small">
                                                    {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="fw-semibold text-primary-custom">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $user->email }}</span>
                                    </td>
                                    <td>
                                        @if ($role === 'admin')
                                            <span class="badge rounded-pill" style="background: #343434; color: white;">
                                                {{ $role }}
                                            </span>
                                        @elseif ($role === 'worker')
                                            <span class="badge rounded-pill" style="background: #A08A7A; color: white;">
                                                {{ $role }}
                                            </span>
                                        @elseif ($role === 'customer')
                                            <span class="badge rounded-pill" style="background: #ECE9E2; color: #343434;">
                                                {{ $role }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ $role ?? '—' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <span class="text-muted small">{{ optional($user->created_at)->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="text-end">
                                            <a href="#" 
                                               class="btn btn-sm btn-outline-secondary rounded-circle" 
                                               title="Ver">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="#" 
                                               class="btn btn-sm btn-outline-secondary rounded-circle" 
                                               title="Editar">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($users->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="ti ti-inbox me-2"></i>{{ __('No hay usuarios creados aún') }}
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
        #usersTable {
            color: #343434;
        }

        #usersTable thead th {
            background-color: #ECE9E2;
            color: #343434;
            font-weight: 600;
            border-color: #D9D4CE;
            padding: 1rem 0.75rem;
        }

        #usersTable tbody tr {
            border-color: #D9D4CE;
            transition: background-color 0.2s ease;
        }

        #usersTable tbody tr:hover {
            background-color: #F5F3F0;
        }

        #usersTable tbody td {
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

        .text-primary-custom {
            color: var(--color-primary) !important;
        }

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

        .text-secondary {
            color: var(--color-secondary) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si la tabla tiene datos antes de inicializar DataTables
            const tableBody = document.querySelector('#usersTable tbody');
            const hasData = tableBody.querySelectorAll('tr:not(:has(td[colspan]))').length > 0;

            if (hasData) {
                // Inicializar DataTables solo si hay datos
                const table = $('#usersTable').DataTable({
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
        });
    </script>
@endpush
