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
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#create">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </button>
            </div>
            <div class="card-body">
                <!-- Filter dan Pencarian - DIPERBAIKI -->
                <form action="{{ route('products.index') }}" method="GET">
    <div class="row mb-4">
        
        <!-- SEARCH -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari produk..."
                    name="search" value="{{ request('search') }}">
            </div>
        </div>

        <!-- KATEGORI -->
        <div class="col-md-3">
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>

                @foreach ($category as $c)
                    <option value="{{ $c->id }}"
                        {{ request('category_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->category_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</form>


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
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Produk 1 -->
                            @foreach ($products as $item)
                                
                            
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $item->barcode }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-cup-straw" style="font-size: 1.5rem; color: #4361ee;"></i>
                                        </div> --}}
                                        <div>
                                            <strong>{{ $item->name }}</strong><br>
                                            <small class="text-muted">{{ $item->category ? $item->category->category_name : '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $item->price }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $item->stock }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button onclick="window.location.href='{{ route('products.edit', $item->id) }}'" class="btn btn-outline-primary" 
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                            data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
                
                {{-- <!-- Pagination -->
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
                </nav> --}}
            </div>
        </div>
    </div>
</div>

<!-- INCLUDE MODAL TAMBAH PRODUK DARI FILE TERPISAH -->
@include('partials.create')

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