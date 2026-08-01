<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminPengajuanController;

// 1. HALAMAN UTAMA (PUBLIC)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. RUTE GUEST (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    
    Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');
    Route::post('/forgot-password', function () {
        return back()->with('status', 'Kami telah mengirimkan tautan reset kata sandi ke email Anda!');
    })->name('password.email');
});

// 3. RUTE USER (Sudah Login Biasa)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pengelompokan awalan '/layanan' agar lebih rapi
    Route::prefix('layanan')->group(function () {
        Route::get('/pengajuan-website', function () { return view('pengajuan.website'); })->name('pengajuan.website');
        Route::get('/pengajuan-email', function () { return view('pengajuan.email'); })->name('pengajuan.email');
        Route::get('/pengajuan-tte', function () { return view('pengajuan.tte'); })->name('pengajuan.tte');
        Route::get('/pengajuan-cloud', function () { return view('pengajuan.cloud'); })->name('pengajuan.cloud');
        Route::get('/pengajuan-bantuan', function () { return view('pengajuan.bantuan'); })->name('pengajuan.bantuan');
    });

    // Riwayat Pengajuan User (Tabel, Detail, & Chat) - Sudah diamankan di dalam auth
    Route::get('/riwayat-pengajuan', [UserDashboardController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/riwayat-pengajuan/{id}', [UserDashboardController::class, 'show'])->name('user.pengajuan.show');
    Route::post('/riwayat-pengajuan/{id}/pesan', [UserDashboardController::class, 'kirimPesan'])->name('user.pengajuan.pesan');
});

// 4. RUTE ADMIN (Sudah Login & Role Admin)
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/teknis-digital/web-desa', function () { return view('admin.web-desa.index'); })->name('admin.web-desa.index');
    Route::get('/email-resmi', function () { return view('admin.email.index'); })->name('admin.email.index');
    Route::get('/layanan-tte', function () { return view('admin.tte.index'); })->name('admin.tte.index');
    Route::get('/layanan-bantuan', function () { return view('admin.bantuan.index'); })->name('admin.bantuan.index');
    Route::get('/layanan-cloud', function () { return view('admin.cloud.index'); })->name('admin.cloud.index');

    // Route untuk Proses Pengajuan oleh Admin (Daftar, Detail, Chat & Update Timeline)
    Route::prefix('admin/pengajuan')->group(function () {
        Route::get('/', [AdminPengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::get('/{id}', [AdminPengajuanController::class, 'show'])->name('admin.pengajuan.show');
        Route::post('/{id}/pesan', [AdminPengajuanController::class, 'balasPesan'])->name('admin.pengajuan.pesan');
        Route::post('/{id}/progress', [AdminPengajuanController::class, 'updateProgress'])->name('admin.pengajuan.progress');
    });
});