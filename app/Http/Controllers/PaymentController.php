<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $cart = session('cart');

        if (!$cart || count($cart) === 0) {
            return redirect()->route('pos.index')
                ->with('error', 'Keranjang kosong');
        }

        // 🔒 FILTER item lama (tanpa subtotal)
        $cart = collect($cart)->map(function ($item) {
            $item['subtotal'] = $item['subtotal']
                ?? $item['price'] * $item['qty'];
            return $item;
        })->toArray();

        session(['cart' => $cart]);

        $total = collect($cart)->sum('subtotal');

        return view('payment.index', compact('cart', 'total'));
    }
}
