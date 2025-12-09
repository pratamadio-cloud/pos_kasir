@extends('layouts.admin')

@section('title', 'Semua Transaksi')
@section('page-title', 'Manajemen Transaksi')

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
                <div class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" value="{{ date('Y-m-01') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Kasir</label>
                        <select class="form-select">
                            <option value="">Semua Kasir</option>
                            <option value="1">Kasir-1</option>
                            <option value="2">Kasir-2</option>
                            <option value="3">Kasir-3</option>
                            <option value="4">Kasir-Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Terapkan
                        </button>
                    </div>
                </div>

                <!-- Summary -->
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-3">
                            <small>Total Transaksi</small>
                            <h5 class="mb-0">1,248</h5>
                        </div>
                        <div class="col-md-3">
                            <small>Total Pendapatan</small>
                            <h5 class="mb-0">RP 42,580,000</h5>
                        </div>
                        <div class="col-md-3">
                            <small>Rata-rata/Transaksi</small>
                            <h5 class="mb-0">RP 34,118</h5>
                        </div>
                        <div class="col-md-3">
                            <small>Periode</small>
                            <h5 class="mb-0">1 - 30 {{ date('F Y') }}</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 10; $i++)
                            <tr>
                                <td>
                                    <strong>INV-2024-00{{ 130 - $i }}</strong>
                                    <br><small class="text-muted">#T{{ 130 - $i }}</small>
                                </td>
                                <td>
                                    {{ date('d/m/Y', strtotime("-$i days")) }}
                                    <br><small>{{ date('H:i', strtotime("+$i hours")) }}</small>
                                </td>
                                <td>
                                    @php
                                        $cashiers = ['Kasir-1', 'Kasir-2', 'Kasir-3', 'Kasir-Admin'];
                                        $cashier = $cashiers[array_rand($cashiers)];
                                        $shifts = ['Pagi', 'Siang', 'Malam'];
                                        $shift = $shifts[array_rand($shifts)];
                                    @endphp
                                    <span class="badge bg-info">{{ $cashier }}</span>
                                    <br><small>{{ $shift }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ rand(1, 8) }} items</span>
                                </td>
                                <td>
                                    <strong>RP {{ number_format(rand(10000, 200000), 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $methods = [
                                            ['bg-success text-white', 'Tunai'],
                                            ['bg-primary text-white', 'QRIS'],
                                            ['bg-info text-dark', 'Transfer']  // Changed from bg-purple to bg-info with dark text
                                        ];
                                        $method = $methods[array_rand($methods)];
                                    @endphp
                                    <span class="badge {{ $method[0] }}">{{ $method[1] }}</span>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
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
                <form>
                    <div class="mb-3">
                        <label class="form-label">Format File</label>
                        <select class="form-select">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rentang Tanggal</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date" class="form-control" value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data yang Diexport</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Data Transaksi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Detail Item</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Data Kasir</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">
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
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Transaksi</label>
                        <select class="form-select" multiple>
                            <option value="completed" selected>Selesai</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" multiple>
                            <option value="cash" selected>Tunai</option>
                            <option value="qris" selected>QRIS</option>
                            <option value="transfer" selected>Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jumlah Minimum</label>
                        <input type="number" class="form-control" placeholder="RP 0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jumlah Maksimum</label>
                        <input type="number" class="form-control" placeholder="RP 1,000,000">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Urut Berdasarkan</label>
                        <select class="form-select">
                            <option value="date_desc">Tanggal (Terbaru)</option>
                            <option value="date_asc">Tanggal (Terlama)</option>
                            <option value="amount_desc">Jumlah (Terbesar)</option>
                            <option value="amount_asc">Jumlah (Terkecil)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shift Kasir</label>
                        <select class="form-select" multiple>
                            <option value="morning">Pagi</option>
                            <option value="afternoon">Siang</option>
                            <option value="night">Malam</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary">Reset Filter</button>
                <button type="button" class="btn btn-primary">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Row selection - simplified without checkbox logic
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            this.classList.toggle('table-active');
        });
    });
</script>
@endsection