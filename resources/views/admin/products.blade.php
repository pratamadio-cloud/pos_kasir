@extends('layouts.admin')

@section('title', 'Manajemen Produk - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-box-seam me-2"></i>Manajemen Produk
            </h1>
            <p class="text-muted mb-0">Kelola produk dan inventori toko</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle me-1"></i>Tambah Produk
            </button>
        </div>
    </div>

    <!-- Alert Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari Produk</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama atau barcode...">
                </div>
                
                <div class="col-md-2">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status Stok</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="stok_sedikit" {{ request('status') == 'stok_sedikit' ? 'selected' : '' }}>Stok Sedikit</option>
                        <option value="stok_habis" {{ request('status') == 'stok_habis' ? 'selected' : '' }}>Stok Habis</option>
                        <option value="stok_tertinggi" {{ request('status') == 'stok_tertinggi' ? 'selected' : '' }}>Stok Tertinggi</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="sort" class="form-label">Urutkan</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="">Default</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Barcode</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Tanggal Ditambahkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                            <td>
                                @if($product->photo)
                                    <img src="{{ Storage::url($product->photo) }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace">{{ $product->barcode }}</span>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-info">{{ $product->category->category_name }}</span>
                                @else
                                    <span class="badge bg-secondary">Tidak ada kategori</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($product->stock > 20)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 10)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-danger">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-muted small">
                                    {{ $product->created_at ? $product->created_at->format('d/m/Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="Edit" data-bs-toggle="modal" 
                                            data-bs-target="#editProductModal" 
                                            onclick="loadEditData({{ $product->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-success" title="Tambah Stok" 
                                            data-bs-toggle="modal" data-bs-target="#addStockModal"
                                            onclick="setProductForStock({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus produk {{ $product->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-box-seam display-6"></i>
                                    <p class="mt-2">Belum ada produk</p>
                                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                        <i class="bi bi-plus-circle me-1"></i>Tambah Produk Pertama
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Sebelumnya">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        
                        @php
                            $current = $products->currentPage();
                            $last = $products->lastPage();
                            $maxVisible = 8;
                            $half = floor($maxVisible / 2);
                            
                            $start = max(1, $current - $half);
                            $end = min($last, $start + $maxVisible - 1);
                            
                            if ($end - $start + 1 < $maxVisible) {
                                $start = max(1, $end - $maxVisible + 1);
                            }
                            
                            if ($start > 1) {
                                echo '<li class="page-item"><a class="page-link" href="' . $products->url(1) . '">1</a></li>';
                                if ($start > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            for ($i = $start; $i <= $end; $i++) {
                                $active = $i == $current ? 'active' : '';
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $products->url($i) . '">' . $i . '</a></li>';
                            }
                            
                            if ($end < $last) {
                                if ($end < $last - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="' . $products->url($last) . '">' . $last . '</a></li>';
                            }
                        @endphp
                        
                        <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Selanjutnya">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Tambah Produk -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label">Barcode (EAN13)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="barcode" 
                                   pattern="\d{13}" maxlength="13" 
                                   placeholder="13 digit barcode">
                            <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                                <i class="bi bi-upc"></i> Generate
                            </button>
                        </div>
                        <div class="form-text">
                            Kosongkan untuk generate otomatis. Format: 13 digit angka EAN13
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga *</label>
                            <input type="number" class="form-control" name="price" required min="0" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Awal *</label>
                            <input type="number" class="form-control" name="stock" required min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Produk (Opsional)</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal: 2MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Produk -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Tambah Stok -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Stok
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h6 id="productName"></h6>
                        <p class="text-muted mb-0">Stok saat ini: <strong id="currentStock"></strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Tambahan *</label>
                        <input type="number" class="form-control" name="quantity" required min="1" value="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" name="note" rows="2" placeholder="Contoh: Restok dari supplier..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i>Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

 {{-- <div class="mb-3">
                    <label class="form-label">Barcode (EAN13) *</label>
                    <div class="input-group">
                        <input type="hidden" class="form-control" name="barcode" 
                               value="${escapeHtml(data.product.barcode)}" 
                               pattern="\\d{13}" maxlength="13" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="generateBarcodeForEdit()">
                            <i class="bi bi-upc"></i> Generate
                        </button>
                    </div> --}}

@endsection

@section('scripts')
<script>
    // CSRF Token untuk AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // ========== FUNGSI PRODUK ==========
    
    // Generate barcode otomatis
    function generateBarcode() {
        // Generate 12 angka random
        let number = '';
        for (let i = 0; i < 12; i++) {
            number += Math.floor(Math.random() * 10);
        }
        
        // Hitung checksum EAN13
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            let digit = parseInt(number[i]);
            sum += (i % 2 === 0) ? digit : (digit * 3);
        }
        
        let checksum = (10 - (sum % 10)) % 10;
        let barcode = number + checksum;
        
        // Set nilai ke input
        const barcodeInput = document.querySelector('#addProductModal input[name="barcode"]');
        if (barcodeInput) {
            barcodeInput.value = barcode;
        }
    }
    
    // Load data untuk edit modal
    function loadEditData(productId) {
        // Tampilkan loading
        const modalBody = document.getElementById('editModalBody');
        modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        fetch(`/admin/products/${productId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Produk tidak ditemukan');
                }
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            const form = document.getElementById('editProductForm');
            form.action = `/admin/products/${productId}`;
            
            const hasPhoto = data.product.photo ? true : false;
            const photoUrl = hasPhoto ? `/storage/${escapeHtml(data.product.photo)}` : '';
            
            modalBody.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" class="form-control" name="name" value="${escapeHtml(data.product.name)}" required>
                </div>
            
                        <input type="hidden" class="form-control" name="barcode" 
                               value="${escapeHtml(data.product.barcode)}" 
                               pattern="\\d{13}" maxlength="13" required>
                     
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga *</label>
                        <input type="number" class="form-control" name="price" value="${data.product.price}" required min="0" step="0.01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok *</label>
                        <input type="number" class="form-control" name="stock" value="${data.product.stock}" required min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori *</label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        ${data.categories.map(cat => `
                            <option value="${cat.id}" ${cat.id == data.product.category_id ? 'selected' : ''}>
                                ${escapeHtml(cat.category_name)}
                            </option>
                        `).join('')}
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Produk</label>
                    
                    ${hasPhoto ? `
                        <div class="mb-2">
                            <img src="${photoUrl}" alt="Current Photo" 
                                 class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="removePhoto">
                                <label class="form-check-label text-danger" for="removePhoto">
                                    Hapus foto saat ini
                                </label>
                            </div>
                        </div>
                        <div class="mt-2">
                    ` : ''}
                    
                    <input type="file" class="form-control" name="photo" accept="image/*">
                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal: 2MB</div>
                    
                    ${hasPhoto ? `</div>` : ''}
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${escapeHtml(error.message)}
                </div>
            `;
        });
    }

    // Generate barcode untuk edit modal
    function generateBarcodeForEdit() {
        // Generate 12 angka random
        let number = '';
        for (let i = 0; i < 12; i++) {
            number += Math.floor(Math.random() * 10);
        }
        
        // Hitung checksum EAN13
        let sum = 0;
        for (let i = 0; i < 12; i++) {
            let digit = parseInt(number[i]);
            sum += (i % 2 === 0) ? digit : (digit * 3);
        }
        
        let checksum = (10 - (sum % 10)) % 10;
        let barcode = number + checksum;
        
        // Set nilai ke input di edit modal
        const barcodeInput = document.querySelector('#editProductForm input[name="barcode"]');
        if (barcodeInput) {
            barcodeInput.value = barcode;
        }
    }

    // Helper function untuk escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Set data untuk tambah stok modal
    function setProductForStock(productId, productName, currentStock) {
        document.getElementById('productName').textContent = productName;
        document.getElementById('currentStock').textContent = currentStock;
        document.getElementById('addStockForm').action = `/admin/products/${productId}/add-stock`;
    }

    // ========== EVENT LISTENERS ==========
    
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus pada modal
        const addProductModal = document.getElementById('addProductModal');
        if (addProductModal) {
            addProductModal.addEventListener('shown.bs.modal', function() {
                this.querySelector('input[name="name"]')?.focus();
            });
        }
        
        const addStockModal = document.getElementById('addStockModal');
        if (addStockModal) {
            addStockModal.addEventListener('shown.bs.modal', function() {
                this.querySelector('input[name="quantity"]')?.focus();
            });
        }
        
        // Reset modal edit
        const editModal = document.getElementById('editProductModal');
        if (editModal) {
            editModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('editModalBody').innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
            });
        }
        
        // Validasi input barcode (hanya angka, max 13 digit)
        document.addEventListener('input', function(e) {
            if (e.target.name === 'barcode') {
                // Hanya angka
                e.target.value = e.target.value.replace(/\D/g, '');
                // Max 13 digit
                if (e.target.value.length > 13) {
                    e.target.value = e.target.value.substring(0, 13);
                }
            }
        });
        
        // Auto dismiss alert setelah 5 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                try {
                    bsAlert.close();
                } catch (e) {
                    // Fallback jika Bootstrap Alert tidak tersedia
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            });
        }, 5000);
    });
</script>

<style>
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    
    .page-link {
        border-radius: 4px;
        margin: 0 2px;
    }
    
    .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .btn-outline-danger:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-outline-danger:not(:disabled):hover {
        background-color: #dc3545;
        color: white;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    /* Animation untuk alert */
    .alert {
        transition: opacity 0.5s ease;
    }
    
    .alert.fade {
        opacity: 0;
    }
    
    /* Image styling */
    .product-image {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: transform 0.2s;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    /* Barcode input styling */
    input[name="barcode"] {
        font-family: monospace;
        letter-spacing: 1px;
    }
</style>
@endsection