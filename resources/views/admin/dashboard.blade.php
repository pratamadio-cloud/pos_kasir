@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h3>Dashboard Admin</h3>
        <p class="text-muted">Ringkasan statistik dan aktivitas sistem</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card stats-card-1">
            <div class="card-body">
                <div class="card-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <h5 class="card-title text-muted">Pendapatan Hari Ini</h5>
                <h2 class="fw-bold">RP 4,250,000</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success">
                        <i class="bi bi-arrow-up-right"></i> 12.5%
                    </span>
                    <small>vs Kemarin</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card stats-card-2">
            <div class="card-body">
                <div class="card-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <h5 class="card-title text-muted">Total Transaksi</h5>
                <h2 class="fw-bold">128</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success">
                        <i class="bi bi-arrow-up-right"></i> 8.3%
                    </span>
                    <small>vs Kemarin</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card stats-card-3">
            <div class="card-body">
                <div class="card-icon">
                    <i class="bi bi-cart-check"></i>
                </div>
                <h5 class="card-title text-muted">Produk Terjual</h5>
                <h2 class="fw-bold">542</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success">
                        <i class="bi bi-arrow-up-right"></i> 15.2%
                    </span>
                    <small>vs Kemarin</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card stats-card-4">
            <div class="card-body">
                <div class="card-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h5 class="card-title text-muted">Kasir Aktif</h5>
                <h2 class="fw-bold">3</h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success">
                        <i class="bi bi-check-circle"></i>
                    </span>
                    <small>Online</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body text-center">
                <i class="bi bi-plus-circle text-primary" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="mb-2">Tambah Produk</h5>
                <p class="text-muted small mb-3">Tambah produk baru ke inventori</p>
                <a href="/admin/products" class="btn btn-admin btn-sm d-inline-flex align-items-center justify-content-center"
                   style="background-color: #4361ee; color: white; border: none; min-width: 140px; padding: 8px 20px;">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body text-center">
                <i class="bi bi-printer text-success" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="mb-2">Cetak Laporan</h5>
                <p class="text-muted small mb-3">Cetak laporan harian/bulanan</p>
                <a href="/admin/reports" class="btn btn-admin btn-sm d-inline-flex align-items-center justify-content-center"
                   style="background-color: #0bdb62; color: white; border: none; min-width: 140px; padding: 8px 20px;">
                    <i class="bi bi-printer me-1"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-arrow-down text-warning" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="mb-2">Export Data</h5>
                <p class="text-muted small mb-3">Export data transaksi & produk</p>
                <button class="btn btn-sm fw-bold d-inline-flex align-items-center justify-content-center" onclick="exportData()"
                        style="background-color: #e4b02cff; color: white; border: none; min-width: 140px; padding: 8px 20px;">
                    <i class="bi bi-download me-1"></i>Export Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Transaksi Terbaru
                </h5>
                <a href="/admin/transactions" class="btn btn-admin btn-outline-light btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>INV-2024-00125</strong>
                                    <br><small class="text-muted">#T125</small>
                                </td>
                                <td>{{ date('H:i', strtotime('-2 minutes')) }}</td>
                                <td>
                                    <span class="badge bg-info">Kasir-1</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">5 items</span>
                                </td>
                                <td>
                                    <strong>RP 85,000</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">Tunai</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>INV-2024-00124</strong>
                                    <br><small class="text-muted">#T124</small>
                                </td>
                                <td>{{ date('H:i', strtotime('-5 minutes')) }}</td>
                                <td>
                                    <span class="badge bg-info">Kasir-2</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">3 items</span>
                                </td>
                                <td>
                                    <strong>RP 45,000</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">QRIS</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>INV-2024-00123</strong>
                                    <br><small class="text-muted">#T123</small>
                                </td>
                                <td>{{ date('H:i', strtotime('-15 minutes')) }}</td>
                                <td>
                                    <span class="badge bg-warning">Admin</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">3 items</span>
                                </td>
                                <td>
                                    <strong>RP 40,000</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">Tunai</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts - UKURAN LEBIH KECIL -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Pendapatan 7 Hari Terakhir
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="180"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Metode Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <canvas id="paymentChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Revenue Chart - UKURAN LEBIH KECIL
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['6 hari lalu', '5 hari lalu', '4 hari lalu', '3 hari lalu', '2 hari lalu', 'Kemarin', 'Hari Ini'],
            datasets: [{
                label: 'Pendapatan (RP)',
                data: [3200000, 3500000, 3800000, 4200000, 4100000, 3950000, 4250000],
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'RP ' + (value/1000000).toFixed(1) + ' jt';
                            }
                            return 'RP ' + (value/1000).toFixed(0) + ' rb';
                        },
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 45
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            },
            elements: {
                line: {
                    tension: 0.3
                }
            }
        }
    });

    // Payment Method Chart - UKURAN LEBIH KECIL
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tunai', 'QRIS', 'Transfer Bank'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    '#28a745',
                    '#007bff',
                    '#6f42c1'
                ],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    titleFont: {
                        size: 12
                    },
                    bodyFont: {
                        size: 11
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Export Data Function
    function exportData() {
        const exportOptions = `
            <div class="dropdown-menu show p-2" style="min-width: 200px;">
                <h6 class="dropdown-header">Pilih Data untuk Export</h6>
                <button class="dropdown-item py-2" onclick="exportTransactions()">
                    <i class="bi bi-receipt me-2"></i>Transaksi
                </button>
                <button class="dropdown-item py-2" onclick="exportProducts()">
                    <i class="bi bi-box-seam me-2"></i>Produk
                </button>
                <button class="dropdown-item py-2" onclick="exportReports()">
                    <i class="bi bi-file-earmark-text me-2"></i>Laporan
                </button>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item py-2" onclick="exportAll()">
                    <i class="bi bi-download me-2"></i>Semua Data
                </button>
            </div>
        `;
        
        // Simulasi export
        showAlert('Export data transaksi berhasil!', 'success');
    }

    function exportTransactions() {
        showAlert('Data transaksi berhasil diexport!', 'success');
    }

    function exportProducts() {
        showAlert('Data produk berhasil diexport!', 'success');
    }

    function exportReports() {
        showAlert('Laporan berhasil diexport!', 'success');
    }

    function exportAll() {
        showAlert('Semua data berhasil diexport!', 'success');
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.style.maxWidth = '300px';
        alertDiv.innerHTML = `
            <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle' : 'bi-check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
</script>

<style>
    /* Styling untuk tombol yang lebih kecil */
    .btn-admin.btn-sm {
        padding: 8px 16px;
        font-size: 0.875rem;
        border-radius: 6px;
        max-width: 160px;
    }
    
    /* Styling untuk card Quick Actions yang lebih kecil */
    .dashboard-card .card-body.text-center {
        padding: 1.25rem;
    }
    
    .dashboard-card .card-body.text-center h5 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-card .card-body.text-center p.small {
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 1rem;
    }
    
    /* Styling untuk chart container yang lebih kecil */
    .card-body canvas {
        max-height: 180px;
    }
    
    /* Styling untuk card header yang lebih kecil */
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    /* Ukuran icon lebih kecil */
    .card-body.text-center i {
        font-size: 1.8rem !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-admin.btn-sm {
            max-width: 100%;
        }
        
        .card-body canvas {
            max-height: 160px;
        }
        
        .dashboard-card .card-body.text-center {
            padding: 1rem;
        }
    }
    
    /* Hover effect untuk tombol */
    .btn-admin:hover {
        transform: translateY(-1px);
        transition: transform 0.2s;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection