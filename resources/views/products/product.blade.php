@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-box-seam me-2"></i>Daftar Produk
                    </h5>
                    <small class="opacity-75">Total {{ $products->total() }} produk ditemukan</small>
                </div>
                <button type="button" class="btn btn-light btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#create">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Produk
                </button>
            </div>
            <div class="card-body p-4">
                <!-- Filter dan Pencarian -->
                <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <!-- SEARCH -->
                        <div class="col-md-5">
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 border-end-0 py-2" 
                                       placeholder="Cari produk berdasarkan nama atau barcode..."
                                       name="search" value="{{ request('search') }}">
                                @if(request('search'))
                                <button class="btn btn-outline-secondary" type="button" onclick="window.location.href='{{ route('products.index') }}'">
                                    <i class="bi bi-x"></i>
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- KATEGORI -->
                        <div class="col-md-3">
                            <select name="category_id" class="form-select form-select-lg shadow-sm" 
                                    onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach ($category as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- RESET FILTER -->
                        @if(request('search') || request('category_id'))
                        <div class="col-md-2">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                        </div>
                        @endif
                    </div>
                </form>

                <!-- Tabel Produk -->
                <div class="table-responsive rounded-3 border shadow-sm">
                    <table class="table table-hover mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th width="50" class="ps-4">No</th>
                                <th>Barcode</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th width="120" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                            <tr class="align-middle">
                                <td class="ps-4 fw-bold text-primary">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="bi bi-upc-scan me-1"></i>{{ $item->barcode }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-3">
                                            <i class="bi bi-cup-straw text-primary" style="font-size: 1.25rem;"></i>
                                        </div> --}}
                                        <div>
                                            <strong class="d-block">{{ $item->name }}</strong>
                                            <small class="text-muted">
                                                <i class="bi bi-tag me-1"></i>
                                                {{ $item->category ? $item->category->category_name : 'Tanpa Kategori' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-success fs-5">Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                        <small class="text-muted">Per unit</small>
                                    </div>
                                </td>
                                <td>
                                    @if($item->stock > 20)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>{{ $item->stock }} pcs
                                        </span>
                                    @elseif($item->stock > 0)
                                        <span class="badge bg-warning rounded-pill px-3 py-2">
                                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $item->stock }} pcs
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('products.edit', $item->id) }}" 
                                           class="btn btn-outline-primary border-2 px-3" 
                                           data-bs-toggle="tooltip" title="Edit Produk">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $item->id) }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger border-2 px-3"
                                                    data-bs-toggle="tooltip" title="Hapus Produk">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-box-seam display-4"></i>
                                        <h5 class="mt-3">Tidak ada produk ditemukan</h5>
                                        <p>Mulai dengan menambahkan produk baru</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                    </div>
                    <nav aria-label="Page navigation">
                        {{ $products->links() }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- INCLUDE MODAL TAMBAH PRODUK DARI FILE TERPISAH -->
@include('partials.create')

<script>
    // Event listener untuk filter
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<style>
    /* Styling tambahan untuk tampilan yang lebih baik */
    .card {
        border-radius: 12px;
    }
    
    .table-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table-primary th {
        border: none;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateY(-2px);
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-outline-primary:hover {
        background-color: #667eea;
        color: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .pagination .page-link {
        border-radius: 6px;
        margin: 0 3px;
        border: 1px solid #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endsection