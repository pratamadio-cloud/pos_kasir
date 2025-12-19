<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\TransactionItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
     * Method untuk transaksi hari ini (kasir)
     */
    public function today()
    {
        $today = Carbon::today();
        
        // Query untuk pagination
        $transactions = Transaction::with(['items', 'cashier'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get cashier info
        $cashierName = Auth::check() ? Auth::user()->name : 'Kasir';
        
        // Get shift info
        $shiftInfo = $this->getCurrentShift();
        
        // Hitung summary menggunakan agregasi database untuk efisiensi
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalRevenue = Transaction::whereDate('created_at', $today)->sum('total');
        
        // Untuk total items terjual, perlu query terpisah
        $totalItemsSold = TransactionItem::whereHas('transaction', function($query) use ($today) {
            $query->whereDate('created_at', $today);
        })->sum('qty');
        
        $firstTransaction = Transaction::whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->first();
        
        $lastTransaction = Transaction::whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $averagePerTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        $summary = [
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'total_items_sold' => $totalItemsSold,
            'average_per_transaction' => $averagePerTransaction,
            'first_transaction_time' => $firstTransaction ? $firstTransaction->created_at->format('H:i') : '-',
            'last_transaction_time' => $lastTransaction ? $lastTransaction->created_at->format('H:i') : '-'
        ];
        
        return view('transactions.today', compact(
            'transactions',
            'summary',
            'cashierName',
            'shiftInfo',
            'today'
        ));
    }
    
    private function getCurrentShift()
    {
        $hour = Carbon::now()->hour;
        
        if ($hour >= 8 && $hour < 16) {
            return [
                'name' => 'Pagi',
                'time' => '08-16',
                'status' => 'Aktif'
            ];
        } elseif ($hour >= 16 && $hour < 24) {
            return [
                'name' => 'Sore',
                'time' => '16-24',
                'status' => 'Aktif'
            ];
        } else {
            return [
                'name' => 'Malam',
                'time' => '00-08',
                'status' => 'Non-Aktif'
            ];
        }
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

        $transactionId = null; // Variabel untuk menyimpan ID transaksi
        
        DB::transaction(function () use ($request, $cart, $total, &$transactionId) {

            $cashierId = Auth::check() ? Auth::id() : 1;

            $transaction = Transaction::create([
                'invoice_no' => 'INV-' . now()->format('YmdHis'),
                'cashier_id' => $cashierId,
                'total' => $total,
                'paid' => $request->paid,
                'change' => $request->paid - $total,
                'payment_method' => $request->payment_method,
            ]);
            
            $transactionId = $transaction->id; // Simpan ID transaksi

            foreach ($cart as $item) {
                TransactionItem::create([
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

        // ✅ PERUBAHAN DI SINI: Redirect ke halaman detail transaksi
        return redirect()->route('transactions.show', $transactionId)
            ->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with(['items.product', 'cashier'])
            ->findOrFail($id);
        
        // Format response untuk API
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        }
        
        // Jika tidak API, tampilkan view
        return view('transactions.transaksi-detail', compact('transaction'));
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