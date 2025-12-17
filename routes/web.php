<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;

// LOGIN
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// TEST AUTH
// Route::middleware(['auth', 'role:cashier'])->group(function () {
//     Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
// });

// TEST ROLE
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [PosController::class, 'addToCart'])->name('pos.add');
    Route::post('/pos/update', [PosController::class, 'updateCart'])->name('pos.update');
    Route::post('/pos/remove', [PosController::class, 'removeFromCart'])->name('pos.remove');
    Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/transaction', [TransactionsController::class, 'store'])->name('transaction.store');
});



// Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
// Route::post('/pos/add', [PosController::class, 'addToCart'])->name('pos.add');
// Route::post('/pos/update', [PosController::class, 'updateCart'])->name('pos.update');
// Route::post('/pos/remove', [PosController::class, 'removeFromCart'])->name('pos.remove');
// Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');


// Route::get('/pos/payment', function () {
//     return view('pos.payment');
// });

// Route::get('/payment', [PaymentController::class, 'index'])
//         ->name('payment.index');

// Route::post('/transaction', [TransactionsController::class, 'store'])
//         ->name('transaction.store');

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