@extends('layouts.app')

@section('title', 'Kasir POS')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Input Produk -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-upc-scan me-2"></i>Input Produk
                </h5>
            </div>
            <div class="card-body">
                <!-- Barcode Scanner -->
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
                                <div class="card-body text-center">
                                    {{-- <div class="mb-2">
                                        <i class="bi bi-cup-straw" style="font-size: 2rem; color: #4361ee;"></i>
                                    </div> --}}
                                    <h6 class="card-title mb-1">{{ $item->name }}</h6>
                                    <p class="text-muted mb-1">{{ $item->price }}</p>
                                    <small class="text-success">{{ $item->stock }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- {{ $products->links() }} --}}
                    {{ $products->appends(request()->query())->links() }}
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Keranjang Belanja -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
                </h5>
            </div>
            <div class="card-body">
                <!-- Daftar Item di Cart -->
                <div class="cart-items mb-3">
                    <!-- Item 1 -->
                    <div class="pos-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Kopi Hitam</h6>
                                <small class="text-muted">RP 15,000</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <span class="mx-2">2</span>
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="bi bi-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger ms-3" type="button">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-end mt-1">
                            <span class="fw-bold subtotal">RP 30,000</span>
                        </div>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="pos-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Teh Manis</h6>
                                <small class="text-muted">RP 10,000</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <span class="mx-2">1</span>
                                <button class="btn btn-sm btn-outline-secondary" type="button">
                                    <i class="bi bi-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger ms-3" type="button">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-end mt-1">
                            <span class="fw-bold subtotal">RP 10,000</span>
                        </div>
                    </div>
                </div>

                <!-- Total Pembayaran -->
                <div class="cart-total">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Item:</span>
                        <span class="fw-bold">3</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Belanja:</span>
                        <span class="fw-bold fs-5 text-primary" id="total-amount">RP 40,000</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/pos/payment" class="btn btn-primary btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Proses Pembayaran
                        </a>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Menambahkan produk ke cart
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            // Simulasi penambahan produk
            alert('Produk ditambahkan ke keranjang!');
        });
    });
</script>
@endsection