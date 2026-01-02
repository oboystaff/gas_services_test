@extends('layout.base')

@section('page-styles')
    <link href="{{ asset('assets/dist/css/dashboard.css?t=' . time()) }}" rel="stylesheet" />
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row g-3">
            <!-- Daily Sales Revenue -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('invoices.index', ['display' => 'daily']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon cyan">
                                <i class="mdi mdi-view-dashboard text-white"></i>
                            </div>
                            <div class="stats-label">Daily Sales Revenue</div>
                            <h2 class="stats-value">{{ $total['dailySales'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Weekly Sales Revenue -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('invoices.index', ['display' => 'weekly']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon success">
                                <i class="mdi mdi-chart-areaspline text-white"></i>
                            </div>
                            <div class="stats-label">Weekly Sales Revenue</div>
                            <h2 class="stats-value">{{ $total['weeklySales'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Monthly Sales Revenue -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('invoices.index', ['display' => 'monthly']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon warning">
                                <i class="mdi mdi-collage text-white"></i>
                            </div>
                            <div class="stats-label">Monthly Sales Revenue</div>
                            <h2 class="stats-value">{{ $total['monthlySales'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Completed Deliveries -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('gas-requests.index', ['display' => 'completed']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon danger">
                                <i class="mdi mdi-border-outside text-white"></i>
                            </div>
                            <div class="stats-label">Completed Deliveries</div>
                            <h2 class="stats-value">{{ $total['completedDeliveries'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Weekly Receipts -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('payments.index', ['display' => 'weekly']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon info">
                                <i class="mdi mdi-arrow-all text-white"></i>
                            </div>
                            <div class="stats-label">Weekly Receipts</div>
                            <h2 class="stats-value">{{ $total['weeklyReceipts'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Monthly Receipts -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('payments.index', ['display' => 'monthly']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon purple">
                                <i class="mdi mdi-receipt text-white"></i>
                            </div>
                            <div class="stats-label">Monthly Receipts</div>
                            <h2 class="stats-value">{{ $total['monthlyReceipts'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Pending Request -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('gas-requests.index', ['display' => 'total_pending']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon orange">
                                <i class="mdi mdi-relative-scale text-white"></i>
                            </div>
                            <div class="stats-label">Total Pending Request</div>
                            <h2 class="stats-value">{{ $total['totalPendingRequest'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Receivables -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('dashboard.debtors', ['display' => 'receivables']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon teal">
                                <i class="mdi mdi-pencil text-white"></i>
                            </div>
                            <div class="stats-label">Total Receivables</div>
                            <h2 class="stats-value">{{ $total['receivables'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Customers -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('customers.index', ['display' => 'all']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon cyan">
                                <i class="mdi mdi-account text-white"></i>
                            </div>
                            <div class="stats-label">Total Customers</div>
                            <h2 class="stats-value">{{ $total['totalCustomers'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Due Dates -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{ route('due-dates.index', ['display' => 'all']) }}" style="text-decoration: none;">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="stats-icon danger">
                                <i class="mdi mdi-calendar-check text-white"></i>
                            </div>
                            <div class="stats-label">Total Invoices Due</div>
                            <h2 class="stats-value">{{ $total['totalDueDates'] }}</h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-3 mt-2">
            <div class="col-md-8">
                <div class="chart-card">
                    <div class="card-body">
                        <h5 class="card-title">Invoices vs Payments ({{ now()->year }})</h5>
                        <div style="height: 400px; max-width: 1200px; margin: 0 auto;">
                            <canvas id="invoicePaymentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="table-card">
                    <div class="card-body">
                        <h5 class="card-title">Top 10 Debtors</h5>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-white">Customer</th>
                                        <th class="text-white text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($debtors as $debtor)
                                        <tr>
                                            <td>{{ $debtor->name }}</td>
                                            <td class="text-end text-danger fw-bold">
                                                {{ number_format($debtor->balance, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No debtors</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('invoicePaymentChart');

            if (!ctx) {
                console.error('Canvas element not found');
                return;
            }

            const monthsData = @json($months);
            const invoiceData = monthsData.map(item => parseFloat(item.invoice) || 0);
            const paymentData = monthsData.map(item => parseFloat(item.payment) || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ],
                    datasets: [{
                            label: 'Invoices',
                            data: invoiceData,
                            backgroundColor: 'rgba(102, 126, 234, 0.8)',
                            borderColor: 'rgba(102, 126, 234, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barThickness: 30,
                            maxBarThickness: 40
                        },
                        {
                            label: 'Payments',
                            data: paymentData,
                            backgroundColor: 'rgba(245, 87, 108, 0.8)',
                            borderColor: 'rgba(245, 87, 108, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barThickness: 30,
                            maxBarThickness: 40
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2.5,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            titleFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y.toLocaleString();
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6c757d'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                },
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6c757d'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    }
                }
            });
        });
    </script>
@endsection
