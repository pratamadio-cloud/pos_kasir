<?php

use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES ==========

// Landing/Login Page
Route::get('/', function () {
    return view('auth.login');
});

// Login Route
Route::get('/login', function () {
    return view('auth.login');
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

// Admin Login
Route::get('/admin-login', function () {
    return view('admin.login');
});

// ========== APPLICATION ROUTES ==========

// POS Routes
Route::get('/pos', function () {
    return view('pos.index');
});

Route::get('/pos/payment', function () {
    return view('pos.payment');
});

Route::get('/pos/print', function () {
    return view('pos.print');
});

// Products Routes
Route::get('/products', function () {
    return view('products.product');
});

Route::get('/products/edit', function () {
    return view('products.edit');
});

Route::get('/products/hapus', function () {
    return view('products.hapus');
});

// Transactions Routes
Route::get('/transactions', function () {
    return view('transactions.transaksi');
});

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
});
Route::get('/admin/transactions', function () {
    return view('admin.transactions');  // Pastikan file ada di resources/views/admin/transactions.blade.php
});