@extends('layouts.app')

@section('title', 'Detail Transaksi')

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
        'cash' => 'bg-success',
        'qris' => 'bg-primary',
        'transfer' => 'bg-info'
    ];
    
    $methodLabels = [
        'cash' => 'Tunai',
        'qris' => 'QRIS',
        'transfer' => 'Transfer Bank'
    ];
@endphp

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>Detail Transaksi
                    </h4>
                    {{-- <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transaksi</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav> --}}
                </div>
                <div>
                    <a href="{{ route('transactions.today') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    {{-- <button onclick="printReceipt()" class="btn btn-primary">
                        <i class="bi bi-printer me-1"></i>Cetak Struk
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    @if(isset($transaction) && $transaction)
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Transaction Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-cart-check me-2"></i>Detail Transaksi
                        </h5>
                        <div>
                            <span class="badge bg-success">Selesai</span>
                            <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-dark' }}">
                                {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Transaction Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">No. Invoice</label>
                                <p class="mb-0 fw-bold">{{ $transaction->invoice_no }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Tanggal & Waktu</label>
                                <p class="mb-0">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Kasir</label>
                                <p class="mb-0">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $transaction->cashier->name ?? 'Kasir' }}
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Shift</label>
                                <p class="mb-0">
                                    {{ getShift($transaction->created_at) }} 
                                    <small class="text-muted">({{ getShiftTime(getShift($transaction->created_at)) }})</small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $index => $item)
                                @php
                                    $product = $item->product;
                                    $productName = $product->name ?? 'Produk #' . $item->product_id;
                                    $productCode = $product->code ?? $product->barcode ?? '';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(isset($product->image) && $product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $productName }}"
                                                 class="rounded me-3" 
                                                 width="40" 
                                                 height="40"
                                                 style="object-fit: cover;">
                                            @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $productName }}</div>
                                                @if($productCode)
                                                <small class="text-muted">Kode: {{ $productCode }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end fw-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold fs-5">Total</td>
                                    <td class="text-end fw-bold fs-5 text-primary">
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>Detail Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Metode Pembayaran</label>
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $methodColors[$transaction->payment_method] ?? 'bg-dark' }} me-2">
                                        {{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                                    </span>
                                    @if($transaction->payment_method == 'qris')
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-qr-code me-1"></i>QRIS
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Uang Dibayar</label>
                                <p class="mb-0 fs-5 fw-bold text-success">
                                    Rp {{ number_format($transaction->paid, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Kembalian</label>
                                <p class="mb-0 fs-5 fw-bold text-primary">
                                    Rp {{ number_format($transaction->change, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Receipt Preview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>Pratinjau Struk
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div id="receipt-content" class="receipt-preview" style="font-family: 'Courier New', monospace; font-size: 11px;">
                        <!-- Receipt Header -->
                        <div class="text-center mb-2">
                            <div class="fw-bold" style="font-size: 14px;">{{ config('app.name', 'TOKO KITA') }}</div>
                            <div>{{ config('app.address', 'Jl. Contoh No. 123') }}</div>
                            <div>Telp: {{ config('app.phone', '0812-3456-7890') }}</div>
                            <div>========================</div>
                        </div>
                        
                        <!-- Transaction Info -->
                        <div class="mb-2">
                            <div>Invoice: {{ $transaction->invoice_no }}</div>
                            <div>Tanggal: {{ $transaction->created_at->format('d/m/Y') }}</div>
                            <div>Waktu: {{ $transaction->created_at->format('H:i:s') }}</div>
                            <div>Kasir: {{ $transaction->cashier->name ?? 'Kasir' }}</div>
                            <div>------------------------</div>
                        </div>
                        
                        <!-- Items -->
                        <div class="mb-2">
                            @foreach($transaction->items as $item)
                            <div class="d-flex justify-content-between mb-1">
                                <div style="width: 70%;">
                                    {{ $item->product->name ?? 'Item' }}
                                    <div style="margin-left: 10px; font-size: 10px;">
                                        {{ $item->qty }} x @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div style="width: 30%; text-align: right;">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                            <div>------------------------</div>
                        </div>
                        
                        <!-- Summary -->
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <div>TOTAL:</div>
                                <div>Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>Bayar ({{ $methodLabels[$transaction->payment_method] ?? $transaction->payment_method }}):</div>
                                <div>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</div>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <div>KEMBALI:</div>
                                <div>Rp {{ number_format($transaction->change, 0, ',', '.') }}</div>
                            </div>
                            <div>------------------------</div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="text-center mt-3">
                            <div>Terima kasih atas kunjungan Anda</div>
                            <div>*** {{ $transaction->invoice_no }} ***</div>
                        </div>
                    </div>
                    
                    <!-- Print Button -->
                    <div class="mt-3 d-grid gap-2">
                        <button onclick="printReceipt()" class="btn btn-outline-primary">
                            <i class="bi bi-printer me-2"></i>Cetak Struk
                        </button>
                        {{-- <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list me-2"></i>Daftar Transaksi
                        </a>
                    </div> --}}
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small mb-1">ID Transaksi</label>
                        <p class="mb-0">{{ $transaction->id }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Waktu Transaksi</label>
                        <p class="mb-0">{{ $transaction->created_at->format('d F Y H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Waktu Update</label>
                        <p class="mb-0">{{ $transaction->updated_at->format('d F Y H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Total Items</label>
                        <p class="mb-0 fw-bold">{{ $transaction->items->sum('qty') }} items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Transaction Not Found -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="mb-3">Transaksi Tidak Ditemukan</h4>
                    <p class="text-muted mb-4">Transaksi yang Anda cari tidak ditemukan atau telah dihapus.</p>
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Print receipt function
    function printReceipt() {
        const originalContent = document.body.innerHTML;
        const receiptContent = document.getElementById('receipt-content').innerHTML;
        
        // Create print window
        const printWindow = window.open('', '_blank', 'width=350,height=600');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Struk {{ $transaction->invoice_no ?? '' }}</title>
                <style>
                    @media print {
                        body { 
                            margin: 0; 
                            padding: 0; 
                            font-family: 'Courier New', monospace;
                            font-size: 11px;
                        }
                        @page { 
                            size: 80mm auto; 
                            margin: 0; 
                        }
                        .no-print { display: none !important; }
                    }
                    body { 
                        padding: 10px; 
                        font-family: 'Courier New', monospace;
                        font-size: 11px;
                    }
                    .receipt-preview { 
                        max-width: 100%; 
                        margin: 0 auto;
                    }
                </style>
            </head>
            <body>
                <div class="receipt-preview">
                    ${receiptContent}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => window.close(), 500);
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add print shortcut (Ctrl+P)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                printReceipt();
            }
        });
        
        // Show print dialog automatically if URL has ?print parameter
        if (window.location.search.includes('print=true')) {
            setTimeout(printReceipt, 1000);
        }
    });
</script>

<style>
    .receipt-preview {
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        max-width: 280px;
        margin: 0 auto;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .card {
        border-radius: 10px;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        padding: 6px 12px;
        font-weight: 500;
        border-radius: 6px;
    }
    
    .btn {
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 500;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background-color: transparent !important;
            border: none !important;
        }
    }
</style>
@endsection