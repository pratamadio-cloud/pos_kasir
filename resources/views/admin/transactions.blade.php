@extends('layouts.admin')

@section('title', 'Semua Transaksi')
@section('page-title', 'Manajemen Transaksi')

@php
    // Helper function untuk menentukan shift
    function getShift($time)
    {
        $hour = $time->hour;
        if ($hour >= 8 && $hour < 16) return 'Pagi';
        if ($hour >= 16 && $hour < 24) return 'Sore';
        return 'Malam';
    }
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Semua Transaksi
                </h5>
                <div>
                    <button class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel me-1"></i>Filter Lanjutan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Quick Filters -->
                <form method="GET" action="{{ route('admin.transactions') }}" id="filterForm">
                    <div class="row mb-4">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Kasir</label>
                            <select class="form-select" name="cashier_id">
                                <option value="">Semua Kasir</option>
                                @foreach($cashiers as $cashier)
                                    @if($cashier)
                                    <option value="{{ $cashier->id }}" 
                                            {{ $cashierId == $cashier->id ? 'selected' : '' }}>
                                        {{ $cashier->name }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Terapkan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Summary -->
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-3">
                            <small>Total Transaksi</small>
                            <h5 class="mb-0">{{ number_format($summary['total_transactions']) }}</h5>
                        </div>
                        <div class="col-md-3">
                            <small>Total Pendapatan</small>
                            <h5 class="mb-0">RP {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <small>Rata-rata/Transaksi</small>
                            <h5 class="mb-0">RP {{ number_format($summary['average_per_transaction'], 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <small>Periode</small>
                            <h5 class="mb-0">{{ $summary['period'] }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <strong>{{ $transaction->invoice_no }}</strong>
                                    <br><small class="text-muted">#{{ $transaction->id }}</small>
                                </td>
                                <td>
                                    {{ $transaction->created_at->format('d/m/Y') }}
                                    <br><small>{{ $transaction->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $transaction->cashier->name ?? 'Kasir' }}</span>
                                    <br><small>{{ getShift($transaction->created_at) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $transaction->items->sum('qty') }} items</span>
                                </td>
                                <td>
                                    <strong>RP {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $methodColors = [
                                            'cash' => 'bg-success text-white',
                                            'qris' => 'bg-info text-white',
                                            'transfer' => 'bg-primary text-white'
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
                                <td class="text-center">
                                    <a href="{{ route('admin.transactions-detail', $transaction->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Tidak ada transaksi</h5>
                                    <p class="text-muted">Tidak ada transaksi pada periode yang dipilih</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination - Diperbarui -->
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
                            @php
                                $current = $transactions->currentPage();
                                $last = $transactions->lastPage();
                                $start = max(1, $current - 2);
                                $end = min($last, $start + 4);
                                
                                if ($end - $start < 4) {
                                    $start = max(1, $end - 4);
                                }
                            @endphp
                            
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $transactions->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                                @endif
                            @endif
                            
                            @for ($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $transactions->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            @if($end < $last)
                                @if($end < $last - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $transactions->url($last) }}">{{ $last }}</a>
                                </li>
                            @endif

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
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-download me-2"></i>Export Data Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm" action="{{ route('admin.transactions.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    <input type="hidden" name="cashier_id" value="{{ $cashierId }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Format File</label>
                        <select class="form-select" name="format" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data yang Diexport</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_details" checked>
                            <label class="form-check-label">Data Transaksi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_items" checked>
                            <label class="form-check-label">Detail Item</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_cashier">
                            <label class="form-check-label">Data Kasir</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="exportForm" class="btn btn-primary" id="exportButton">
                    <i class="bi bi-download me-2"></i>Download Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-funnel me-2"></i>Filter Lanjutan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="advancedFilterForm" method="GET" action="{{ route('admin.transactions') }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select" name="payment_methods[]" multiple>
                                <option value="cash" {{ in_array('cash', request('payment_methods', [])) ? 'selected' : '' }}>Tunai</option>
                                <option value="qris" {{ in_array('qris', request('payment_methods', [])) ? 'selected' : '' }}>QRIS</option>
                                <option value="transfer" {{ in_array('transfer', request('payment_methods', [])) ? 'selected' : '' }}>Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Minimum</label>
                            <input type="number" class="form-control" name="min_amount" 
                                   placeholder="RP 0" value="{{ request('min_amount') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Maksimum</label>
                            <input type="number" class="form-control" name="max_amount" 
                                   placeholder="RP 1,000,000" value="{{ request('max_amount') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urut Berdasarkan</label>
                            <select class="form-select" name="sort_by">
                                <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>Tanggal (Terbaru)</option>
                                <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>Tanggal (Terlama)</option>
                                <option value="amount_desc" {{ request('sort_by') == 'amount_desc' ? 'selected' : '' }}>Jumlah (Terbesar)</option>
                                <option value="amount_asc" {{ request('sort_by') == 'amount_asc' ? 'selected' : '' }}>Jumlah (Terkecil)</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="resetAdvancedFilters()">Reset Filter</button>
                <button type="submit" form="advancedFilterForm" class="btn btn-primary">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Helper function untuk menentukan shift dari waktu
    function getShiftFromTime(time) {
        const hour = new Date(time).getHours();
        if (hour >= 8 && hour < 16) return 'Pagi';
        if (hour >= 16 && hour < 24) return 'Sore';
        return 'Malam';
    }
    
    // Export form submission
    document.getElementById('exportForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('exportButton');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        btn.disabled = true;
        
        // Form akan submit secara normal
    });
    
    // Reset advanced filters
    function resetAdvancedFilters() {
        const form = document.getElementById('advancedFilterForm');
        form.reset();
        
        // Reset select multiple
        const selects = form.querySelectorAll('select[multiple]');
        selects.forEach(select => {
            Array.from(select.options).forEach(option => {
                option.selected = false;
            });
        });
        
        // Submit form
        form.submit();
    }
    
    // Auto submit date inputs
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', function() {
            // Jika bukan di modal filter, submit form utama
            if (!this.closest('.modal')) {
                document.getElementById('filterForm').submit();
            }
        });
    });
    
    // Auto submit kasir select
    document.querySelector('select[name="cashier_id"]').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Row click untuk melihat detail
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            // Jangan trigger jika klik pada button/link
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || 
                e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            
            // Highlight row
            this.classList.toggle('table-active');
        });
    });
    
    // Format angka ke Rupiah
    function formatRupiah(amount) {
        return 'RP ' + new Intl.NumberFormat('id-ID').format(amount);
    }
</script>

<style>
    /* Custom styling untuk tabel */
    tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    tbody tr.table-active {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    /* Badge styling */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    /* Modal styling */
    .modal-content {
        border-radius: 12px;
        border: none;
    }
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        border-radius: 6px;
        margin: 0 3px;
        border: 1px solid #dee2e6;
        color: #4361ee;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
        color: white;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    /* Pagination info */
    .text-muted strong {
        color: #495057;
    }
    
    /* Select multiple styling */
    select[multiple] {
        min-height: 120px;
    }
    
    select[multiple] option {
        padding: 8px 12px;
    }
    
    /* Empty state styling */
    .bi-receipt {
        opacity: 0.5;
    }
</style>
@endsection