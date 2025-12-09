@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <h3>Laporan Transaksi</h3>
        <p class="text-muted">Lihat dan analisis data transaksi</p>
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
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Periode</label>
                        <select class="form-select form-select-sm" id="reportPeriod">
                            <option value="today">Hari Ini</option>
                            <option value="yesterday">Kemarin</option>
                            <option value="week" selected>Minggu Ini</option>
                            <option value="month">Bulan Ini</option>
                            <option value="custom">Kustom</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="customDateRange" style="display: none;">
                        <label class="form-label small mb-1">Dari Tanggal</label>
                        <input type="date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3" id="customDateRange2" style="display: none;">
                        <label class="form-label small mb-1">Sampai Tanggal</label>
                        <input type="date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-light btn-admin btn-sm w-100" id="generateReport">
                            <i class="bi bi-arrow-clockwise me-1"></i>Generate
                        </button>
                    </div>
                </div>
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
                                <th class="pe-3">Aksi</th> <!-- PERBAIKAN DI SINI -->
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 10; $i++)
                            @php
                                $cashiers = ['Kasir-1', 'Kasir-2', 'Kasir-3', 'Admin'];
                                $cashier = $cashiers[array_rand($cashiers)];
                                $methods = [
                                    ['bg-success', 'Tunai'],
                                    ['bg-primary', 'QRIS'],
                                    ['bg-dark', 'Transfer']
                                ];
                                $method = $methods[array_rand($methods)];
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <strong class="small">INV-2024-00{{ 130 - $i }}</strong>
                                    <br><small class="text-muted">#T{{ 130 - $i }}</small>
                                </td>
                                <td>
                                    <span class="small">{{ date('d/m/Y', strtotime("-$i days")) }}</span>
                                    <br><small class="text-muted">{{ date('H:i', strtotime("+$i hours")) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $cashier }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ rand(1, 8) }} items</span>
                                </td>
                                <td>
                                    <strong class="small">RP {{ number_format(rand(10000, 200000), 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $method[0] }}">{{ $method[1] }}</span>
                                </td>
                                <td class="pe-3">
                                    <button class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="bi bi-eye small"></i>
                                    </button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-2">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#">‹</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">›</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
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
                            <h6 class="mb-0">1,248</h6>
                            <small class="text-success ms-1">
                                <i class="bi bi-arrow-up-right"></i> 8.3%
                            </small>
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
                            <h6 class="mb-0">42.5 Jt</h6>
                            <small class="text-success ms-1">
                                <i class="bi bi-arrow-up-right"></i> 12.5%
                            </small>
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
                            <h6 class="mb-0">5,420</h6>
                            <small class="text-success ms-1">
                                <i class="bi bi-arrow-up-right"></i> 15.2%
                            </small>
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
                            <h6 class="mb-0">34,118</h6>
                            <small class="text-success ms-1">
                                <i class="bi bi-arrow-up-right"></i> 3.8%
                            </small>
                        </div>
                    </div>
                </div>
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
        salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Pendapatan (RP)',
                    data: [4200000, 3800000, 4500000, 5200000, 4800000, 5500000, 4250000],
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
                                return 'RP ' + (value/1000000).toFixed(1) + ' jt';
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
                    }
                }
            }
        });

        // Payment Chart - Transfer warna biru hitam (#212529)
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        paymentChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tunai', 'QRIS', 'Transfer'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: [
                        '#28a745',    // Hijau untuk Tunai
                        '#007bff',    // Biru untuk QRIS
                        '#212529'     // Biru Hitam untuk Transfer
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
        } else {
            customRange.style.display = 'none';
            customRange2.style.display = 'none';
        }
    });

    // Generate report
    document.getElementById('generateReport').addEventListener('click', function() {
        const period = document.getElementById('reportPeriod').value;
        
        // Simulate loading
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Loading...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert(`Laporan untuk periode ${period} berhasil dibuat!`);
        }, 1500);
    });

    // Report type buttons functionality
    document.querySelectorAll('.btn-report-type').forEach(button => {
        button.addEventListener('click', function() {
            const reportType = this.getAttribute('data-type');
            const periodSelect = document.getElementById('reportPeriod');
            
            switch(reportType) {
                case 'daily':
                    periodSelect.value = 'today';
                    break;
                case 'weekly':
                    periodSelect.value = 'week';
                    break;
                case 'monthly':
                    periodSelect.value = 'month';
                    break;
            }
            
            // Trigger change event to update custom date display
            periodSelect.dispatchEvent(new Event('change'));
            
            // Simulate generating report
            document.getElementById('generateReport').click();
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