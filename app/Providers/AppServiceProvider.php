<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;   // Tambahkan ini
use Illuminate\Support\ServiceProvider;  // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bagikan status saklar chat ke SEMUA halaman view sekaligus!
        View::share('chatAktif', Cache::get('chat_global_aktif', true));
    }
}
