<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

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
    ]);

    // jika tidak ada barcode disubmit, generate otomatis
    if (empty($validated['barcode'])) {
        $validated['barcode'] = $this->generateEAN13();
    }

    try {
        Products::create($validated);
    } catch (\Illuminate\Database\QueryException $e) {

        if (str_contains($e->getMessage(), 'UNIQUE')) {
            // jika barcode bentrok, generate ulang
            $validated['barcode'] = $this->generateEAN13();
            Products::create($validated);
        } else {
            throw $e;
        }
    }

    return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan');
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
        'barcode' => 'nullable|digits:13|unique:products,barcode,' . $product->id,
        'name' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:category,id',
    ]);

    // Jika barcode tidak diisi, generate otomatis
    if (empty($validated['barcode'])) {
        $validated['barcode'] = $this->generateEAN13();
    }

    $product->update($validated);

    return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Products::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil di hapus!');
    }
}
