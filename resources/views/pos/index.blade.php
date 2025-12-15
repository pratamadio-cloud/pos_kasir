@extends('layouts.app')

@section('title', 'Kasir POS')

@section('content')
<div class="row pos-wrapper">
    <!-- Kolom Kiri: Input Produk -->
    <div class="{{ $hasCart ? 'col-md-8' : 'col-md-12' }}">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-upc-scan me-2"></i>Input Produk
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label for="barcode" class="form-label">Cari Produk</label>
                    <form action="{{ route('pos.index') }}" method="GET">
                        @csrf
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" id="barcode" placeholder="Scan barcode atau ketik kode/nama produk" name="search" value="{{ request('search') }}" autofocus>
                        <button class="btn btn-secondary" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                    </form>
                </div>

                <!-- Daftar Produk -->
                <div class="mb-4">
                    <h6 class="mb-3">Daftar Produk Cepat</h6>
                    <div class="row">
                        <!-- Produk 1 -->
                        @foreach ($products as $item)
                            
                        
                        <div class="col-md-3 mb-3">
                            <div class="card product-card" style="cursor: pointer;">
                                <div onclick="document.getElementById('add-{{ $item->id }}').submit()" style="cursor: pointer;">
                                    <form id="add-{{ $item->id }}" action="{{ route('pos.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                    </form>
                                    <div class="card-body text-center">
                                        <h6 class="card-title mb-1">{{ $item->name }}</h6>
                                        <p class="text-muted mb-1">{{ $item->price }}</p>
                                        <small class="text-success">{{ $item->stock }}</small>
                                    </div>
                                </div>    
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{ $products->appends(request()->query())->links() }}
                    
                </div>
            </div>
        </div>
    </div>

    @if ($hasCart)
    <div class="col-md-4">
    <div class="card pos-cart">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
            </h5>
        </div>

        {{-- BODY FLEX --}}
        <div class="card-body cart-body">

            {{-- CART ITEMS (SCROLL) --}}
            <div class="cart-items p-3" id="cartItems">
                @forelse ($cart as $productId => $item)
                    @php $subtotal = $item['qty'] * $item['price']; @endphp

                    <div class="pos-item mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $item['name'] }}</strong><br>
                                <small class="text-muted">
                                    Rp {{ number_format($item['price']) }}
                                </small>
                            </div>

                            <div class="d-flex align-items-center gap-1">
                                <form action="{{ route('pos.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <input type="hidden" name="action" value="minus">
                                    <button class="btn btn-sm btn-outline-secondary">−</button>
                                </form>

                                <span>{{ $item['qty'] }}</span>

                                <form action="{{ route('pos.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <input type="hidden" name="action" value="plus">
                                    <button class="btn btn-sm btn-outline-secondary">+</button>
                                </form>

                                <form action="{{ route('pos.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-end fw-bold">
                            Rp {{ number_format($subtotal) }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Keranjang kosong</p>
                @endforelse
            </div>

            {{-- TOTAL (STICKY BOTTOM) --}}
            <div class="cart-total ">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Item</span>
                    <strong>{{ $total_item }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Total Belanja</span>
                    <strong class="text-primary fs-5">
                        Rp {{ number_format($total) }}
                    </strong>
                </div>

                <div class="d-grid gap-2">
                    <a href="/pos/payment" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card me-2"></i>Proses Pembayaran
                    </a>
                    <form action="{{ route('pos.clear') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

</div>
@endsection

@push('styles')
<style>
/* CARD KERANJANG */
.pos-cart {
    height: calc(100vh - 110px); /* header + navbar */
    display: flex;
    flex-direction: column;
}

/* BODY KERANJANG */
.cart-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 0;
    overflow: hidden;
}

/* LIST ITEM → SCROLL */
.cart-items {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

/* TOTAL → FIX BAWAH */
.cart-total {
    border-top: 1px solid #dee2e6;
    padding: 1rem;
    background: #fff;
    box-shadow: 0 -2px 6px rgba(0,0,0,0.05);
}

@media (max-width: 768px) {
    .pos-cart {
        height: calc(100vh - 90px);
    }
}

</style>
@endpush

<script>
    const cart = document.getElementById('cartItems');
    if (cart) {
        cart.scrollBottom = cart.scrollHeight;
    }
</script>

