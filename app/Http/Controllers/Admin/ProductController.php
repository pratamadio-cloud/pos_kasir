<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display admin products page.
     */
    public function index(Request $request)
    {
        $query = Products::with('category');
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        
        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter status stok
        if ($request->filled('status')) {
            if ($request->status == 'stok_sedikit') {
                $query->where('stock', '<=', 10)->where('stock', '>', 0);
            } elseif ($request->status == 'stok_habis') {
                $query->where('stock', '<=', 0);
            } elseif ($request->status == 'stok_tertinggi') {
                $query->where('stock', '>', 50)->orderBy('stock', 'desc');
            }
        }
        
        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc': $query->orderBy('name', 'asc'); break;
                case 'name_desc': $query->orderBy('name', 'desc'); break;
                case 'price_asc': $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                case 'stock_asc': $query->orderBy('stock', 'asc'); break;
                case 'stock_desc': $query->orderBy('stock', 'desc'); break;
                case 'newest': $query->orderBy('created_at', 'desc'); break;
                case 'oldest': $query->orderBy('created_at', 'asc'); break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(10);
        $categories = Category::all();
        
        return view('admin.products', compact('products', 'categories'));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'nullable|digits:13|unique:products,barcode',
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:category,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // generate barcode jika kosong
        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateEAN13();
        }

        // Handle upload foto
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            // Simpan ke storage
            $path = $photo->storeAs('products', $filename, 'public');
            $validated['photo'] = $path;
        }

        try {
            Products::create($validated);
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
            
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'UNIQUE')) {
                // jika barcode bentrok, generate ulang
                $validated['barcode'] = $this->generateEAN13();
                Products::create($validated);
                return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
            }
            return redirect()->route('admin.products.index')->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit product (untuk modal).
     */
    public function edit($id)
    {
        $product = Products::with('category')->findOrFail($id);
        $categories = Category::all();
        
        return response()->json([
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update a product.
     */
    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $validated = $request->validate([
            'barcode' => 'nullable|digits:13|unique:products,barcode,' . $product->id,
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:category,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        // generate barcode jika kosong
        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateEAN13();
        }

        // Handle remove foto
        if ($request->has('remove_photo') && $request->remove_photo == '1') {
            // Hapus foto lama jika ada
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $validated['photo'] = null;
        }
        // Handle upload foto baru
        elseif ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            // Simpan foto baru
            $path = $photo->storeAs('products', $filename, 'public');
            $validated['photo'] = $path;
            
            // Hapus foto lama jika ada
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
        } else {
            // Jika tidak ada upload baru, pertahankan foto lama
            unset($validated['photo']);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Add stock to product.
     */
    public function addStock(Request $request, $id)
    {
        $product = Products::findOrFail($id);
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255'
        ]);
        
        $oldStock = $product->stock;
        $product->stock += $request->quantity;
        $product->save();
        
        return redirect()->route('admin.products.index')
            ->with('success', "Stok <strong>{$product->name}</strong> berhasil ditambah <strong>{$request->quantity}</strong> unit. Stok sekarang: <strong>{$product->stock}</strong>");
    }

    /**
     * Delete a product.
     */
    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        
        // Hapus foto jika ada
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }
        
        $productName = $product->name;
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', "Produk <strong>{$productName}</strong> berhasil dihapus!");
    }

    /**
     * Generate EAN13 barcode.
     */
    private function generateEAN13()
    {
        // 12 angka pertama
        $number = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);

        // hitung checksum
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $number[$i];
            $sum += ($i % 2 === 0) ? $digit : ($digit * 3);
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return $number . $checksum;
    }

    /**
     * Get all categories (AJAX).
     */
    public function getCategories()
    {
        try {
            Log::info('API: getCategories dipanggil');
            
            // Query sederhana tanpa withCount jika error
            $categories = Category::select('id', 'category_name')->orderBy('category_name')->get();
            
            // Tambahkan count produk secara manual
            $categoriesWithCount = $categories->map(function($category) {
                $productCount = Products::where('category_id', $category->id)->count();
                return [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'products_count' => $productCount
                ];
            });
            
            Log::info('API: Berhasil mengambil ' . $categories->count() . ' kategori');
            
            return response()->json($categoriesWithCount);
            
        } catch (\Exception $e) {
            Log::error('API Error getCategories: ' . $e->getMessage());
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new category (AJAX).
     */
    public function storeCategory(Request $request)
    {
        try {
            Log::info('API: storeCategory dipanggil', ['data' => $request->all()]);
            
            // PERBAIKAN: Sesuaikan dengan struktur database (255 karakter)
            $request->validate([
                'category_name' => 'required|string|max:255|unique:category,category_name'
            ]);
            
            $category = Category::create([
                'category_name' => $request->category_name
            ]);
            
            Log::info('API: Kategori berhasil dibuat - ID: ' . $category->id);
            
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'products_count' => 0
                ],
                'message' => 'Kategori berhasil ditambahkan'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('API: Validasi gagal', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => $e->errors()['category_name'][0] ?? 'Validasi gagal'
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('API Error storeCategory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a category (AJAX).
     */
    public function destroyCategory($id)
    {
        try {
            Log::info('=== API DELETE CATEGORY START ===');
            Log::info('Category ID: ' . $id);
            
            // Cari kategori
            $category = Category::find($id);
            
            if (!$category) {
                Log::warning('API: Kategori tidak ditemukan - ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }
            
            Log::info('API: Kategori ditemukan - ' . $category->category_name);
            
            // Cek apakah kategori digunakan
            $productCount = Products::where('category_id', $id)->count();
            Log::info('API: Jumlah produk menggunakan kategori: ' . $productCount);
            
            if ($productCount > 0) {
                Log::info('API: Kategori tidak dapat dihapus - masih digunakan');
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $productCount . ' produk'
                ], 400);
            }
            
            // Hapus kategori
            $categoryName = $category->category_name;
            $category->delete();
            
            Log::info('API: Kategori berhasil dihapus - ' . $categoryName);
            Log::info('=== API DELETE CATEGORY END ===');
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori "' . $categoryName . '" berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Error destroyCategory: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}