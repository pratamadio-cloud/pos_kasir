@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="row">
    <!-- Daftar Makanan -->
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cart me-2"></i>Daftar Pesanan
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60%">Item</th>
                                <th width="15%">Qty</th>
                                <th width="25%" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup-straw text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Nasi Goreng Special</h6>
                                            <small class="text-muted">RP 25,000</small>
                                        </div>
                                    </div>
                                </td>
                                <td>1</td>
                                <td class="text-end fw-bold">RP 25,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup-straw text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Es Teh Manis</h6>
                                            <small class="text-muted">RP 5,000</small>
                                        </div>
                                    </div>
                                </td>
                                <td>3</td>
                                <td class="text-end fw-bold">RP 15,000</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup-straw text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Kentang Goreng</h6>
                                            <small class="text-muted">RP 12,000</small>
                                        </div>
                                    </div>
                                </td>
                                <td>2</td>
                                <td class="text-end fw-bold">RP 24,000</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total Item</th>
                                <th class="text-end">3 items</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Ringkasan Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col">Subtotal</div>
                    <div class="col text-end fw-bold">RP 64,000</div>
                </div>
                <div class="row mb-2">
                    <div class="col">Pajak (10%)</div>
                    <div class="col text-end fw-bold">RP 6,400</div>
                </div>
                <div class="row mb-2">
                    <div class="col">Diskon</div>
                    <div class="col text-end fw-bold text-success">-RP 0</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col fw-bold">Total</div>
                    <div class="col text-end">
                        <h4 class="text-primary fw-bold mb-0">RP 70,400</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cash-stack me-2"></i>Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <!-- Informasi Total -->
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span>Total Belanja:</span>
                            <h4 class="fw-bold mb-0">RP 70,400</h4>
                            <small class="text-muted">3 items dalam pesanan</small>
                        </div>
                        <div class="text-end">
                            <span class="d-block">No. Transaksi:</span>
                            <span class="fw-bold">#TRX-20231215-001</span>
                        </div>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                <form>
                    <!-- Metode Pembayaran -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cash" checked>
                                    <label class="form-check-label" for="cash">
                                        <i class="bi bi-cash-coin me-1"></i> Tunai
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="qris">
                                    <label class="form-check-label" for="qris">
                                        <i class="bi bi-qr-code me-1"></i> QRIS
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="transfer">
                                    <label class="form-check-label" for="transfer">
                                        <i class="bi bi-bank me-1"></i> Transfer
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Uang -->
                    <div class="mb-4">
                        <label for="amountPaid" class="form-label fw-bold">Uang Dibayar</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">RP</span>
                            <input type="number" class="form-control" id="amountPaid" 
                                   value="100000" min="70400" step="1000">
                        </div>
                        <div class="form-text">Masukkan jumlah uang yang dibayarkan customer</div>
                    </div>

                    <!-- Kembalian -->
                    <div class="mb-4">
                        <div class="alert alert-success">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span>Kembalian:</span>
                                    <h3 class="fw-bold mb-0" id="change-amount">RP 29,600</h3>
                                </div>
                                <i class="bi bi-cash-coin fs-1"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan (Opsional) -->
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold">Catatan Transaksi</label>
                        <textarea class="form-control" id="notes" rows="2" 
                                  placeholder="Tambah catatan untuk transaksi ini (opsional)"></textarea>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="/pos/print" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Selesaikan Transaksi & Cetak Struk
                        </a>
                        <a href="/pos" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Kasir
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Hitung kembalian saat input berubah
    document.getElementById('amountPaid').addEventListener('input', function() {
        const total = 70400; // Total belanja baru
        const paid = parseInt(this.value) || 0;
        const change = paid - total;
        
        if (change >= 0) {
            document.getElementById('change-amount').textContent = formatRupiah(change);
        } else {
            document.getElementById('change-amount').textContent = formatRupiah(0);
        }
    });

    // Format angka ke format Rupiah
    function formatRupiah(angka) {
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });
        return formatter.format(angka);
    }

    // Update metode pembayaran
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const paymentMethod = this.id;
            const amountPaidInput = document.getElementById('amountPaid');
            
            if (paymentMethod === 'cash') {
                amountPaidInput.removeAttribute('readonly');
                amountPaidInput.min = 70400;
            } else {
                amountPaidInput.value = 70400;
                amountPaidInput.setAttribute('readonly', true);
                document.getElementById('change-amount').textContent = formatRupiah(0);
            }
        });
    });
</script>
@endsection