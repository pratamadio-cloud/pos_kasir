@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@php
    // Helper function untuk menentukan shift
    function getShift($time)
    {
        $hour = $time->hour;
        if ($hour >= 8 && $hour < 16) return 'Pagi';
        if ($hour >= 16 && $hour < 24) return 'Sore';
        return 'Malam';
    }
    
    // Helper function untuk format shift time
    function getShiftTime($shift)
    {
        switch($shift) {
            case 'Pagi': return '08:00 - 16:00';
            case 'Sore': return '16:00 - 24:00';
            case 'Malam': return '00:00 - 08:00';
            default: return '-';
        }
    }
    
    // Method colors dan labels
    $methodColors = [
        'cash' => 'bg-success text-white',
        'qris' => 'bg-primary text-white',
        'transfer' => 'bg-info text-white'
    ];
    $methodLabels = [
        'cash' => 'Tunai',
        'qris' => 'QRIS',
        'transfer' => 'Transfer'
    ];
@endphp

@section('content')
@if(isset($transaction) && $transaction)
<div class="row">
    <div class="col-lg-8">
        <!-- Transaction Details -->
        <div class="card dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Detail Transaksi
                </h5>
                <div>
                    <span class="badge bg-success me-2">Selesai</span>
                    <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-secondary' }}">
                        {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="140">No. Invoice</th>
                                <td><strong>{{ $transaction->invoice_no }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ $transaction->created_at->format('d F Y, H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Kasir</th>
                                <td>
                                    <span class="badge bg-info">{{ $transaction->cashier->name ?? 'Kasir' }}</span>
                                    (Shift: {{ getShift($transaction->created_at) }})
                                </td>
                            </tr>
                            <tr>
                                <th>Shift</th>
                                <td>{{ getShift($transaction->created_at) }} ({{ getShiftTime(getShift($transaction->created_at)) }})</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="140">Total Items</th>
                                <td><strong>{{ $transaction->items->sum('qty') }} items</strong></td>
                            </tr>
                            <tr>
                                <th>Subtotal</th>
                                <td>RP {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Pajak</th>
                                <td>RP 0</td>
                            </tr>
                            <tr>
                                <th><h5 class="mb-0">Total</h5></th>
                                <td><h5 class="mb-0 text-primary">RP {{ number_format($transaction->total, 0, ',', '.') }}</h5></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Items List -->
                <h6 class="mb-3">Items yang Dibeli</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                            @php
                                $product = $item->product;
                                $icons = ['bi-cup-straw', 'bi-cup', 'bi-cart', 'bi-box'];
                                $icon = $icons[array_rand($icons)];
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi {{ $icon }}" style="font-size: 1.5rem; color: #4361ee;"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $product->name ?? 'Produk #' . $item->product_id }}</strong>
                                            @if($product->barcode ?? false)
                                            <br><small class="text-muted">Barcode: {{ $product->barcode }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>RP {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->qty }}</td>
                                <td><strong>RP {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong class="text-primary">RP {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-cash-stack me-2"></i>Detail Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <p class="form-control-plaintext">{{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Uang Dibayar</label>
                            <p class="form-control-plaintext">RP {{ number_format($transaction->paid, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kembalian</label>
                            <p class="form-control-plaintext text-success fw-bold">RP {{ number_format($transaction->change, 0, ',', '.') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <p>
                                <span class="badge bg-success">Lunas</span>
                                <small class="text-muted ms-2">{{ $transaction->created_at->format('H:i:s') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card dashboard-card mb-4">
              <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-receipt-cutoff me-2"></i>Pratinjau Struk
                </h5>
            </div>
            <div class="card-body">
                <div class="receipt-preview border p-3" style="font-family: 'Courier New', monospace; font-size: 12px;">
                    <div class="text-center mb-2">
                        <strong>POS SYSTEM</strong><br>
                        Transaksi Detail<br>
                        -----------------
                    </div>
                    <div class="mb-2">
                        <strong>{{ $transaction->invoice_no }}</strong><br>
                        {{ $transaction->created_at->format('d/m/Y H:i:s') }}<br>
                        Kasir: {{ $transaction->cashier->name ?? 'Kasir' }}
                    </div>
                    <hr class="my-1">
                    <div>
                        @foreach($transaction->items as $item)
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $item->product->name ?? 'Item' }} ({{ $item->qty }}x)</span>
                            <span>RP {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <hr class="my-1">
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>Total:</span>
                            <span>RP {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tunai:</span>
                            <span>RP {{ number_format($transaction->paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Kembali:</span>
                            <span>RP {{ number_format($transaction->change, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <hr class="my-1">
                    <div class="text-center mt-2">
                        Terima kasih telah berbelanja
                    </div>
                </div>
            </div>
        </div>

             <!-- Additional Info -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Transaksi
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">ID Transaksi</label>
                    <p class="form-control-plaintext">{{ $transaction->id }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Transaksi</label>
                    <p class="form-control-plaintext">{{ $transaction->created_at->format('d F Y H:i:s') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kasir</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-info">{{ $transaction->cashier->name ?? 'Kasir' }}</span>
                        (ID: {{ $transaction->cashier_id }})
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-secondary' }}">
                            {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Back Button -->
<div class="mt-4">
    <a href="/admin/transactions" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Transaksi
    </a>
</div>
        </div>

        <!-- Receipt Preview -->
        {{-- <div class="card dashboard-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-receipt-cutoff me-2"></i>Pratinjau Struk
                </h5>
            </div>
            <div class="card-body">
                <div class="receipt-preview border p-3" style="font-family: 'Courier New', monospace; font-size: 12px;">
                    <div class="text-center mb-2">
                        <strong>POS SYSTEM</strong><br>
                        Transaksi Detail<br>
                        -----------------
                    </div>
                    <div class="mb-2">
                        <strong>{{ $transaction->invoice_no }}</strong><br>
                        {{ $transaction->created_at->format('d/m/Y H:i:s') }}<br>
                        Kasir: {{ $transaction->cashier->name ?? 'Kasir' }}
                    </div>
                    <hr class="my-1">
                    <div>
                        @foreach($transaction->items as $item)
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $item->product->name ?? 'Item' }} ({{ $item->qty }}x)</span>
                            <span>RP {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <hr class="my-1">
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>Total:</span>
                            <span>RP {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tunai:</span>
                            <span>RP {{ number_format($transaction->paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Kembali:</span>
                            <span>RP {{ number_format($transaction->change, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <hr class="my-1">
                    <div class="text-center mt-2">
                        Terima kasih telah berbelanja
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <!-- Additional Info -->
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Transaksi
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">ID Transaksi</label>
                    <p class="form-control-plaintext">{{ $transaction->id }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Transaksi</label>
                    <p class="form-control-plaintext">{{ $transaction->created_at->format('d F Y H:i:s') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kasir</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-info">{{ $transaction->cashier->name ?? 'Kasir' }}</span>
                        (ID: {{ $transaction->cashier_id }})
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-secondary' }}">
                            {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Cancel Modal -->
{{-- <div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Batalkan Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Anda yakin ingin membatalkan transaksi ini?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Perhatian:</strong> Transaksi yang sudah dibatalkan tidak dapat dikembalikan.
                    Stok produk akan dikembalikan ke sistem.
                </div>
                <div class="mb-3">
                    <label class="form-label">Alasan Pembatalan</label>
                    <textarea class="form-control" rows="3" placeholder="Masukkan alasan pembatalan..." id="cancelReason"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="cancelTransaction()">
                    <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                </button>
            </div>
        </div>
    </div>
</div> --}}

@else
<!-- Transaction Not Found -->
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-4">Transaksi Tidak Ditemukan</h4>
                <p class="text-muted">Transaksi yang Anda cari tidak ditemukan atau telah dihapus.</p>
                <a href="/admin/transactions" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Transaksi
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Back Button -->
<div class="mt-4">
    <a href="/admin/transactions" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Transaksi
    </a>
</div>
@endsection

@section('scripts')
<script>
    // Print receipt
    function printReceipt() {
        const receiptContent = document.querySelector('.receipt-preview');
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = receiptContent.outerHTML;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();
    }
    
    // Cancel transaction
    function cancelTransaction() {
        const reason = document.getElementById('cancelReason').value.trim();
        
        if (!reason) {
            alert('Silakan masukkan alasan pembatalan!');
            return;
        }
        
        const btn = document.querySelector('#cancelModal .btn-danger');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Membatalkan...';
        btn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            alert('Transaksi berhasil dibatalkan!');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('cancelModal'));
            modal.hide();
            
            // Reset button
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Redirect back to transactions list
            window.location.href = '/admin/transactions';
        }, 2000);
    }
    
    // Auto calculate if needed
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-subtotal').forEach(item => {
            total += parseFloat(item.textContent.replace(/[^0-9.-]+/g, ""));
        });
        document.getElementById('transaction-total').textContent = 'RP ' + total.toLocaleString('id-ID');
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add print button functionality
        const printButton = document.querySelector('.btn-primary[onclick="printReceipt()"]');
        if (printButton) {
            printButton.addEventListener('click', printReceipt);
        }
        
        // Auto calculate if there are dynamic elements
        calculateTotal();
    });
</script>

<style>
    .receipt-preview {
        background-color: #fff;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .table-borderless th {
        font-weight: 600;
        color: #495057;
    }
    
    .table-borderless td {
        color: #212529;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        line-height: 1.5;
        color: #212529;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .receipt-preview {
            max-width: 100% !important;
            border: none !important;
            padding: 0 !important;
        }
    }
    
    /* Button styling */
    .btn {
        border-radius: 6px;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endsection