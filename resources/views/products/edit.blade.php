@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Edit Produk
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Barcode -->
                        <div class="col-md-6 mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                        </div>

                        <!-- Nama Produk -->
                        <div class="col-md-6 mb-3">
                            <label for="productName" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="productName" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <!-- Harga -->
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">RP</span>
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            </div>
                        </div>

                        <!-- Stok -->
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" id="category">
                                @foreach ($category as $c)
                                <option value="{{ $c->id }}"
                                    {{ $product->category_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Batal
                        </a>
                        <div>
                            <button type="reset" class="btn btn-danger me-2">
                                <i class="bi bi-trash me-2"></i>Hapus
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection