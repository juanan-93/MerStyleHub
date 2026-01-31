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
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-users" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">{{ $dashboardData['totalClientes'] ?? 0 }}</div>
                            <div class="stat-label">Clientes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios -->
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-briefcase" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">{{ $dashboardData['totalServicios'] ?? 0 }}</div>
                            <div class="stat-label">Servicios</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos -->
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background-color: rgba(160, 138, 122, 0.1);">
                                <i class="ti ti-coin" style="font-size: 2rem; color: var(--color-primary);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="stat-number">{{ number_format($dashboardData['totalIngresos'] ?? 0, 2, ',', '.') }}€</div>
                            <div class="stat-label">Ingresos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row mb-4">
        <!-- Gráfica de Ingresos -->
        <div class="col-12 col-lg-8 mb-3">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-chart-line me-2"></i>Ingresos Últimos 12 Meses
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

    <!-- Estado de Pagos de Usuarios -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-users me-2"></i>Estado de Pagos de Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background-color: var(--color-light);">
                                    <th>Cliente</th>
                                    <th>Fecha Alta</th>
                                    <th>Servicio</th>
                                    <th>Precio Servicio</th>
                                    <th>% Pagado</th>
                                    <th>Importe Pagado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dashboardData['userPaymentStatus'] ?? [] as $profile)
                                    @php
                                        $percentPaid = $profile->percentage_paid ?? 0;
                                        // Seleccionar precio según tipo de servicio (presencial/online)
                                        $productPrice = $profile->service_type === 'online' 
                                            ? ($profile->product->price_online ?? 0)
                                            : ($profile->product->price_presencial ?? 0);
                                        $amountPaid = ($percentPaid / 100) * $productPrice;
                                        
                                        // Determinar estado y color
                                        if ($percentPaid >= 100) {
                                            $statusClass = 'bg-success';
                                            $statusText = 'Completado';
                                        } elseif ($percentPaid > 0) {
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Parcial';
                                        } else {
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Pendiente';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 35px; height: 35px; background-color: rgba(160, 138, 122, 0.1);">
                                                    <i class="ti ti-user" style="color: var(--color-primary);"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-medium">{{ $profile->user->name ?? 'N/A' }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $profile->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $profile->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $profile->product->title ?? 'Sin servicio' }}</td>
                                        <td>{{ number_format($productPrice, 2, ',', '.') }}€</td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 80px;">
                                                <div class="progress-bar {{ $percentPaid >= 100 ? 'bg-success' : ($percentPaid > 0 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min($percentPaid, 100) }}%;" 
                                                     aria-valuenow="{{ $percentPaid }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($percentPaid, 0) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($amountPaid, 2, ',', '.') }}€</td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="ti ti-users-minus mb-2" style="font-size: 2rem; color: var(--color-primary);"></i>
                                            <p class="text-muted mb-0">No hay usuarios con servicios contratados</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfica de Ingresos con datos reales
            const ctx = document.getElementById('chartVentas');
            if (ctx) {
                const chartLabels = @json($dashboardData['chartLabels'] ?? []);
                const chartValues = @json($dashboardData['chartValues'] ?? []);
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels.length > 0 ? chartLabels : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        datasets: [{
                            label: 'Ingresos (€)',
                            data: chartValues.length > 0 ? chartValues : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
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
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + '€';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value + '€';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>