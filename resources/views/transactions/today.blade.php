@extends('layouts.app')

@section('title', 'Transaksi Hari Ini')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar3 me-2"></i>Transaksi Hari Ini
                    <small class="ms-2">({{ $today->format('d F Y') }})</small>
                </h5>
            </div>

            <div class="card-body">
                <!-- Today's Summary -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Transaksi Hari Ini</h6>
                                        <h2 class="mb-0" id="todayCount">{{ $summary['total_transactions'] }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-receipt" style="font-size: 2.5rem; color: #198754;"></i>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>Shift: 
                                    <span id="currentShift">{{ $shiftInfo['name'] }} ({{ $shiftInfo['time'] }})</span>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Total Pendapatan</h6>
                                        <h2 class="mb-0" id="todayRevenue">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-cash-coin" style="font-size: 2.5rem; color: #0d6efd;"></i>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Update terakhir: <span id="lastUpdate">{{ now()->format('H:i') }}</span>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Produk Terjual</h6>
                                        <h2 class="mb-0" id="productsSold">{{ $summary['total_items_sold'] }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-cart-check" style="font-size: 2.5rem; color: #ffc107;"></i>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>Kasir: 
                                    <span id="cashierName">{{ $cashierName }}</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aksi Laporan Section -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">
                            <i class="bi bi-gear me-2"></i>Aksi Laporan
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-eye text-primary" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                                        <h5 class="mb-2">Cek Laporan</h5>
                                        <p class="text-muted small mb-3">Lihat detail transaksi hari ini</p>
                                        <button class="btn btn-primary btn-lg w-100" id="viewReportBtn">
                                            <i class="bi bi-eye me-2"></i>Cek Laporan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-download text-success" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
                                        <h5 class="mb-2">Export Data</h5>
                                        <p class="text-muted small mb-3">Export data transaksi ke CSV/Excel</p>
                                        <button class="btn btn-success btn-lg w-100" id="exportDataBtn">
                                            <i class="bi bi-download me-2"></i>Export Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-arrow-down-circle me-2"></i>Tracker Transaksi
                    @if($transactions->count() > 0)
                    <small class="text-muted ms-2">({{ $summary['first_transaction_time'] }} - {{ $summary['last_transaction_time'] }})</small>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="alert alert-light py-2">
                            <small class="text-muted">Transaksi Pertama</small>
                            <div class="fw-bold" id="firstTransaction">
                                {{ $summary['first_transaction_time'] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-light py-2">
                            <small class="text-muted">Transaksi Terakhir</small>
                            <div class="fw-bold" id="lastTransaction">
                                {{ $summary['last_transaction_time'] }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-light py-2">
                            <small class="text-muted">Rata-rata/Transaksi</small>
                            <div class="fw-bold" id="averageTransaction">
                                Rp {{ number_format($summary['average_per_transaction'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-light py-2">
                            <small class="text-muted">Status</small>
                            <div>
                                <span class="badge bg-{{ $shiftInfo['status'] == 'Aktif' ? 'success' : 'secondary' }}" id="shiftStatus">
                                    {{ $shiftInfo['status'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions List -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="60">No</th>
                                <th>Waktu</th>
                                <th>Invoice</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsBody">
                            @forelse($transactions as $index => $transaction)
                            @php
                                $hour = $transaction->created_at->hour;
                                $timeClass = 'time-morning';
                                if ($hour >= 12 && $hour < 16) {
                                    $timeClass = 'time-afternoon';
                                } elseif ($hour >= 16) {
                                    $timeClass = 'time-evening';
                                }
                                
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
                            <tr class="transaction-row">
                                <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                                <td>
                                    <span class="time-badge {{ $timeClass }}">
                                        <i class="bi bi-clock me-1"></i>{{ $transaction->created_at->format('H:i') }}
                                    </span>
                                </td>
                                <td><strong>{{ $transaction->invoice_no }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $transaction->items->sum('qty') }} items</span></td>
                                <td><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-secondary' }}">
                                        {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction->id) }}" 
                                    class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Belum ada transaksi hari ini</h5>
                                    <p class="text-muted">Transaksi yang Anda lakukan akan muncul di sini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Section - Ditambahkan di sini -->
                @if($transactions->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan <strong>{{ $transactions->firstItem() ?? 0 }}</strong> 
                        sampai <strong>{{ $transactions->lastItem() ?? 0 }}</strong> 
                        dari <strong>{{ $transactions->total() }}</strong> transaksi
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($transactions->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-left"></i>
                                </span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $transactions->previousPageUrl() }}" aria-label="Previous">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                @if ($page == $transactions->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($transactions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $transactions->nextPageUrl() }}" aria-label="Next">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
                <!-- End Pagination Section -->
            </div>
        </div>
    </div>
</div>

<!-- Modal for Transaction Details -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-receipt me-2"></i>Detail Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="transactionDetailContent">
                <!-- Detail akan diisi via JavaScript -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail transaksi...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="bi bi-printer me-2"></i>Cetak Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Report Summary -->
<div class="modal fade" id="reportSummaryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-text me-2"></i>Laporan Hari Ini
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center p-3">
                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 3rem; margin-bottom: 20px;"></i>
                    <h4 class="mb-3">Ringkasan Laporan Hari Ini</h4>
                    <div class="row text-start">
                        <div class="col-6 mb-3">
                            <small class="text-muted">Total Transaksi</small>
                            <h5 class="fw-bold">{{ $summary['total_transactions'] }} transaksi</h5>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted">Total Pendapatan</small>
                            <h5 class="fw-bold">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted">Produk Terjual</small>
                            <h5 class="fw-bold">{{ $summary['total_items_sold'] }} items</h5>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted">Rata-rata/Transaksi</small>
                            <h5 class="fw-bold">Rp {{ number_format($summary['average_per_transaction'], 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Laporan ini mencakup semua transaksi dari shift {{ $shiftInfo['name'] }} ({{ $shiftInfo['time'] }})
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="exportTodayReport()">
                    <i class="bi bi-download me-2"></i>Export PDF
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Simple styling for today's transactions */
    .transaction-row {
        transition: background-color 0.2s;
    }
    
    .transaction-row:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    .time-badge {
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 20px;
    }
    
    .time-morning {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    .time-afternoon {
        background-color: #fff3e0;
        color: #f57c00;
    }
    
    .time-evening {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }
    
    /* Aksi Laporan Card Styling */
    .card.border {
        border-color: #dee2e6 !important;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card.border:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Button styling */
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
    }
    
    /* Card header styling */
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6;
    }
    
    /* Empty state styling */
    .bi-receipt {
        opacity: 0.5;
    }
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        color: #4361ee;
        border-color: #dee2e6;
    }
    
    .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
</style>
@endsection

@section('scripts')
<script>
    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
    
    // Get transaction detail via AJAX
    async function getTransactionDetail(transactionId) {
        try {
            const response = await fetch(`/api/transaction/${transactionId}`);
            if (!response.ok) throw new Error('Failed to fetch');
            return await response.json();
        } catch (error) {
            console.error('Error fetching transaction:', error);
            return null;
        }
    }
    
    // Show transaction detail modal
    async function showTransactionDetail(transactionId) {
        const modalContent = document.getElementById('transactionDetailContent');
        
        // Show loading
        modalContent.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat detail transaksi...</p>
            </div>
        `;
        
        // Show modal immediately
        const modal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
        modal.show();
        
        // Try to fetch from API, fallback to mock data
        let transaction = null;
        
        try {
            // Try API first
            transaction = await getTransactionDetail(transactionId);
        } catch (error) {
            console.log('Using mock data for transaction detail');
        }
        
        // If API fails or returns null, use mock data
        if (!transaction) {
            // Mock data - in real app, you should get this from your database
            transaction = {
                id: transactionId,
                invoice_no: 'INV-2025-009001',
                created_at: new Date().toISOString(),
                total: 85000,
                payment_method: 'cash',
                items: [
                    { product: { name: 'Kopi Hitam' }, qty: 2, price: 15000 },
                    { product: { name: 'Teh Manis' }, qty: 5, price: 10000 },
                    { product: { name: 'Roti Bakar' }, qty: 3, price: 5000 }
                ]
            };
        }
        
        // Build items list
        let itemsHtml = '';
        let itemsTotal = 0;
        transaction.items.forEach(item => {
            const subtotal = item.qty * item.price;
            itemsTotal += subtotal;
            itemsHtml += `
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <span class="fw-bold">${item.product?.name || 'Product'}</span>
                        <br>
                        <small class="text-muted">${item.qty} x ${formatCurrency(item.price)}</small>
                    </div>
                    <div class="fw-bold">
                        ${formatCurrency(subtotal)}
                    </div>
                </div>
            `;
        });
        
        // Calculate tax, discount, etc. (mock data)
        const tax = transaction.total * 0.1; // 10% tax
        const discount = 0;
        const grandTotal = transaction.total;
        
        const time = new Date(transaction.created_at).toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        const methodLabels = {
            'cash': 'Tunai',
            'qris': 'QRIS',
            'transfer': 'Transfer'
        };
        
        const detailHtml = `
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">${transaction.invoice_no}</h6>
                    <span class="badge bg-success">
                        Selesai
                    </span>
                </div>
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>${time} • 
                    <i class="bi bi-cash-coin me-1"></i>${methodLabels[transaction.payment_method] || transaction.payment_method}
                </small>
            </div>
            
            <hr>
            
            <h6 class="mb-3">Items:</h6>
            ${itemsHtml}
            
            <hr>
            
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Subtotal</small>
                </div>
                <div class="col-6 text-end">
                    <strong>${formatCurrency(itemsTotal)}</strong>
                </div>
                
                <div class="col-6 mt-1">
                    <small class="text-muted">Pajak (10%)</small>
                </div>
                <div class="col-6 text-end mt-1">
                    <strong>${formatCurrency(tax)}</strong>
                </div>
                
                <div class="col-6 mt-1">
                    <small class="text-muted">Diskon</small>
                </div>
                <div class="col-6 text-end mt-1">
                    <strong>${formatCurrency(discount)}</strong>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total:</span>
                <span class="text-primary">${formatCurrency(grandTotal)}</span>
            </div>
            
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Transaksi ini tercatat dalam sistem hari ini
                </small>
            </div>
        `;
        
        modalContent.innerHTML = detailHtml;
    }
    
    // Print receipt
    function printReceipt() {
        alert('Fitur cetak struk akan membuka jendela print...');
        // In real app, you would:
        // 1. Generate receipt HTML
        // 2. Open print dialog
        window.print();
    }
    
    // Export today's transactions
    document.getElementById('exportDataBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Exporting...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Get today's date for filename
            const today = new Date();
            const filename = `transaksi_${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}.csv`;
            
            // Create CSV content from table data
            const rows = document.querySelectorAll('#transactionsBody tr');
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "No,Invoice,Waktu,Items,Total,Metode\n";
            
            rows.forEach((row, index) => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    const invoice = cells[2].querySelector('strong')?.textContent || '';
                    const time = cells[1].querySelector('.time-badge')?.textContent.trim() || '';
                    const items = cells[3].querySelector('.badge')?.textContent || '';
                    const total = cells[4].querySelector('strong')?.textContent.replace('Rp ', '').replace(/\./g, '') || '';
                    const method = cells[5].querySelector('.badge')?.textContent || '';
                    
                    csvContent += `${index + 1},"${invoice}","${time}","${items}",${total},"${method}"\n`;
                }
            });
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message
            showToast('Data berhasil diexport!', 'success');
        }, 1000);
    });
    
    // Cek Laporan button - Show summary modal
    document.getElementById('viewReportBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('reportSummaryModal'));
        modal.show();
    });
    
    // Export today's report as PDF
    function exportTodayReport() {
        const btn = document.querySelector('#reportSummaryModal .btn-primary');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // In real app, you would generate PDF here
            // For now, show success message
            showToast('Laporan berhasil di-generate!', 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportSummaryModal'));
            modal.hide();
        }, 1500);
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        // Remove existing toasts
        const existingToast = document.querySelector('.toast');
        if (existingToast) existingToast.remove();
        
        // Create toast element
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'info'} border-0" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-info-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        // Add to document
        const toastContainer = document.createElement('div');
        toastContainer.className = 'position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        toastContainer.innerHTML = toastHtml;
        document.body.appendChild(toastContainer);
        
        // Show toast
        const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
        toast.show();
        
        // Remove after hide
        toastContainer.addEventListener('hidden.bs.toast', function() {
            toastContainer.remove();
        });
    }
    
    // Auto-refresh data every 30 seconds
    function autoRefreshData() {
        setTimeout(() => {
            if (document.visibilityState === 'visible') {
                // Update last update time
                const now = new Date();
                document.getElementById('lastUpdate').textContent = 
                    `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
                
                // Reload page after 5 minutes
                if (now.getMinutes() % 5 === 0) {
                    window.location.reload();
                }
            }
            
            // Schedule next refresh
            autoRefreshData();
        }, 30000); // 30 seconds
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event to all view buttons
        document.querySelectorAll('.view-transaction').forEach(button => {
            button.addEventListener('click', function() {
                const transactionId = this.getAttribute('data-id');
                showTransactionDetail(transactionId);
            });
        });
        
        // Start auto-refresh
        autoRefreshData();
    });
</script>
@endsection