@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <h3>Laporan Transaksi</h3>
        <p class="text-muted">Lihat dan analisis data transaksi</p>
    </div>
</div>

<!-- Compact Summary Cards -->
<div class="row mb-3 g-2">
    <div class="col-md-3 col-6">
        <div class="card dashboard-card h-100">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 p-1 rounded">
                            <i class="bi bi-cart-check text-primary" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <small class="text-muted">Total Transaksi</small>
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">{{ number_format($summary['total_transactions']) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card dashboard-card h-100">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-1 rounded">
                            <i class="bi bi-currency-dollar text-success" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <small class="text-muted">Total Pendapatan</small>
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">RP {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card dashboard-card h-100">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 p-1 rounded">
                            <i class="bi bi-box-seam text-warning" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <small class="text-muted">Produk Terjual</small>
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">{{ number_format($summary['total_items_sold']) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card dashboard-card h-100">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 p-1 rounded">
                            <i class="bi bi-graph-up-arrow text-info" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <small class="text-muted">Rata-rata/Transaksi</small>
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">RP {{ number_format($summary['average_per_transaction'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Type Buttons -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card dashboard-card">
            <div class="card-body p-2">
                <div class="row g-2 text-center">
                    <div class="col-md-4">
                        <div class="p-2 border rounded">
                            <i class="bi bi-cash-stack text-primary" style="font-size: 1rem;"></i>
                            <h6 class="mb-0 mt-1">Laporan Harian</h6>
                            <p class="text-muted small mb-1">Ringkasan harian</p>
                            <button class="btn btn-outline-primary btn-sm btn-report-type" data-type="daily">
                                Lihat
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded">
                            <i class="bi bi-calendar-week text-success" style="font-size: 1rem;"></i>
                            <h6 class="mb-0 mt-1">Laporan Mingguan</h6>
                            <p class="text-muted small mb-1">Analisis mingguan</p>
                            <button class="btn btn-outline-success btn-sm btn-report-type" data-type="weekly">
                                Lihat
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded">
                            <i class="bi bi-calendar-month text-warning" style="font-size: 1rem;"></i>
                            <h6 class="mb-0 mt-1">Laporan Bulanan</h6>
                            <p class="text-muted small mb-1">Laporan bulanan</p>
                            <button class="btn btn-outline-warning btn-sm btn-report-type" data-type="monthly">
                                Lihat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card dashboard-card">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.reports') }}" id="reportForm">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Periode</label>
                            <select class="form-select form-select-sm" name="period" id="reportPeriod">
                                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Kustom</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="customDateRange" style="{{ $period == 'custom' ? 'display: block;' : 'display: none;' }}">
                            <label class="form-label small mb-1">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="start_date" 
                                   value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3" id="customDateRange2" style="{{ $period == 'custom' ? 'display: block;' : 'display: none;' }}">
                            <label class="form-label small mb-1">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="end_date"
                                   value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-light btn-admin btn-sm w-100" id="generateReport">
                                <i class="bi bi-arrow-clockwise me-1"></i>Generate
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h5 class="mb-0" style="font-size: 1rem;">
                    <i class="bi bi-receipt me-1"></i>Data Transaksi
                    <small class="text-muted ms-2">
                        ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
                    </small>
                </h5>
                <div>
                    <button class="btn btn-outline-secondary btn-sm me-1" onclick="window.print()">
                        <i class="bi bi-printer"></i>
                    </button>
                    <button class="btn btn-outline-light btn-admin btn-sm">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                   
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Invoice</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th class="pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="ps-3">
                                    <strong class="small">{{ $transaction->invoice_no }}</strong>
                                    <br><small class="text-muted">#{{ $transaction->id }}</small>
                                </td>
                                <td>
                                    <span class="small">{{ $transaction->created_at->format('d/m/Y') }}</span>
                                    <br><small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $transaction->cashier->name ?? 'Kasir' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $transaction->items->sum('qty') }} items</span>
                                </td>
                                <td>
                                    <strong class="small">RP {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $methodColors = [
                                            'cash' => 'bg-success',
                                            'qris' => 'bg-info',
                                            'transfer' => 'bg-primary'
                                        ];
                                        $methodLabels = [
                                            'cash' => 'Tunai',
                                            'qris' => 'QRIS',
                                            'transfer' => 'Transfer'
                                        ];
                                    @endphp
                                    <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-secondary' }}">
                                        {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                                    </span>
                                </td>
                                <td class="pe-3">
                                    <a href="{{ route('admin.transactions-detail', $transaction->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">Tidak ada data transaksi pada periode ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                <div class="p-2">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            {{-- Previous Page Link --}}
                            @if($transactions->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $transactions->previousPageUrl() }}">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @for($i = 1; $i <= $transactions->lastPage(); $i++)
                                <li class="page-item {{ $transactions->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $transactions->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Next Page Link --}}
                            @if($transactions->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $transactions->nextPageUrl() }}">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts - SAME HEIGHT -->
