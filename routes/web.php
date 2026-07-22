<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rute khusus pengunjung yang BELUM login (Guest)
Route::middleware('guest')->group(function () {
    
    // Tampilan Halaman
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    /* 
    | NANTINYA: 
    | Buka komentar pada kode di bawah ini jika Controller sudah dibuat 
    | untuk memproses pengiriman data (submit form).
    */
    // Route::post('/login', [AuthController::class, 'authenticate']);
    // Route::post('/register', [AuthController::class, 'store']);
    // Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
});

// Rute khusus pengunjung yang SUDAH login (Auth)
Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post('/forgot-password', function () {
    // Ini hanya dummy agar tidak error, nanti diganti mengarah ke Controller
    return back()->with('status', 'Kami telah mengirimkan tautan reset kata sandi ke email Anda!');
})->name('password.email');



// Route untuk halaman utama (Landing Page)
Route::get('/', function () {
    return view('welcome'); // Sesuaikan dengan nama file landing page Anda
});

// Route untuk halaman Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route untuk halaman Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// ==========================================
// ROUTE DASHBOARD ADMIN
// ==========================================
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');