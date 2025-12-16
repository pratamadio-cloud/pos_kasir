@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
@php
    $total = 0;
    $totalItem = 0;
@endphp

<div class="row">
    <!-- DAFTAR PESANAN -->
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-cart me-2"></i>Daftar Pesanan</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            @php
                                $subtotal = $item['price'] * $item['qty'];
                                $total += $subtotal;
                                $totalItem += $item['qty'];
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $item['name'] }}</strong><br>
                                    <small class="text-muted">Rp {{ number_format($item['price']) }}</small>
                                </td>
                                <td>{{ $item['qty'] }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2">Total Item</th>
                            <th class="text-end">{{ $totalItem }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- RINGKASAN -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <h4 class="text-primary fw-bold">Rp {{ number_format($total) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM PEMBAYARAN -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Pembayaran</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transaction.store') }}">
                    @csrf

                    <input type="hidden" name="total" value="{{ $total }}">

                    <!-- METODE -->
                    <div class="mb-3">
                        <label class="fw-bold">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Tunai</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <!-- UANG DIBAYAR -->
                    <div class="mb-3">
                        <label class="fw-bold">Uang Dibayar</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            <input
                                type="number"
                                class="form-control"
                                name="paid"
                                id="paid"
                                min="{{ $total }}"
                                required>
                        </div>
                    </div>

                    <!-- KEMBALIAN -->
                    <div class="alert alert-success">
                        <div class="d-flex justify-content-between">
                            <strong>Kembalian</strong>
                            <h3 id="change">Rp 0</h3>
                        </div>
                    </div>

                    <!-- ACTION -->
                    <button class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-circle me-2"></i>Selesaikan Transaksi
                    </button>
                    <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary btn-lg w-100 mt-1">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Kasir
                    </a>
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
    const changeEl = document.getElementById('change');

    paidInput.addEventListener('input', function () {
        const paid = parseInt(this.value) || 0;
        const change = paid - total;
        changeEl.innerText = formatRupiah(change > 0 ? change : 0);
    });

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);
    }
</script>
@endsection
