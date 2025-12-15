<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Products::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('barcode', 'like', '%' . $request->search . '%')
                ->orWhereHas('category', function ($qc) use ($request) {
                    $qc->where('category_name', 'like', '%' . $request->search . '%');
                });
            });
        }

        $cart = session()->get('cart', []);
        $hasCart = count($cart) > 0;

        // 🔥 JUMLAH ITEM DINAMIS
        $perPage = $hasCart ? 8 : 8;

        $products = $query->paginate($perPage);

        $total = 0;
        $total_item = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $total_item += $item['qty'];
        }

        return view('pos.index', compact(
            'products',
            'cart',
            'total',
            'total_item',
            'hasCart'
        ));
    }


    public function addToCart(Request $request)
    {
        $product = Products::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty']++;
        } else {
            $cart[$product->id] = [
                'name'  => $product->name,
                'price' => $product->price,
                'qty'   => 1,
            ];
        }

        session()->put('cart', $cart);
        return back();
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (!isset($cart[$productId])) {
            return back();
        }

        if ($request->action === 'plus') {
            $cart[$productId]['qty']++;
        }

        if ($request->action === 'minus') {
            if ($cart[$productId]['qty'] > 1) {
                $cart[$productId]['qty']--;
            }
        }

        session()->put('cart', $cart);
        return back();
    }


    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return back();
    }

    public function clearCart()
    {
        session()->forget('cart');

        return redirect()->route('pos.index')
            ->with('success', 'Transaksi dibatalkan');
    }

}
