<?php

use App\Http\Controllers\AdminPengajuanController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordAdminController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserPengajuanController;
use App\Http\Middleware\IsAdmin;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Public & Tracking Routes (Bebas Akses Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/track-tiket/{nomor_tiket}', [TrackingController::class, 'track'])->name('track.tiket')->middleware('throttle:20,1');

/*
|--------------------------------------------------------------------------
| 2. Guest Routes (Hanya Sebelum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process')->middleware('throttle:5,1');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'submitRequest'])->name('password.email')->middleware('throttle:3,1');
});

/*
|--------------------------------------------------------------------------
| 3. Authenticated User Routes (Khusus ASN / User Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('layanan')->group(function () {
        Route::get('/pengajuan-website', function () {
            return view('pengajuan.website');
        })->name('pengajuan.website');
        Route::get('/pengajuan-email', function () {
            return view('pengajuan.email');
        })->name('pengajuan.email');
        Route::get('/pengajuan-tte', function () {
            return view('pengajuan.tte');
        })->name('pengajuan.tte');
        Route::get('/pengajuan-cloud', function () {
            return view('pengajuan.cloud');
        })->name('pengajuan.cloud');
        Route::get('/pengajuan-bantuan', function () {
            return view('pengajuan.bantuan');
        })->name('pengajuan.bantuan');

        Route::middleware('throttle:10,1')->group(function () {
            Route::post('/pengajuan-website/store', [UserPengajuanController::class, 'storeWebsite'])->name('pengajuan.website.store');
            Route::post('/pengajuan/email/store', [UserPengajuanController::class, 'storeEmail'])->name('pengajuan.email.store');
            Route::post('/pengajuan/tte/store', [UserPengajuanController::class, 'storeTte'])->name('pengajuan.tte.store');
            Route::post('/pengajuan/cloud/store', [UserPengajuanController::class, 'storeCloud'])->name('pengajuan.cloud.store');
            Route::post('/pengajuan/bantuan/store', [UserPengajuanController::class, 'storeBantuan'])->name('pengajuan.bantuan.store');
        });
    });

    Route::get('/riwayat-pengajuan', [UserDashboardController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/riwayat-pengajuan/{id}', [UserDashboardController::class, 'show'])->name('user.pengajuan.show');
    Route::post('/riwayat-pengajuan/{id}/pesan', [UserDashboardController::class, 'kirimPesan'])->name('user.pengajuan.pesan')->middleware('throttle:20,1');

    Route::get('/dokumen/{pengajuan}/{jenis}', [DocumentController::class, 'unduh'])->name('dokumen.unduh');
});

/*
|--------------------------------------------------------------------------
| 4. Admin Routes (Khusus Administrator Diskominsa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        $countWeb = Pengajuan::where('jenis_layanan', 'Pembuatan Website')->count();
        $countEmail = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->count();
        $countTTE = Pengajuan::where('jenis_layanan', 'Layanan TTE')->count();
        $countCloud = Pengajuan::where('jenis_layanan', 'Cloud Government')->count();
        $countBantuan = Pengajuan::where('jenis_layanan', 'Pusat Bantuan')->count();

        $query = Pengajuan::with('user');

        if ($request->filled('search')) {
            $query->where('nomor_tiket', 'like', '%'.trim($request->search).'%');
        }

        if ($request->filled('status')) {
            if ($request->status == 'Proses') {
                $query->where('status', 'Proses');
            } else {
                $query->where('status', $request->status);
            }
        }

        $pengajuans = $query->latest()->paginate(10);
        $chatAktif = Cache::get('chat_global_aktif', true);

        return view('admin.dashboard', compact(
            'countWeb', 'countEmail', 'countTTE', 'countCloud', 'countBantuan', 'pengajuans', 'chatAktif'
        ));
    })->name('admin.dashboard');

    Route::get('/pengaturan', function () {
        $chatAktif = Cache::get('chat_global_aktif', true);

        return view('admin.pengaturan.index', compact('chatAktif'));
    })->name('admin.pengaturan');

    Route::post('/toggle-chat', function () {
        $statusSekarang = Cache::get('chat_global_aktif', true);
        Cache::put('chat_global_aktif', ! $statusSekarang);
        $pesan = ! $statusSekarang ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('sukses', "Fitur Global Chat berhasil $pesan!");
    })->name('admin.toggleChat')->middleware('throttle:20,1');

    Route::get('/reset-password-requests', [ResetPasswordAdminController::class, 'index'])->name('admin.reset-password.index');
    Route::post('/reset-password-requests/{id}', [ResetPasswordAdminController::class, 'process'])->name('admin.reset-password.process')->middleware('throttle:20,1');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::middleware('throttle:20,1')->group(function () {
        Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::get('/teknis-digital/website', [AdminPengajuanController::class, 'website'])->name('admin.website.index');
    Route::get('/email-resmi', [AdminPengajuanController::class, 'emailResmi'])->name('admin.email.index');
    Route::get('/layanan-tte', [AdminPengajuanController::class, 'layananTte'])->name('admin.tte.index');
    Route::get('/layanan-cloud', [AdminPengajuanController::class, 'layananCloud'])->name('admin.cloud.index');
    Route::get('/layanan-bantuan', [AdminPengajuanController::class, 'layananBantuan'])->name('admin.bantuan.index');

    Route::prefix('pengajuan')->group(function () {
        Route::middleware('throttle:20,1')->group(function () {
            Route::post('/store-website', [AdminPengajuanController::class, 'storeWebsite'])->name('admin.pengajuan.storeWebsite');
            Route::post('/store-email', [AdminPengajuanController::class, 'storeEmailResmi'])->name('admin.pengajuan.storeEmail');
            Route::post('/store-tte', [AdminPengajuanController::class, 'storeTte'])->name('admin.pengajuan.storeTte');
            Route::post('/store-cloud', [AdminPengajuanController::class, 'storeCloud'])->name('admin.pengajuan.storeCloud');
            Route::post('/layanan-bantuan/store', [AdminPengajuanController::class, 'storeBantuan'])->name('admin.bantuan.store');

            Route::delete('/{id}/destroy', [AdminPengajuanController::class, 'destroy'])->name('admin.pengajuan.destroy');
            Route::put('/{id}/update', [AdminPengajuanController::class, 'updateProgres'])->name('admin.pengajuan.update');
        });
    });

});
