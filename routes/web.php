<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\UserPengajuanController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// =========================================================
// ROUTE GUEST (Belum Login)
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
    
    Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');
    Route::post('/forgot-password', function () {
        return back()->with('status', 'Kami telah mengirimkan tautan reset kata sandi ke email Anda!');
    })->name('password.email');
});


// =========================================================
// ROUTE USER / PEMOHON (Sudah Login)
// =========================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. Form View & Store Pengajuan Layanan
    Route::prefix('layanan')->group(function () {
        Route::get('/pengajuan-website', function () { return view('pengajuan.website'); })->name('pengajuan.website');
        Route::get('/pengajuan-email', function () { return view('pengajuan.email'); })->name('pengajuan.email');
        Route::get('/pengajuan-tte', function () { return view('pengajuan.tte'); })->name('pengajuan.tte');
        Route::get('/pengajuan-cloud', function () { return view('pengajuan.cloud'); })->name('pengajuan.cloud');
        Route::get('/pengajuan-bantuan', function () { return view('pengajuan.bantuan'); })->name('pengajuan.bantuan');
        
        Route::post('/pengajuan-website/store', [UserPengajuanController::class, 'storeWebsite'])->name('pengajuan.website.store');
        Route::post('/pengajuan/email/store', [UserPengajuanController::class, 'storeEmail'])->name('pengajuan.email.store');
        Route::post('/pengajuan/tte/store', [UserPengajuanController::class, 'storeTte'])->name('pengajuan.tte.store');
        Route::post('/pengajuan/cloud/store', [UserPengajuanController::class, 'storeCloud'])->name('pengajuan.cloud.store');
        Route::post('/pengajuan/bantuan/store', [UserPengajuanController::class, 'storeBantuan'])->name('pengajuan.bantuan.store');
    });

    // 2. Riwayat & Chat User
    Route::get('/riwayat-pengajuan', [UserDashboardController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/riwayat-pengajuan/{id}', [UserDashboardController::class, 'show'])->name('user.pengajuan.show');
    Route::post('/riwayat-pengajuan/{id}/pesan', [UserDashboardController::class, 'kirimPesan'])->name('user.pengajuan.pesan');
});


// =========================================================
// ROUTE ADMIN (Sudah Login & Role Admin)
// =========================================================
Route::middleware(['auth', IsAdmin::class])->group(function () {
    
    // 1. Dashboard Utama Admin
    Route::get('/admin/dashboard', function () {
        $countWeb     = \App\Models\Pengajuan::where('jenis_layanan', 'Pembuatan Website')->count(); 
        $countEmail   = \App\Models\Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->count();
        $countTTE     = \App\Models\Pengajuan::where('jenis_layanan', 'Layanan TTE')->count();
        $countCloud   = \App\Models\Pengajuan::where('jenis_layanan', 'Cloud Government')->count();
        $countBantuan = \App\Models\Pengajuan::where('jenis_layanan', 'Reset Password')->count();
        $pengajuans   = \App\Models\Pengajuan::latest()->get();
        $chatAktif    = Cache::get('chat_global_aktif', true);

        return view('admin.dashboard', compact(
            'countWeb', 'countEmail', 'countTTE', 'countCloud', 'countBantuan', 'pengajuans', 'chatAktif'
        )); 
    })->name('admin.dashboard');

    // 2. Pengaturan Saklar Chat
    Route::get('/admin/pengaturan', function () {
        $chatAktif = Cache::get('chat_global_aktif', true);
        return view('admin.pengaturan.index', compact('chatAktif'));
    })->name('admin.pengaturan');

    Route::post('/admin/toggle-chat', function () {
        $statusSekarang = Cache::get('chat_global_aktif', true);
        Cache::put('chat_global_aktif', !$statusSekarang);
        
        $pesan = !$statusSekarang ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('sukses', "Fitur Global Chat berhasil $pesan!");
    })->name('admin.toggleChat');
    
    // 3. Halaman Kelola Per Layanan
    Route::get('/teknis-digital/website', [AdminPengajuanController::class, 'website'])->name('admin.website.index'); 
    Route::get('/email-resmi', [AdminPengajuanController::class, 'emailResmi'])->name('admin.email.index');
    Route::get('/layanan-tte', [AdminPengajuanController::class, 'layananTte'])->name('admin.tte.index');
    Route::get('/layanan-cloud', [AdminPengajuanController::class, 'layananCloud'])->name('admin.cloud.index');
    Route::get('/layanan-bantuan', [AdminPengajuanController::class, 'layananBantuan'])->name('admin.bantuan.index');

    // 4. Aksi CRUD Admin
    Route::prefix('admin/pengajuan')->group(function () {
        Route::get('/', [AdminPengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::get('/{id}', [AdminPengajuanController::class, 'show'])->name('admin.pengajuan.show');
        
        // Simpan Data Manual dari Admin
        Route::post('/store-website', [AdminPengajuanController::class, 'storeWebsite'])->name('admin.pengajuan.storeWebsite'); 
        Route::post('/store-email', [AdminPengajuanController::class, 'storeEmailResmi'])->name('admin.pengajuan.storeEmail');
        Route::post('/store-tte', [AdminPengajuanController::class, 'storeTte'])->name('admin.pengajuan.storeTte');
        Route::post('/store-cloud', [AdminPengajuanController::class, 'storeCloud'])->name('admin.pengajuan.storeCloud');
        Route::post('/layanan-bantuan/store', [AdminPengajuanController::class, 'storeBantuan'])->name('admin.bantuan.store');
        
        // Update & Hapus
        Route::delete('/{id}/destroy', [AdminPengajuanController::class, 'destroy'])->name('admin.pengajuan.destroy');
        Route::put('/{id}/update', [AdminPengajuanController::class, 'updateProgres'])->name('admin.pengajuan.update');
    });

});