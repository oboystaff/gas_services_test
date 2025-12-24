@extends('layout.base')

@section('page-styles')
@endsection

@section('page-content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Sales Cards  -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('invoices.index', ['display' => 'daily']) }}">
                    <div class="card card-hover">
                        <div class="box bg-cyan text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-view-dashboard"></i>
                            </h1>

                            <h6 class="text-white mb-2">Daily Sales Revenue</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['dailySales'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('invoices.index', ['display' => 'weekly']) }}">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-chart-areaspline"></i>
                            </h1>

                            <h6 class="text-white">Weekly Sales Revenue</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['weeklySales'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('invoices.index', ['display' => 'monthly']) }}">
                    <div class="card card-hover">
                        <div class="box bg-warning text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-collage"></i>
                            </h1>

                            <h6 class="text-white">Monthly Sales Revenue</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['monthlySales'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('gas-requests.index', ['display' => 'completed']) }}">
                    <div class="card card-hover">
                        <div class="box bg-danger text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-border-outside"></i>
                            </h1>

                            <h6 class="text-white">Completed Deliveries</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['completedDeliveries'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('payments.index', ['display' => 'weekly']) }}">
                    <div class="card card-hover">
                        <div class="box bg-info text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-arrow-all"></i>
                            </h1>

                            <h6 class="text-white">Weekly Receipts</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['weeklyReceipts'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('payments.index', ['display' => 'monthly']) }}">
                    <div class="card card-hover">
                        <div class="box bg-danger text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-receipt"></i>
                            </h1>

                            <h6 class="text-white">Monthly Receipts</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['monthlyReceipts'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('gas-requests.index', ['display' => 'total_pending']) }}">
                    <div class="card card-hover">
                        <div class="box bg-info text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-relative-scale"></i>
                            </h1>

                            <h6 class="text-white">Total Pending Request</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['totalPendingRequest'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('dashboard.debtors', ['display' => 'receivables']) }}">
                    <div class="card card-hover">
                        <div class="box bg-cyan text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-pencil"></i>
                            </h1>

                            <h6 class="text-white">Total Receivables</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['receivables'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
                <a href="{{ route('customers.index', ['display' => 'all']) }}">
                    <div class="card card-hover">
                        <div class="box bg-success text-center">
                            <h1 class="font-light text-white">
                                <i class="mdi mdi-calendar-check"></i>
                            </h1>

                            <h6 class="text-white">Total Customers</h6>

                            <h2 class="text-white font-bold">
                                {{ $total['totalCustomers'] }}
                            </h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Invoices vs Payments ({{ now()->year }})</h5>
                            <div style="height: 400px; max-width: 1200px; margin: 0 auto;">
                                <canvas id="invoicePaymentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Top 10 Debtors</h5>

                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-white">Customer</th>
                                            <th class="text-white text-end">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($debtors as $debtor)
                                            <tr>
                                                <td>{{ $debtor->name }}</td>
                                                <td class="text-end text-danger">
                                                    {{ number_format($debtor->balance, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">No debtors</td>
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
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barThickness: 30,
                            maxBarThickness: 40
                        },
                        {
                            label: 'Payments',
                            data: paymentData,
                            backgroundColor: 'rgba(75, 192, 192, 0.7)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
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
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
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
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                },
                                font: {
                                    size: 11
                                }
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
