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
        <div class="card stats-card shadow-sm border-0" style="border-top: 4px solid #667eea;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon-wrapper bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-cash-coin text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Pendapatan Hari Ini</h6>
                        <h4 class="fw-bold mb-0">RP {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
                        <div class="mt-2 d-flex align-items-center">
                            <small class="text-muted me-2">vs Kemarin</small>
                            <span class="badge bg-light text-{{ $revenueChange >= 0 ? 'success' : 'danger' }} d-flex align-items-center" style="font-size: 12px; padding: 2px 8px;">
                                <i class="bi bi-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}-right me-1"></i>
                                {{ abs(round($revenueChange, 1)) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card shadow-sm border-0" style="border-top: 4px solid #f093fb;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon-wrapper bg-light-pink rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-receipt text-pink fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Total Transaksi</h6>
                        <h4 class="fw-bold mb-0">{{ $todayTransactions }}</h4>
                        <div class="mt-2 d-flex align-items-center">
                            <small class="text-muted me-2">vs Kemarin</small>
                            <span class="badge bg-light text-{{ $transactionChange >= 0 ? 'success' : 'danger' }} d-flex align-items-center" style="font-size: 12px; padding: 2px 8px;">
                                <i class="bi bi-arrow-{{ $transactionChange >= 0 ? 'up' : 'down' }}-right me-1"></i>
                                {{ abs(round($transactionChange, 1)) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card shadow-sm border-0" style="border-top: 4px solid #4facfe;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon-wrapper bg-light-blue rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-cart-check text-blue fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Produk Terjual</h6>
                        <h4 class="fw-bold mb-0">{{ $todayItemsSold }}</h4>
                        <div class="mt-2 d-flex align-items-center">
                            <small class="text-muted me-2">vs Kemarin</small>
                            <span class="badge bg-light text-{{ $itemsChange >= 0 ? 'success' : 'danger' }} d-flex align-items-center" style="font-size: 12px; padding: 2px 8px;">
                                <i class="bi bi-arrow-{{ $itemsChange >= 0 ? 'up' : 'down' }}-right me-1"></i>
                                {{ abs(round($itemsChange, 1)) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card shadow-sm border-0" style="border-top: 4px solid #43e97b;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="icon-wrapper bg-light-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-box-seam text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Total Produk</h6>
                        <h4 class="fw-bold mb-0">{{ $totalProducts }}</h4>
                        <div class="mt-2 d-flex align-items-center">
                            <small class="text-muted me-2">vs Kemarin</small>
                            <span class="badge bg-light text-success d-flex align-items-center" style="font-size: 12px; padding: 2px 8px;">
                                <i class="bi bi-arrow-up-right me-1"></i> 0%
                            </span>
                        </div>
                    </div>
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
                <a href="{{ route('admin.products.index') }}" class="btn btn-admin btn-sm d-inline-flex align-items-center justify-content-center"
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
                <a href="{{ route('admin.reports') }}" class="btn btn-admin btn-sm d-inline-flex align-items-center justify-content-center"
                   style="background-color: #0bdb62; color: white; border: none; min-width: 140px; padding: 8px 20px;">
                    <i class="bi bi-printer me-1"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body text-center">
                <i class="bi bi-clock-history text-warning" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <h5 class="mb-2">Riwayat Transaksi</h5>
                <p class="text-muted small mb-3">Lihat semua transaksi</p>
                <a href="{{ route('admin.transactions') }}" class="btn btn-admin btn-sm d-inline-flex align-items-center justify-content-center"
                   style="background-color: #e4b02c; color: white; border: none; min-width: 140px; padding: 8px 20px;">
                    <i class="bi bi-clock-history me-1"></i>Lihat Transaksi
                </a>
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
                    <i class="bi bi-clock-history me-2"></i>Transaksi Hari Ini
                </h5>
                @if($recentTransactions->count() > 0)
                <a href="#" class="btn btn-admin btn-outline-light btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                </a>
                @endif
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
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
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <strong>{{ $transaction->transaction_code }}</strong>
                                    <br><small class="text-muted">#{{ $transaction->id }}</small>
                                </td>
                                <td>{{ $transaction->created_at->format('H:i') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $transaction->cashier->name ?? 'Admin' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $transaction->items->count() }} items</span>
                                </td>
                                <td>
                                  <strong>RP {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $paymentColors = [
                                            'cash' => 'success',
                                            'qris' => 'info',
                                            'transfer' => 'primary'
                                        ];
                                        $method = $paymentColors[$transaction->payment_method] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $method }}">
                                        {{ strtoupper($transaction->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                     <a href="{{ route('admin.transactions-detail', $transaction->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-receipt display-6 text-muted"></i>
                    <p class="mt-2 text-muted">Belum ada transaksi hari ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
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
                    <i class="bi bi-pie-chart me-2"></i>Metode Pembayaran Hari Ini
                </h5>
            </div>
            <div class="card-body">
                @if(count($paymentData) > 0)
                <canvas id="paymentChart" height="180"></canvas>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-pie-chart display-6 text-muted"></i>
                    <p class="mt-2 text-muted">Belum ada data pembayaran hari ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Transaction Detail -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="transactionDetailBody">
                <!-- Detail akan dimuat via AJAX -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($last7Days),
            datasets: [{
                label: 'Pendapatan (RP)',
                data: @json($revenueData),
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            if (value >= 1000000) {
                                return 'RP ' + (value/1000000).toFixed(1) + ' jt';
                            } else if (value >= 1000) {
                                return 'RP ' + (value/1000).toFixed(0) + ' rb';
                            }
                            return 'RP ' + value;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'RP ' + (value/1000000).toFixed(1) + ' jt';
                            } else if (value >= 1000) {
                                return 'RP ' + (value/1000).toFixed(0) + ' rb';
                            }
                            return 'RP ' + value;
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
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    // Payment Method Chart
    @if(count($paymentData) > 0)
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: @json($paymentLabels),
            datasets: [{
                data: @json($paymentData),
                backgroundColor: ['#28a745', '#00FFFF', '#0000FF'],
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
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} transaksi (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    @endif

    // Show Transaction Detail
    function showTransactionDetail(transactionId) {
        fetch(`/admin/transactions/${transactionId}/detail`)
            .then(response => response.json())
            .then(data => {
                const modalBody = document.getElementById('transactionDetailBody');
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Invoice:</strong> ${data.transaction_code}</p>
                            <p><strong>Tanggal:</strong> ${new Date(data.created_at).toLocaleString()}</p>
                            <p><strong>Kasir:</strong> ${data.cashier.name}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Metode Bayar:</strong> ${data.payment_method.toUpperCase()}</p>
                            <p><strong>Status:</strong> <span class="badge bg-success">${data.payment_status}</span></p>
                            <p><strong>Total:</strong> RP ${new Intl.NumberFormat('id-ID').format(data.total_amount)}</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Items:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.items.map(item => `
                                    <tr>
                                        <td>${item.product.name}</td>
                                        <td>${item.quantity}</td>
                                        <td>RP ${new Intl.NumberFormat('id-ID').format(item.price)}</td>
                                        <td>RP ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>RP ${new Intl.NumberFormat('id-ID').format(data.total_amount)}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
                
                const modal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail transaksi');
            });
    }

    // Auto refresh dashboard setiap 30 detik
    let refreshInterval = null;
    
    function startAutoRefresh() {
        if (refreshInterval) clearInterval(refreshInterval);
        
        refreshInterval = setInterval(() => {
            fetch('/admin/dashboard/data')
                .then(response => response.json())
                .then(data => {
                    // Update stats cards
                    document.querySelector('.stats-card-1 h2').textContent = 'RP ' + new Intl.NumberFormat('id-ID').format(data.todayRevenue);
                    document.querySelector('.stats-card-2 h2').textContent = data.todayTransactions;
                    document.querySelector('.stats-card-3 h2').textContent = data.todayItemsSold;
                    
                    // Update transaction table (jika perlu)
                    // Note: Untuk update table yang kompleks, mungkin perlu reload page
                    // Atau implementasi AJAX yang lebih advanced
                })
                .catch(error => console.error('Refresh error:', error));
        }, 30000); // 30 detik
    }
    
    // Start auto refresh ketika halaman selesai load
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
        
        // Stop refresh ketika user meninggalkan tab
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (refreshInterval) clearInterval(refreshInterval);
            } else {
                startAutoRefresh();
            }
        });
    });
</script>

<style>
    .dashboard-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .stats-card-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stats-card-2 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .stats-card-3 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .stats-card-4 {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }
    
    .card-icon {
        font-size: 2rem;
        margin-bottom: 15px;
        opacity: 0.8;
    }
    
    .btn-admin {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-admin:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
@endsection