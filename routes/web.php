<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES ==========

// Landing/Login Page
// Route::get('/', function () {
//     return view('auth.login');
// });

// Login Route
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PosController;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Dashboard Pilihan Login
Route::get('/', function () {
    return view('auth.dashboard'); // Dashboard pilihan login
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
    // In a real app, you would:
    // 1. Clear session
    // 2. Clear cookies
    // 3. Log activity
    // 4. Redirect to login
    
    // For demo, just redirect to login
    return redirect('/login')->with('success', 'Anda telah logout dari sistem.');
});

// ========== APPLICATION ROUTES ==========

// POS Routes
// Route::get('/pos', function () {
//     return view('pos.index');
// });

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::resource('pos', PosController::class);

Route::get('/pos/payment', function () {
    return view('pos.payment');
});

Route::get('/pos/print', function () {
    return view('pos.print');
});

// Products Routes$ }}</sm

// Route::get('/products', function () {
//     return view('products.product');
// });

// Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::resource('products', ProductsController::class);

// Route::get('/products/edit', function () {
//     return view('products.edit');
// });
// Route::get('product.edit')

Route::get('/products/hapus', function () {
    return view('products.hapus');
});

// Transactions Routes
// Route::get('/transactions', function () {
//     return view('transactions.transaksi');
// });

Route::get('/transactions', [TransactionsController::class, 'index']);

// ========== ADMIN ROUTES ==========
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });
    
    Route::get('/products', function () {
        return view('admin.products');
    });
    
    Route::get('/reports', function () {
        return view('admin.reports');
    });

    Route::get('/transactions', function () {
        return view('admin.transactions');
    });

    Route::get('/transaction-detail/{id}', function ($id) {
        return view('admin.transaction-detail', ['id' => $id]);
    });
});