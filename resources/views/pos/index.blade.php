@extends('layouts.app')

@section('title', 'Kasir POS')

@section('content')
<div class="row pos-wrapper">
    <!-- Kolom Kiri: Input Produk -->
    <div class="{{ $hasCart ? 'col-md-8' : 'col-md-12' }}">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-upc-scan me-2"></i>Input Produk
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <label for="barcode" class="form-label fw-semibold">Cari Produk</label>
                    <form action="{{ route('pos.index') }}" method="GET">
                        @csrf
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-upc-scan text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" id="barcode" 
                                   placeholder="Scan barcode atau ketik kode/nama produk" 
                                   name="search" value="{{ request('search') }}" autofocus>
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Produk -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0 text-dark">Daftar Produk Cepat</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $products->total() }} produk tersedia
                        </span>
                    </div>
                    
                    <div class="row g-3">
                        @foreach ($products as $item)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card product-card h-100 border-0 shadow-sm hover-shadow transition-all" 
                                 style="cursor: pointer; border-radius: 12px;"
                                 onclick="document.getElementById('add-{{ $item->id }}').submit()">
                                <form id="add-{{ $item->id }}" action="{{ route('pos.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                                </form>
                                
                                <!-- Product Badge -->
                                @if($item->stock <= 20)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-danger bg-opacity-90 text-white">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Hampir Habis
                                    </span>
                                </div>
                                @elseif($item->stock >= 21)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success bg-opacity-90 text-white">
                                        <i class="bi bi-check-circle-fill me-1"></i>Tersedia
                                    </span>
                                </div>
                                @endif
                                
                                <!-- Product Image Placeholder -->
                                <div class="product-image-placeholder bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 140px; border-radius: 12px 12px 0 0;">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}"
                                            alt="{{ $item->name }}"
                                            class="img-fluid"
                                            style="max-height: 140px; object-fit: cover; width: 100%;">
                                    @else
                                        <div class="text-center">
                                            <i class="bi bi-cup-hot-fill display-6 text-primary opacity-50"></i>
                                            <p class="text-muted mt-2 mb-0 small">Produk</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-body p-3">
                                    <h6 class="card-title fw-bold mb-2 text-truncate" title="{{ $item->name }}">
                                        {{ $item->name }}
                                    </h6>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-primary fs-5">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-box-seam me-1"></i>{{ $item->stock }}
                                        </span>
                                    </div>
                                    
                                    <!-- Quick Add Button -->
                                    <div class="d-grid mt-3">
                                        <button class="btn btn-outline-primary btn-sm fw-semibold">
                                            <i class="bi bi-plus-circle me-1"></i>Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="mt-5">
                        <nav aria-label="Product pagination">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                                </div>
                                <ul class="pagination pagination-sm mb-0">
                                    {{ $products->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </ul>
                            </div>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($hasCart)
    <div class="col-md-4">
        <div class="card pos-cart shadow-sm border-0">
            <div class="card-header bg-gradient-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
                    <span class="badge bg-white text-success ms-2">{{ $total_item }}</span>
                </h5>
            </div>

            {{-- BODY FLEX --}}
            <div class="card-body cart-body p-0">

                {{-- CART ITEMS (SCROLL) --}}
                <div class="cart-items p-3" id="cartItems">
                    @forelse ($cart as $productId => $item)
                        @php $subtotal = $item['qty'] * $item['price']; @endphp

                        <div class="pos-item mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start mb-1">
                                        <div class="me-2">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 36px; height: 36px;">
                                                <i class="bi bi-cup text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <strong class="d-block mb-1">{{ $item['name'] }}</strong>
                                            <small class="text-muted d-block">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <form action="{{ route('pos.update') }}" method="POST" class="mb-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $productId }}">
                                            <input type="hidden" name="action" value="minus">
                                            <button class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center" 
                                                    style="width: 28px; height: 28px; padding: 0;">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                        </form>

                                        <span class="fw-bold mx-2" style="min-width: 20px; text-align: center;">
                                            {{ $item['qty'] }}
                                        </span>

                                        <form action="{{ route('pos.update') }}" method="POST" class="mb-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $productId }}">
                                            <input type="hidden" name="action" value="plus">
                                            <button class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center" 
                                                    style="width: 28px; height: 28px; padding: 0;">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </span>
                                        <form action="{{ route('pos.remove') }}" method="POST" class="mb-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $productId }}">
                                            <button class="btn btn-sm btn-outline-danger border-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-cart-x display-4 text-muted opacity-50"></i>
                            </div>
                            <p class="text-muted mb-0">Keranjang belanja kosong</p>
                            <small class="text-muted">Tambahkan produk untuk memulai transaksi</small>
                        </div>
                    @endforelse
                </div>

                {{-- TOTAL (STICKY BOTTOM) --}}
                @if($total_item > 0)
                <div class="cart-total">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Item</span>
                        <strong class="fs-6">{{ $total_item }}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Belanja</span>
                        <strong class="text-primary fs-4 fw-bold">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('payment.index') }}" class="btn btn-primary btn-lg py-3 fw-semibold shadow-sm">
                            <i class="bi bi-credit-card-fill me-2"></i>Proses Pembayaran
                        </a>
                        <form action="{{ route('pos.clear') }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger btn-lg py-3 fw-semibold">
                                <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                            </button>
                        </form>
                    </div>
                </div>
                @endif
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
    height: calc(100vh - 110px);
    display: flex;
    flex-direction: column;
    border-radius: 16px;
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
    border-top: 1px solid #e9ecef;
    padding: 1.5rem;
    background: linear-gradient(to bottom, #fff, #f8f9fa);
    backdrop-filter: blur(10px);
}

/* PRODUCT CARD HOVER EFFECT */
.product-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.08);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    border-color: var(--bs-primary);
}

.product-card:hover .product-image-placeholder {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.product-image-placeholder {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

/* BADGE STYLES */
.badge {
    font-weight: 500;
    padding: 0.4em 0.8em;
}

/* INPUT GROUP STYLES */
.input-group-lg .form-control {
    border-radius: 0 0.375rem 0.375rem 0 !important;
}

.input-group-lg .input-group-text {
    border-radius: 0.375rem 0 0 0.375rem !important;
}

/* SCROLLBAR STYLING */
.cart-items::-webkit-scrollbar {
    width: 6px;
}

.cart-items::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.cart-items::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.cart-items::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* GRADIENT HEADERS */
.bg-gradient-primary {
    background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .pos-cart {
        height: calc(100vh - 90px);
        border-radius: 12px;
    }
    
    .product-card {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .card-header h5 {
        font-size: 1rem;
    }
    
    .cart-total .btn-lg {
        padding: 0.75rem !important;
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
    // Auto scroll to bottom of cart
    const cartContainer = document.getElementById('cartItems');
    if (cartContainer) {
        cartContainer.scrollTop = cartContainer.scrollHeight;
    }

    // Product card hover effect enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });

        // Focus barcode input on page load
        const barcodeInput = document.getElementById('barcode');
        if (barcodeInput) {
            setTimeout(() => {
                barcodeInput.focus();
            }, 100);
        }
    });
</script>
@endpush