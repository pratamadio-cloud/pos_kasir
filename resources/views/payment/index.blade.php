{{-- <form method="POST" action="{{ route('transaction.store') }}">
@csrf

<table class="table">
    @foreach ($cart as $item)
    <tr>
        <td>{{ $item['name'] }}</td>
        <td>{{ $item['qty'] }}</td>
        <td>Rp {{ number_format($item['price']) }}</td>
        <td>Rp {{ number_format($item['subtotal']) }}</td>
    </tr>
    @endforeach
</table>

<h4>Total: Rp {{ number_format($total) }}</h4>

<select name="payment_method" required>
    <option value="cash">Cash</option>
    <option value="qris">QRIS</option>
    <option value="transfer">Transfer</option>
</select>

<input type="number" name="paid" required>

<button type="submit">Selesaikan Transaksi</button>
</form> --}}



@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cash-stack me-2"></i>Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <!-- Informasi Total -->
                <form method="POST" action="{{ route('transaction.store') }}">
                    @csrf
                <table class="table">
                    @foreach ($cart as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>Rp {{ number_format($item['price']) }}</td>
                        <td>Rp {{ number_format($item['subtotal']) }}</td>
                    </tr>
                    @endforeach
                </table>
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between">
                        <span>Total Belanja:</span>
                        <span class="fw-bold fs-5">Rp {{ number_format($total) }}</span>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                
                    <!-- Metode Pembayaran -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="cash" checked>
                                    <label class="form-check-label" for="cash">
                                        <i class="bi bi-cash-coin me-1"></i> Tunai
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="qris" value="qris">
                                    <label class="form-check-label" for="qris">
                                        <i class="bi bi-qr-code me-1"></i> QRIS
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="transfer" value="transfer">
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
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="paid" class="form-control" required>
                        </div>
                        <div class="form-text">Masukkan jumlah uang yang dibayarkan customer</div>
                    </div>

                    <!-- Kembalian -->
                    <div class="mb-4">
                        <div class="alert alert-success">
                            <div class="d-flex justify-content-between">
                                <span>Kembalian:</span>
                                <span class="fw-bold fs-4" id="change-text">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="pay-btn">
                            <i class="bi bi-check-circle me-2"></i>Selesaikan Transaksi
                        </button>
                        <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary">
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
    const total = {{ $total }};
    const paidInput = document.getElementById('paid');
    const changeText = document.getElementById('change-text');

    paidInput.addEventListener('input', function () {
        const paid = parseInt(this.value) || 0;
        const change = paid - total;

        changeText.innerText = change >= 0
            ? 'Rp ' + change.toLocaleString('id-ID')
            : 'Rp 0';
    });

    const btn = document.getElementById('pay-btn');

    paidInput.addEventListener('input', function () {
        const paid = parseInt(this.value) || 0;
        btn.disabled = paid < total;
    });
</script>
@endsection