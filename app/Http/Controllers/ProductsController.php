<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Products::with('category');

        // ====== FILTER SEARCH ======
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('barcode', 'like', '%' . $request->search . '%');
        }

        // ====== FILTER KATEGORI ======
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Pagination
        $products = $query->paginate(5)->appends($request->query());

        $category = Category::all(); // untuk dropdown

        // eager load relasi 'category' supaya tidak terjadi query berulang (N+1)
        // $products = Products::with('category')->paginate($max_data);

        // jika view form create ada di halaman ini (modal), kirim juga kategori
        return view('products.product', compact('products', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form tambah data
        return view('partials.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'nullable|digits:13|unique:products,barcode',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:category,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // barcode otomatis
        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateEAN13();
        }

        // 🔽 upload foto
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                                        ->store('products', 'public');
        }

        try {
            Products::create($validated);
        } catch (\Illuminate\Database\QueryException $e) {

            if (str_contains($e->getMessage(), 'UNIQUE')) {
                $validated['barcode'] = $this->generateEAN13();
                Products::create($validated);
            } else {
                throw $e;
            }
        }

        return redirect()->route('products.index')
            ->with('success', 'Product berhasil ditambahkan');
    }



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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::findOrFail($id);
        $category = Category::all(); // untuk dropdown kategori

        return view('products.edit', compact('product', 'category'));
    }

  /**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    $product = Products::findOrFail($id);

    $validated = $request->validate([
        'barcode'     => 'nullable|digits:13|unique:products,barcode,' . $product->id,
        'name'        => 'required',
        'price'       => 'required|numeric',
        'stock'       => 'required|integer',
        'category_id' => 'required|exists:category,id',
        'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'delete_photo' => 'nullable|boolean',
    ]);

    // ===== BARCODE =====
    if (empty($validated['barcode'])) {
        $validated['barcode'] = $this->generateEAN13();
    }

    // ===== HAPUS FOTO SAAT INI JIKA DI CHECKBOX =====
    if ($request->has('delete_photo') && $request->delete_photo == '1') {
        // hapus foto lama jika ada
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }
        $validated['photo'] = null;
    }
    // ===== FOTO BARU =====
    else if ($request->hasFile('photo')) {
        // hapus foto lama jika ada
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        // simpan foto baru
        $validated['photo'] = $request->file('photo')
            ->store('products', 'public');
    } else {
        // jika tidak ada file baru dan tidak menghapus foto, pertahankan foto lama
        $validated['photo'] = $product->photo;
    }

    $product->update($validated);

    return redirect()
        ->route('products.index')
        ->with('success', 'Produk berhasil diperbarui');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $product = Products::findOrFail($id);

    // hapus foto
    if ($product->photo && Storage::disk('public')->exists($product->photo)) {
        Storage::disk('public')->delete($product->photo);
    }

    $product->delete();

    return redirect()->route('products.index')
        ->with('success', 'Produk berhasil dihapus!');
}

}
