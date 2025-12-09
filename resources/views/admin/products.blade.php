@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h3>Manajemen Produk</h3>
        <p class="text-muted">Kelola produk dan inventori</p>
    </div>
</div>

<!-- Product Management Card -->
<div class="row">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i>Daftar Produk
                </h5>
                <button type="button" class="btn btn-admin" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </button>
            </div>
            <div class="card-body">
                <!-- Search and Filter - DIUBAH MENJADI SAMA DENGAN SEBELUMNYA -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari produk..." id="searchProduct">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            <option value="minuman">Minuman</option>
                            <option value="makanan">Makanan</option>
                            <option value="snack">Snack</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="stok_sedikit">Stok Sedikit</option>
                            <option value="stok_habis">Stok Habis</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-outline-secondary" onclick="resetFilters()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </button>
                    </div>
                </div>

                <!-- Products Table - BIARKAN ADA -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Barcode</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 8; $i++)
                            @php
                                $products = [
                                    ['name' => 'Kopi Hitam', 'category' => 'minuman', 'price' => 15000, 'stock' => 25, 'status' => 'active'],
                                    ['name' => 'Teh Manis', 'category' => 'minuman', 'price' => 10000, 'stock' => 42, 'status' => 'active'],
                                    ['name' => 'Roti Bakar', 'category' => 'makanan', 'price' => 20000, 'stock' => 5, 'status' => 'low'],
                                    ['name' => 'Air Mineral', 'category' => 'minuman', 'price' => 5000, 'stock' => 100, 'status' => 'active'],
                                ];
                                $product = $products[array_rand($products)];
                                $statusClass = $product['status'] === 'active' ? 'bg-success' : 'bg-warning';
                                $statusText = $product['status'] === 'active' ? 'Aktif' : 'Stok Rendah';
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup-straw" style="font-size: 1.5rem; color: #4361ee;"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $product['name'] }}</strong><br>
                                            <small class="text-muted">SKU: PROD{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">8997009510{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($product['category']) }}</span>
                                </td>
                                <td>
                                    <strong>RP {{ number_format($product['price'], 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($product['stock'] > 20)
                                        <span class="badge bg-success">{{ $product['stock'] }}</span>
                                    @elseif($product['stock'] > 10)
                                        <span class="badge bg-warning">{{ $product['stock'] }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $product['stock'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#editProductModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Pagination - BIARKAN ADA -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- INCLUDE MODAL TAMBAH PRODUK DARI FILE TERPISAH -->
@include('partials.add-product-modal')

<!-- Edit Product Modal - BIARKAN ADA -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk *</label>
                        <input type="text" class="form-control" value="Kopi Hitam" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <input type="text" class="form-control" value="8997009510023">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga *</label>
                            <input type="number" class="form-control" value="15000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok *</label>
                            <input type="number" class="form-control" value="25" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" required>
                                <option value="minuman" selected>Minuman</option>
                                <option value="makanan">Makanan</option>
                                <option value="snack">Snack</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="active" selected>Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="2">Kopi hitam dengan rasa pahit yang khas</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="editProductForm" class="btn btn-admin">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Form submission for edit product
    document.getElementById('editProductForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
            modal.hide();
            
            alert('Produk berhasil diperbarui!');
        }, 1500);
    });
    
    // Fungsi untuk reset filter
    function resetFilters() {
        document.getElementById('searchProduct').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('statusFilter').value = '';
        
        // Tampilkan alert sukses
        showAlert('Filter berhasil direset!', 'success');
    }
    
    // Fungsi untuk menampilkan alert
    function showAlert(message, type) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) existingAlert.remove();
        
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
        alertDiv.innerHTML = `
            <i class="bi ${type === 'danger' ? 'bi-exclamation-triangle' : 'bi-check-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after filter section
        const filterSection = document.querySelector('.row.mb-4');
        filterSection.parentNode.insertBefore(alertDiv, filterSection.nextSibling);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Event listener untuk filter
    document.addEventListener('DOMContentLoaded', function() {
        // Filter saat input berubah
        document.getElementById('searchProduct').addEventListener('input', function() {
            applyFilters();
        });
        
        document.getElementById('categoryFilter').addEventListener('change', function() {
            applyFilters();
        });
        
        document.getElementById('statusFilter').addEventListener('change', function() {
            applyFilters();
        });
    });
    
    // Fungsi untuk menerapkan filter
    function applyFilters() {
        const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        // Implementasi filter di sini
        console.log('Filter diterapkan:', { searchTerm, category, status });
        
        // Contoh: filter sederhana pada tabel
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const productName = row.querySelector('td:nth-child(1) strong').textContent.toLowerCase();
            const productCategory = row.querySelector('td:nth-child(3) .badge').textContent.toLowerCase();
            const productStock = parseInt(row.querySelector('td:nth-child(5) .badge').textContent);
            const productStatus = row.querySelector('td:nth-child(6) .badge').textContent.toLowerCase();
            
            let matchesSearch = true;
            let matchesCategory = true;
            let matchesStatus = true;
            
            // Filter pencarian
            if (searchTerm && !productName.includes(searchTerm)) {
                matchesSearch = false;
            }
            
            // Filter kategori
            if (category && productCategory !== category) {
                matchesCategory = false;
            }
            
            // Filter status
            if (status === 'stok_sedikit' && productStock >= 10) {
                matchesStatus = false;
            } else if (status === 'stok_habis' && productStock > 0) {
                matchesStatus = false;
            } else if (status === 'aktif' && productStatus !== 'aktif') {
                matchesStatus = false;
            } else if (status === 'nonaktif' && productStatus !== 'nonaktif') {
                matchesStatus = false;
            }
            
            // Tampilkan/sembunyikan baris
            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<style>
    /* Styling untuk filter */
    .form-select:focus, .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    /* Styling untuk input group */
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
</style>
@endsection