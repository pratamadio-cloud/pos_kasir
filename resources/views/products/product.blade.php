@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam me-2"></i>Daftar Produk
                </h5>
                <!-- GANTI LINK DENGAN BUTTON UNTUK MEMBUKA MODAL -->
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </button>
            </div>
            <div class="card-body">
                <!-- Filter dan Pencarian - DIPERBAIKI -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari produk..." id="searchInput">
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

                <!-- Tabel Produk - BIARKAN ADA SEBAGAI CONTOH -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Barcode</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Produk 1 -->
                            <tr>
                                <td>1</td>
                                <td>
                                    <span class="badge bg-light text-dark">8997009510023</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup-straw" style="font-size: 1.5rem; color: #4361ee;"></i>
                                        </div>
                                        <div>
                                            <strong>Kopi Hitam</strong><br>
                                            <small class="text-muted">Minuman</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>RP 15,000</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">25</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/products/edit" class="btn btn-outline-primary" 
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/products/hapus" class="btn btn-outline-danger"
                                           data-bs-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-info"
                                           data-bs-toggle="tooltip" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Produk 2 -->
                            <tr>
                                <td>2</td>
                                <td>
                                    <span class="badge bg-light text-dark">8997009510024</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup" style="font-size: 1.5rem; color: #4361ee;"></i>
                                        </div>
                                        <div>
                                            <strong>Teh Manis</strong><br>
                                            <small class="text-muted">Minuman</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>RP 10,000</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">42</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Produk 3 -->
                            <tr>
                                <td>3</td>
                                <td>
                                    <span class="badge bg-light text-dark">8997009510025</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cake2" style="font-size: 1.5rem; color: #4361ee;"></i>
                                        </div>
                                        <div>
                                            <strong>Roti Bakar</strong><br>
                                            <small class="text-muted">Makanan</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>RP 20,000</strong>
                                </td>
                                <td>
                                    <span class="badge bg-warning">5</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
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

<script>
    // Fungsi untuk reset filter
    function resetFilters() {
        document.getElementById('searchInput').value = '';
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
        document.getElementById('searchInput').addEventListener('input', function() {
            applyFilters();
        });
        
        document.getElementById('categoryFilter').addEventListener('change', function() {
            applyFilters();
        });
        
        document.getElementById('statusFilter').addEventListener('change', function() {
            applyFilters();
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // Fungsi untuk menerapkan filter
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        // Implementasi filter di sini (contoh sederhana)
        console.log('Filter diterapkan:', { searchTerm, category, status });
        
        // Untuk implementasi lengkap, bisa ditambahkan logika filtering tabel
        // showAlert('Filter diterapkan!', 'info');
    }
</script>

<style>
    /* Styling tambahan untuk filter */
    .form-select:focus, .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
</style>
@endsection