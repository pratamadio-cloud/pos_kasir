<div class="modal fade" id="create" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- <div class="col-12">
                            <label class="form-label">Barcode (Opsional)</label>
                            <input type="text" class="form-control" id="productBarcode" 
                                   placeholder="Kode barcode produk" name="barcode">
                        </div> --}}
                        <div class="col-12">
                            <label class="form-label">Nama Produk *</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" id="productCategory" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($category as $c)
                                    <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Harga (Rp) *</label>
                            <input type="number" class="form-control" id="productPrice" name="price" required min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Stok Awal *</label>
                            <input type="number" class="form-control" id="productStock" name="stock" required min="0">
                        </div>
                        
                        {{-- <div class="col-12">
                            <label class="form-label">Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="productDescription" rows="2"></textarea>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" onclick="saveNewProduct()">
                            <i class="bi bi-save me-1"></i>Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
    function showAddProductModal() {
        const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
        modal.show();
        
        // Clear form
        document.getElementById('addProductForm').reset();
        
        // Focus on name field
        setTimeout(() => {
            document.getElementById('productName').focus();
        }, 500);
    }
    
    function saveNewProduct() {
        const name = document.getElementById('productName').value.trim();
        const category = document.getElementById('productCategory').value;
        const price = parseInt(document.getElementById('productPrice').value) || 0;
        const stock = parseInt(document.getElementById('productStock').value) || 0;
        const barcode = document.getElementById('productBarcode').value.trim();
        const description = document.getElementById('productDescription').value.trim();
        
        // Validation
        if (!name) {
            alert('Nama produk harus diisi!');
            return;
        }
        if (!category) {
            alert('Kategori harus dipilih!');
            return;
        }
        if (price <= 0) {
            alert('Harga harus lebih dari 0!');
            return;
        }
        
        // Get existing products
        let products = JSON.parse(localStorage.getItem('pos_products') || '[]');
        
        // Generate new ID
        const newId = products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
        
        // Add new product
        const newProduct = {
            id: newId,
            name: name,
            category: category,
            price: price,
            stock: stock,
            barcode: barcode || null,
            description: description || null,
            status: 'active',
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString()
        };
        
        products.push(newProduct);
        localStorage.setItem('pos_products', JSON.stringify(products));
        
        // Show success message
        alert(`Produk "${name}" berhasil ditambahkan!`);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
        modal.hide();
        
        // Reload products
        loadProducts();
    }
</script>