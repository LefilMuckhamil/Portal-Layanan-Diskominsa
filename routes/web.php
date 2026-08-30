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
use App\Models\KategoriBantuan;
use App\Models\Pengajuan;
use App\Models\Setting;
use Illuminate\Http\Request;
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
        Route::get('/pengajuan-subdomain', function () {
            return view('pengajuan.subdomain');
        })->name('pengajuan.subdomain');
        Route::get('/pengajuan-hosting', function () {
            return view('pengajuan.hosting');
        })->name('pengajuan.hosting');
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
            return view('pengajuan.bantuan', [
                'kategoris' => KategoriBantuan::where('is_active', true)->orderBy('id')->get(),
            ]);
        })->name('pengajuan.bantuan');

        Route::middleware('throttle:10,1')->group(function () {
            Route::post('/pengajuan-website/store', [UserPengajuanController::class, 'storeWebsite'])->name('pengajuan.website.store');
            Route::post('/pengajuan-subdomain/store', [UserPengajuanController::class, 'storeSubdomain'])->name('pengajuan.subdomain.store');
            Route::post('/pengajuan-hosting/store', [UserPengajuanController::class, 'storeHosting'])->name('pengajuan.hosting.store');
            Route::post('/pengajuan/email/store', [UserPengajuanController::class, 'storeEmail'])->name('pengajuan.email.store');
            Route::post('/pengajuan/tte/store', [UserPengajuanController::class, 'storeTte'])->name('pengajuan.tte.store');
            Route::post('/pengajuan/cloud/store', [UserPengajuanController::class, 'storeCloud'])->name('pengajuan.cloud.store');
            Route::post('/pengajuan/bantuan/store', [UserPengajuanController::class, 'storeBantuan'])->name('pengajuan.bantuan.store');
        });
    });

    Route::get('/riwayat-pengajuan', [UserDashboardController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/riwayat-pengajuan/{id}', [UserDashboardController::class, 'show'])->name('user.pengajuan.show');
    Route::post('/riwayat-pengajuan/{id}/pesan', [UserDashboardController::class, 'kirimPesan'])->name('user.pengajuan.pesan')->middleware('throttle:20,1');
    Route::get('/riwayat-pengajuan/{id}/chat', [UserDashboardController::class, 'getChat'])->name('user.pengajuan.chat')->middleware('throttle:60,1');

    Route::get('/dokumen/{pengajuan}/{jenis}', [DocumentController::class, 'unduh'])->name('dokumen.unduh');
});

