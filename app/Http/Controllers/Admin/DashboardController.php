<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Data Hari Ini - SESUAIKAN DENGAN NAMA KOLOM DI MODEL
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayRevenue = Transaction::whereDate('created_at', $today)->sum('total'); // ganti 'total_amount' jadi 'total'
        $todayItemsSold = TransactionItem::whereHas('transaction', function($query) use ($today) {
            $query->whereDate('created_at', $today);
        })->sum('qty'); // ganti 'quantity' jadi 'qty'
        
        // Data Kemarin - SESUAIKAN DENGAN NAMA KOLOM DI MODEL
        $yesterdayTransactions = Transaction::whereDate('created_at', $yesterday)->count();
        $yesterdayRevenue = Transaction::whereDate('created_at', $yesterday)->sum('total'); // ganti 'total_amount' jadi 'total'
        $yesterdayItemsSold = TransactionItem::whereHas('transaction', function($query) use ($yesterday) {
            $query->whereDate('created_at', $yesterday);
        })->sum('qty'); // ganti 'quantity' jadi 'qty'
        
        // Hitung persentase perubahan
        $transactionChange = $yesterdayTransactions > 0 ? 
            (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100 : 0;
        $revenueChange = $yesterdayRevenue > 0 ? 
            (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 0;
        $itemsChange = $yesterdayItemsSold > 0 ? 
            (($todayItemsSold - $yesterdayItemsSold) / $yesterdayItemsSold) * 100 : 0;
        
        // Total produk
        $totalProducts = Products::count();
        
        // Transaksi terbaru hari ini - SESUAIKAN DENGAN NAMA KOLOM
        $recentTransactions = Transaction::with('cashier')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Data chart 7 hari terakhir - SESUAIKAN DENGAN NAMA KOLOM
        $last7Days = [];
        $revenueData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days[] = $date->format('d/m');
            
            $revenue = Transaction::whereDate('created_at', $date)->sum('total'); // ganti 'total_amount' jadi 'total'
            $revenueData[] = $revenue;
        }
        
        // Data metode pembayaran hari ini - SESUAIKAN DENGAN NAMA KOLOM
        $paymentMethods = Transaction::whereDate('created_at', $today)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total) as total_amount')) // ganti 'total_amount' jadi 'total'
            ->groupBy('payment_method')
            ->get();
        
        $paymentLabels = [];
        $paymentData = [];
        $paymentColors = [
            'cash' => '#28a745',
            'qris' => '#007bff',
            'transfer' => '#6f42c1'
        ];
        
        foreach ($paymentMethods as $method) {
            $paymentLabels[] = ucfirst($method->payment_method);
            $paymentData[] = $method->count;
        }
        
        return view('admin.dashboard', compact(
            'todayTransactions',
            'todayRevenue',
            'todayItemsSold',
            'totalProducts',
            'recentTransactions',
            'transactionChange',
            'revenueChange',
            'itemsChange',
            'last7Days',
            'revenueData',
            'paymentLabels',
            'paymentData',
            'paymentColors'
        ));
    }
    
    /**
     * API untuk mengambil data dashboard (untuk auto-refresh)
     */
    public function dashboardData(Request $request)
    {
        $today = Carbon::today();
        
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayRevenue = Transaction::whereDate('created_at', $today)->sum('total'); // ganti 'total_amount' jadi 'total'
        $todayItemsSold = TransactionItem::whereHas('transaction', function($query) use ($today) {
            $query->whereDate('created_at', $today);
        })->sum('qty'); // ganti 'quantity' jadi 'qty'
        
        return response()->json([
            'todayTransactions' => $todayTransactions,
            'todayRevenue' => $todayRevenue,
            'todayItemsSold' => $todayItemsSold,
            'updated_at' => now()->format('H:i:s')
        ]);
    }
}