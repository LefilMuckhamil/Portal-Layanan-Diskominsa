<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminPengajuanController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    
    Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');
    Route::post('/forgot-password', function () {
        return back()->with('status', 'Kami telah mengirimkan tautan reset kata sandi ke email Anda!');
    })->name('password.email');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('layanan')->group(function () {
        Route::get('/pengajuan-website', function () { return view('pengajuan.website'); })->name('pengajuan.website');
        Route::get('/pengajuan-email', function () { return view('pengajuan.email'); })->name('pengajuan.email');
        Route::get('/pengajuan-tte', function () { return view('pengajuan.tte'); })->name('pengajuan.tte');
        Route::get('/pengajuan-cloud', function () { return view('pengajuan.cloud'); })->name('pengajuan.cloud');
        Route::get('/pengajuan-bantuan', function () { return view('pengajuan.bantuan'); })->name('pengajuan.bantuan');
    });

    Route::get('/riwayat-pengajuan', [UserDashboardController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/riwayat-pengajuan/{id}', [UserDashboardController::class, 'show'])->name('user.pengajuan.show');
    Route::post('/riwayat-pengajuan/{id}/pesan', [UserDashboardController::class, 'kirimPesan'])->name('user.pengajuan.pesan');
    Route::post('/pengajuan/{id}/kirim-pesan', [UserDashboardController::class, 'kirimPesan'])->name('user.kirim.pesan');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', function () {
        $countWeb     = \App\Models\Pengajuan::where('jenis_layanan', 'Pembuatan Web Desa')->count();
        $countEmail   = \App\Models\Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->count();
        $countTTE     = \App\Models\Pengajuan::where('jenis_layanan', 'Layanan TTE')->count();
        $countCloud   = \App\Models\Pengajuan::where('jenis_layanan', 'Cloud Government')->count();
        $countBantuan = \App\Models\Pengajuan::where('jenis_layanan', 'Reset Password / OTP')->count();
        $pengajuans   = \App\Models\Pengajuan::latest()->get();
        $chatAktif    = Cache::get('chat_global_aktif', true);

        return view('admin.dashboard', compact(
            'countWeb', 'countEmail', 'countTTE', 'countCloud', 'countBantuan', 'pengajuans', 'chatAktif'
        )); 
    })->name('admin.dashboard');

    Route::get('/admin/pengaturan', function () {
        $chatAktif = Cache::get('chat_global_aktif', true);
        return view('admin.pengaturan.index', compact('chatAktif'));
    })->name('admin.pengaturan');

    Route::post('/admin/toggle-chat', function () {
        $status = Cache::get('chat_global_aktif', true);
        Cache::put('chat_global_aktif', !$status);
        return back()->with('sukses', 'Status Global Chat berhasil diperbarui!');
    })->name('admin.toggleChat');
    
    Route::get('/teknis-digital/web-desa', [AdminPengajuanController::class, 'webDesa'])->name('admin.web-desa.index');
    Route::get('/email-resmi', [AdminPengajuanController::class, 'emailResmi'])->name('admin.email.index');
    Route::get('/layanan-tte', [AdminPengajuanController::class, 'layananTte'])->name('admin.tte.index');
    Route::get('/layanan-bantuan', function () { return view('admin.bantuan.index'); })->name('admin.bantuan.index');
    Route::get('/layanan-cloud', [AdminPengajuanController::class, 'layananCloud'])->name('admin.cloud.index');

    Route::prefix('admin/pengajuan')->group(function () {
        Route::get('/', [AdminPengajuanController::class, 'index'])->name('admin.pengajuan.index');
        Route::get('/{id}', [AdminPengajuanController::class, 'show'])->name('admin.pengajuan.show');
        
        Route::post('/store-web-desa', [AdminPengajuanController::class, 'storeWebDesa'])->name('admin.pengajuan.storeWebDesa');
        Route::post('/store-email', [AdminPengajuanController::class, 'storeEmailResmi'])->name('admin.pengajuan.storeEmail');
        Route::post('/store-tte', [AdminPengajuanController::class, 'storeTte'])->name('admin.pengajuan.storeTte');
        Route::post('/store-cloud', [AdminPengajuanController::class, 'storeCloud'])->name('admin.pengajuan.storeCloud');
        
        Route::delete('/{id}/destroy', [AdminPengajuanController::class, 'destroy'])->name('admin.pengajuan.destroy');
        Route::post('/{id}/pesan', [AdminPengajuanController::class, 'balasPesan'])->name('admin.pengajuan.pesan');
        Route::post('/{id}/progress', [AdminPengajuanController::class, 'updateProgress'])->name('admin.pengajuan.progress');
        Route::put('/{id}/update', [AdminPengajuanController::class, 'updateProgres'])->name('admin.pengajuan.update');
    });

    // Jangan lupa pastikan ada 'use Illuminate\Support\Facades\Cache;' di paling atas file web.php jika belum ada.

    // Dashboard Admin dengan Statistik & Status Chat
    Route::get('/admin/dashboard', function () {
        $countWeb     = \App\Models\Pengajuan::where('jenis_layanan', 'Pembuatan Web Desa')->count();
        $countEmail   = \App\Models\Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->count();
        $countTTE     = \App\Models\Pengajuan::where('jenis_layanan', 'Layanan TTE')->count();
        $countCloud   = \App\Models\Pengajuan::where('jenis_layanan', 'Cloud Government')->count();
        $countBantuan = \App\Models\Pengajuan::where('jenis_layanan', 'Reset Password / OTP')->count();
        $pengajuans   = \App\Models\Pengajuan::latest()->get();
        
        // Baca status saklar chat (Default-nya true/ON)
        $chatAktif = \Illuminate\Support\Facades\Cache::get('chat_global_aktif', true);

        return view('admin.dashboard', compact(
            'countWeb', 'countEmail', 'countTTE', 'countCloud', 'countBantuan', 'pengajuans', 'chatAktif'
        )); 
    })->name('admin.dashboard');

    // Rute Master Switch untuk ON/OFF Chat
    Route::post('/admin/toggle-chat', function () {
        // Ambil status saat ini, lalu balikkan (jika true jadi false, jika false jadi true)
        $statusSekarang = \Illuminate\Support\Facades\Cache::get('chat_global_aktif', true);
        \Illuminate\Support\Facades\Cache::put('chat_global_aktif', !$statusSekarang);

        $pesan = !$statusSekarang ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('sukses', "Fitur Global Chat berhasil $pesan!");
    })->name('admin.toggleChat');
});