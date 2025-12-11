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
    public function index()
    {
        $products = Products::all();
        $category = Category::all();

        // eager load relasi 'category' supaya tidak terjadi query berulang (N+1)
        $products = Products::with('category')->get();

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
        // Validasi form tambah data
        $validated = $request->validate([
            'barcode' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:category,id',
        ]);

        // Simpan ke database
        Products::create($validated);

        // Redirect kembali
        return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