<div class="row g-2">
    <div class="col-md-8">
        <div class="card dashboard-card h-100">
            <div class="card-header py-2 px-3">
                <h5 class="mb-0" style="font-size: 0.95rem;">Trend Penjualan</h5>
                <small class="text-muted">
                    @if($period == 'week')
                        Minggu Ini
                    @elseif($period == 'month')
                        Bulan Ini
                    @else
                        Periode {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                    @endif
                </small>
            </div>
            <div class="card-body p-2" style="min-height: 180px;">
                <canvas id="salesChart" height="130"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card h-100">
            <div class="card-header py-2 px-3">
                <h5 class="mb-0" style="font-size: 0.95rem;">Metode Pembayaran</h5>
                <small class="text-muted">Distribusi berdasarkan metode</small>
            </div>
            <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 180px;">
                <canvas id="paymentChart" height="130"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Charts
    let salesChart, paymentChart;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesLabels = @json(collect($salesChartData)->pluck('label'));
        const salesData = @json(collect($salesChartData)->pluck('revenue'));
        
        salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Pendapatan (RP)',
                    data: salesData,
                    backgroundColor: '#4361ee',
                    borderColor: '#3f37c9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'RP ' + (value/1000000).toFixed(1) + ' jt';
                                } else if (value >= 1000) {
                                    return 'RP ' + (value/1000).toFixed(0) + ' rb';
                                } else {
                                    return 'RP ' + value;
                                }
                            },
                            font: {
                                size: 9
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'RP ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Payment Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentLabels = @json($paymentChartData['labels'] ?? []);
        const paymentCounts = @json($paymentChartData['counts'] ?? []);
        
        paymentChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentCounts,
                    backgroundColor: [
                        '#28a745',    // Hijau untuk Tunai
                        '#00FFFF',    // Biru untuk QRIS
                        '#0000FF'     // Biru Hitam untuk Transfer
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 8,
                            usePointStyle: true,
                            font: {
                                size: 9
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = paymentCounts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((context.raw / total) * 100) : 0;
                                return `${context.label}: ${context.raw} transaksi (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '50%'
            }
        });
    });

    // Custom date range toggle
    document.getElementById('reportPeriod').addEventListener('change', function() {
        const customRange = document.getElementById('customDateRange');
        const customRange2 = document.getElementById('customDateRange2');
        
        if (this.value === 'custom') {
            customRange.style.display = 'block';
            customRange2.style.display = 'block';
            
            // Set default dates jika kosong
            const today = new Date().toISOString().split('T')[0];
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            const weekAgoStr = weekAgo.toISOString().split('T')[0];
            
            if (!customRange.querySelector('input').value) {
                customRange.querySelector('input').value = weekAgoStr;
            }
            if (!customRange2.querySelector('input').value) {
                customRange2.querySelector('input').value = today;
            }
        } else {
            customRange.style.display = 'none';
            customRange2.style.display = 'none';
            
            // Auto submit saat pilih periode non-custom
            document.getElementById('reportForm').submit();
        }
    });

    // Report type buttons functionality
    document.querySelectorAll('.btn-report-type').forEach(button => {
        button.addEventListener('click', function() {
            const reportType = this.getAttribute('data-type');
            let url = '';
            
            switch(reportType) {
                case 'daily':
                    url = "{{ route('admin.reports.daily') }}";
                    break;
                case 'weekly':
                    url = "{{ route('admin.reports.weekly') }}";
                    break;
                case 'monthly':
                    url = "{{ route('admin.reports.monthly') }}";
                    break;
            }
            
            if (url) {
                window.location.href = url;
            }
        });
    });

    // Auto submit saat date input berubah
    document.querySelectorAll('#customDateRange input, #customDateRange2 input').forEach(input => {
        input.addEventListener('change', function() {
            if (document.getElementById('reportPeriod').value === 'custom') {
                document.getElementById('reportForm').submit();
            }
        });
    });
</script>

<style>
.card.dashboard-card {
    border: 1px solid #e0e0e0;
    font-size: 0.85rem;
}

.card.dashboard-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 0.5rem 1rem;
}

.card.dashboard-card .card-body {
    padding: 0.75rem;
}

.table-sm th,
.table-sm td {
    padding: 0.5rem;
    font-size: 0.85rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

.form-control-sm, .form-select-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

h6 {
    font-size: 1rem;
    font-weight: 600;
}

.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

/* Make everything more compact */
.mb-3 {
    margin-bottom: 1rem !important;
}

.mt-1 {
    margin-top: 0.25rem !important;
}

.mb-1 {
    margin-bottom: 0.25rem !important;
}

.ms-1 {
    margin-left: 0.25rem !important;
}

.ms-2 {
    margin-left: 0.5rem !important;
}

/* Charts equal height */
.row.g-2 .card {
    display: flex;
    flex-direction: column;
}

.row.g-2 .card-body {
    flex: 1;
}

/* Smaller chart styling */
canvas {
    max-height: 130px;
    width: 100% !important;
}

/* Transfer badge color - Biru Hitam */
.bg-dark {
    background-color: #212529 !important;
}
</style>
@endsection