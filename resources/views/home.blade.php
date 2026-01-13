

@extends('layouts.app', ['title' => 'Dashboard'])

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        <i class="ti ti-home me-1"></i>Dashboard
    </li>
@endsection

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, var(--color-white) 0%, var(--color-light) 100%);
    }
    
    .stat-card .stat-number {
        color: var(--color-primary);
        font-weight: 700;
        font-size: 2rem;
    }
    
    .stat-card .stat-label {
        color: var(--color-secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')

    <!-- Encabezado -->
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1" style="color: var(--color-secondary);">
            Bienvenido, {{ Auth::user()->name }}
        </h1>
        <p class="text-muted">Aquí está el resumen de tu plataforma</p>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <!-- Clientes -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-users" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Clientes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-package" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Productos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-shopping-cart" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">0</div>
                            <div class="stat-label">Pedidos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos -->
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-coin" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">$0</div>
                            <div class="stat-label">Ingresos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row mb-4">
        <!-- Gráfica de Ventas -->
        <div class="col-12 col-lg-8 mb-3">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-chart-line me-2"></i>Ventas Este Mes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartVentas" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Información -->
        <div class="col-12 col-lg-4 mb-3">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-info-circle me-2"></i>Información
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Bienvenido a MerStyleHub, tu plataforma de gestión completa.</p>
                    
                    <div class="mb-3">
                        <small class="text-muted">Versión</small>
                        <p class="mb-0">1.0.0</p>
                    </div>
                    
                    <div>
                        <small class="text-muted">Última actualización</small>
                        <p class="mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Últimos Pedidos -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-history me-2"></i>Últimos Pedidos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background-color: var(--color-light);">
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted mb-0">No hay pedidos aún</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gráfica de Ventas
        const ctx = document.getElementById('chartVentas');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Ventas',
                        data: [12, 19, 3, 5, 2, 3, 8],
                        borderColor: '#A08A7A',
                        backgroundColor: 'rgba(160, 138, 122, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#A08A7A',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
