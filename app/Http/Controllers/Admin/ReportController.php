<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default: Minggu ini
        $period = $request->get('period', 'week');
        $startDate = null;
        $endDate = null;
        
        // Set tanggal berdasarkan periode
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'custom':
                if ($request->start_date && $request->end_date) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                } else {
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                }
                break;
            default:
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
        }

        // Get transactions - pagination 8 transaksi per halaman
        $transactions = Transaction::with(['items', 'cashier'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(8); // PERUBAHAN: paginate(8) bukan paginate(10)

        // Calculate summary
        $summary = $this->calculateSummary($startDate, $endDate);

        // Get chart data
        $salesChartData = $this->getSalesChartData($startDate, $endDate, $period);
        $paymentChartData = $this->getPaymentChartData($startDate, $endDate);

        return view('admin.reports', compact(
            'transactions',
            'summary',
            'salesChartData',
            'paymentChartData',
            'period',
            'startDate',
            'endDate'
        ));
    }

    private function calculateSummary($startDate, $endDate)
    {
        // Total transaksi
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Total pendapatan
        $totalRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        
        // Total produk terjual
        $totalItemsSold = TransactionItem::whereHas('transaction', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('qty');
        
        // Rata-rata per transaksi
        $averagePerTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        return [
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'total_items_sold' => $totalItemsSold,
            'average_per_transaction' => $averagePerTransaction
        ];
    }

    private function getSalesChartData($startDate, $endDate, $period)
    {
        $data = [];
        
        if ($period == 'week') {
            // Data per hari dalam minggu
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $total = Transaction::whereDate('created_at', $date)->sum('total');
                $data[] = [
                    'label' => $days[$i],
                    'revenue' => $total ?? 0
                ];
            }
        } elseif ($period == 'month') {
            // Data per minggu dalam bulan (maksimal 5 minggu)
            $currentDate = $startDate->copy();
            $weekNumber = 1;
            
            while ($currentDate <= $endDate && $weekNumber <= 5) {
                $weekEnd = $currentDate->copy()->endOfWeek();
                if ($weekEnd > $endDate) {
                    $weekEnd = $endDate;
                }
                
                $total = Transaction::whereBetween('created_at', [$currentDate, $weekEnd])->sum('total');
                $data[] = [
                    'label' => 'Minggu ' . $weekNumber,
                    'revenue' => $total ?? 0
                ];
                
                $currentDate = $weekEnd->copy()->addDay();
                $weekNumber++;
            }
        } else {
            // Data harian untuk periode custom atau hari ini
            if ($startDate->diffInDays($endDate) <= 7) {
                // Tampilkan per hari jika kurang dari 7 hari
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $total = Transaction::whereDate('created_at', $currentDate)->sum('total');
                    $data[] = [
                        'label' => $currentDate->format('d/m'),
                        'revenue' => $total ?? 0
                    ];
                    $currentDate->addDay();
                }
            } else {
                // Tampilkan per minggu jika lebih dari 7 hari
                $currentDate = $startDate->copy();
                $weekNumber = 1;
                while ($currentDate <= $endDate && $weekNumber <= 8) {
                    $weekEnd = $currentDate->copy()->addDays(6);
                    if ($weekEnd > $endDate) {
                        $weekEnd = $endDate;
                    }
                    
                    $total = Transaction::whereBetween('created_at', [$currentDate, $weekEnd])->sum('total');
                    $data[] = [
                        'label' => 'M' . $weekNumber,
                        'revenue' => $total ?? 0
                    ];
                    
                    $currentDate = $weekEnd->copy()->addDay();
                    $weekNumber++;
                }
            }
        }
        
        return $data;
    }

    private function getPaymentChartData($startDate, $endDate)
    {
        $payments = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        $labels = [];
        $counts = [];
        
        // Default semua metode
        $allMethods = ['cash' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer'];
        
        foreach ($allMethods as $method => $label) {
            $payment = $payments->where('payment_method', $method)->first();
            $labels[] = $label;
            $counts[] = $payment ? $payment->count : 0;
        }
        
        return [
            'labels' => $labels,
            'counts' => $counts
        ];
    }

    // Method untuk laporan spesifik
    public function daily($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        
        $transactions = Transaction::with(['items', 'cashier'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->paginate(8); // PERUBAHAN: paginate(8) bukan paginate(10)
            
        $summary = $this->calculateSummary($date->startOfDay(), $date->endOfDay());
        
        // Get chart data untuk sehari (per jam)
        $salesChartData = $this->getDailyChartData($date);
        $paymentChartData = $this->getPaymentChartData($date->startOfDay(), $date->endOfDay());
        
        return view('admin.reports', [
            'transactions' => $transactions,
            'summary' => $summary,
            'salesChartData' => $salesChartData,
            'paymentChartData' => $paymentChartData,
            'period' => 'custom',
            'startDate' => $date->startOfDay(),
            'endDate' => $date->endOfDay()
        ]);
    }

    private function getDailyChartData($date)
    {
        $data = [];
        // Data per 4 jam
        $timeSlots = [
            ['start' => '00:00:00', 'end' => '04:00:00', 'label' => '00-04'],
            ['start' => '04:00:00', 'end' => '08:00:00', 'label' => '04-08'],
            ['start' => '08:00:00', 'end' => '12:00:00', 'label' => '08-12'],
            ['start' => '12:00:00', 'end' => '16:00:00', 'label' => '12-16'],
            ['start' => '16:00:00', 'end' => '20:00:00', 'label' => '16-20'],
            ['start' => '20:00:00', 'end' => '23:59:59', 'label' => '20-24'],
        ];
        
        foreach ($timeSlots as $slot) {
            $startTime = $date->copy()->setTimeFromTimeString($slot['start']);
            $endTime = $date->copy()->setTimeFromTimeString($slot['end']);
            
            $total = Transaction::whereBetween('created_at', [$startTime, $endTime])->sum('total');
            $data[] = [
                'label' => $slot['label'],
                'revenue' => $total ?? 0
            ];
        }
        
        return $data;
    }

    public function weekly($startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        
        $transactions = Transaction::with(['items', 'cashier'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(8); // PERUBAHAN: paginate(8) bukan paginate(10)
            
        $summary = $this->calculateSummary($startDate, $endDate);
        $salesChartData = $this->getSalesChartData($startDate, $endDate, 'week');
        $paymentChartData = $this->getPaymentChartData($startDate, $endDate);
        
        return view('admin.reports', [
            'transactions' => $transactions,
            'summary' => $summary,
            'salesChartData' => $salesChartData,
            'paymentChartData' => $paymentChartData,
            'period' => 'custom',
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function monthly($year = null, $month = null)
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $transactions = Transaction::with(['items', 'cashier'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(8); // PERUBAHAN: paginate(8) bukan paginate(10)
            
        $summary = $this->calculateSummary($startDate, $endDate);
        $salesChartData = $this->getSalesChartData($startDate, $endDate, 'month');
        $paymentChartData = $this->getPaymentChartData($startDate, $endDate);
        
        return view('admin.reports', [
            'transactions' => $transactions,
            'summary' => $summary,
            'salesChartData' => $salesChartData,
            'paymentChartData' => $paymentChartData,
            'period' => 'custom',
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }


    // =================================================================
    // METHOD BARU UNTUK HALAMAN SEMUA TRANSAKSI ADMIN
    // =================================================================

    /**
     * Method untuk halaman semua transaksi admin
     */
    public function allTransactions(Request $request)
    {
        // Default filter bulan ini
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfDay());
        
        // Parse dates jika string
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }
        
        
        $cashierId = $request->get('cashier_id');
        $paymentMethods = $request->get('payment_methods', []);
        $minAmount = $request->get('min_amount');
        $maxAmount = $request->get('max_amount');
        $sortBy = $request->get('sort_by', 'date_desc');
        
        // Query transactions
        $query = Transaction::with(['items', 'cashier'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        // Filter by cashier jika ada
        if ($cashierId) {
            $query->where('cashier_id', $cashierId);
        }
        
        // Filter by payment methods
        if (!empty($paymentMethods)) {
            $query->whereIn('payment_method', $paymentMethods);
        }
        
        // Filter by amount
        if ($minAmount) {
            $query->where('total', '>=', $minAmount);
        }
        if ($maxAmount) {
            $query->where('total', '<=', $maxAmount);
        }
        
        // Sorting
        switch ($sortBy) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('total', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('total', 'asc');
                break;
            default: // date_desc
                $query->orderBy('created_at', 'desc');
        }
        
        // Get unique cashiers for filter dropdown
        $cashiers = Transaction::select('cashier_id')
            ->with('cashier')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('cashier_id')
            ->get()
            ->pluck('cashier')
            ->filter();
        
        // PERUBAHAN DI SINI: paginate(8) bukan paginate(15)
        $transactions = $query->paginate(8);
        
        // Calculate summary
        $summary = $this->calculateTransactionsSummary($transactions, $startDate, $endDate);
        
        return view('admin.transactions', compact(
            'transactions',
            'summary',
            'startDate',
            'endDate',
            'cashiers',
            'cashierId',
            'paymentMethods',
            'minAmount',
            'maxAmount',
            'sortBy'
        ));
    }

    public function transactionDetail($id)
    {
        $transaction = Transaction::with(['items.product', 'cashier'])
            ->find($id);
        
        if (!$transaction) {
            return view('admin.transactions-detail')->with('error', 'Transaksi tidak ditemukan');
        }
        
        return view('admin.transactions-detail', compact('transaction'));
    }
    
    /**
     * Helper method untuk menghitung summary transaksi
     */
    private function calculateTransactionsSummary($transactions, $startDate, $endDate)
    {
        // Get all transactions for summary calculations (not just paginated)
        $query = Transaction::whereBetween('created_at', [$startDate, $endDate]);
        
        $totalTransactions = $query->count();
        $totalRevenue = $query->sum('total');
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        return [
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'average_per_transaction' => $averageTransaction,
            'period' => $startDate->format('d F Y') . ' - ' . $endDate->format('d F Y')
        ];
    }
    
    /**
     * Method untuk export transaksi (opsional)
     */
    public function exportTransactions(Request $request)
    {
        // Validasi
        $request->validate([
            'format' => 'required|in:csv,excel,pdf',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        
        // Get transactions based on filters
        $query = Transaction::with(['items', 'cashier'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        if ($request->cashier_id) {
            $query->where('cashier_id', $request->cashier_id);
        }
        
        $transactions = $query->get();
        
        // Format filename
        $filename = 'transactions_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d');
        
        if ($request->format === 'csv') {
            return $this->exportToCSV($transactions, $filename);
        }
        
        // Untuk Excel/PDF, Anda perlu package tambahan
        return back()->with('error', 'Format export belum tersedia. Gunakan CSV.');
    }
    
    /**
     * Export data ke CSV
     */
    private function exportToCSV($transactions, $filename)
    {
        $csvData = "No,Invoice,Tanggal,Waktu,Kasir,Items,Total,Metode\n";
        
        foreach ($transactions as $index => $transaction) {
            $csvData .= sprintf(
                "%d,%s,%s,%s,%s,%d,%.2f,%s\n",
                $index + 1,
                $transaction->invoice_no,
                $transaction->created_at->format('d/m/Y'),
                $transaction->created_at->format('H:i'),
                $transaction->cashier->name ?? 'Kasir',
                $transaction->items->sum('qty'),
                $transaction->total,
                $transaction->payment_method
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
    }
    
    /**
     * Helper function untuk menentukan shift dari waktu (untuk view)
     */
    public static function getShiftFromTime($time)
    {
        $hour = $time->hour;
        if ($hour >= 8 && $hour < 16) return 'Pagi';
        if ($hour >= 16 && $hour < 24) return 'Sore';
        return 'Malam';
    }
}