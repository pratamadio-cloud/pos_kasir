<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\TransactionItems;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('transactions.transaksi');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer',
            'paid' => 'required|numeric|min:0',
        ]);

        $cart = session('cart');

        if (!$cart || count($cart) === 0) {
            return redirect('/pos');
        }

        $total = collect($cart)->sum('subtotal');

        if ($request->paid < $total) {
            return back()->with('error', 'Uang tidak cukup');
        }

        DB::transaction(function () use ($request, $cart, $total) {

            $cashierId = config('pos.default_cashier_id'); // auth()->id() ?? 

            $transaction = Transactions::create([
                'invoice_no' => 'INV-' . now()->format('YmdHis'),
                'cashier_id' => $cashierId,
                'total' => $total,
                'paid' => $request->paid,
                'change' => $request->paid - $total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart as $item) {
                TransactionItems::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // kurangi stok
                Products::where('id', $item['product_id'])
                    ->decrement('stock', $item['qty']);
            }
        });

        session()->forget('cart');

        return redirect()->route('pos.index');
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