/*
|--------------------------------------------------------------------------
| 4. Admin Routes (Khusus Administrator Diskominsa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        $request->validate([
            'date_mulai' => 'nullable|date_format:Y-m-d',
            'date_selesai' => 'nullable|date_format:Y-m-d',
            'tanggal' => 'nullable|date_format:Y-m-d',
        ], [
            'date_mulai.date_format' => 'Format tanggal mulai tidak valid.',
            'date_selesai.date_format' => 'Format tanggal selesai tidak valid.',
            'tanggal.date_format' => 'Format tanggal tidak valid.',
        ]);

        $dateMulai = $request->filled('date_mulai') ? $request->date_mulai : null;
        $dateSelesai = $request->filled('date_selesai') ? $request->date_selesai : null;
        $tanggal = $request->filled('tanggal') ? $request->tanggal : null;

        if ($dateMulai && $dateSelesai && $dateMulai > $dateSelesai) {
            return back()->with('error', 'Tanggal mulai tidak boleh melebihi tanggal selesai.');
        }

        if ($tanggal) {
            $dateMulai = $tanggal;
            $dateSelesai = $tanggal;
        }

        $dateScope = function ($q) use ($dateMulai, $dateSelesai) {
            if ($dateMulai) {
                $q->where('created_at', '>=', $dateMulai);
            }
            if ($dateSelesai) {
                $q->where('created_at', '<=', $dateSelesai.' 23:59:59');
            }
        };

        $countWeb = Pengajuan::where('jenis_layanan', 'Pembuatan Website')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countEmail = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countTTE = Pengajuan::where('jenis_layanan', 'Layanan TTE')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countCloud = Pengajuan::where('jenis_layanan', 'Cloud Government')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countBantuan = Pengajuan::where('jenis_layanan', 'Pusat Bantuan')->when($dateMulai || $dateSelesai, $dateScope)->count();

        $statusCounts = Pengajuan::selectRaw('status, count(*) as total')
            ->when($dateMulai || $dateSelesai, $dateScope)
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartData = [
            'layanan' => ['Website', 'Email Resmi', 'TTE', 'Cloud Gov', 'Bantuan'],
            'volume' => [$countWeb, $countEmail, $countTTE, $countCloud, $countBantuan],
            'status' => [
                'Pending' => (int) $statusCounts->get('Pending', 0),
                'Proses' => (int) $statusCounts->get('Proses', 0),
                'Selesai' => (int) $statusCounts->get('Selesai', 0),
                'Ditolak' => (int) $statusCounts->get('Ditolak', 0),
            ],
        ];

        $query = Pengajuan::with('user');

        if ($dateMulai || $dateSelesai) {
            $query->where(function ($q) use ($dateMulai, $dateSelesai) {
                if ($dateMulai) {
                    $q->where('created_at', '>=', $dateMulai);
                }
                if ($dateSelesai) {
                    $q->where('created_at', '<=', $dateSelesai.' 23:59:59');
                }
            });
        }

        if ($request->filled('search')) {
            $query->where('nomor_tiket', 'like', '%'.addcslashes(trim($request->search), '%_').'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->latest()->paginate(10);
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        $calendarParams = array_filter([
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'date_mulai' => $request->input('date_mulai'),
            'date_selesai' => $request->input('date_selesai'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.dashboard.partials.table', compact(
                    'pengajuans', 'dateMulai', 'dateSelesai'
                ))->render(),
            ]);
        }

        return view('admin.dashboard', compact(
            'countWeb', 'countEmail', 'countTTE', 'countCloud', 'countBantuan',
            'pengajuans', 'chatAktif', 'chartData', 'dateMulai', 'dateSelesai', 'tanggal', 'calendarParams'
        ));
    })->name('admin.dashboard');

    Route::get('/dashboard/chart-data', function (Request $request) {
        $request->validate([
            'tanggal' => 'nullable|date_format:Y-m-d',
        ]);

        $dateMulai = $request->filled('tanggal') ? $request->tanggal : null;
        $dateSelesai = $request->filled('tanggal') ? $request->tanggal : null;

        $dateScope = function ($q) use ($dateMulai, $dateSelesai) {
            if ($dateMulai) {
                $q->where('created_at', '>=', $dateMulai);
            }
            if ($dateSelesai) {
                $q->where('created_at', '<=', $dateSelesai.' 23:59:59');
            }
        };

        $countWeb = Pengajuan::where('jenis_layanan', 'Pembuatan Website')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countEmail = Pengajuan::where('jenis_layanan', 'Pembuatan Email Resmi')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countTTE = Pengajuan::where('jenis_layanan', 'Layanan TTE')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countCloud = Pengajuan::where('jenis_layanan', 'Cloud Government')->when($dateMulai || $dateSelesai, $dateScope)->count();
        $countBantuan = Pengajuan::where('jenis_layanan', 'Pusat Bantuan')->when($dateMulai || $dateSelesai, $dateScope)->count();

        $statusCounts = Pengajuan::selectRaw('status, count(*) as total')
            ->when($dateMulai || $dateSelesai, $dateScope)
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartData = [
            'layanan' => ['Website', 'Email Resmi', 'TTE', 'Cloud Gov', 'Bantuan'],
            'volume' => [$countWeb, $countEmail, $countTTE, $countCloud, $countBantuan],
            'status' => [
                'Pending' => (int) $statusCounts->get('Pending', 0),
                'Proses' => (int) $statusCounts->get('Proses', 0),
                'Selesai' => (int) $statusCounts->get('Selesai', 0),
                'Ditolak' => (int) $statusCounts->get('Ditolak', 0),
            ],
        ];

        return response()->json([
            'status' => 'success',
            'chartData' => $chartData,
            'tanggal' => $request->input('tanggal'),
        ]);
    })->name('admin.dashboard.chartData');

    Route::get('/pengaturan', function () {
        $chatAktif = Setting::get('chat_global_aktif', '1') === '1';

        return view('admin.pengaturan.index', compact('chatAktif'));
    })->name('admin.pengaturan');

    Route::post('/toggle-chat', function () {
        $statusSekarang = Setting::get('chat_global_aktif', '1');
        $newStatus = $statusSekarang === '1' ? '0' : '1';
        Setting::set('chat_global_aktif', $newStatus);
        $pesan = $newStatus === '1' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('sukses', "Fitur Global Chat berhasil $pesan!");
    })->name('admin.toggleChat')->middleware('throttle:10,1');

    Route::get('/reset-password-requests', [ResetPasswordAdminController::class, 'index'])->name('admin.reset-password.index');
    Route::post('/reset-password-requests/{id}', [ResetPasswordAdminController::class, 'process'])->name('admin.reset-password.process')->middleware('throttle:20,1');
    Route::delete('/reset-password-requests/{id}', [ResetPasswordAdminController::class, 'destroy'])->name('admin.reset-password.destroy')->middleware('throttle:20,1');

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

    Route::get('/pengajuan/export', [AdminPengajuanController::class, 'export'])->name('admin.pengajuan.export')->middleware('throttle:5,1');

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

        Route::get('/{id}/chat', [AdminPengajuanController::class, 'getChat'])->name('admin.pengajuan.chat')->middleware('throttle:60,1');
    });

});
