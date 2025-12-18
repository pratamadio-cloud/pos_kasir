<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\DashboardController;

// ========== PUBLIC ROUTES ==========

// Login Route
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Pilihan Login
Route::get('/', function () {
    return view('auth.login'); // Dashboard pilihan login
});

// Login Admin
Route::get('/admin-login', function () {
    return view('admin.login');
});

// Logout Route (Show confirmation page)
Route::get('/logout', function () {
    return view('auth.logout');
});

// Logout Action (Actually perform logout)
Route::get('/logout-action', function () {
    return redirect('/login')->with('success', 'Anda telah logout dari sistem.');
});

// ========== APPLICATION ROUTES ==========
Route::middleware(['auth', 'role:cashier'])->group(function () {
    // POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [PosController::class, 'addToCart'])->name('pos.add');
    Route::post('/pos/update', [PosController::class, 'updateCart'])->name('pos.update');
    Route::post('/pos/remove', [PosController::class, 'removeFromCart'])->name('pos.remove');
    Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');

    // Payment & Transaction
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/transaction', [TransactionsController::class, 'store'])->name('transaction.store');
    Route::get('/pos/print', function () {
        return view('pos.print');
    });

    // Products Routes (KASIR)
    Route::resource('products', ProductsController::class);
    Route::get('/products/hapus', function () {
        return view('products.hapus');
    });

    // Transactions Routes (KASIR) - TAMBAHAN DARI SINI
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionsController::class, 'index'])->name('index');
        Route::get('/today', [TransactionsController::class, 'today'])->name('today');
        Route::get('/create', [TransactionsController::class, 'create'])->name('create');
        Route::post('/', [TransactionsController::class, 'store'])->name('store');
        Route::get('/{id}', [TransactionsController::class, 'show'])->name('show'); // INI YANG DIPERBAIKI
        
        // TAMBAHAN: Fitur tambahan untuk kasir
        Route::post('/{id}/cancel', [TransactionsController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/refund', [TransactionsController::class, 'refund'])->name('refund');
        Route::get('/{id}/print-receipt', [TransactionsController::class, 'printReceipt'])->name('print');
        Route::get('/statistics', [TransactionsController::class, 'statistics'])->name('statistics');
        Route::get('/search', [TransactionsController::class, 'search'])->name('search');
    });
});

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'dashboardData'])->name('admin.dashboard.data');
    
    // Products Admin
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('/products/{product}/add-stock', [AdminProductController::class, 'addStock'])->name('admin.products.addStock');
    
    // Kategori (AJAX)
    Route::get('/categories', [AdminProductController::class, 'getCategories'])->name('admin.categories.index');
    Route::post('/categories', [AdminProductController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/categories/{category}', [AdminProductController::class, 'destroyCategory'])->name('admin.categories.destroy');
    
    // REPORTS (Laporan)
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/daily/{date?}', [ReportController::class, 'daily'])->name('admin.reports.daily');
    Route::get('/reports/weekly/{date?}', [ReportController::class, 'weekly'])->name('admin.reports.weekly');
    Route::get('/reports/monthly/{year?}/{month?}', [ReportController::class, 'monthly'])->name('admin.reports.monthly');
    
    // TRANSACTIONS (Transaksi Admin)
    Route::get('/transactions', [ReportController::class, 'allTransactions'])->name('admin.transactions');
    Route::post('/transactions/export', [ReportController::class, 'exportTransactions'])->name('admin.transactions.export');
    
    // TRANSACTION DETAIL (Detail Transaksi Admin)
    Route::get('/transactions-detail/{id}', [ReportController::class, 'transactionDetail'])->name('admin.transactions-detail');
});