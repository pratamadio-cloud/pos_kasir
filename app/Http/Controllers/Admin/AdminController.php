<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::query();
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        
        // Filter kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        // Filter status
        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'stok_sedikit':
                    $query->where('stock', '<', 10)->where('stock', '>', 0);
                    break;
                case 'stok_habis':
                    $query->where('stock', 0);
                    break;
                case 'aktif':
                    $query->where('stock', '>', 0);
                    break;
                case 'nonaktif':
                    $query->where('stock', 0);
                    break;
            }
        }
        
        // Sorting
        $sort = $request->get('sort', 'created_at_desc');
        switch ($request->get('sort')) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(15);
        
        // Stats
        $stats = [
            'total' => Products::count(),
            'low_stock' => Products::where('stock', '<', 10)->where('stock', '>', 0)->count(),
            'out_of_stock' => Products::where('stock', 0)->count(),
            'total_value' => Products::sum(DB::raw('price * stock'))
        ];
        
        return view('admin.products', compact('products', 'stats'));
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);
        
        // Logic untuk import produk
        // ...
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diimport');
    }
    
    public function export(Request $request)
    {
        // Logic untuk export produk ke Excel/CSV
        // ...
        
        return response()->download($filePath);
    }
    
    public function addStock(Request $request, Products $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product->stock += $request->quantity;
        $product->save();
        
        // Log stock adjustment
        // ...
        
        return redirect()->route('admin.products.index')
            ->with('success', "Stok {$product->name} berhasil ditambah {$request->quantity} unit");
    }
}